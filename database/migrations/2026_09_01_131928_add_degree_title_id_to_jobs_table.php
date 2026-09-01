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
        Schema::table('jobs', function (Blueprint $table) {
            $table->unsignedInteger('degree_title_id')->nullable()->after('degree_level_id');
            $table->foreign('degree_title_id')->references('id')->on('education_degree_titles')->onDelete('set null');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropForeign(['degree_title_id']);
            $table->dropColumn('degree_title_id');
        });
    }
};
