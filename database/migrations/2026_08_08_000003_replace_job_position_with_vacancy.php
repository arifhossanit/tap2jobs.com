<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('jobs')
            ->whereNull('vacancy')
            ->update(['vacancy' => DB::raw('GREATEST(position, 1)')]);

        Schema::table('jobs', function (Blueprint $table) {
            $table->unsignedInteger('vacancy')->default(1)->nullable(false)->change();
            $table->dropColumn('position');
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->integer('position')->default(1)->after('vacancy');
        });

        DB::table('jobs')->update(['position' => DB::raw('vacancy')]);

        Schema::table('jobs', function (Blueprint $table) {
            $table->unsignedInteger('vacancy')->nullable()->default(null)->change();
        });
    }
};
