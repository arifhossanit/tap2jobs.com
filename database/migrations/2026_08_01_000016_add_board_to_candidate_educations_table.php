<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidate_educations', function (Blueprint $table) {
            if (! Schema::hasColumn('candidate_educations', 'board')) {
                $table->string('board')->nullable()->after('major');
            }
        });

        DB::statement('ALTER TABLE candidate_educations MODIFY result VARCHAR(255) NULL');
        DB::statement('ALTER TABLE candidate_educations MODIFY year INT NULL');
    }

    public function down(): void
    {
        Schema::table('candidate_educations', function (Blueprint $table) {
            if (Schema::hasColumn('candidate_educations', 'board')) {
                $table->dropColumn('board');
            }
        });

        DB::statement("UPDATE candidate_educations SET result = '' WHERE result IS NULL");
        DB::statement('UPDATE candidate_educations SET year = 0 WHERE year IS NULL');
        DB::statement('ALTER TABLE candidate_educations MODIFY result VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE candidate_educations MODIFY year INT NOT NULL');
    }
};
