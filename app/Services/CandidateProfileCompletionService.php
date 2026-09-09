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
                    [filled($user?->dob), 3],
                    [filled($user?->gender), 2],
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
                    [! empty($candidate->preferred_job_categories) || ! empty($candidate->preferred_functional_categories), 2],
                    [! empty($candidate->preferred_job_locations_inside), 1],
                    [
                        ! empty($candidate->preferred_special_skills)
                            || ! empty($candidate->preferred_job_locations_outside)
                            || ! empty($candidate->preferred_organization_types),
                        1,
                    ],
                ]),
                'missing' => $this->missing([
                    [! empty($candidate->preferred_job_categories) || ! empty($candidate->preferred_functional_categories), 'Add preferred job category'],
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
                'weight' => 5,
                'score' => $skillCount > 0 ? 5 : 0,
                'missing' => $skillCount > 0 ? [] : ['Add skill'],
            ],
            'Education' => [
                'weight' => 20,
                'score' => CandidateEducation::query()->where('candidate_id', $candidate->id)->exists() ? 20 : 0,
                'missing' => CandidateEducation::query()->where('candidate_id', $candidate->id)->exists() ? [] : ['Add education'],
            ],
            'Training / certification' => [
                'weight' => 5,
                'score' => ($this->hasTraining($candidate) ? 3 : 0) + ($this->hasCertification($candidate) ? 2 : 0),
                'missing' => array_values(array_filter([
                    $this->hasTraining($candidate) ? null : 'Add training',
                    $this->hasCertification($candidate) ? null : 'Add professional certification',
                ])),
            ],
            'Experience' => [
                'weight' => 10,
                'score' => CandidateExperience::query()->where('candidate_id', $candidate->id)->exists()
                    || (int) $candidate->experience > 0 ? 10 : 0,
                'missing' => CandidateExperience::query()->where('candidate_id', $candidate->id)->exists()
                    || (int) $candidate->experience > 0 ? [] : ['Add job experience or total experience'],
            ],
            'Other profile' => [
                'weight' => 5,
                'score' => $this->supportingProfileScore($candidate),
                'missing' => $this->supportingProfileMissing($candidate),
            ],
            'Accomplishment' => [
                'weight' => 5,
                'score' => $this->accomplishmentScore($candidate),
                'missing' => $this->accomplishmentMissing($candidate),
            ],
        ];

        $tabs = [
            'Personal details' => $this->combineChecks($checks, [
                'Personal details', 'Address details', 'Career and application', 'Preferred area', 'Relevant information',
            ]),
            'Education' => $this->combineChecks($checks, ['Education', 'Training / certification']),
            'Employment' => $this->combineChecks($checks, ['Experience']),
            'Other information' => $this->combineChecks($checks, ['Skills', 'Other profile']),
            'Accomplishment' => $this->combineChecks($checks, ['Accomplishment']),
        ];

        $completed = collect($tabs)
            ->filter(fn (array $check) => $check['score'] >= $check['weight'])
            ->count();
        $total = count($tabs);
        $percentage = collect($tabs)
            ->sum('score');

        return [
            'percentage' => $percentage,
            'completed' => $completed,
            'total' => $total,
            'color' => $this->color($percentage),
            'breakdown' => collect($tabs)
                ->map(fn (array $check, string $label) => [
                    'label' => $label,
                    'score' => $check['score'],
                    'weight' => $check['weight'],
                    'complete' => $check['score'] >= $check['weight'],
                    'missing' => $check['missing'],
                ])
                ->values()
                ->all(),
            'missing' => collect($tabs)
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

    private function combineChecks(array $checks, array $labels): array
    {
        $selected = collect($labels)->map(fn (string $label) => $checks[$label]);

        return [
            'weight' => $selected->sum('weight'),
            'score' => $selected->sum('score'),
            'missing' => $selected->flatMap(fn (array $check) => $check['missing'])->values()->all(),
        ];
    }

    private function hasTraining(Candidate $candidate): bool
    {
        return CandidateTraining::query()->where('candidate_id', $candidate->id)->exists();
    }

    private function hasCertification(Candidate $candidate): bool
    {
        return Schema::hasTable('candidate_certifications')
            && DB::table('candidate_certifications')->where('candidate_id', $candidate->id)->exists();
    }

    private function supportingProfileStates(Candidate $candidate): array
    {
        return [
            [CandidateExtraCurricular::query()->where('candidate_id', $candidate->id)->exists(), 'Add extracurricular activity'],
            [Schema::hasTable('candidate_language') && DB::table('candidate_language')->where('user_id', $candidate->user_id)->exists(), 'Add language proficiency'],
            [CandidateLink::query()->where('candidate_id', $candidate->id)->exists(), 'Add link account'],
            [CandidateReference::query()->where('candidate_id', $candidate->id)->exists(), 'Add reference'],
        ];
    }

    private function supportingProfileScore(Candidate $candidate): int
    {
        $states = $this->supportingProfileStates($candidate);
        $completed = collect($states)->filter(fn (array $item) => $item[0])->count();

        return $completed + ($completed === count($states) ? 1 : 0);
    }

    private function supportingProfileMissing(Candidate $candidate): array
    {
        $states = $this->supportingProfileStates($candidate);
        $missing = collect($states)->reject(fn (array $item) => $item[0])->pluck(1)->values()->all();

        if ($missing !== []) {
            $missing[] = 'Complete all Other Information sections for the final point';
        }

        return $missing;
    }

    private function accomplishmentStates(Candidate $candidate): array
    {
        $types = CandidateAccomplishment::query()
            ->where('candidate_id', $candidate->id)
            ->pluck('type')
            ->unique();

        return [
            [$types->contains(CandidateAccomplishment::TYPE_PORTFOLIO), 'Add portfolio'],
            [$types->contains(CandidateAccomplishment::TYPE_PUBLICATION), 'Add publication'],
            [$types->contains(CandidateAccomplishment::TYPE_AWARD), 'Add award'],
            [$types->contains(CandidateAccomplishment::TYPE_PROJECT), 'Add project'],
            [$types->contains(CandidateAccomplishment::TYPE_OTHER), 'Add other accomplishment'],
        ];
    }

    private function accomplishmentScore(Candidate $candidate): int
    {
        return collect($this->accomplishmentStates($candidate))->filter(fn (array $item) => $item[0])->count();
    }

    private function accomplishmentMissing(Candidate $candidate): array
    {
        return collect($this->accomplishmentStates($candidate))
            ->reject(fn (array $item) => $item[0])
            ->pluck(1)
            ->values()
            ->all();
    }

    private function color(int $percentage): string
    {
        if ($percentage <= 30) {
            return '#f04438';
        }

        if ($percentage <= 60) {
            return '#f79009';
        }

        return '#12b76a';
    }
}
