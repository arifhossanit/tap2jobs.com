<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            if (! Schema::hasColumn('candidates', 'present_country_name')) {
                $table->string('present_country_name')->nullable()->after('present_address_type');
            }
            if (! Schema::hasColumn('candidates', 'permanent_country_name')) {
                $table->string('permanent_country_name')->nullable()->after('permanent_country_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            foreach (['present_country_name', 'permanent_country_name'] as $column) {
                if (Schema::hasColumn('candidates', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
