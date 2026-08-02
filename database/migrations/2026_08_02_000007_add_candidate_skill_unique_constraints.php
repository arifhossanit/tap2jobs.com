<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->mergeDuplicateSkills();
        $this->deleteDuplicateCandidateSkills();
        $this->deleteDuplicateCandidateSkillSources();

        if (Schema::hasTable('skills') && ! $this->indexExists('skills', 'skills_name_unique')) {
            Schema::table('skills', function (Blueprint $table) {
                $table->unique('name', 'skills_name_unique');
            });
        }

        if (Schema::hasTable('candidate_skills') && ! $this->indexExists('candidate_skills', 'candidate_skills_user_skill_unique')) {
            Schema::table('candidate_skills', function (Blueprint $table) {
                $table->unique(['user_id', 'skill_id'], 'candidate_skills_user_skill_unique');
            });
        }

        if (Schema::hasTable('candidate_skill_sources') && ! $this->indexExists('candidate_skill_sources', 'candidate_skill_sources_skill_source_unique')) {
            Schema::table('candidate_skill_sources', function (Blueprint $table) {
                $table->unique(['candidate_skill_id', 'source'], 'candidate_skill_sources_skill_source_unique');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('candidate_skill_sources') && $this->indexExists('candidate_skill_sources', 'candidate_skill_sources_skill_source_unique')) {
            Schema::table('candidate_skill_sources', function (Blueprint $table) {
                $table->dropUnique('candidate_skill_sources_skill_source_unique');
            });
        }

        if (Schema::hasTable('candidate_skills') && $this->indexExists('candidate_skills', 'candidate_skills_user_skill_unique')) {
            Schema::table('candidate_skills', function (Blueprint $table) {
                $table->dropUnique('candidate_skills_user_skill_unique');
            });
        }

        if (Schema::hasTable('skills') && $this->indexExists('skills', 'skills_name_unique')) {
            Schema::table('skills', function (Blueprint $table) {
                $table->dropUnique('skills_name_unique');
            });
        }
    }

    private function mergeDuplicateSkills(): void
    {
        if (! Schema::hasTable('skills')) {
            return;
        }

        $duplicates = DB::table('skills')
            ->selectRaw('LOWER(name) as normalized_name, MIN(id) as keep_id')
            ->whereNotNull('name')
            ->groupByRaw('LOWER(name)')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            $duplicateIds = DB::table('skills')
                ->whereRaw('LOWER(name) = ?', [$duplicate->normalized_name])
                ->where('id', '!=', $duplicate->keep_id)
                ->pluck('id');

            foreach ($duplicateIds as $duplicateId) {
                if (Schema::hasTable('candidate_skills')) {
                    $duplicateCandidateSkillIds = DB::table('candidate_skills as duplicate')
                        ->join('candidate_skills as keep', function ($join) use ($duplicate, $duplicateId) {
                            $join->on('keep.user_id', '=', 'duplicate.user_id')
                                ->where('keep.skill_id', '=', $duplicate->keep_id)
                                ->where('duplicate.skill_id', '=', $duplicateId);
                        })
                        ->pluck('duplicate.id');

                    if ($duplicateCandidateSkillIds->count()) {
                        if (Schema::hasTable('candidate_skill_sources')) {
                            DB::table('candidate_skill_sources')->whereIn('candidate_skill_id', $duplicateCandidateSkillIds)->delete();
                        }
                        DB::table('candidate_skills')->whereIn('id', $duplicateCandidateSkillIds)->delete();
                    }

                    DB::table('candidate_skills')->where('skill_id', $duplicateId)->update(['skill_id' => $duplicate->keep_id]);
                }
                if (Schema::hasTable('jobs_skill')) {
                    DB::table('jobs_skill')->where('skill_id', $duplicateId)->update(['skill_id' => $duplicate->keep_id]);
                }
                DB::table('skills')->where('id', $duplicateId)->delete();
            }
        }
    }

    private function deleteDuplicateCandidateSkills(): void
    {
        if (! Schema::hasTable('candidate_skills')) {
            return;
        }

        $duplicateIds = DB::table('candidate_skills as duplicate')
            ->join('candidate_skills as keep', function ($join) {
                $join->on('keep.user_id', '=', 'duplicate.user_id')
                    ->on('keep.skill_id', '=', 'duplicate.skill_id')
                    ->whereColumn('keep.id', '<', 'duplicate.id');
            })
            ->pluck('duplicate.id');

        if (! $duplicateIds->count()) {
            return;
        }

        if (Schema::hasTable('candidate_skill_sources')) {
            DB::table('candidate_skill_sources')->whereIn('candidate_skill_id', $duplicateIds)->delete();
        }

        DB::table('candidate_skills')->whereIn('id', $duplicateIds)->delete();
    }

    private function deleteDuplicateCandidateSkillSources(): void
    {
        if (! Schema::hasTable('candidate_skill_sources')) {
            return;
        }

        $duplicateIds = DB::table('candidate_skill_sources as duplicate')
            ->join('candidate_skill_sources as keep', function ($join) {
                $join->on('keep.candidate_skill_id', '=', 'duplicate.candidate_skill_id')
                    ->on('keep.source', '=', 'duplicate.source')
                    ->whereColumn('keep.id', '<', 'duplicate.id');
            })
            ->pluck('duplicate.id');

        if ($duplicateIds->count()) {
            DB::table('candidate_skill_sources')->whereIn('id', $duplicateIds)->delete();
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        return count(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index])) > 0;
    }
};
