<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidate_educations', function (Blueprint $table) {
            if (! Schema::hasColumn('candidate_educations', 'foreign_university_country')) {
                $table->string('foreign_university_country', 120)->nullable()->after('foreign_institute');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidate_educations', function (Blueprint $table) {
            if (Schema::hasColumn('candidate_educations', 'foreign_university_country')) {
                $table->dropColumn('foreign_university_country');
            }
        });
    }
};
