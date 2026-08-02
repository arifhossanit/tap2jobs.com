<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('languages')) {
            return;
        }

        $this->mergeDuplicateLanguages();

        if (! $this->indexExists('languages', 'languages_language_unique')) {
            Schema::table('languages', function (Blueprint $table) {
                $table->unique('language', 'languages_language_unique');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('languages') && $this->indexExists('languages', 'languages_language_unique')) {
            Schema::table('languages', function (Blueprint $table) {
                $table->dropUnique('languages_language_unique');
            });
        }
    }

    private function mergeDuplicateLanguages(): void
    {
        $duplicates = DB::table('languages')
            ->selectRaw('LOWER(language) as normalized_language, MIN(id) as keep_id')
            ->whereNotNull('language')
            ->groupByRaw('LOWER(language)')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            $duplicateIds = DB::table('languages')
                ->whereRaw('LOWER(language) = ?', [$duplicate->normalized_language])
                ->where('id', '!=', $duplicate->keep_id)
                ->pluck('id');

            foreach ($duplicateIds as $duplicateId) {
                if (Schema::hasTable('candidate_language')) {
                    $duplicateCandidateLanguageIds = DB::table('candidate_language as duplicate')
                        ->join('candidate_language as keep', function ($join) use ($duplicate, $duplicateId) {
                            $join->on('keep.user_id', '=', 'duplicate.user_id')
                                ->where('keep.language_id', '=', $duplicate->keep_id)
                                ->where('duplicate.language_id', '=', $duplicateId);
                        })
                        ->pluck('duplicate.id');

                    if ($duplicateCandidateLanguageIds->count()) {
                        DB::table('candidate_language')->whereIn('id', $duplicateCandidateLanguageIds)->delete();
                    }

                    DB::table('candidate_language')->where('language_id', $duplicateId)->update(['language_id' => $duplicate->keep_id]);
                }

                DB::table('languages')->where('id', $duplicateId)->delete();
            }
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        return count(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index])) > 0;
    }
};
