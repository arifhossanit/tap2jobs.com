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
        Schema::table('ads', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
            $table->string('consultation_type')->nullable()->after('cta_text');
        });

        $usedSlugs = [];

        DB::table('ads')->select(['id', 'title'])->orderBy('id')->get()->each(function ($ad) use (&$usedSlugs) {
            $baseSlug = Str::slug((string) $ad->title) ?: 'ad-'.$ad->id;
            $slug = $baseSlug;
            $suffix = 2;

            while (isset($usedSlugs[$slug])) {
                $slug = $baseSlug.'-'.$suffix++;
            }

            $usedSlugs[$slug] = true;
            DB::table('ads')->where('id', $ad->id)->update(['slug' => $slug]);
        });

        Schema::table('ads', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'consultation_type']);
        });
    }
};
