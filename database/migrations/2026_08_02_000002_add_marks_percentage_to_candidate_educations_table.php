<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidate_educations', function (Blueprint $table) {
            if (! Schema::hasColumn('candidate_educations', 'marks_percentage')) {
                $table->decimal('marks_percentage', 5, 2)->nullable()->after('result');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidate_educations', function (Blueprint $table) {
            if (Schema::hasColumn('candidate_educations', 'marks_percentage')) {
                $table->dropColumn('marks_percentage');
            }
        });
    }
};
