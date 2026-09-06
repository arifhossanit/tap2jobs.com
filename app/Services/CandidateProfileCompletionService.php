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
    public const MINIMUM_APPLICATION_PERCENTAGE = 30;

    public function calculate(Candidate $candidate): array
    {
        $user = $candidate->user;
        $skillCount = CandidateSkill::query()->where('user_id', $candidate->user_id)->count();

        $checks = [
            'Personal details' => [
                'weight' => 30,
                'score' => $this->score([
                    [filled($user?->first_name), 3],
                    [filled($user?->last_name), 3],
                    [filled($user?->email), 4],
                    [filled($user?->phone), 4],
                    [filled($user?->dob), 2],
                    [filled($user?->gender), 1],
                    [filled($candidate->marital_status_id), 1],
                    [filled($candidate->father_name), 2],
                    [filled($candidate->mother_name), 2],
                    [filled($candidate->religion), 2],
                    [filled($candidate->nationality), 2],
                    [filled($candidate->national_id_card) || filled($candidate->passport_number), 1],
                    [
                        filled($candidate->secondary_mobile)
                            || filled($candidate->alternate_email)
                            || filled($candidate->emergency_contact),
                        1,
                    ],
                ]),
                'missing' => $this->missing([
                    [filled($user?->first_name), 'Add first name'],
                    [filled($user?->last_name), 'Add last name'],
                    [filled($user?->email), 'Add primary email address'],
                    [filled($user?->phone), 'Add primary phone number'],
                    [filled($user?->dob), 'Add date of birth'],
                    [filled($user?->gender), 'Select gender'],
                    [filled($candidate->marital_status_id), 'Select marital status'],
                    [filled($candidate->father_name), 'Add father name'],
                    [filled($candidate->mother_name), 'Add mother name'],
                    [filled($candidate->religion), 'Select religion'],
                    [filled($candidate->nationality), 'Add nationality'],
                    [filled($candidate->national_id_card) || filled($candidate->passport_number), 'Add national ID or passport number'],
                    [
                        filled($candidate->secondary_mobile)
                            || filled($candidate->alternate_email)
                            || filled($candidate->emergency_contact),
                        'Add secondary mobile, alternate email, or emergency contact',
                    ],
                ]),
            ],
            'Address details' => [
                'weight' => 10,
                'score' => $this->score([
                    [filled($candidate->present_address_type), 1],
                    [filled($user?->country_id), 1],
                    [filled($user?->state_id), 2],
                    [filled($user?->city_id), 2],
                    [filled($user?->thana_id), 1],
                    [filled($candidate->present_post_office), 1],
                    [filled($candidate->address), 1],
                    [
                        $candidate->permanent_same_as_present
                            || filled($candidate->permanent_address)
                            || filled($candidate->permanent_country_id),
                        1,
                    ],
                ]),
                'missing' => $this->missing([
                    [filled($candidate->present_address_type), 'Select present address type'],
                    [filled($user?->country_id), 'Select country'],
                    [filled($user?->state_id), 'Select division'],
                    [filled($user?->city_id), 'Select district'],
                    [filled($user?->thana_id), 'Select thana'],
                    [filled($candidate->present_post_office), 'Add post office'],
                    [filled($candidate->address), 'Add address'],
                    [
                        $candidate->permanent_same_as_present
                            || filled($candidate->permanent_address)
                            || filled($candidate->permanent_country_id),
                        'Add permanent address or mark it same as present',
                    ],
                ]),
            ],
            'Career and application' => [
                'weight' => 3,
                'score' => $this->score([
                    [filled($candidate->objective), 1],
                    [filled($candidate->job_level), 1],
                    [filled($candidate->job_nature), 1],
                ]),
                'missing' => $this->missing([
                    [filled($candidate->objective), 'Add career objective'],
                    [filled($candidate->job_level), 'Select job level'],
                    [filled($candidate->job_nature), 'Select job nature'],
                ]),
            ],
            'Preferred area' => [
                'weight' => 4,
                'score' => $this->score([
                    [! empty($candidate->preferred_functional_categories), 2],
                    [! empty($candidate->preferred_job_locations_inside), 1],
                    [
                        ! empty($candidate->preferred_special_skills)
                            || ! empty($candidate->preferred_job_locations_outside)
                            || ! empty($candidate->preferred_organization_types),
                        1,
                    ],
                ]),
                'missing' => $this->missing([
                    [! empty($candidate->preferred_functional_categories), 'Add preferred functional category'],
                    [! empty($candidate->preferred_job_locations_inside), 'Add preferred job location'],
                    [
                        ! empty($candidate->preferred_special_skills)
                            || ! empty($candidate->preferred_job_locations_outside)
                            || ! empty($candidate->preferred_organization_types),
                        'Add preferred skill, outside location, or organization type',
                    ],
                ]),
            ],
            'Relevant information' => [
                'weight' => 3,
                'score' => $this->score([
                    [filled($candidate->career_summary), 1],
                    [filled($candidate->special_qualification), 1],
                    [filled($candidate->keywords), 1],
                ]),
                'missing' => $this->missing([
                    [filled($candidate->career_summary), 'Add career summary'],
                    [filled($candidate->special_qualification), 'Add special qualification'],
                    [filled($candidate->keywords), 'Add keywords'],
                ]),
            ],
            'Skills' => [
                'weight' => 12,
                'score' => min(12, $skillCount * 4),
                'missing' => $skillCount >= 3 ? [] : ['Add at least '.(3 - $skillCount).' more skill'.((3 - $skillCount) > 1 ? 's' : '')],
            ],
            'Education' => [
                'weight' => 16,
                'score' => CandidateEducation::query()->where('candidate_id', $candidate->id)->exists() ? 16 : 0,
                'missing' => CandidateEducation::query()->where('candidate_id', $candidate->id)->exists() ? [] : ['Add education'],
            ],
            'Training / certification' => [
                'weight' => 7,
                'score' => $this->hasTrainingOrCertification($candidate) ? 7 : 0,
                'missing' => $this->hasTrainingOrCertification($candidate) ? [] : ['Add training or certification'],
            ],
            'Experience' => [
                'weight' => 12,
                'score' => CandidateExperience::query()->where('candidate_id', $candidate->id)->exists()
                    || (int) $candidate->experience > 0 ? 12 : 0,
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
        if ($percentage >= self::MINIMUM_APPLICATION_PERCENTAGE) {
            return '#12b76a';
        }

        if ($percentage >= 30) {
            return '#f79009';
        }

        return '#f04438';
    }
}
