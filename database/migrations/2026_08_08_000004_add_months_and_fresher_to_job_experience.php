<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->unsignedTinyInteger('experience_months')->default(0)->after('experience');
            $table->boolean('is_fresher')->default(false)->after('experience_months');
        });

        DB::table('jobs')->whereNull('experience')->update([
            'experience' => 0,
            'is_fresher' => true,
        ]);

        DB::table('jobs')->where('experience', 0)->update(['is_fresher' => true]);

        Schema::table('jobs', function (Blueprint $table) {
            $table->unsignedSmallInteger('experience')->default(0)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->integer('experience')->nullable()->default(null)->change();
            $table->dropColumn(['experience_months', 'is_fresher']);
        });
    }
};
