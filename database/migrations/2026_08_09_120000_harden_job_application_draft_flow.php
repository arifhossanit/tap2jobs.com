<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->double('expected_salary')->nullable()->change();
            $table->unique(['job_id', 'candidate_id'], 'job_applications_job_candidate_unique');
        });
    }

    public function down(): void
    {
        DB::table('job_applications')->whereNull('expected_salary')->update(['expected_salary' => 0]);

        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropUnique('job_applications_job_candidate_unique');
            $table->double('expected_salary')->nullable(false)->change();
        });
    }
};
