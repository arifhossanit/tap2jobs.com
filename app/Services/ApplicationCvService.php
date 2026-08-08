<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\CandidateAccomplishment;
use App\Models\CandidateEducation;
use App\Models\CandidateExperience;
use App\Models\CandidateExtraCurricular;
use App\Models\CandidateLink;
use App\Models\CandidateReference;
use App\Models\CandidateSkill;
use App\Models\CandidateTraining;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ApplicationCvService
{
    public const TITLE = 'Application CV';

    public const APPLICATION_CV_PROPERTY = 'is_application_cv';

    public function ensure(Candidate $candidate): Media
    {
        $candidate->loadMissing('user');

        $pdfContent = Pdf::loadView('candidate.profile.application_cv_pdf', $this->viewData($candidate))
            ->setPaper('a4')
            ->output();

        $resumes = $candidate->getMedia(Candidate::RESUME_PATH);
        $applicationCv = $resumes->first(
            fn (Media $media) => (bool) $media->getCustomProperty(self::APPLICATION_CV_PROPERTY, false)
        );

        if ($applicationCv) {
            Storage::disk($applicationCv->disk)->put($applicationCv->getPathRelativeToRoot(), $pdfContent);
            $applicationCv->size = strlen($pdfContent);
            $applicationCv->mime_type = 'application/pdf';
            $applicationCv->setCustomProperty('title', self::TITLE);
            $applicationCv->setCustomProperty('generated_at', now()->toIso8601String());
            $applicationCv->save();
        } else {
            $applicationCv = $candidate
                ->addMediaFromString($pdfContent)
                ->usingFileName('application-cv-'.$candidate->id.'.pdf')
                ->withCustomProperties([
                    'title' => self::TITLE,
                    self::APPLICATION_CV_PROPERTY => true,
                    'is_default' => false,
                    'generated_at' => now()->toIso8601String(),
                ])
                ->toMediaCollection(Candidate::RESUME_PATH, config('app.media_disc'));

            $resumes->push($applicationCv);
        }

        $hasUploadedDefault = $resumes->contains(function (Media $media) use ($applicationCv) {
            return $media->id !== $applicationCv->id
                && ! $media->getCustomProperty(self::APPLICATION_CV_PROPERTY, false)
                && (bool) $media->getCustomProperty('is_default', false);
        });

        if ($hasUploadedDefault) {
            $applicationCv->setCustomProperty('is_default', false)->save();
        } else {
            $this->makeDefault($candidate, $applicationCv);
        }

        return $applicationCv->fresh();
    }

    public function makeDefault(Candidate $candidate, Media $selectedResume): void
    {
        abort_unless(
            $selectedResume->model_type === Candidate::class
                && (int) $selectedResume->model_id === (int) $candidate->id
                && $selectedResume->collection_name === Candidate::RESUME_PATH,
            403
        );

        $candidate->unsetRelation('media');
        $candidate->getMedia(Candidate::RESUME_PATH)->each(function (Media $resume) use ($selectedResume) {
            $shouldBeDefault = $resume->id === $selectedResume->id;

            if ((bool) $resume->getCustomProperty('is_default', false) !== $shouldBeDefault) {
                $resume->setCustomProperty('is_default', $shouldBeDefault)->save();
            }
        });
    }

    private function viewData(Candidate $candidate): array
    {
        $candidateId = $candidate->id;

        return [
            'candidate' => $candidate,
            'user' => $candidate->user,
            'plainText' => fn (?string $value): string => $this->plainText($value),
            'educations' => CandidateEducation::with('degreeLevel')
                ->where('candidate_id', $candidateId)
                ->orderBy('sort_order')
                ->orderByDesc('id')
                ->get(),
            'experiences' => CandidateExperience::with('expertises')
                ->where('candidate_id', $candidateId)
                ->orderBy('sort_order')
                ->orderByDesc('id')
                ->get(),
            'trainings' => CandidateTraining::where('candidate_id', $candidateId)
                ->orderBy('sort_order')
                ->orderByDesc('id')
                ->get(),
            'skills' => CandidateSkill::with('skill')
                ->where('user_id', $candidate->user_id)
                ->get(),
            'accomplishments' => CandidateAccomplishment::where('candidate_id', $candidateId)
                ->orderBy('sort_order')
                ->orderByDesc('id')
                ->get(),
            'links' => CandidateLink::where('candidate_id', $candidateId)
                ->orderBy('sort_order')
                ->get(),
            'references' => CandidateReference::where('candidate_id', $candidateId)
                ->orderBy('sort_order')
                ->get(),
            'extraCurriculars' => CandidateExtraCurricular::where('candidate_id', $candidateId)->get(),
        ];
    }

    private function plainText(?string $value): string
    {
        if (blank($value)) {
            return '';
        }

        $text = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/<\s*br\s*\/?>/i', "\n", $text);
        $text = preg_replace('/<\s*li[^>]*>/i', '• ', $text);
        $text = preg_replace('/<\s*\/\s*(p|div|li|h[1-6]|ul|ol)\s*>/i', "\n", $text);
        $text = strip_tags($text);
        $text = preg_replace("/[ \t]+\n/", "\n", $text);
        $text = preg_replace("/\n{3,}/", "\n\n", $text);

        return trim($text);
    }
}
