<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('disability_inclusion_policy')->nullable()->after('has_disability_facilities');
            $table->boolean('disability_inclusion_training')->nullable()->after('disability_inclusion_policy');
            $table->json('disability_facilities')->nullable()->after('disability_inclusion_training');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'disability_inclusion_policy',
                'disability_inclusion_training',
                'disability_facilities',
            ]);
        });
    }
};
