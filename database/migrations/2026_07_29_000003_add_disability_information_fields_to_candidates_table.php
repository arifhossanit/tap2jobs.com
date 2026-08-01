<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            if (! Schema::hasColumn('candidates', 'has_disability_id')) {
                $column = $table->boolean('has_disability_id')->nullable();
                if (Schema::hasColumn('candidates', 'keywords')) {
                    $column->after('keywords');
                }
            }
            if (! Schema::hasColumn('candidates', 'disability_id_number')) {
                $table->string('disability_id_number')->nullable()->after('has_disability_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('candidates', 'has_disability_id') ? 'has_disability_id' : null,
                Schema::hasColumn('candidates', 'disability_id_number') ? 'disability_id_number' : null,
            ]);

            if ($columns) {
                $table->dropColumn(array_values($columns));
            }
        });
    }
};
