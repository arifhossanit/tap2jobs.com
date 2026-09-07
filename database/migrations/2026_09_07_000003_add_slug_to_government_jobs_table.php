<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('government_jobs', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        DB::table('government_jobs')->orderBy('id')->each(function ($job) {
            $baseSlug = Str::slug($job->title) ?: 'government-job';
            $slug = $baseSlug;
            $suffix = 2;

            while (DB::table('government_jobs')->where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$suffix;
                $suffix++;
            }

            DB::table('government_jobs')->where('id', $job->id)->update(['slug' => $slug]);
        });

        Schema::table('government_jobs', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('government_jobs', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
