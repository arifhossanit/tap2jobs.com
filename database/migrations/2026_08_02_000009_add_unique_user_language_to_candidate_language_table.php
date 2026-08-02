<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('candidate_language')) {
            return;
        }

        $this->deleteDuplicateCandidateLanguages();

        if (! $this->indexExists('candidate_language', 'candidate_language_user_language_unique')) {
            Schema::table('candidate_language', function (Blueprint $table) {
                $table->unique(['user_id', 'language_id'], 'candidate_language_user_language_unique');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('candidate_language') && $this->indexExists('candidate_language', 'candidate_language_user_language_unique')) {
            Schema::table('candidate_language', function (Blueprint $table) {
                $table->dropUnique('candidate_language_user_language_unique');
            });
        }
    }

    private function deleteDuplicateCandidateLanguages(): void
    {
        $duplicateIds = DB::table('candidate_language as duplicate')
            ->join('candidate_language as keep', function ($join) {
                $join->on('keep.user_id', '=', 'duplicate.user_id')
                    ->on('keep.language_id', '=', 'duplicate.language_id')
                    ->whereColumn('keep.id', '<', 'duplicate.id');
            })
            ->pluck('duplicate.id');

        if ($duplicateIds->count()) {
            DB::table('candidate_language')->whereIn('id', $duplicateIds)->delete();
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        return count(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index])) > 0;
    }
};
