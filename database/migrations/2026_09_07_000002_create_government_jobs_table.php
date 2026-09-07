<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('government_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('organization_name');
            $table->string('source_name')->nullable();
            $table->date('published_at')->nullable();
            $table->date('application_deadline')->nullable();
            $table->string('circular_path');
            $table->string('circular_mime_type', 100);
            $table->string('application_url')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('government_jobs');
    }
};
