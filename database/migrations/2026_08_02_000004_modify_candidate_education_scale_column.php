<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('candidate_educations', 'scale')) {
            return;
        }

        DB::statement('ALTER TABLE candidate_educations MODIFY scale SMALLINT UNSIGNED NULL');
    }

    public function down(): void
    {
        if (! Schema::hasColumn('candidate_educations', 'scale')) {
            return;
        }

        DB::statement('ALTER TABLE candidate_educations MODIFY scale VARCHAR(255) NULL');
    }
};
