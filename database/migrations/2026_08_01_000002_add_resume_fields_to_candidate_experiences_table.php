<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidate_experiences', function (Blueprint $table) {
            if (! Schema::hasColumn('candidate_experiences', 'company_business')) {
                $table->string('company_business')->nullable()->after('company');
            }
            if (! Schema::hasColumn('candidate_experiences', 'department')) {
                $table->string('department')->nullable()->after('experience_title');
            }
            if (! Schema::hasColumn('candidate_experiences', 'company_location')) {
                $table->string('company_location')->nullable()->after('description');
            }
            if (! Schema::hasColumn('candidate_experiences', 'sort_order')) {
                $table->unsignedSmallInteger('sort_order')->default(0)->after('company_location');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidate_experiences', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('candidate_experiences', 'company_business') ? 'company_business' : null,
                Schema::hasColumn('candidate_experiences', 'department') ? 'department' : null,
                Schema::hasColumn('candidate_experiences', 'company_location') ? 'company_location' : null,
                Schema::hasColumn('candidate_experiences', 'sort_order') ? 'sort_order' : null,
            ]);

            if ($columns) {
                $table->dropColumn(array_values($columns));
            }
        });
    }
};
