<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultation_leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('ad_id')->nullable();
            $table->unsignedInteger('company_size_id')->nullable();
            $table->unsignedInteger('company_category_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 40);
            $table->string('company_name')->nullable();
            $table->string('designation')->nullable();
            $table->string('company_website')->nullable();
            $table->string('consultation_type')->default('job_posting');
            $table->string('preferred_contact_method')->nullable();
            $table->string('preferred_contact_time')->nullable();
            $table->text('message')->nullable();
            $table->string('source_page')->nullable();
            $table->string('clicked_url')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('status', 40)->default('new');
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['company_category_id', 'created_at']);

            $table->foreign('ad_id')->references('id')->on('ads')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('company_size_id')->references('id')->on('company_sizes')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('company_category_id')->references('id')->on('company_categories')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_leads');
    }
};
