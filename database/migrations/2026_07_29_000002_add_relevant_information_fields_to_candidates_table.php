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
            if (! Schema::hasColumn('candidates', 'career_summary')) {
                $column = $table->text('career_summary')->nullable();
                if (Schema::hasColumn('candidates', 'preferred_organization_types')) {
                    $column->after('preferred_organization_types');
                } elseif (Schema::hasColumn('candidates', 'job_nature')) {
                    $column->after('job_nature');
                }
            }
            if (! Schema::hasColumn('candidates', 'special_qualification')) {
                $column = $table->text('special_qualification')->nullable();
                if (Schema::hasColumn('candidates', 'career_summary')) {
                    $column->after('career_summary');
                }
            }
            if (! Schema::hasColumn('candidates', 'keywords')) {
                $column = $table->text('keywords')->nullable();
                if (Schema::hasColumn('candidates', 'special_qualification')) {
                    $column->after('special_qualification');
                }
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
                Schema::hasColumn('candidates', 'career_summary') ? 'career_summary' : null,
                Schema::hasColumn('candidates', 'special_qualification') ? 'special_qualification' : null,
                Schema::hasColumn('candidates', 'keywords') ? 'keywords' : null,
            ]);

            if ($columns) {
                $table->dropColumn(array_values($columns));
            }
        });
    }
};
