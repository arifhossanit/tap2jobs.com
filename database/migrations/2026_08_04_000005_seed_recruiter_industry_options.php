<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $industryGroups = [
            'Agro based Industry' => [
                'Agro based firms (incl. Agro Processing/Seed/GM)',
                'Agro based Startup',
                'Auto Rice Mill',
                'Dairy',
                'Farming',
                'Fisheries',
                'Hatchery',
                'Livestock',
                'Animal/Plant Breeding',
            ],
            'Advertising/ Marketing' => [
                'Advertising Agency',
                'Advertising Technology (AdTech) Startup',
            ],
            'Architecture/ Engineering/ Construction' => [
                'Architecture Firm',
                'Engineering Firm',
                'Interior Design',
            ],
            'Information Technology' => [
                'E-commerce',
                'E-commerce Startup',
            ],
            'Hospitality/ Travel' => [
                'Airline',
                'Airport Service',
                'Amusement Park',
            ],
        ];

        $typeIds = DB::table('industry_types')->pluck('id', 'name');
        $now = now();

        foreach ($industryGroups as $typeName => $industryNames) {
            foreach ($industryNames as $industryName) {
                if (DB::table('industries')->where('name', $industryName)->exists()) {
                    DB::table('industries')->where('name', $industryName)->update([
                        'industry_type_id' => $typeIds[$typeName],
                    ]);
                    continue;
                }

                DB::table('industries')->insert([
                    'industry_type_id' => $typeIds[$typeName],
                    'name' => $industryName,
                    'description' => $industryName,
                    'is_default' => true,
                    'created_by' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        // These reference records may already be selected by companies, so rollback keeps them intact.
    }
};
