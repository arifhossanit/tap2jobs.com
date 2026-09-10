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
        Schema::disableForeignKeyConstraints();

        try {
            Schema::table('candidate_educations', function (Blueprint $table) {
                $table->unsignedBigInteger('marks_percentage')->nullable()->change();
            });
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            Schema::table('candidate_educations', function (Blueprint $table) {
                $table->decimal('marks_percentage', 5, 2)->nullable()->change();
            });
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }
};
