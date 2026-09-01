<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('job_workplaces')) {
            Schema::create('job_workplaces', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('job_id');
                $table->foreign('job_id')->references('id')->on('jobs')->cascadeOnDelete();
                $table->string('workplace_value', 150);
                $table->timestamps();

                $table->unique(['job_id', 'workplace_value']);
                $table->index('workplace_value');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('job_workplaces');
    }
};