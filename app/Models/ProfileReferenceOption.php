<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProfileReferenceOption extends Model
{
    public const SCOPE_COMMON = 'common';
    public const SCOPE_CANDIDATE = 'candidate';
    public const SCOPE_EMPLOYER = 'employer';

    public const TYPE_GENDER = 'gender';
    public const TYPE_RELIGION = 'religion';
    public const TYPE_BLOOD_GROUP = 'blood_group';
    public const TYPE_DISABILITY_DIFFICULTY = 'disability_difficulty';
    public const TYPE_SKILL_LEARNING_SOURCE = 'skill_learning_source';
    public const TYPE_LANGUAGE_PROFICIENCY = 'language_proficiency';
    public const TYPE_ONLINE_PROFILE_PLATFORM = 'online_profile_platform';
    public const TYPE_REFERENCE_RELATION = 'reference_relation';
    public const TYPE_EDUCATION_RESULT = 'education_result';
    public const TYPE_ARMY_BA_NO_PREFIX = 'army_ba_no_prefix';
    public const TYPE_ARMY_RANK = 'army_rank';
    public const TYPE_ARMY_EMPLOYMENT_TYPE = 'army_employment_type';
    public const TYPE_ARMY_ARMS = 'army_arms';
    public const TYPE_JOB_GENDER_PREFERENCE = 'job_gender_preference';
    public const TYPE_JOB_EMPLOYMENT_STATUS = 'job_employment_status';
    public const TYPE_JOB_WORKPLACE = 'job_workplace';
    public const TYPE_JOB_EXPERIENCE_UNIT = 'job_experience_unit';
    public const TYPE_EMPLOYER_DISABILITY_FACILITY = 'employer_disability_facility';
    public const TYPE_CONSULTATION_TYPE = 'consultation_type';
    public const TYPE_CONSULTATION_CONTACT_METHOD = 'consultation_contact_method';

    public $fillable = [
        'scope',
        'label',
        'value',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public static function typeLabels(): array
    {
        return [
            self::TYPE_GENDER => 'Gender',
            self::TYPE_RELIGION => 'Religion',
            self::TYPE_BLOOD_GROUP => 'Blood Group',
            self::TYPE_DISABILITY_DIFFICULTY => 'Disability Difficulty',
            self::TYPE_SKILL_LEARNING_SOURCE => 'Skill Learning Source',
            self::TYPE_LANGUAGE_PROFICIENCY => 'Language Proficiency',
            self::TYPE_ONLINE_PROFILE_PLATFORM => 'Online Profile Platform',
            self::TYPE_REFERENCE_RELATION => 'Reference Relation',
            self::TYPE_EDUCATION_RESULT => 'Education Result',
            self::TYPE_ARMY_BA_NO_PREFIX => 'BA No Prefix',
            self::TYPE_ARMY_RANK => 'Rank',
            self::TYPE_ARMY_EMPLOYMENT_TYPE => 'Employment Type',
            self::TYPE_ARMY_ARMS => 'Arms',
            self::TYPE_JOB_GENDER_PREFERENCE => 'Gender Preference',
            self::TYPE_JOB_EMPLOYMENT_STATUS => 'Job Nature',
            self::TYPE_JOB_WORKPLACE => 'Workplace',
            self::TYPE_JOB_EXPERIENCE_UNIT => 'Experience Unit',
            self::TYPE_EMPLOYER_DISABILITY_FACILITY => 'Disability Facilities',
            self::TYPE_CONSULTATION_TYPE => 'Consultation Types',
            self::TYPE_CONSULTATION_CONTACT_METHOD => 'Contact Methods',
        ];
    }

    public static function scopeLabels(): array
    {
        return [
            self::SCOPE_COMMON => 'Common',
            self::SCOPE_CANDIDATE => 'Candidate',
            self::SCOPE_EMPLOYER => 'Employer',
        ];
    }

    public static function tableMap(): array
    {
        return [
            self::TYPE_GENDER => 'reference_genders',
            self::TYPE_RELIGION => 'reference_religions',
            self::TYPE_BLOOD_GROUP => 'reference_blood_groups',
            self::TYPE_DISABILITY_DIFFICULTY => 'reference_disability_difficulties',
            self::TYPE_SKILL_LEARNING_SOURCE => 'reference_skill_learning_sources',
            self::TYPE_LANGUAGE_PROFICIENCY => 'reference_language_proficiencies',
            self::TYPE_ONLINE_PROFILE_PLATFORM => 'reference_online_profile_platforms',
            self::TYPE_REFERENCE_RELATION => 'reference_relations',
            self::TYPE_EDUCATION_RESULT => 'reference_education_results',
            self::TYPE_ARMY_BA_NO_PREFIX => 'reference_army_ba_no_prefixes',
            self::TYPE_ARMY_RANK => 'reference_army_ranks',
            self::TYPE_ARMY_EMPLOYMENT_TYPE => 'reference_army_employment_types',
            self::TYPE_ARMY_ARMS => 'reference_army_arms',
            self::TYPE_JOB_GENDER_PREFERENCE => 'reference_job_gender_preferences',
            self::TYPE_JOB_EMPLOYMENT_STATUS => 'reference_job_employment_statuses',
            self::TYPE_JOB_WORKPLACE => 'reference_job_workplaces',
            self::TYPE_JOB_EXPERIENCE_UNIT => 'reference_job_experience_units',
            self::TYPE_EMPLOYER_DISABILITY_FACILITY => 'reference_employer_disability_facilities',
            self::TYPE_CONSULTATION_TYPE => 'reference_consultation_types',
            self::TYPE_CONSULTATION_CONTACT_METHOD => 'reference_consultation_contact_methods',
        ];
    }

    public static function tableFor(string $type): string
    {
        return self::tableMap()[$type] ?? '';
    }

    public static function menuGroups(): array
    {
        return [
            self::SCOPE_COMMON => [
                self::TYPE_GENDER,
                self::TYPE_LANGUAGE_PROFICIENCY,
                self::TYPE_ONLINE_PROFILE_PLATFORM,
            ],
            self::SCOPE_CANDIDATE => [
                self::TYPE_RELIGION,
                self::TYPE_BLOOD_GROUP,
                self::TYPE_DISABILITY_DIFFICULTY,
                self::TYPE_SKILL_LEARNING_SOURCE,
                self::TYPE_REFERENCE_RELATION,
                self::TYPE_EDUCATION_RESULT,
                self::TYPE_ARMY_BA_NO_PREFIX,
                self::TYPE_ARMY_RANK,
                self::TYPE_ARMY_EMPLOYMENT_TYPE,
                self::TYPE_ARMY_ARMS,
            ],
            self::SCOPE_EMPLOYER => [
                self::TYPE_REFERENCE_RELATION,
                self::TYPE_JOB_GENDER_PREFERENCE,
                self::TYPE_JOB_EMPLOYMENT_STATUS,
                self::TYPE_JOB_WORKPLACE,
                self::TYPE_JOB_EXPERIENCE_UNIT,
                self::TYPE_EMPLOYER_DISABILITY_FACILITY,
                self::TYPE_CONSULTATION_TYPE,
                self::TYPE_CONSULTATION_CONTACT_METHOD,
            ],
        ];
    }

    public static function isAllowedForScope(string $scope, string $type): bool
    {
        return in_array($type, self::menuGroups()[$scope] ?? [], true);
    }

    public static function candidateDedicatedRouteNames(): array
    {
        return [
            self::TYPE_RELIGION => 'candidateReligions',
            self::TYPE_BLOOD_GROUP => 'bloodGroups',
            self::TYPE_DISABILITY_DIFFICULTY => 'disabilityDifficulties',
            self::TYPE_SKILL_LEARNING_SOURCE => 'skillLearningSources',
            self::TYPE_REFERENCE_RELATION => 'candidateReferenceRelations',
            self::TYPE_EDUCATION_RESULT => 'educationResults',
            self::TYPE_ARMY_BA_NO_PREFIX => 'armyBaNoPrefixes',
            self::TYPE_ARMY_RANK => 'armyRanks',
            self::TYPE_ARMY_EMPLOYMENT_TYPE => 'armyEmploymentTypes',
            self::TYPE_ARMY_ARMS => 'armyArms',
        ];
    }

    public static function commonDedicatedRouteNames(): array
    {
        return [
            self::TYPE_GENDER => 'genders',
            self::TYPE_LANGUAGE_PROFICIENCY => 'languageProficiencies',
            self::TYPE_ONLINE_PROFILE_PLATFORM => 'onlineProfilePlatforms',
        ];
    }

    public static function employerDedicatedRouteNames(): array
    {
        return [
            self::TYPE_REFERENCE_RELATION => 'employerReferenceRelations',
            self::TYPE_JOB_GENDER_PREFERENCE => 'jobGenderPreferences',
            self::TYPE_JOB_EMPLOYMENT_STATUS => 'jobEmploymentStatuses',
            self::TYPE_JOB_WORKPLACE => 'jobWorkplaces',
            self::TYPE_JOB_EXPERIENCE_UNIT => 'jobExperienceUnits',
            self::TYPE_EMPLOYER_DISABILITY_FACILITY => 'employerDisabilityFacilities',
            self::TYPE_CONSULTATION_TYPE => 'consultationTypes',
            self::TYPE_CONSULTATION_CONTACT_METHOD => 'consultationContactMethods',
        ];
    }

    public static function dedicatedRouteName(string $scope, string $type): ?string
    {
        if ($scope === self::SCOPE_COMMON) {
            return self::commonDedicatedRouteNames()[$type] ?? null;
        }

        if ($scope === self::SCOPE_CANDIDATE) {
            return self::candidateDedicatedRouteNames()[$type] ?? null;
        }

        if ($scope === self::SCOPE_EMPLOYER) {
            return self::employerDedicatedRouteNames()[$type] ?? null;
        }

        return null;
    }

    public static function defaults(): array
    {
        return [
            self::TYPE_GENDER => [
                self::SCOPE_COMMON => ['0' => 'Male', '1' => 'Female', '2' => 'Other'],
            ],
            self::TYPE_RELIGION => [
                self::SCOPE_COMMON => ['Islam', 'Hinduism', 'Christianity', 'Buddhism', 'Other'],
            ],
            self::TYPE_BLOOD_GROUP => [
                self::SCOPE_CANDIDATE => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
            ],
            self::TYPE_DISABILITY_DIFFICULTY => [
                self::SCOPE_CANDIDATE => [
                    'no_difficulty' => 'No difficulty',
                    'some_difficulty' => 'Some difficulty',
                    'a_lot_of_difficulty' => 'A lot of difficulty',
                    'cannot_do' => 'Cannot do',
                ],
            ],
            self::TYPE_SKILL_LEARNING_SOURCE => [
                self::SCOPE_CANDIDATE => ['Self', 'Job', 'Educational', 'Professional Training', 'NTVQF'],
            ],
            self::TYPE_LANGUAGE_PROFICIENCY => [
                self::SCOPE_COMMON => ['High', 'Medium', 'Low'],
            ],
            self::TYPE_ONLINE_PROFILE_PLATFORM => [
                self::SCOPE_COMMON => ['Facebook', 'GitHub', 'LinkedIn', 'Twitter', 'Website'],
            ],
            self::TYPE_REFERENCE_RELATION => [
                self::SCOPE_CANDIDATE => ['Relative', 'Academic', 'Professional', 'Other'],
                self::SCOPE_EMPLOYER => ['Business', 'Professional', 'Vendor', 'Other'],
            ],
            self::TYPE_EDUCATION_RESULT => [
                self::SCOPE_CANDIDATE => [
                    'First Division/Class',
                    'Second Division/Class',
                    'Third Division/Class',
                    'Grade',
                    'Appeared',
                    'Enrolled',
                    'Awarded',
                    'Do not mention',
                    'Pass',
                ],
            ],
            self::TYPE_ARMY_BA_NO_PREFIX => [
                self::SCOPE_CANDIDATE => ['BA', 'BSS', 'JC'],
            ],
            self::TYPE_ARMY_RANK => [
                self::SCOPE_CANDIDATE => ['Captain', 'Major', 'Colonel'],
            ],
            self::TYPE_ARMY_EMPLOYMENT_TYPE => [
                self::SCOPE_CANDIDATE => ['Commissioned', 'Non Commissioned'],
            ],
            self::TYPE_ARMY_ARMS => [
                self::SCOPE_CANDIDATE => ['Infantry', 'Artillery', 'Signals'],
            ],
            self::TYPE_JOB_GENDER_PREFERENCE => [
                self::SCOPE_EMPLOYER => ['2' => 'Both', '1' => 'Male', '0' => 'Female'],
            ],
            self::TYPE_JOB_EMPLOYMENT_STATUS => [
                self::SCOPE_EMPLOYER => [
                    'permanent' => 'Permanent',
                    'temporary' => 'Temporary',
                    'project_based' => 'Project Based',
                    'probationary' => 'Probationary',
                    'commission_based' => 'Commission Based',
                ],
            ],
            self::TYPE_JOB_WORKPLACE => [
                self::SCOPE_EMPLOYER => [
                    'work_from_office' => 'Work From Office',
                    'work_from_home' => 'Work From Home',
                    'hybrid' => 'Hybrid',
                ],
            ],
            self::TYPE_JOB_EXPERIENCE_UNIT => [
                self::SCOPE_EMPLOYER => [
                    'month' => 'Month',
                    'year' => 'Year',
                    'month_year' => 'Month/Year',
                ],
            ],
            self::TYPE_EMPLOYER_DISABILITY_FACILITY => [
                self::SCOPE_EMPLOYER => [
                    'accessible_documentation' => 'Accessible documentation',
                    'accessible_washrooms' => 'Accessible washrooms',
                    'adapted_transport' => 'Adapted transport',
                    'assistive_software' => 'Assistive software',
                    'flexible_shifts' => 'Flexible shifts',
                    'work_from_home' => 'Work from home',
                    'ramps_lifts' => 'Ramps and lifts',
                    'reasonable_accommodation' => 'Reasonable accommodation',
                    'warning_indicators' => 'Warning indicators',
                    'workstation_adaptations' => 'Workstation adaptations',
                ],
            ],
            self::TYPE_CONSULTATION_TYPE => [
                self::SCOPE_EMPLOYER => [
                    'job_posting' => 'Job Posting',
                    'employer_branding' => 'Employer Branding',
                    'recruitment_support' => 'Recruitment Support',
                    'advertising' => 'Advertising',
                    'other' => 'Other',
                ],
            ],
            self::TYPE_CONSULTATION_CONTACT_METHOD => [
                self::SCOPE_EMPLOYER => [
                    'phone' => 'Phone',
                    'email' => 'Email',
                    'whatsapp' => 'WhatsApp',
                ],
            ],
        ];
    }

    public static function options(string $type, array $scopes = [self::SCOPE_COMMON, self::SCOPE_CANDIDATE]): array
    {
        $table = self::tableFor($type);

        if ($table === '' || ! Schema::hasTable($table)) {
            return self::defaultOptions($type, $scopes);
        }

        $options = DB::table($table)
            ->whereIn('scope', $scopes)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('label')
            ->pluck('label', 'value')
            ->toArray();

        return $options ?: self::defaultOptions($type, $scopes);
    }

    public static function records(?string $type = null, ?string $scope = null): Collection
    {
        return collect(self::tableMap())
            ->when($type, fn (Collection $tables) => $tables->only($type))
            ->flatMap(function (string $table, string $recordType) use ($scope) {
                if (! Schema::hasTable($table)) {
                    return collect();
                }

                $query = DB::table($table)
                    ->select([
                        'id',
                        'scope',
                        'label',
                        'value',
                        'sort_order',
                        'is_active',
                        'created_at',
                        'updated_at',
                    ]);

                if ($scope) {
                    $query->where('scope', $scope);
                }

                return $query->get()->map(function ($record) use ($recordType) {
                    $record->type = $recordType;
                    $record->route_key = $recordType.':'.$record->id;

                    return $record;
                });
            })
            ->sortBy([
                ['type', 'asc'],
                ['sort_order', 'asc'],
                ['label', 'asc'],
            ])
            ->values();
    }

    public static function findRecord(string $type, int $id): ?self
    {
        $table = self::tableFor($type);

        if ($table === '' || ! Schema::hasTable($table)) {
            return null;
        }

        $record = (new self())->setTable($table);
        $record = $record->newQuery()->find($id);

        if ($record) {
            $record->setAttribute('type', $type);
            $record->syncOriginal();
        }

        return $record;
    }

    public static function createRecord(string $type, array $input): self
    {
        $record = (new self())->setTable(self::tableFor($type));
        $record->fill(collect($input)->except('type')->toArray());
        $record->save();
        $record->setAttribute('type', $type);
        $record->syncOriginal();

        return $record;
    }

    public static function values(string $type, array $scopes = [self::SCOPE_COMMON, self::SCOPE_CANDIDATE]): array
    {
        return array_keys(self::options($type, $scopes));
    }

    public static function defaultOptions(string $type, array $scopes = [self::SCOPE_COMMON, self::SCOPE_CANDIDATE]): array
    {
        $options = [];

        foreach (self::defaults()[$type] ?? [] as $scope => $items) {
            if (! in_array($scope, $scopes, true)) {
                continue;
            }

            foreach ($items as $value => $label) {
                if (is_int($value) && ! in_array($type, [self::TYPE_GENDER, self::TYPE_JOB_GENDER_PREFERENCE], true)) {
                    $value = (string) $label;
                }

                $options[(string) $value] = (string) $label;
            }
        }

        return $options;
    }

    protected static function booted(): void
    {
        static::saving(function (ProfileReferenceOption $option) {
            $option->scope = $option->scope ?: self::SCOPE_COMMON;
            $option->value = filled($option->value) ? trim((string) $option->value) : trim((string) $option->label);
        });
    }
}
