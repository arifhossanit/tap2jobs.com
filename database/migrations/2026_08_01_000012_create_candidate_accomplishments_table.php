<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_accomplishments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('candidate_id');
            $table->string('type');
            $table->string('title');
            $table->date('issued_on')->nullable();
            $table->string('url')->nullable();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('candidate_id')->references('id')->on('candidates')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->index(['candidate_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_accomplishments');
    }
};
