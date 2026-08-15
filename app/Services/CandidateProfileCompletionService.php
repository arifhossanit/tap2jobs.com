<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\CandidateEducation;
use App\Models\CandidateExperience;
use App\Models\CandidateSkill;

class CandidateProfileCompletionService
{
    public function calculate(Candidate $candidate): array
    {
        $user = $candidate->user;
        $checks = [
            'Basic information' => filled($user?->first_name) && filled($user?->email),
            'Contact number' => filled($user?->phone),
            'Location' => filled($user?->country_id) && filled($user?->state_id),
            'Career summary' => filled($candidate->objective) || filled($candidate->career_summary),
            'Career preferences' => filled($candidate->functional_area_id)
                || filled($candidate->career_level_id)
                || filled($candidate->job_nature),
            'Skills' => CandidateSkill::query()->where('user_id', $candidate->user_id)->exists(),
            'Preferred area' => ! empty($candidate->preferred_functional_categories)
                || ! empty($candidate->preferred_special_skills)
                || ! empty($candidate->preferred_job_locations_inside),
            'Education' => CandidateEducation::query()->where('candidate_id', $candidate->id)->exists(),
            'Experience' => CandidateExperience::query()->where('candidate_id', $candidate->id)->exists()
                || (int) $candidate->experience > 0,
            'Resume' => $candidate->getMedia(Candidate::RESUME_PATH)->isNotEmpty(),
        ];

        $completed = collect($checks)->filter()->count();
        $total = count($checks);
        $percentage = $total > 0 ? (int) round(($completed / $total) * 100) : 0;

        return [
            'percentage' => $percentage,
            'completed' => $completed,
            'total' => $total,
            'color' => $this->color($percentage),
        ];
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
