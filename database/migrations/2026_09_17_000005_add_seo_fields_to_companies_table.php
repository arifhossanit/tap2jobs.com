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
        if (! Schema::hasColumn('companies', 'slug')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('unique_id');
                $table->string('seo_title', 180)->nullable()->after('slug');
                $table->string('meta_description', 255)->nullable()->after('seo_title');
            });
        }

        $usedSlugs = DB::table('companies')->whereNotNull('slug')->pluck('slug')->flip()->all();
        DB::table('companies')
            ->leftJoin('users', 'users.id', '=', 'companies.user_id')
            ->whereNull('companies.slug')
            ->select(['companies.id', 'companies.company_name', 'users.first_name'])
            ->chunkById(1000, function ($companies) use (&$usedSlugs) {
                $updates = [];
                foreach ($companies as $company) {
                    $base = Str::slug((string) ($company->company_name ?: $company->first_name)) ?: 'company-'.$company->id;
                    $slug = $base;
                    $suffix = 2;
                    while (isset($usedSlugs[$slug])) {
                        $slug = $base.'-'.$suffix++;
                    }
                    $usedSlugs[$slug] = true;
                    $updates[] = ['id' => $company->id, 'slug' => $slug];
                }
                DB::table('companies')->upsert($updates, ['id'], ['slug']);
            }, 'companies.id', 'id');

        Schema::table('companies', fn (Blueprint $table) => $table->unique('slug'));
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'seo_title', 'meta_description']);
        });
    }
};
