<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'thana_id')) {
                $table->unsignedBigInteger('thana_id')->nullable()->after('city_id');
            }
        });

        Schema::table('jobs', function (Blueprint $table) {
            if (! Schema::hasColumn('jobs', 'thana_id')) {
                $table->unsignedBigInteger('thana_id')->nullable()->after('city_id');
            }
        });

        Schema::table('candidate_experiences', function (Blueprint $table) {
            if (! Schema::hasColumn('candidate_experiences', 'thana_id')) {
                $table->unsignedBigInteger('thana_id')->nullable()->after('city_id');
            }
        });

        Schema::table('candidate_educations', function (Blueprint $table) {
            if (! Schema::hasColumn('candidate_educations', 'thana_id')) {
                $table->unsignedBigInteger('thana_id')->nullable()->after('city_id');
            }
        });

        Schema::table('candidates', function (Blueprint $table) {
            if (! Schema::hasColumn('candidates', 'permanent_thana_id')) {
                $table->unsignedBigInteger('permanent_thana_id')->nullable()->after('permanent_city_id');
            }
        });
    }

    public function down(): void
    {
        foreach ([
            'users' => 'thana_id',
            'jobs' => 'thana_id',
            'candidate_experiences' => 'thana_id',
            'candidate_educations' => 'thana_id',
            'candidates' => 'permanent_thana_id',
        ] as $tableName => $column) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName, $column) {
                if (Schema::hasColumn($tableName, $column)) {
                    $table->dropColumn($column);
                }
            });
        }
    }
};
