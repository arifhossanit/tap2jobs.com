<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->text('career_summary')->nullable()->after('preferred_organization_types');
            $table->text('special_qualification')->nullable()->after('career_summary');
            $table->text('keywords')->nullable()->after('special_qualification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn([
                'career_summary',
                'special_qualification',
                'keywords',
            ]);
        });
    }
};
