<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('required_degree_levels') || ! Schema::hasTable('education_major_groups')) {
            return;
        }

        $now = now();
        $levelIds = DB::table('required_degree_levels')
            ->whereIn('code', ['secondary', 'higher_secondary'])
            ->pluck('id', 'code');

        $majors = [
            'secondary' => ['General', 'Science', 'Business Studies', 'Humanities', 'Vocational', 'Others'],
            'higher_secondary' => ['General', 'Science', 'Business Studies', 'Humanities', 'Vocational', 'Others'],
        ];

        foreach ($majors as $code => $items) {
            if (! isset($levelIds[$code])) {
                continue;
            }

            foreach ($items as $index => $name) {
                DB::table('education_major_groups')->updateOrInsert(
                    ['required_degree_level_id' => $levelIds[$code], 'name' => $name],
                    [
                        'is_custom' => false,
                        'sort_order' => $index + 1,
                        'is_active' => true,
                        'updated_at' => $now,
                        'created_at' => $now,
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        //
    }
};
