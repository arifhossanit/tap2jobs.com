<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            if (! Schema::hasColumn('candidates', 'mother_name')) {
                $table->string('mother_name')->nullable()->after('father_name');
            }
            if (! Schema::hasColumn('candidates', 'religion')) {
                $table->string('religion')->nullable()->after('mother_name');
            }
            if (! Schema::hasColumn('candidates', 'passport_number')) {
                $table->string('passport_number')->nullable()->after('national_id_card');
            }
            if (! Schema::hasColumn('candidates', 'passport_issue_date')) {
                $table->date('passport_issue_date')->nullable()->after('passport_number');
            }
            if (! Schema::hasColumn('candidates', 'secondary_mobile')) {
                $table->string('secondary_mobile')->nullable()->after('passport_issue_date');
            }
            if (! Schema::hasColumn('candidates', 'alternate_email')) {
                $table->string('alternate_email')->nullable()->after('secondary_mobile');
            }
            if (! Schema::hasColumn('candidates', 'emergency_contact')) {
                $table->string('emergency_contact')->nullable()->after('alternate_email');
            }
            if (! Schema::hasColumn('candidates', 'blood_group')) {
                $table->string('blood_group')->nullable()->after('emergency_contact');
            }
            if (! Schema::hasColumn('candidates', 'height')) {
                $table->decimal('height', 5, 2)->nullable()->after('blood_group');
            }
            if (! Schema::hasColumn('candidates', 'weight')) {
                $table->decimal('weight', 5, 2)->nullable()->after('height');
            }
            if (! Schema::hasColumn('candidates', 'objective')) {
                $table->text('objective')->nullable()->after('weight');
            }
            if (! Schema::hasColumn('candidates', 'job_level')) {
                $table->string('job_level')->nullable()->after('objective');
            }
            if (! Schema::hasColumn('candidates', 'job_nature')) {
                $table->string('job_nature')->nullable()->after('job_level');
            }
            if (! Schema::hasColumn('candidates', 'preferred_functional_categories')) {
                $table->json('preferred_functional_categories')->nullable()->after('job_nature');
            }
            if (! Schema::hasColumn('candidates', 'preferred_special_skills')) {
                $table->json('preferred_special_skills')->nullable()->after('preferred_functional_categories');
            }
            if (! Schema::hasColumn('candidates', 'preferred_job_locations_inside')) {
                $table->json('preferred_job_locations_inside')->nullable()->after('preferred_special_skills');
            }
            if (! Schema::hasColumn('candidates', 'preferred_job_locations_outside')) {
                $table->json('preferred_job_locations_outside')->nullable()->after('preferred_job_locations_inside');
            }
            if (! Schema::hasColumn('candidates', 'preferred_organization_types')) {
                $table->json('preferred_organization_types')->nullable()->after('preferred_job_locations_outside');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('candidates', 'mother_name') ? 'mother_name' : null,
                Schema::hasColumn('candidates', 'religion') ? 'religion' : null,
                Schema::hasColumn('candidates', 'passport_number') ? 'passport_number' : null,
                Schema::hasColumn('candidates', 'passport_issue_date') ? 'passport_issue_date' : null,
                Schema::hasColumn('candidates', 'secondary_mobile') ? 'secondary_mobile' : null,
                Schema::hasColumn('candidates', 'alternate_email') ? 'alternate_email' : null,
                Schema::hasColumn('candidates', 'emergency_contact') ? 'emergency_contact' : null,
                Schema::hasColumn('candidates', 'blood_group') ? 'blood_group' : null,
                Schema::hasColumn('candidates', 'height') ? 'height' : null,
                Schema::hasColumn('candidates', 'weight') ? 'weight' : null,
                Schema::hasColumn('candidates', 'objective') ? 'objective' : null,
                Schema::hasColumn('candidates', 'job_level') ? 'job_level' : null,
                Schema::hasColumn('candidates', 'job_nature') ? 'job_nature' : null,
                Schema::hasColumn('candidates', 'preferred_functional_categories') ? 'preferred_functional_categories' : null,
                Schema::hasColumn('candidates', 'preferred_special_skills') ? 'preferred_special_skills' : null,
                Schema::hasColumn('candidates', 'preferred_job_locations_inside') ? 'preferred_job_locations_inside' : null,
                Schema::hasColumn('candidates', 'preferred_job_locations_outside') ? 'preferred_job_locations_outside' : null,
                Schema::hasColumn('candidates', 'preferred_organization_types') ? 'preferred_organization_types' : null,
            ]);

            if ($columns) {
                $table->dropColumn(array_values($columns));
            }
        });
    }
};
