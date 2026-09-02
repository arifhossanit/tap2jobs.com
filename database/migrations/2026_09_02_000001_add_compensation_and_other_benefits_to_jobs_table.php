<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            if (! Schema::hasColumn('jobs', 'compensation_and_other_benefits')) {
                $table->text('compensation_and_other_benefits')->nullable()->after('key_responsibilities');
            }
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            if (Schema::hasColumn('jobs', 'compensation_and_other_benefits')) {
                $table->dropColumn('compensation_and_other_benefits');
            }
        });
    }
};
