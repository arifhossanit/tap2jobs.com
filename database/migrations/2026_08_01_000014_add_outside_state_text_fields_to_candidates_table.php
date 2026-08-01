<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            if (! Schema::hasColumn('candidates', 'present_state_division')) {
                $table->string('present_state_division')->nullable()->after('present_post_office');
            }
            if (! Schema::hasColumn('candidates', 'permanent_state_division')) {
                $table->string('permanent_state_division')->nullable()->after('permanent_state_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            foreach (['present_state_division', 'permanent_state_division'] as $column) {
                if (Schema::hasColumn('candidates', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
