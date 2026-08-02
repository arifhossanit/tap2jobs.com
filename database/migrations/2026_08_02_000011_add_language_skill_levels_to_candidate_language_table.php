<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('candidate_language')) {
            return;
        }

        Schema::table('candidate_language', function (Blueprint $table) {
            if (! Schema::hasColumn('candidate_language', 'reading_level')) {
                $table->string('reading_level')->nullable()->after('proficiency_level');
            }
            if (! Schema::hasColumn('candidate_language', 'writing_level')) {
                $table->string('writing_level')->nullable()->after('reading_level');
            }
            if (! Schema::hasColumn('candidate_language', 'speaking_level')) {
                $table->string('speaking_level')->nullable()->after('writing_level');
            }
        });

        if (Schema::hasColumn('candidate_language', 'proficiency_level')) {
            DB::table('candidate_language')
                ->whereNotNull('proficiency_level')
                ->update([
                    'reading_level' => DB::raw('COALESCE(reading_level, proficiency_level)'),
                    'writing_level' => DB::raw('COALESCE(writing_level, proficiency_level)'),
                    'speaking_level' => DB::raw('COALESCE(speaking_level, proficiency_level)'),
                ]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('candidate_language')) {
            return;
        }

        Schema::table('candidate_language', function (Blueprint $table) {
            if (Schema::hasColumn('candidate_language', 'speaking_level')) {
                $table->dropColumn('speaking_level');
            }
            if (Schema::hasColumn('candidate_language', 'writing_level')) {
                $table->dropColumn('writing_level');
            }
            if (Schema::hasColumn('candidate_language', 'reading_level')) {
                $table->dropColumn('reading_level');
            }
        });
    }
};
