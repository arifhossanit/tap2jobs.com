<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('candidate_trainings') && Schema::hasColumn('candidate_trainings', 'topics')) {
            DB::statement('ALTER TABLE candidate_trainings MODIFY topics TEXT NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('candidate_trainings') && Schema::hasColumn('candidate_trainings', 'topics')) {
            DB::statement('ALTER TABLE candidate_trainings MODIFY topics VARCHAR(255) NULL');
        }
    }
};
