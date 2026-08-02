<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_references', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('candidate_id');
            $table->string('name');
            $table->string('designation');
            $table->string('organization');
            $table->string('email')->nullable();
            $table->string('relation')->nullable();
            $table->string('mobile')->nullable();
            $table->string('office_phone')->nullable();
            $table->string('residential_phone')->nullable();
            $table->text('address')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('candidate_id')->references('id')->on('candidates')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_references');
    }
};
