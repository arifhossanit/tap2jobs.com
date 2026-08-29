<?php

use App\Models\ProfileReferenceOption;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    private array $excludedTypes = [
        ProfileReferenceOption::TYPE_CONSULTATION_TYPE,
        ProfileReferenceOption::TYPE_CONSULTATION_CONTACT_METHOD,
    ];

    public function up(): void
    {
        foreach (array_diff_key(ProfileReferenceOption::tableMap(), array_flip($this->excludedTypes)) as $table) {
            if (Schema::hasTable($table)) {
                continue;
            }

            Schema::create($table, function (Blueprint $table) {
                $table->id();
                $table->string('scope', 30)->default(ProfileReferenceOption::SCOPE_COMMON);
                $table->string('label', 150);
                $table->string('value', 150);
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->unique(['scope', 'value']);
                $table->index(['scope', 'is_active']);
            });
        }

        $migrated = false;
        if (Schema::hasTable('profile_reference_options')) {
            foreach (array_diff_key(ProfileReferenceOption::tableMap(), array_flip($this->excludedTypes)) as $type => $table) {
                $rows = DB::table('profile_reference_options')->where('type', $type)->get();
                foreach ($rows as $row) {
                    DB::table($table)->updateOrInsert(
                        ['scope' => $row->scope, 'value' => $row->value],
                        [
                            'label' => $row->label,
                            'sort_order' => $row->sort_order,
                            'is_active' => $row->is_active,
                            'created_at' => $row->created_at,
                            'updated_at' => $row->updated_at,
                        ]
                    );
                    $migrated = true;
                }
            }

            Schema::dropIfExists('profile_reference_options');
        }

        if (! $migrated) {
            foreach (array_diff_key(ProfileReferenceOption::defaults(), array_flip($this->excludedTypes)) as $type => $scopeOptions) {
                $table = ProfileReferenceOption::tableFor($type);
                foreach ($scopeOptions as $scope => $options) {
                    $sortOrder = 1;
                    foreach ($options as $value => $label) {
                        if (is_int($value) && $type !== ProfileReferenceOption::TYPE_GENDER) {
                            $value = (string) $label;
                        }

                        DB::table($table)->updateOrInsert(
                            ['scope' => $scope, 'value' => (string) $value],
                            [
                                'label' => (string) $label,
                                'sort_order' => $sortOrder++,
                                'is_active' => true,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                    }
                }
            }
        }
    }

    public function down(): void
    {
        foreach (array_diff_key(ProfileReferenceOption::tableMap(), array_flip($this->excludedTypes)) as $table) {
            Schema::dropIfExists($table);
        }
    }
};
