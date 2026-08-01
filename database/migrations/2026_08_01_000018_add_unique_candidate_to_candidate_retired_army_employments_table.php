<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('candidate_retired_army_employments')) {
            return;
        }

        if ($this->hasIndex('candidate_retired_army_employments_candidate_id_unique')) {
            return;
        }

        Schema::table('candidate_retired_army_employments', function (Blueprint $table) {
            $table->unique('candidate_id', 'candidate_retired_army_employments_candidate_id_unique');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('candidate_retired_army_employments')) {
            return;
        }

        if (! $this->hasIndex('candidate_retired_army_employments_candidate_id_unique')) {
            return;
        }

        Schema::table('candidate_retired_army_employments', function (Blueprint $table) {
            $table->dropUnique('candidate_retired_army_employments_candidate_id_unique');
        });
    }

    private function hasIndex(string $index): bool
    {
        return ! empty(DB::select(
            'SHOW INDEX FROM candidate_retired_army_employments WHERE Key_name = ?',
            [$index]
        ));
    }
};
