<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            if (! Schema::hasColumn('faqs', 'title_en')) {
                $table->string('title_en')->nullable()->after('title');
            }

            if (! Schema::hasColumn('faqs', 'title_bn')) {
                $table->string('title_bn')->nullable()->after('title_en');
            }

            if (! Schema::hasColumn('faqs', 'description_en')) {
                $table->text('description_en')->nullable()->after('description');
            }

            if (! Schema::hasColumn('faqs', 'description_bn')) {
                $table->text('description_bn')->nullable()->after('description_en');
            }
        });

        Schema::table('faq_categories', function (Blueprint $table) {
            if (! Schema::hasColumn('faq_categories', 'name_en')) {
                $table->string('name_en')->nullable()->after('name');
            }

            if (! Schema::hasColumn('faq_categories', 'name_bn')) {
                $table->string('name_bn')->nullable()->after('name_en');
            }
        });

        DB::table('faqs')->whereNull('title_en')->update(['title_en' => DB::raw('title')]);
        DB::table('faqs')->whereNull('description_en')->update(['description_en' => DB::raw('description')]);
        DB::table('faq_categories')->whereNull('name_en')->update(['name_en' => DB::raw('name')]);
    }

    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            foreach (['title_en', 'title_bn', 'description_en', 'description_bn'] as $column) {
                if (Schema::hasColumn('faqs', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('faq_categories', function (Blueprint $table) {
            foreach (['name_en', 'name_bn'] as $column) {
                if (Schema::hasColumn('faq_categories', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
