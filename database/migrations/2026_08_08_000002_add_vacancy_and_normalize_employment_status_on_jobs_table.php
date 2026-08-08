<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->unsignedInteger('vacancy')->nullable()->after('experience');
        });

        DB::table('jobs')
            ->whereNull('employment_status')
            ->update([
                'employment_status' => DB::raw("CASE WHEN is_freelance = 1 THEN 'freelance' ELSE 'full_time' END"),
            ]);

        Schema::table('jobs', function (Blueprint $table) {
            $table->string('employment_status', 30)->default('full_time')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('employment_status', 30)->nullable()->default(null)->change();
            $table->dropColumn('vacancy');
        });
    }
};
