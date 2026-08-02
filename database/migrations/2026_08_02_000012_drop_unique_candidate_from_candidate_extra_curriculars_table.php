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

        if ($this->indexExists('candidate_extra_curriculars', 'candidate_extra_curriculars_candidate_unique')) {
            $this->dropCandidateForeignKey();

            Schema::table('candidate_extra_curriculars', function (Blueprint $table) {
                $table->dropUnique('candidate_extra_curriculars_candidate_unique');
            });

            if (! $this->indexExists('candidate_extra_curriculars', 'candidate_extra_curriculars_candidate_id_index')) {
                Schema::table('candidate_extra_curriculars', function (Blueprint $table) {
                    $table->index('candidate_id', 'candidate_extra_curriculars_candidate_id_index');
                });
            }

            $this->addCandidateForeignKey();
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('candidate_extra_curriculars')) {
            return;
        }

        if (! $this->indexExists('candidate_extra_curriculars', 'candidate_extra_curriculars_candidate_unique')) {
            $this->deleteDuplicateExtraCurriculars();
            $this->dropCandidateForeignKey();

            if ($this->indexExists('candidate_extra_curriculars', 'candidate_extra_curriculars_candidate_id_index')) {
                Schema::table('candidate_extra_curriculars', function (Blueprint $table) {
                    $table->dropIndex('candidate_extra_curriculars_candidate_id_index');
                });
            }

            Schema::table('candidate_extra_curriculars', function (Blueprint $table) {
                $table->unique('candidate_id', 'candidate_extra_curriculars_candidate_unique');
            });

            $this->addCandidateForeignKey();
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        return count(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index])) > 0;
    }

    private function dropCandidateForeignKey(): void
    {
        $foreignKey = $this->candidateForeignKeyName();

        if ($foreignKey === null) {
            return;
        }

        Schema::table('candidate_extra_curriculars', function (Blueprint $table) use ($foreignKey) {
            $table->dropForeign($foreignKey);
        });
    }

    private function addCandidateForeignKey(): void
    {
        if ($this->candidateForeignKeyName() !== null) {
            return;
        }

        Schema::table('candidate_extra_curriculars', function (Blueprint $table) {
            $table->foreign('candidate_id')
                ->references('id')
                ->on('candidates')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    private function candidateForeignKeyName(): ?string
    {
        $foreignKeys = DB::select(
            'SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE()
             AND TABLE_NAME = ?
             AND COLUMN_NAME = ?
             AND REFERENCED_TABLE_NAME IS NOT NULL
             LIMIT 1',
            ['candidate_extra_curriculars', 'candidate_id']
        );

        return $foreignKeys[0]->CONSTRAINT_NAME ?? null;
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
};
