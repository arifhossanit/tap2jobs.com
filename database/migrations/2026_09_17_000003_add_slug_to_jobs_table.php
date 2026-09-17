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
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('slug', 191)->nullable()->after('job_id');
        });

        $usedSlugs = [];

        DB::table('jobs')
            ->select(['id', 'job_title'])
            ->orderBy('id')
            ->chunkById(200, function ($jobs) use (&$usedSlugs) {
                foreach ($jobs as $job) {
                    $baseSlug = Str::slug(html_entity_decode(strip_tags((string) $job->job_title), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                    $baseSlug = Str::limit($baseSlug ?: 'job', 180, '');
                    $slug = $baseSlug;
                    $suffix = 2;

                    while (isset($usedSlugs[mb_strtolower($slug)])) {
                        $slug = Str::limit($baseSlug, 180, '').'-'.$suffix++;
                    }

                    $usedSlugs[mb_strtolower($slug)] = true;
                    DB::table('jobs')->where('id', $job->id)->update(['slug' => $slug]);
                }
            });

        Schema::table('jobs', function (Blueprint $table) {
            $table->unique('slug', 'jobs_slug_unique');
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropUnique('jobs_slug_unique');
            $table->dropColumn('slug');
        });
    }
};
