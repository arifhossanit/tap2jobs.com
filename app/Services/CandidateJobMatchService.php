<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\CandidateEducation;
use App\Models\CandidateExperience;
use App\Models\CandidateSkill;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class CandidateJobMatchService
{
    public const SCORE_WEIGHTS = [
        'skills' => 20,
        'preferred_category' => 30,
        'keywords' => 5,
        'experience' => 15,
        'location' => 15,
        'education' => 10,
        'job_nature' => 3,
        'salary' => 2,
    ];

    private array $candidateExperienceMonthsCache = [];

    public function topMatches(Candidate $candidate, ?int $limit = 8): Collection
    {
        $candidateSkillIds = $this->candidateSkillIds($candidate);
        $preferredCategoryIds = $this->ids($candidate->preferred_job_categories ?? []);
        $preferredLocationIds = $this->ids($candidate->preferred_job_locations_inside ?? []);
        $candidateKeywords = $this->candidateKeywords($candidate);

        if (! $this->hasMatchingSignals($candidate, $candidateSkillIds, $preferredCategoryIds, $preferredLocationIds, $candidateKeywords)) {
            return collect();
        }

        $appliedJobIds = JobApplication::query()
            ->where('candidate_id', $candidate->id)
            ->where('status', '!=', JobApplication::STATUS_DRAFT)
            ->pluck('job_id');

        $jobsQuery = Job::query()
            ->with(['company', 'jobsSkill', 'jobCategory', 'jobCategories', 'city', 'state', 'country', 'locations'])
            ->where('status', Job::STATUS_OPEN)
            ->where('is_suspended', Job::NOT_SUSPENDED)
            ->whereDate('job_expiry_date', '>=', now()->toDateString())
            ->when($appliedJobIds->isNotEmpty(), fn ($query) => $query->whereNotIn('id', $appliedJobIds))
            ->latest();

        $jobs = $limit === null
            ? $jobsQuery->get()
            : $jobsQuery->limit(180)->get();

        $matches = $jobs
            ->map(function (Job $job) use ($candidate, $candidateSkillIds, $preferredCategoryIds, $preferredLocationIds, $candidateKeywords) {
                return $this->scoreJob($job, $candidate, $candidateSkillIds, $preferredCategoryIds, $preferredLocationIds, $candidateKeywords);
            })
            ->sortByDesc(fn (Job $job) => [$job->match_score, optional($job->created_at)->timestamp ?? 0])
            ->values();

        $matches = $matches->filter(fn (Job $job) => $job->match_score > 0);

        return $limit === null ? $matches->values() : $matches->take($limit)->values();
    }

    private function scoreJob(
        Job $job,
        Candidate $candidate,
        Collection $candidateSkillIds,
        Collection $preferredCategoryIds,
        Collection $preferredLocationIds,
        Collection $candidateKeywords
    ): Job {
        $breakdown = array_fill_keys(array_keys(self::SCORE_WEIGHTS), 0);
        $reasons = collect();

        $jobSkillIds = $job->jobsSkill->pluck('id')->map(fn ($id) => (int) $id)->unique();
        $matchedSkills = $jobSkillIds->intersect($candidateSkillIds);
        if ($jobSkillIds->isNotEmpty() && $matchedSkills->isNotEmpty()) {
            $breakdown['skills'] = min(self::SCORE_WEIGHTS['skills'], (int) round(
                ($matchedSkills->count() / max($jobSkillIds->count(), 1)) * self::SCORE_WEIGHTS['skills']
            ));
            $reasons->push(['label' => $matchedSkills->count().' skill'.($matchedSkills->count() > 1 ? 's' : '').' matched', 'score' => $breakdown['skills']]);
        }

        $jobCategoryIds = $job->jobCategories->pluck('id')
            ->push($job->job_category_id)
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique();
        if ($preferredCategoryIds->intersect($jobCategoryIds)->isNotEmpty()) {
            $breakdown['preferred_category'] = self::SCORE_WEIGHTS['preferred_category'];
            $reasons->push(['label' => 'Preferred job category matched', 'score' => $breakdown['preferred_category']]);
        }

        $keywordScore = $this->keywordScore($job, $candidateKeywords);
        if ($keywordScore > 0) {
            $breakdown['keywords'] = $keywordScore;
            $reasons->push(['label' => 'Job title/profile keywords matched', 'score' => $keywordScore]);
        }

        $experienceScore = $this->experienceScore($job, $candidate);
        if ($experienceScore > 0) {
            $breakdown['experience'] = $experienceScore;
            $reasons->push(['label' => $experienceScore === self::SCORE_WEIGHTS['experience'] ? 'Experience matched' : 'Experience close match', 'score' => $experienceScore]);
        }

        $locationScore = $this->locationScore($job, $candidate, $preferredLocationIds);
        if ($locationScore > 0) {
            $breakdown['location'] = $locationScore;
            $reasons->push(['label' => $locationScore === self::SCORE_WEIGHTS['location'] ? 'Location matched' : 'Nearby location matched', 'score' => $locationScore]);
        }

        $educationScore = $this->educationScore($job, $candidate);
        if ($educationScore > 0) {
            $breakdown['education'] = $educationScore;
            $reasons->push(['label' => 'Education level matched', 'score' => $educationScore]);
        }

        if ($this->salaryMatches($job, $candidate)) {
            $breakdown['salary'] = self::SCORE_WEIGHTS['salary'];
            $reasons->push(['label' => 'Salary expectation fits', 'score' => $breakdown['salary']]);
        }

        if ($candidate->job_nature && $this->employmentStatusMatches($job->employment_status, $candidate->job_nature)) {
            $breakdown['job_nature'] = self::SCORE_WEIGHTS['job_nature'];
            $reasons->push(['label' => 'Job nature matched', 'score' => $breakdown['job_nature']]);
        }

        $job->match_score = min(100, array_sum($breakdown));
        $job->match_breakdown = collect($breakdown)->map(fn (int $score, string $key) => [
            'key' => $key,
            'score' => $score,
            'weight' => self::SCORE_WEIGHTS[$key],
        ])->values()->all();
        $job->match_reasons = $reasons->sortByDesc('score')->pluck('label')->take(3)->values()->all();

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

    private function candidateKeywords(Candidate $candidate): Collection
    {
        return $this->keywords(collect([
            $candidate->objective,
            $candidate->career_summary,
            $candidate->special_qualification,
            $candidate->keywords,
        ])->filter()->implode(' '));
    }

    private function hasMatchingSignals(
        Candidate $candidate,
        Collection $candidateSkillIds,
        Collection $preferredCategoryIds,
        Collection $preferredLocationIds,
        Collection $candidateKeywords
    ): bool {
        $user = $candidate->user;

        return $candidateSkillIds->isNotEmpty()
            || $preferredCategoryIds->isNotEmpty()
            || $preferredLocationIds->isNotEmpty()
            || $candidateKeywords->isNotEmpty()
            || $this->candidateExperienceMonths($candidate) !== null
            || filled($candidate->expected_salary)
            || filled($candidate->job_nature)
            || filled($user?->country_id)
            || filled($user?->state_id)
            || filled($user?->city_id)
            || filled($user?->thana_id);
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
            ? min(3, (int) round(($titleMatches / $jobTitleKeywords->count()) * 3))
            : 0;
        $bodyScore = min(2, $bodyMatches);

        return min(self::SCORE_WEIGHTS['keywords'], $titleScore + $bodyScore);
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

        if ($job->anywhere_in_bangladesh) {
            return self::SCORE_WEIGHTS['location'];
        }

        $locations = \Illuminate\Support\Facades\Schema::hasTable('job_locations') && $job->locations->isNotEmpty()
            ? $job->locations
            : collect([(object) [
                'country_id' => $job->country_id,
                'state_id' => $job->state_id,
                'city_id' => $job->city_id,
                'thana_id' => $job->thana_id,
            ]]);

        foreach ($locations as $location) {
            if (($user?->thana_id && $location->thana_id === $user->thana_id)
                || ($user?->city_id && $location->city_id === $user->city_id)
                || $preferredLocationIds->contains((int) $location->city_id)) {
                return self::SCORE_WEIGHTS['location'];
            }
        }

        foreach ($locations as $location) {
            if ($user?->state_id && $location->state_id === $user->state_id) {
                return 10;
            }
        }

        foreach ($locations as $location) {
            if ($user?->country_id && $location->country_id === $user->country_id) {
                return 5;
            }
        }

        return 0;
    }

    private function experienceScore(Job $job, Candidate $candidate): int
    {
        $candidateExperience = $this->candidateExperienceMonths($candidate);
        [$minimumExperience] = $this->requiredExperienceMonths($job);

        if ($candidateExperience === null) {
            return 0;
        }

        if ($job->freshers_encouraged && $candidateExperience === 0) {
            return self::SCORE_WEIGHTS['experience'];
        }

        if ($candidateExperience >= $minimumExperience) {
            return self::SCORE_WEIGHTS['experience'];
        }

        $gap = $minimumExperience - $candidateExperience;

        if ($gap <= 6) {
            return 10;
        }

        return $gap <= 12 ? 6 : 0;
    }

    private function educationScore(Job $job, Candidate $candidate): int
    {
        if (! filled($job->degree_level_id)) {
            return self::SCORE_WEIGHTS['education'];
        }

        return CandidateEducation::query()
            ->where('candidate_id', $candidate->id)
            ->where('degree_level_id', $job->degree_level_id)
            ->exists()
                ? self::SCORE_WEIGHTS['education']
                : 0;
    }

    /**
     * @return array{0: int, 1: int}
     */
    public function requiredExperienceMonths(Job $job): array
    {
        $requirement = trim((string) $job->experience_requirement);
        $unit = (string) $job->experience_unit;

        if ($requirement === '') {
            $months = max(0, (int) $job->experience) * 12;

            return [$months, $months];
        }

        $parts = preg_split('/\s*(?:-|\x{2013}|\x{2014}|\bto\b)\s*/iu', $requirement, 2);

        if (count($parts) === 2) {
            $minimum = $this->durationInMonths($parts[0], $unit);
            $maximum = $this->durationInMonths($parts[1], $unit);

            return [min($minimum, $maximum), max($minimum, $maximum)];
        }

        $months = $this->durationInMonths($requirement, $unit);

        return [$months, $months];
    }

    public function candidateExperienceMonths(Candidate $candidate): ?int
    {
        if (array_key_exists($candidate->id, $this->candidateExperienceMonthsCache)) {
            return $this->candidateExperienceMonthsCache[$candidate->id];
        }

        $intervals = CandidateExperience::query()
            ->where('candidate_id', $candidate->id)
            ->whereNotNull('start_date')
            ->get(['start_date', 'end_date', 'currently_working'])
            ->map(function (CandidateExperience $experience): array {
                $start = $experience->start_date->copy()->startOfDay();
                $end = $experience->currently_working || ! $experience->end_date
                    ? now()->startOfDay()
                    : $experience->end_date->copy()->startOfDay();

                return [$start, $end->lt($start) ? $start->copy() : $end];
            })
            ->sortBy(fn (array $interval) => $interval[0]->getTimestamp())
            ->values();

        if ($intervals->isEmpty()) {
            return $this->candidateExperienceMonthsCache[$candidate->id] = $candidate->experience !== null
                ? max(0, (int) $candidate->experience) * 12
                : null;
        }

        $merged = [];
        foreach ($intervals as [$start, $end]) {
            $lastIndex = count($merged) - 1;

            if ($lastIndex < 0 || $start->gt($merged[$lastIndex][1])) {
                $merged[] = [$start, $end];
                continue;
            }

            if ($end->gt($merged[$lastIndex][1])) {
                $merged[$lastIndex][1] = $end;
            }
        }

        return $this->candidateExperienceMonthsCache[$candidate->id] = collect($merged)->sum(function (array $interval): int {
            return max(1, (int) floor($interval[0]->diffInMonths($interval[1])));
        });
    }

    private function durationInMonths(string $value, string $unit): int
    {
        $value = strtolower(trim($value));
        preg_match('/(\d+(?:\.\d+)?)\s*(?:years?|yrs?|y)\b/i', $value, $yearMatch);
        preg_match('/(\d+(?:\.\d+)?)\s*(?:months?|mos?|m)\b/i', $value, $monthMatch);

        if ($yearMatch || $monthMatch) {
            $years = isset($yearMatch[1]) ? (float) $yearMatch[1] : 0;
            $months = isset($monthMatch[1]) ? (float) $monthMatch[1] : 0;

            return max(0, (int) round(($years * 12) + $months));
        }

        preg_match('/\d+(?:\.\d+)?/', $value, $numberMatch);
        $number = isset($numberMatch[0]) ? (float) $numberMatch[0] : 0;

        return max(0, (int) round($unit === Job::EXPERIENCE_UNIT_MONTH ? $number : $number * 12));
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
