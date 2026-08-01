<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidate_language', function (Blueprint $table) {
            if (! Schema::hasColumn('candidate_language', 'proficiency_level')) {
                $table->string('proficiency_level')->nullable()->after('language_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidate_language', function (Blueprint $table) {
            if (Schema::hasColumn('candidate_language', 'proficiency_level')) {
                $table->dropColumn('proficiency_level');
            }
        });
    }
};
