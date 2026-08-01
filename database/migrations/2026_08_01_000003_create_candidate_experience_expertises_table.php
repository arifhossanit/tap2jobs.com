<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_experience_expertises', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('candidate_experience_id');
            $table->string('name');
            $table->unsignedTinyInteger('duration_months')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('candidate_experience_id')->references('id')->on('candidate_experiences')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_experience_expertises');
    }
};
