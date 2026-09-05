<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('job_assigned_categories')) {
            Schema::create('job_assigned_categories', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('job_id');
                $table->unsignedInteger('job_category_id');
                $table->timestamps();

                $table->unique(['job_id', 'job_category_id']);
                $table->foreign('job_id')->references('id')->on('jobs')->cascadeOnDelete();
                $table->foreign('job_category_id')->references('id')->on('job_categories')->cascadeOnDelete();
            });
        }

        DB::statement('
            INSERT IGNORE INTO job_assigned_categories (job_id, job_category_id, created_at, updated_at)
            SELECT id, job_category_id, NOW(), NOW()
            FROM jobs
            WHERE job_category_id IS NOT NULL
        ');
    }

    public function down(): void
    {
        Schema::dropIfExists('job_assigned_categories');
    }
};