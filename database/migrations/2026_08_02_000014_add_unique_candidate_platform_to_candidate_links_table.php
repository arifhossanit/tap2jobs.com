<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('candidate_links')) {
            return;
        }

        $this->deleteDuplicateLinks();

        if (! $this->indexExists('candidate_links', 'candidate_links_candidate_platform_unique')) {
            Schema::table('candidate_links', function (Blueprint $table) {
                $table->unique(['candidate_id', 'platform'], 'candidate_links_candidate_platform_unique');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('candidate_links') && $this->indexExists('candidate_links', 'candidate_links_candidate_platform_unique')) {
            Schema::table('candidate_links', function (Blueprint $table) {
                $table->dropUnique('candidate_links_candidate_platform_unique');
            });
        }
    }

    private function deleteDuplicateLinks(): void
    {
        $duplicateIds = DB::table('candidate_links as duplicate')
            ->join('candidate_links as keep', function ($join) {
                $join->on('keep.candidate_id', '=', 'duplicate.candidate_id')
                    ->on('keep.platform', '=', 'duplicate.platform')
                    ->whereColumn('keep.id', '<', 'duplicate.id');
            })
            ->pluck('duplicate.id');

        if ($duplicateIds->count()) {
            DB::table('candidate_links')->whereIn('id', $duplicateIds)->delete();
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        return count(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index])) > 0;
    }
};
