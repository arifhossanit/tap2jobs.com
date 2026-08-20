<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tableRenames = [
        'profile_genders' => 'reference_genders',
        'profile_religions' => 'reference_religions',
        'profile_blood_groups' => 'reference_blood_groups',
        'profile_disability_difficulties' => 'reference_disability_difficulties',
        'profile_skill_learning_sources' => 'reference_skill_learning_sources',
        'profile_language_proficiencies' => 'reference_language_proficiencies',
        'profile_online_profile_platforms' => 'reference_online_profile_platforms',
        'profile_reference_relations' => 'reference_relations',
        'profile_education_results' => 'reference_education_results',
        'profile_army_ba_no_prefixes' => 'reference_army_ba_no_prefixes',
        'profile_army_ranks' => 'reference_army_ranks',
        'profile_army_employment_types' => 'reference_army_employment_types',
        'profile_army_arms' => 'reference_army_arms',
        'profile_job_gender_preferences' => 'reference_job_gender_preferences',
        'profile_job_employment_statuses' => 'reference_job_employment_statuses',
        'profile_job_workplaces' => 'reference_job_workplaces',
        'profile_job_experience_units' => 'reference_job_experience_units',
        'profile_employer_disability_facilities' => 'reference_employer_disability_facilities',
    ];

    public function up(): void
    {
        foreach ($this->tableRenames as $oldTable => $newTable) {
            if (Schema::hasTable($oldTable) && ! Schema::hasTable($newTable)) {
                Schema::rename($oldTable, $newTable);
                continue;
            }

            if (Schema::hasTable($oldTable) && Schema::hasTable($newTable)) {
                DB::table($oldTable)
                    ->orderBy('id')
                    ->chunk(100, function ($rows) use ($oldTable, $newTable) {
                        foreach ($rows as $row) {
                            DB::table($newTable)->updateOrInsert(
                                ['scope' => $row->scope, 'value' => $row->value],
                                [
                                    'label' => $row->label,
                                    'sort_order' => $row->sort_order,
                                    'is_active' => $row->is_active,
                                    'created_at' => $row->created_at,
                                    'updated_at' => $row->updated_at,
                                ]
                            );
                        }
                    });

                Schema::dropIfExists($oldTable);
            }
        }
    }

    public function down(): void
    {
        foreach (array_reverse($this->tableRenames) as $oldTable => $newTable) {
            if (Schema::hasTable($newTable) && ! Schema::hasTable($oldTable)) {
                Schema::rename($newTable, $oldTable);
            }
        }
    }
};
