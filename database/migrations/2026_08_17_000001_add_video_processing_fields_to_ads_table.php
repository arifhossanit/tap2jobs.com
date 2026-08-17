<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->string('media_processing_status', 30)->default('ready')->after('sort_order');
            $table->text('media_processing_error')->nullable()->after('media_processing_status');
            $table->timestamp('media_processed_at')->nullable()->after('media_processing_error');
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn([
                'media_processing_status',
                'media_processing_error',
                'media_processed_at',
            ]);
        });
    }
};
