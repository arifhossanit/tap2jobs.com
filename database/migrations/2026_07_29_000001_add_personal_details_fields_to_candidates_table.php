<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('mother_name')->nullable()->after('father_name');
            $table->string('religion')->nullable()->after('mother_name');
            $table->string('passport_number')->nullable()->after('national_id_card');
            $table->date('passport_issue_date')->nullable()->after('passport_number');
            $table->string('secondary_mobile')->nullable()->after('passport_issue_date');
            $table->string('alternate_email')->nullable()->after('secondary_mobile');
            $table->string('emergency_contact')->nullable()->after('alternate_email');
            $table->string('blood_group')->nullable()->after('emergency_contact');
            $table->decimal('height', 5, 2)->nullable()->after('blood_group');
            $table->decimal('weight', 5, 2)->nullable()->after('height');
            $table->text('objective')->nullable()->after('weight');
            $table->string('job_level')->nullable()->after('objective');
            $table->string('job_nature')->nullable()->after('job_level');
            $table->json('preferred_functional_categories')->nullable()->after('job_nature');
            $table->json('preferred_special_skills')->nullable()->after('preferred_functional_categories');
            $table->json('preferred_job_locations_inside')->nullable()->after('preferred_special_skills');
            $table->json('preferred_job_locations_outside')->nullable()->after('preferred_job_locations_inside');
            $table->json('preferred_organization_types')->nullable()->after('preferred_job_locations_outside');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn([
                'mother_name',
                'religion',
                'passport_number',
                'passport_issue_date',
                'secondary_mobile',
                'alternate_email',
                'emergency_contact',
                'blood_group',
                'height',
                'weight',
                'objective',
                'job_level',
                'job_nature',
                'preferred_functional_categories',
                'preferred_special_skills',
                'preferred_job_locations_inside',
                'preferred_job_locations_outside',
                'preferred_organization_types',
            ]);
        });
    }
};
