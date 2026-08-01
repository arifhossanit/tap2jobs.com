<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            if (! Schema::hasColumn('candidates', 'present_address_type')) {
                $table->string('present_address_type', 30)->nullable()->after('address');
            }
            if (! Schema::hasColumn('candidates', 'present_post_office')) {
                $table->string('present_post_office')->nullable()->after('present_address_type');
            }
            if (! Schema::hasColumn('candidates', 'permanent_same_as_present')) {
                $table->boolean('permanent_same_as_present')->default(true)->after('present_post_office');
            }
            if (! Schema::hasColumn('candidates', 'permanent_address_type')) {
                $table->string('permanent_address_type', 30)->nullable()->after('permanent_same_as_present');
            }
            if (! Schema::hasColumn('candidates', 'permanent_country_id')) {
                $table->unsignedBigInteger('permanent_country_id')->nullable()->after('permanent_address_type');
            }
            if (! Schema::hasColumn('candidates', 'permanent_state_id')) {
                $table->unsignedBigInteger('permanent_state_id')->nullable()->after('permanent_country_id');
            }
            if (! Schema::hasColumn('candidates', 'permanent_city_id')) {
                $table->unsignedBigInteger('permanent_city_id')->nullable()->after('permanent_state_id');
            }
            if (! Schema::hasColumn('candidates', 'permanent_post_office')) {
                $table->string('permanent_post_office')->nullable()->after('permanent_city_id');
            }
            if (! Schema::hasColumn('candidates', 'permanent_address')) {
                $table->text('permanent_address')->nullable()->after('permanent_post_office');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            foreach ([
                'present_address_type',
                'present_post_office',
                'permanent_same_as_present',
                'permanent_address_type',
                'permanent_country_id',
                'permanent_state_id',
                'permanent_city_id',
                'permanent_post_office',
                'permanent_address',
            ] as $column) {
                if (Schema::hasColumn('candidates', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
