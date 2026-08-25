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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CandidateProfileCompletionService
{
    public function calculate(Candidate $candidate): array
    {
        $user = $candidate->user;
        $skillCount = CandidateSkill::query()->where('user_id', $candidate->user_id)->count();

        $checks = [
            'Basic information' => [
                'weight' => 10,
                'score' => $this->score([
                    [filled($user?->first_name), 3],
                    [filled($user?->last_name), 3],
                    [filled($user?->email), 4],
                ]),
                'missing' => $this->missing([
                    [filled($user?->first_name), 'Add first name'],
                    [filled($user?->last_name), 'Add last name'],
                    [filled($user?->email), 'Add email address'],
                ]),
            ],
            'Contact information' => [
                'weight' => 8,
                'score' => $this->score([
                    [filled($user?->phone), 6],
                    [
                        filled($candidate->secondary_mobile)
                            || filled($candidate->alternate_email)
                            || filled($candidate->emergency_contact),
                        2,
                    ],
                ]),
                'missing' => $this->missing([
                    [filled($user?->phone), 'Add primary phone number'],
                    [
                        filled($candidate->secondary_mobile)
                            || filled($candidate->alternate_email)
                            || filled($candidate->emergency_contact),
                        'Add secondary mobile, alternate email, or emergency contact',
                    ],
                ]),
            ],
            'Location' => [
                'weight' => 12,
                'score' => $this->score([
                    [filled($user?->country_id), 2],
                    [filled($user?->state_id), 2],
                    [filled($user?->city_id), 2],
                    [filled($user?->thana_id), 2],
                    [filled($candidate->address), 4],
                ]),
                'missing' => $this->missing([
                    [filled($user?->country_id), 'Select country'],
                    [filled($user?->state_id), 'Select division'],
                    [filled($user?->city_id), 'Select district'],
                    [filled($user?->thana_id), 'Select thana'],
                    [filled($candidate->address), 'Add address'],
                ]),
            ],
            'Career summary' => [
                'weight' => 8,
                'score' => $this->score([
                    [filled($candidate->objective), 4],
                    [filled($candidate->career_summary), 4],
                ]),
                'missing' => $this->missing([
                    [filled($candidate->objective), 'Add career objective'],
                    [filled($candidate->career_summary), 'Add career summary'],
                ]),
            ],
            'Career preferences' => [
                'weight' => 13,
                'score' => $this->score([
                    [filled($candidate->functional_area_id), 4],
                    [filled($candidate->career_level_id), 3],
                    [filled($candidate->job_nature), 3],
                    [filled($candidate->expected_salary), 3],
                ]),
                'missing' => $this->missing([
                    [filled($candidate->functional_area_id), 'Select functional area'],
                    [filled($candidate->career_level_id), 'Select career level'],
                    [filled($candidate->job_nature), 'Select job nature'],
                    [filled($candidate->expected_salary), 'Add expected salary'],
                ]),
            ],
            'Skills' => [
                'weight' => 12,
                'score' => min(12, $skillCount * 4),
                'missing' => $skillCount >= 3 ? [] : ['Add at least '.(3 - $skillCount).' more skill'.((3 - $skillCount) > 1 ? 's' : '')],
            ],
            'Preferred area' => [
                'weight' => 8,
                'score' => $this->score([
                    [! empty($candidate->preferred_functional_categories), 4],
                    [! empty($candidate->preferred_job_locations_inside), 4],
                ]),
                'missing' => $this->missing([
                    [! empty($candidate->preferred_functional_categories), 'Add preferred functional category'],
                    [! empty($candidate->preferred_job_locations_inside), 'Add preferred job location'],
                ]),
            ],
            'Education' => [
                'weight' => 14,
                'score' => CandidateEducation::query()->where('candidate_id', $candidate->id)->exists() ? 14 : 0,
                'missing' => CandidateEducation::query()->where('candidate_id', $candidate->id)->exists() ? [] : ['Add education'],
            ],
            'Training / certification' => [
                'weight' => 4,
                'score' => $this->hasTrainingOrCertification($candidate) ? 4 : 0,
                'missing' => $this->hasTrainingOrCertification($candidate) ? [] : ['Add training or certification'],
            ],
            'Experience' => [
                'weight' => 8,
                'score' => CandidateExperience::query()->where('candidate_id', $candidate->id)->exists()
                    || (int) $candidate->experience > 0 ? 8 : 0,
                'missing' => CandidateExperience::query()->where('candidate_id', $candidate->id)->exists()
                    || (int) $candidate->experience > 0 ? [] : ['Add job experience or total experience'],
            ],
            'Other profile' => [
                'weight' => 3,
                'score' => $this->hasSupportingProfileInformation($candidate) ? 3 : 0,
                'missing' => $this->hasSupportingProfileInformation($candidate) ? [] : ['Add language, link, reference, accomplishment, or extracurricular activity'],
            ],
        ];

        $completed = collect($checks)
            ->filter(fn (array $check) => $check['score'] >= $check['weight'])
            ->count();
        $total = count($checks);
        $percentage = collect($checks)
            ->sum('score');

        return [
            'percentage' => $percentage,
            'completed' => $completed,
            'total' => $total,
            'color' => $this->color($percentage),
            'breakdown' => collect($checks)
                ->map(fn (array $check, string $label) => [
                    'label' => $label,
                    'score' => $check['score'],
                    'weight' => $check['weight'],
                    'complete' => $check['score'] >= $check['weight'],
                    'missing' => $check['missing'],
                ])
                ->values()
                ->all(),
            'missing' => collect($checks)
                ->filter(fn (array $check) => $check['score'] < $check['weight'])
                ->flatMap(fn (array $check) => $check['missing'])
                ->values()
                ->all(),
        ];
    }

    private function score(array $items): int
    {
        return collect($items)
            ->filter(fn (array $item) => $item[0])
            ->sum(fn (array $item) => $item[1]);
    }

    private function missing(array $items): array
    {
        return collect($items)
            ->reject(fn (array $item) => $item[0])
            ->map(fn (array $item) => $item[1])
            ->values()
            ->all();
    }

    private function hasTrainingOrCertification(Candidate $candidate): bool
    {
        if (CandidateTraining::query()->where('candidate_id', $candidate->id)->exists()) {
            return true;
        }

        return Schema::hasTable('candidate_certifications')
            && DB::table('candidate_certifications')->where('candidate_id', $candidate->id)->exists();
    }

    private function hasSupportingProfileInformation(Candidate $candidate): bool
    {
        if (CandidateExtraCurricular::query()->where('candidate_id', $candidate->id)->exists()
            || CandidateLink::query()->where('candidate_id', $candidate->id)->exists()
            || CandidateReference::query()->where('candidate_id', $candidate->id)->exists()
            || CandidateAccomplishment::query()->where('candidate_id', $candidate->id)->exists()) {
            return true;
        }

        return Schema::hasTable('candidate_language')
            && DB::table('candidate_language')->where('user_id', $candidate->user_id)->exists();
    }

    private function color(int $percentage): string
    {
        if ($percentage >= 80) {
            return '#12b76a';
        }

        if ($percentage >= 50) {
            return '#f79009';
        }

        return '#f04438';
    }
}
