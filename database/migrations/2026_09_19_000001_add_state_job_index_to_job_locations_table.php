<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_locations', function (Blueprint $table) {
            $table->index(['state_id', 'job_id'], 'job_locations_state_job_index');
        });
    }

    public function down(): void
    {
        Schema::table('job_locations', function (Blueprint $table) {
            $table->dropIndex('job_locations_state_job_index');
        });
    }
};
