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
        Schema::table('posts', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
            $table->string('meta_title', 180)->nullable()->after('slug');
            $table->string('meta_description', 255)->nullable()->after('meta_title');
        });

        DB::table('posts')->orderBy('id')->each(function ($post) {
            $base = Str::slug(html_entity_decode(strip_tags((string) $post->title))) ?: 'blog-'.$post->id;
            $slug = $base;
            $suffix = 2;
            while (DB::table('posts')->where('slug', $slug)->exists()) {
                $slug = $base.'-'.$suffix++;
            }
            DB::table('posts')->where('id', $post->id)->update(['slug' => $slug]);
        });

        Schema::table('posts', fn (Blueprint $table) => $table->unique('slug'));
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'meta_title', 'meta_description']);
        });
    }
};
