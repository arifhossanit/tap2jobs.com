<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidate_educations', function (Blueprint $table) {
            if (! Schema::hasColumn('candidate_educations', 'major')) {
                $table->string('major')->nullable()->after('degree_title');
            }
            if (! Schema::hasColumn('candidate_educations', 'foreign_institute')) {
                $table->boolean('foreign_institute')->default(false)->after('institute');
            }
            if (! Schema::hasColumn('candidate_educations', 'show_summary')) {
                $table->boolean('show_summary')->default(false)->after('foreign_institute');
            }
            if (! Schema::hasColumn('candidate_educations', 'cgpa')) {
                $table->string('cgpa')->nullable()->after('result');
            }
            if (! Schema::hasColumn('candidate_educations', 'scale')) {
                $table->string('scale')->nullable()->after('cgpa');
            }
            if (! Schema::hasColumn('candidate_educations', 'duration')) {
                $table->string('duration')->nullable()->after('year');
            }
            if (! Schema::hasColumn('candidate_educations', 'achievement')) {
                $table->text('achievement')->nullable()->after('duration');
            }
            if (! Schema::hasColumn('candidate_educations', 'sort_order')) {
                $table->unsignedSmallInteger('sort_order')->default(0)->after('achievement');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidate_educations', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('candidate_educations', 'major') ? 'major' : null,
                Schema::hasColumn('candidate_educations', 'foreign_institute') ? 'foreign_institute' : null,
                Schema::hasColumn('candidate_educations', 'show_summary') ? 'show_summary' : null,
                Schema::hasColumn('candidate_educations', 'cgpa') ? 'cgpa' : null,
                Schema::hasColumn('candidate_educations', 'scale') ? 'scale' : null,
                Schema::hasColumn('candidate_educations', 'duration') ? 'duration' : null,
                Schema::hasColumn('candidate_educations', 'achievement') ? 'achievement' : null,
                Schema::hasColumn('candidate_educations', 'sort_order') ? 'sort_order' : null,
            ]);

            if ($columns) {
                $table->dropColumn(array_values($columns));
            }
        });
    }
};
