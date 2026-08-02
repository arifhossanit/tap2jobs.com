<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('candidate_extra_curriculars')) {
            return;
        }

        $this->deleteDuplicateExtraCurriculars();

        if (! $this->indexExists('candidate_extra_curriculars', 'candidate_extra_curriculars_candidate_unique')) {
            Schema::table('candidate_extra_curriculars', function (Blueprint $table) {
                $table->unique('candidate_id', 'candidate_extra_curriculars_candidate_unique');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('candidate_extra_curriculars') && $this->indexExists('candidate_extra_curriculars', 'candidate_extra_curriculars_candidate_unique')) {
            Schema::table('candidate_extra_curriculars', function (Blueprint $table) {
                $table->dropUnique('candidate_extra_curriculars_candidate_unique');
            });
        }
    }

    private function deleteDuplicateExtraCurriculars(): void
    {
        $duplicateIds = DB::table('candidate_extra_curriculars as duplicate')
            ->join('candidate_extra_curriculars as keep', function ($join) {
                $join->on('keep.candidate_id', '=', 'duplicate.candidate_id')
                    ->whereColumn('keep.id', '<', 'duplicate.id');
            })
            ->pluck('duplicate.id');

        if ($duplicateIds->count()) {
            DB::table('candidate_extra_curriculars')->whereIn('id', $duplicateIds)->delete();
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        return count(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index])) > 0;
    }
};
