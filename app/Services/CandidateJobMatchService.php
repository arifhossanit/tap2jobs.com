<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\CandidateExperience;
use App\Models\CandidateSkill;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Skill;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class CandidateJobMatchService
{
    public function topMatches(Candidate $candidate, int $limit = 8): Collection
    {
        $candidateSkillIds = $this->candidateSkillIds($candidate);
        $preferredSkillIds = $this->ids($candidate->preferred_special_skills ?? []);
        $allCandidateSkillIds = $candidateSkillIds->merge($preferredSkillIds)->unique()->values();
        $preferredSkillNames = $preferredSkillIds->isEmpty()
            ? collect()
            : Skill::query()->whereIn('id', $preferredSkillIds)->pluck('name', 'id');
        $preferredFunctionalIds = $this->ids($candidate->preferred_functional_categories ?? []);
        $preferredLocationIds = $this->ids($candidate->preferred_job_locations_inside ?? []);
        $candidateKeywords = $this->candidateKeywords($candidate, $allCandidateSkillIds);

        if (! $this->hasMatchingSignals($candidate, $allCandidateSkillIds, $preferredFunctionalIds, $preferredLocationIds, $candidateKeywords)) {
            return collect();
        }

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
            ->map(function (Job $job) use ($candidate, $candidateSkillIds, $preferredSkillIds, $preferredSkillNames, $preferredFunctionalIds, $preferredLocationIds, $candidateKeywords) {
                return $this->scoreJob($job, $candidate, $candidateSkillIds, $preferredSkillIds, $preferredSkillNames, $preferredFunctionalIds, $preferredLocationIds, $candidateKeywords);
            })
            ->sortByDesc(fn (Job $job) => [$job->match_score, optional($job->created_at)->timestamp ?? 0])
            ->values();

        return $matches->filter(fn (Job $job) => $job->match_score > 0)->take($limit)->values();
    }

    private function scoreJob(
        Job $job,
        Candidate $candidate,
        Collection $candidateSkillIds,
        Collection $preferredSkillIds,
        Collection $preferredSkillNames,
        Collection $preferredFunctionalIds,
        Collection $preferredLocationIds,
        Collection $candidateKeywords
    ): Job {
        $score = 0;
        $reasons = [];

        $jobSkillIds = $job->jobsSkill->pluck('id')->map(fn ($id) => (int) $id)->unique();
        $matchedSkills = $jobSkillIds->intersect($candidateSkillIds);
        if ($jobSkillIds->isNotEmpty() && $matchedSkills->isNotEmpty()) {
            $skillScore = min(24, (int) round(($matchedSkills->count() / max($jobSkillIds->count(), 1)) * 24));
            $score += $skillScore;
            $reasons[] = $matchedSkills->count().' skill'.($matchedSkills->count() > 1 ? 's' : '').' matched';
        }

        $matchedPreferredSkillCount = $this->preferredSkillMatchCount($job, $jobSkillIds, $preferredSkillIds, $preferredSkillNames);
        if ($matchedPreferredSkillCount > 0) {
            $preferredSkillScore = min(10, $matchedPreferredSkillCount * 4);
            $score += $preferredSkillScore;
            $reasons[] = $matchedPreferredSkillCount.' preferred special skill'.($matchedPreferredSkillCount > 1 ? 's' : '').' matched';
        }

        if ($candidate->functional_area_id && $job->functional_area_id === $candidate->functional_area_id) {
            $score += 17;
            $reasons[] = 'Functional area matched';
        } elseif ($preferredFunctionalIds->contains((int) $job->functional_area_id)) {
            $score += 14;
            $reasons[] = 'Preferred functional category matched';
        }

        $keywordScore = $this->keywordScore($job, $candidateKeywords);
        if ($keywordScore > 0) {
            $score += $keywordScore;
            $reasons[] = 'Job title/profile keywords matched';
        }

        $experienceScore = $this->experienceScore($job, $candidate);
        if ($experienceScore > 0) {
            $score += $experienceScore;
            $reasons[] = $experienceScore === 12 ? 'Experience matched' : 'Experience close match';
        }

        $locationScore = $this->locationScore($job, $candidate, $preferredLocationIds);
        if ($locationScore > 0) {
            $score += $locationScore;
            $reasons[] = $locationScore >= 9 ? 'Location matched' : 'Nearby/preferred location matched';
        }

        if ($candidate->career_level_id && $job->career_level_id === $candidate->career_level_id) {
            $score += 6;
            $reasons[] = 'Career level matched';
        }

        if ($this->salaryMatches($job, $candidate)) {
            $score += 5;
            $reasons[] = 'Salary expectation fits';
        }

        if ($this->industryMatches($job, $candidate)) {
            $score += 5;
            $reasons[] = 'Industry matched';
        }

        if ($candidate->job_nature && $this->employmentStatusMatches($job->employment_status, $candidate->job_nature)) {
            $score += 2;
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

    private function candidateKeywords(Candidate $candidate, Collection $candidateSkillIds): Collection
    {
        $skillNames = $candidateSkillIds->isEmpty()
            ? collect()
            : Skill::query()->whereIn('id', $candidateSkillIds)->pluck('name');

        return $this->keywords(collect([
            $candidate->objective,
            $candidate->career_summary,
            $candidate->special_qualification,
            $candidate->keywords,
            optional($candidate->functionalArea)->name,
            optional($candidate->careerLevel)->level_name,
            $skillNames->implode(' '),
        ])->filter()->implode(' '));
    }

    private function hasMatchingSignals(
        Candidate $candidate,
        Collection $candidateSkillIds,
        Collection $preferredFunctionalIds,
        Collection $preferredLocationIds,
        Collection $candidateKeywords
    ): bool {
        $user = $candidate->user;

        return $candidateSkillIds->isNotEmpty()
            || $preferredFunctionalIds->isNotEmpty()
            || $preferredLocationIds->isNotEmpty()
            || $candidateKeywords->isNotEmpty()
            || $this->candidateExperienceYears($candidate) !== null
            || filled($candidate->functional_area_id)
            || filled($candidate->career_level_id)
            || filled($candidate->expected_salary)
            || filled($candidate->industry_id)
            || filled($candidate->job_nature)
            || filled($user?->country_id)
            || filled($user?->state_id)
            || filled($user?->city_id)
            || filled($user?->thana_id);
    }

    private function preferredSkillMatchCount(
        Job $job,
        Collection $jobSkillIds,
        Collection $preferredSkillIds,
        Collection $preferredSkillNames
    ): int {
        if ($preferredSkillIds->isEmpty()) {
            return 0;
        }

        $matchedSkillIds = $jobSkillIds->intersect($preferredSkillIds);
        $jobKeywords = $this->keywords(collect([
            $job->job_title,
            $job->description,
            $job->key_responsibilities ?? null,
            optional($job->functionalArea)->name,
            optional($job->jobCategory)->name,
            $job->jobsSkill->pluck('name')->implode(' '),
        ])->filter()->implode(' '));

        $matchedByName = $preferredSkillNames
            ->filter(function ($skillName) use ($jobKeywords) {
                $skillKeywords = $this->keywords($skillName);

                return $skillKeywords->isNotEmpty() && $skillKeywords->intersect($jobKeywords)->isNotEmpty();
            })
            ->keys()
            ->map(fn ($id) => (int) $id);

        return $matchedSkillIds
            ->merge($matchedByName)
            ->unique()
            ->count();
    }

    private function ids(array $values): Collection
    {
        return collect($values)
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->map(fn ($value) => (int) $value)
            ->unique()
            ->values();
    }

    private function keywordScore(Job $job, Collection $candidateKeywords): int
    {
        if ($candidateKeywords->isEmpty()) {
            return 0;
        }

        $jobTitleKeywords = $this->keywords($job->job_title);
        $jobBodyKeywords = $this->keywords(collect([
            $job->description,
            $job->key_responsibilities ?? null,
            optional($job->functionalArea)->name,
            optional($job->jobCategory)->name,
        ])->filter()->implode(' '));

        $titleMatches = $jobTitleKeywords->intersect($candidateKeywords)->count();
        $bodyMatches = $jobBodyKeywords->intersect($candidateKeywords)->count();

        $titleScore = $jobTitleKeywords->isNotEmpty()
            ? min(10, (int) round(($titleMatches / $jobTitleKeywords->count()) * 10))
            : 0;
        $bodyScore = min(5, $bodyMatches);

        return min(15, $titleScore + $bodyScore);
    }

    private function keywords(?string $text): Collection
    {
        $stopWords = [
            'and', 'for', 'the', 'with', 'from', 'this', 'that', 'your', 'you', 'job', 'jobs',
            'work', 'will', 'are', 'our', 'candidate', 'experience', 'year', 'years', 'full',
            'time', 'part', 'ltd', 'limited', 'company', 'bangladesh', 'bd',
        ];

        return Str::of(strip_tags((string) $text))
            ->lower()
            ->replaceMatches('/[^a-z0-9+#.]+/', ' ')
            ->explode(' ')
            ->map(fn ($word) => trim($word))
            ->filter(fn ($word) => strlen($word) >= 3 && ! in_array($word, $stopWords, true))
            ->unique()
            ->values();
    }

    private function locationScore(Job $job, Candidate $candidate, Collection $preferredLocationIds): int
    {
        $user = $candidate->user;

        if (($user?->thana_id && $job->thana_id === $user->thana_id)
            || ($user?->city_id && $job->city_id === $user->city_id)) {
            return 10;
        }

        if (($user?->state_id && $job->state_id === $user->state_id)
            || $preferredLocationIds->contains((int) $job->city_id)) {
            return 7;
        }

        if (($user?->country_id && $job->country_id === $user->country_id)
            || $preferredLocationIds->contains((int) $job->state_id)) {
            return 4;
        }

        return 0;
    }

    private function experienceScore(Job $job, Candidate $candidate): int
    {
        $candidateExperience = $this->candidateExperienceYears($candidate);

        if ($candidateExperience === null || $job->experience === null) {
            return 0;
        }

        if ($job->freshers_encouraged && $candidateExperience === 0) {
            return 12;
        }

        $requiredExperience = (int) $job->experience;

        if ($candidateExperience >= $requiredExperience) {
            return 12;
        }

        return ($requiredExperience - $candidateExperience) <= 1 ? 6 : 0;
    }

    private function candidateExperienceYears(Candidate $candidate): ?int
    {
        if ($candidate->experience !== null) {
            return (int) $candidate->experience;
        }

        $experienceMonths = CandidateExperience::query()
            ->where('candidate_id', $candidate->id)
            ->get(['start_date', 'end_date', 'currently_working'])
            ->sum(function (CandidateExperience $experience): int {
                if (! $experience->start_date) {
                    return 0;
                }

                $endDate = $experience->currently_working ? now() : ($experience->end_date ?: now());

                return max(1, $experience->start_date->diffInMonths($endDate));
            });

        if ($experienceMonths <= 0) {
            return null;
        }

        return (int) floor($experienceMonths / 12);
    }

    private function salaryMatches(Job $job, Candidate $candidate): bool
    {
        if (! filled($candidate->expected_salary) || ! filled($job->salary_from) || ! filled($job->salary_to)) {
            return false;
        }

        $expectedSalary = (float) $candidate->expected_salary;
        $salaryFrom = (float) preg_replace('/[^\d.]/', '', (string) $job->salary_from);
        $salaryTo = (float) preg_replace('/[^\d.]/', '', (string) $job->salary_to);

        if ($salaryFrom <= 0 || $salaryTo <= 0) {
            return false;
        }

        return $expectedSalary >= $salaryFrom && $expectedSalary <= $salaryTo;
    }

    private function industryMatches(Job $job, Candidate $candidate): bool
    {
        if (! filled($candidate->industry_id) || ! $job->company) {
            return false;
        }

        $companyIndustryIds = collect($job->company->industry_ids ?? [])
            ->push($job->company->industry_id)
            ->filter()
            ->map(fn ($id) => (int) $id);

        return $companyIndustryIds->contains((int) $candidate->industry_id);
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
