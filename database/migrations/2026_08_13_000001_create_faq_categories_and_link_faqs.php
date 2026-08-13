<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('faq_categories')) {
            Schema::create('faq_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('icon')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        Schema::table('faqs', function (Blueprint $table) {
            if (! Schema::hasColumn('faqs', 'faq_category_id')) {
                $table->foreignId('faq_category_id')->nullable()->after('id')->constrained('faq_categories')->nullOnDelete();
            }

            if (! Schema::hasColumn('faqs', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0)->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            if (Schema::hasColumn('faqs', 'faq_category_id')) {
                $table->dropConstrainedForeignId('faq_category_id');
            }

            if (Schema::hasColumn('faqs', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
        });

        Schema::dropIfExists('faq_categories');
    }
};
