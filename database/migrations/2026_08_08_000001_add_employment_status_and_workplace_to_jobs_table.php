<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('employment_status', 30)->nullable()->after('job_type_id');
            $table->boolean('work_from_office')->default(false)->after('employment_status');
            $table->boolean('work_from_home')->default(false)->after('work_from_office');
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['employment_status', 'work_from_office', 'work_from_home']);
        });
    }
};
