<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('candidate_retired_army_employments')) {
            return;
        }

        Schema::create('candidate_retired_army_employments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('candidate_id');
            $table->string('ba_no_prefix')->nullable();
            $table->string('ba_no');
            $table->string('rank');
            $table->string('type');
            $table->string('arms');
            $table->string('trade')->nullable();
            $table->string('course')->nullable();
            $table->date('date_of_commission');
            $table->date('date_of_retirement');
            $table->timestamps();

            $table->unique('candidate_id');
            $table->foreign('candidate_id')->references('id')->on('candidates')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_retired_army_employments');
    }
};
