<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 170)->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('company_sizes', function (Blueprint $table) {
            $table->unsignedInteger('company_category_id')->nullable()->after('size');
            $table->foreign('company_category_id')
                ->references('id')
                ->on('company_categories')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('company_sizes', function (Blueprint $table) {
            $table->dropForeign(['company_category_id']);
            $table->dropColumn('company_category_id');
        });

        Schema::dropIfExists('company_categories');
    }
};
