<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            'users' => 'thana_id',
            'jobs' => 'thana_id',
            'candidate_experiences' => 'thana_id',
            'candidate_educations' => 'thana_id',
        ] as $tableName => $afterColumn) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName, $afterColumn) {
                if (! Schema::hasColumn($tableName, 'city_village_name')) {
                    $table->string('city_village_name')->nullable()->after($afterColumn);
                }
            });
        }

        Schema::table('candidates', function (Blueprint $table) {
            if (! Schema::hasColumn('candidates', 'present_city_village_name')) {
                $table->string('present_city_village_name')->nullable()->after('present_country_name');
            }
            if (! Schema::hasColumn('candidates', 'permanent_city_village_name')) {
                $table->string('permanent_city_village_name')->nullable()->after('permanent_country_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            if (Schema::hasColumn('candidates', 'present_city_village_name')) {
                $table->dropColumn('present_city_village_name');
            }
            if (Schema::hasColumn('candidates', 'permanent_city_village_name')) {
                $table->dropColumn('permanent_city_village_name');
            }
        });

        foreach ([
            'users',
            'jobs',
            'candidate_experiences',
            'candidate_educations',
        ] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'city_village_name')) {
                    $table->dropColumn('city_village_name');
                }
            });
        }
    }
};
