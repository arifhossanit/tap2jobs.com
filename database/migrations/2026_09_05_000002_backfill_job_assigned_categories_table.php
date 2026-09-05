<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('job_assigned_categories')) {
            return;
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
        if (! Schema::hasTable('job_assigned_categories')) {
            return;
        }

        DB::statement('
            DELETE jac
            FROM job_assigned_categories jac
            INNER JOIN jobs j ON j.id = jac.job_id
            WHERE j.job_category_id = jac.job_category_id
        ');
    }
};