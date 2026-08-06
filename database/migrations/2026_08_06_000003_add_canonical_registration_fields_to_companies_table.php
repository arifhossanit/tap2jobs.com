<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('company_name', 180)->nullable()->after('ceo');
            $table->string('contact_person_designation', 180)->nullable()->after('contact_person_name');
        });

        DB::table('companies')
            ->select(['id', 'user_id', 'ceo'])
            ->orderBy('id')
            ->chunkById(100, function ($companies) {
                $userNames = DB::table('users')
                    ->whereIn('id', $companies->pluck('user_id')->filter())
                    ->pluck('first_name', 'id');

                foreach ($companies as $company) {
                    DB::table('companies')->where('id', $company->id)->update([
                        'company_name' => $userNames[$company->user_id] ?? null,
                        'contact_person_designation' => $company->ceo,
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'contact_person_designation']);
        });
    }
};
