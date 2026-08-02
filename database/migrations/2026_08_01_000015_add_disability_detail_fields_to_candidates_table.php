<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            if (! Schema::hasColumn('candidates', 'disability_id_show_on_profile')) {
                $table->boolean('disability_id_show_on_profile')->nullable()->after('disability_id_number');
            }
            if (! Schema::hasColumn('candidates', 'disability_difficulty_seeing')) {
                $table->string('disability_difficulty_seeing', 50)->nullable()->after('disability_id_show_on_profile');
            }
            if (! Schema::hasColumn('candidates', 'disability_difficulty_hearing')) {
                $table->string('disability_difficulty_hearing', 50)->nullable()->after('disability_difficulty_seeing');
            }
            if (! Schema::hasColumn('candidates', 'disability_difficulty_remembering')) {
                $table->string('disability_difficulty_remembering', 50)->nullable()->after('disability_difficulty_hearing');
            }
            if (! Schema::hasColumn('candidates', 'disability_difficulty_walking')) {
                $table->string('disability_difficulty_walking', 50)->nullable()->after('disability_difficulty_remembering');
            }
            if (! Schema::hasColumn('candidates', 'disability_difficulty_communicating')) {
                $table->string('disability_difficulty_communicating', 50)->nullable()->after('disability_difficulty_walking');
            }
            if (! Schema::hasColumn('candidates', 'disability_difficulty_self_care')) {
                $table->string('disability_difficulty_self_care', 50)->nullable()->after('disability_difficulty_communicating');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('candidates', 'disability_id_show_on_profile') ? 'disability_id_show_on_profile' : null,
                Schema::hasColumn('candidates', 'disability_difficulty_seeing') ? 'disability_difficulty_seeing' : null,
                Schema::hasColumn('candidates', 'disability_difficulty_hearing') ? 'disability_difficulty_hearing' : null,
                Schema::hasColumn('candidates', 'disability_difficulty_remembering') ? 'disability_difficulty_remembering' : null,
                Schema::hasColumn('candidates', 'disability_difficulty_walking') ? 'disability_difficulty_walking' : null,
                Schema::hasColumn('candidates', 'disability_difficulty_communicating') ? 'disability_difficulty_communicating' : null,
                Schema::hasColumn('candidates', 'disability_difficulty_self_care') ? 'disability_difficulty_self_care' : null,
            ]);

            if ($columns) {
                $table->dropColumn(array_values($columns));
            }
        });
    }
};
