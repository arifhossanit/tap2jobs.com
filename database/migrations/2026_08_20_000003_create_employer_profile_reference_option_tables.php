<?php

use App\Models\ProfileReferenceOption;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $types = array_diff_key(ProfileReferenceOption::tableMap(), array_flip([
            ProfileReferenceOption::TYPE_CONSULTATION_TYPE,
            ProfileReferenceOption::TYPE_CONSULTATION_CONTACT_METHOD,
        ]));

        foreach ($types as $type => $table) {
            if (! Schema::hasTable($table)) {
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

            if (DB::table($table)->exists()) {
                continue;
            }

            foreach (ProfileReferenceOption::defaults()[$type] ?? [] as $scope => $options) {
                $sortOrder = 1;
                foreach ($options as $value => $label) {
                    if (is_int($value) && ! in_array($type, [
                        ProfileReferenceOption::TYPE_GENDER,
                        ProfileReferenceOption::TYPE_JOB_GENDER_PREFERENCE,
                    ], true)) {
                        $value = (string) $label;
                    }

                    DB::table($table)->insert([
                        'scope' => $scope,
                        'label' => (string) $label,
                        'value' => (string) $value,
                        'sort_order' => $sortOrder++,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        foreach ([
            ProfileReferenceOption::TYPE_JOB_GENDER_PREFERENCE,
            ProfileReferenceOption::TYPE_JOB_EMPLOYMENT_STATUS,
            ProfileReferenceOption::TYPE_JOB_WORKPLACE,
            ProfileReferenceOption::TYPE_JOB_EXPERIENCE_UNIT,
            ProfileReferenceOption::TYPE_EMPLOYER_DISABILITY_FACILITY,
        ] as $type) {
            Schema::dropIfExists(ProfileReferenceOption::tableFor($type));
        }
    }
};
