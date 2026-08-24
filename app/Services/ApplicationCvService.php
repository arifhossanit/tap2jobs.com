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
use App\Models\Country;
use App\Models\FunctionalArea;
use App\Models\OwnerShipType;
use App\Models\Skill;
use App\Models\State;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ApplicationCvService
{
    public const TITLE = 'Default';

    public const APPLICATION_CV_PROPERTY = 'is_application_cv';

    public function ensure(Candidate $candidate, bool $force = false): Media
    {
        $candidate->loadMissing('user');

        $resumes = $candidate->getMedia(Candidate::RESUME_PATH);
        $applicationCv = $resumes->first(
            fn (Media $media) => (bool) $media->getCustomProperty(self::APPLICATION_CV_PROPERTY, false)
        );

        if (! $force && $applicationCv && Storage::disk($applicationCv->disk)->exists($applicationCv->getPathRelativeToRoot())) {
            return $applicationCv;
        }

        $pdfContent = Pdf::loadView('candidate.profile.application_cv_pdf', $this->viewData($candidate))
            ->setPaper('a4')
            ->output();

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
                ->toMediaCollection(Candidate::RESUME_PATH, config('app.resume_disk'));

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
        $candidate->loadMissing(['user', 'maritalStatus', 'careerLevel', 'functionalArea']);

        $languages = collect();
        if (Schema::hasTable('candidate_language')) {
            $languageQuery = DB::table('candidate_language')
                ->join('languages', 'languages.id', '=', 'candidate_language.language_id')
                ->where('candidate_language.user_id', $candidate->user_id)
                ->select('languages.language');

            foreach (['proficiency_level', 'reading_level', 'writing_level', 'speaking_level'] as $column) {
                $languageQuery->addSelect(
                    Schema::hasColumn('candidate_language', $column)
                        ? "candidate_language.$column"
                        : DB::raw("NULL as $column")
                );
            }

            $languages = $languageQuery->orderBy('candidate_language.id')->get();
        }

        $certifications = Schema::hasTable('candidate_certifications')
            ? DB::table('candidate_certifications')
                ->where('candidate_id', $candidateId)
                ->orderBy('sort_order')
                ->orderByDesc('id')
                ->get()
            : collect();

        return [
            'candidate' => $candidate,
            'user' => $candidate->user,
            'plainText' => fn (?string $value): string => $this->plainText($value),
            'profilePhoto' => $this->profilePhotoDataUri($candidate->user),
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
            'certifications' => $certifications,
            'languages' => $languages,
            'preferredFunctionalAreas' => FunctionalArea::whereIn(
                'id',
                $candidate->preferred_functional_categories ?? []
            )->pluck('name'),
            'preferredSkills' => Skill::whereIn(
                'id',
                $candidate->preferred_special_skills ?? []
            )->pluck('name'),
            'preferredLocations' => State::whereIn(
                'id',
                $candidate->preferred_job_locations_inside ?? []
            )->pluck('name'),
            'preferredCountries' => Country::whereIn(
                'id',
                $candidate->preferred_job_locations_outside ?? []
            )->pluck('name'),
            'preferredOrganizations' => OwnerShipType::whereIn(
                'id',
                $candidate->preferred_organization_types ?? []
            )->pluck('name'),
        ];
    }

    private function profilePhotoDataUri(User $user): ?string
    {
        $profilePhoto = $user->getFirstMedia(User::PROFILE);

        if (! $profilePhoto || ! is_file($profilePhoto->getPath())) {
            return null;
        }

        return 'data:'.$profilePhoto->mime_type.';base64,'.base64_encode(
            file_get_contents($profilePhoto->getPath())
        );
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
