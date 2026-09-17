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
        Schema::table('job_categories', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->string('seo_title', 180)->nullable()->after('description');
            $table->string('meta_description', 255)->nullable()->after('seo_title');
            $table->longText('seo_content')->nullable()->after('meta_description');
        });

        DB::table('job_categories')->orderBy('id')->each(function ($category) {
            $baseSlug = Str::slug((string) $category->name) ?: 'job-category-'.$category->id;
            $slug = $baseSlug;
            $suffix = 2;

            while (DB::table('job_categories')->where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$suffix++;
            }

            DB::table('job_categories')->where('id', $category->id)->update(['slug' => $slug]);
        });

        Schema::table('job_categories', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('job_categories', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'seo_title', 'meta_description', 'seo_content']);
        });
    }
};
