<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $seen = [];

        DB::table('candidates')
            ->select(['id', 'unique_id'])
            ->orderBy('id')
            ->chunkById(500, function ($candidates) use (&$seen) {
                foreach ($candidates as $candidate) {
                    $uniqueId = (string) $candidate->unique_id;

                    if ($uniqueId === '' || isset($seen[$uniqueId])) {
                        do {
                            $uniqueId = Str::random(12);
                        } while (isset($seen[$uniqueId]) || DB::table('candidates')->where('unique_id', $uniqueId)->exists());

                        DB::table('candidates')
                            ->where('id', $candidate->id)
                            ->update(['unique_id' => $uniqueId]);
                    }

                    $seen[$uniqueId] = true;
                }
            });

        Schema::table('candidates', function (Blueprint $table) {
            $table->unique('unique_id', 'candidates_unique_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropUnique('candidates_unique_id_unique');
        });
    }
};
