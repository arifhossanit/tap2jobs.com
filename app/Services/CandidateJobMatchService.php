<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\CandidateSkill;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Support\Collection;

class CandidateJobMatchService
{
    public function topMatches(Candidate $candidate, int $limit = 6): Collection
    {
        $candidateSkillIds = $this->candidateSkillIds($candidate);
        $preferredSkillIds = $this->ids($candidate->preferred_special_skills ?? []);
        $allCandidateSkillIds = $candidateSkillIds->merge($preferredSkillIds)->unique()->values();
        $preferredFunctionalIds = $this->ids($candidate->preferred_functional_categories ?? []);
        $preferredLocationIds = $this->ids($candidate->preferred_job_locations_inside ?? []);
        $appliedJobIds = JobApplication::query()
            ->where('candidate_id', $candidate->id)
            ->where('status', '!=', JobApplication::STATUS_DRAFT)
            ->pluck('job_id');

        $jobs = Job::query()
            ->with(['company', 'jobsSkill', 'jobCategory', 'functionalArea', 'city', 'state', 'country'])
            ->where('status', Job::STATUS_OPEN)
            ->where('is_suspended', Job::NOT_SUSPENDED)
            ->whereDate('job_expiry_date', '>=', now()->toDateString())
            ->when($appliedJobIds->isNotEmpty(), fn ($query) => $query->whereNotIn('id', $appliedJobIds))
            ->latest()
            ->limit(180)
            ->get();

        $matches = $jobs
            ->map(function (Job $job) use ($candidate, $allCandidateSkillIds, $preferredFunctionalIds, $preferredLocationIds) {
                return $this->scoreJob($job, $candidate, $allCandidateSkillIds, $preferredFunctionalIds, $preferredLocationIds);
            })
            ->sortByDesc(fn (Job $job) => [$job->match_score, optional($job->created_at)->timestamp ?? 0])
            ->values();

        $strongMatches = $matches->filter(fn (Job $job) => $job->match_score > 0)->take($limit)->values();

        if ($strongMatches->isNotEmpty()) {
            return $strongMatches;
        }

        return $matches
            ->take($limit)
            ->each(function (Job $job) {
                $job->match_score = 0;
                $job->match_reasons = ['Update your profile to improve matches'];
            })
            ->values();
    }

    private function scoreJob(
        Job $job,
        Candidate $candidate,
        Collection $candidateSkillIds,
        Collection $preferredFunctionalIds,
        Collection $preferredLocationIds
    ): Job {
        $score = 0;
        $reasons = [];

        $jobSkillIds = $job->jobsSkill->pluck('id')->map(fn ($id) => (int) $id)->unique();
        $matchedSkills = $jobSkillIds->intersect($candidateSkillIds);
        if ($jobSkillIds->isNotEmpty() && $matchedSkills->isNotEmpty()) {
            $skillScore = (int) round(($matchedSkills->count() / $jobSkillIds->count()) * 40);
            $score += min(40, max(15, $skillScore));
            $reasons[] = $matchedSkills->count().' skill'.($matchedSkills->count() > 1 ? 's' : '').' matched';
        }

        if ($candidate->functional_area_id && $job->functional_area_id === $candidate->functional_area_id) {
            $score += 20;
            $reasons[] = 'Functional area matched';
        } elseif ($preferredFunctionalIds->contains((int) $job->functional_area_id)) {
            $score += 16;
            $reasons[] = 'Preferred functional category matched';
        }

        if ($candidate->career_level_id && $job->career_level_id === $candidate->career_level_id) {
            $score += 12;
            $reasons[] = 'Career level matched';
        }

        if ($this->locationMatches($job, $candidate, $preferredLocationIds)) {
            $score += 14;
            $reasons[] = 'Location matched';
        }

        if ($this->experienceMatches($job, $candidate)) {
            $score += 10;
            $reasons[] = 'Experience matched';
        }

        if ($candidate->job_nature && $this->employmentStatusMatches($job->employment_status, $candidate->job_nature)) {
            $score += 4;
            $reasons[] = 'Job nature matched';
        }

        $job->match_score = min(100, $score);
        $job->match_reasons = array_slice($reasons, 0, 3);

        return $job;
    }

    private function candidateSkillIds(Candidate $candidate): Collection
    {
        return CandidateSkill::query()
            ->where('user_id', $candidate->user_id)
            ->pluck('skill_id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();
    }

    private function ids(array $values): Collection
    {
        return collect($values)
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->map(fn ($value) => (int) $value)
            ->unique()
            ->values();
    }

    private function locationMatches(Job $job, Candidate $candidate, Collection $preferredLocationIds): bool
    {
        $user = $candidate->user;

        return ($user?->city_id && $job->city_id === $user->city_id)
            || ($user?->state_id && $job->state_id === $user->state_id)
            || $preferredLocationIds->contains((int) $job->state_id)
            || $preferredLocationIds->contains((int) $job->city_id);
    }

    private function experienceMatches(Job $job, Candidate $candidate): bool
    {
        if ($job->freshers_encouraged && (int) $candidate->experience === 0) {
            return true;
        }

        if ($candidate->experience === null || $job->experience === null) {
            return false;
        }

        return (int) $candidate->experience >= (int) $job->experience;
    }

    private function employmentStatusMatches(?string $jobStatus, ?string $candidateNature): bool
    {
        $map = [
            'contract' => Job::EMPLOYMENT_STATUS_CONTRACTUAL,
            'full_time' => Job::EMPLOYMENT_STATUS_FULL_TIME,
            'part_time' => Job::EMPLOYMENT_STATUS_PART_TIME,
            'internship' => Job::EMPLOYMENT_STATUS_INTERNSHIP,
            'freelance' => Job::EMPLOYMENT_STATUS_FREELANCE,
        ];

        return isset($map[$candidateNature]) && $jobStatus === $map[$candidateNature];
    }
}
