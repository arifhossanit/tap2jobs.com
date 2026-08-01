<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_skill_sources', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('candidate_skill_id');
            $table->string('source');
            $table->timestamps();

            $table->foreign('candidate_skill_id')->references('id')->on('candidate_skills')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_skill_sources');
    }
};
