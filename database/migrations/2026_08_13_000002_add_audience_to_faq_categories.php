<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('faq_categories', 'audience')) {
            Schema::table('faq_categories', function (Blueprint $table) {
                $table->string('audience')->default('candidate')->after('slug');
                $table->index('audience');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('faq_categories', 'audience')) {
            Schema::table('faq_categories', function (Blueprint $table) {
                $table->dropIndex(['audience']);
                $table->dropColumn('audience');
            });
        }
    }
};
