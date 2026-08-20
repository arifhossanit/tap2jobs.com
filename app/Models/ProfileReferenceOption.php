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
            self::TYPE_GENDER => 'profile_genders',
            self::TYPE_RELIGION => 'profile_religions',
            self::TYPE_BLOOD_GROUP => 'profile_blood_groups',
            self::TYPE_DISABILITY_DIFFICULTY => 'profile_disability_difficulties',
            self::TYPE_SKILL_LEARNING_SOURCE => 'profile_skill_learning_sources',
            self::TYPE_LANGUAGE_PROFICIENCY => 'profile_language_proficiencies',
            self::TYPE_ONLINE_PROFILE_PLATFORM => 'profile_online_profile_platforms',
            self::TYPE_REFERENCE_RELATION => 'profile_reference_relations',
            self::TYPE_EDUCATION_RESULT => 'profile_education_results',
            self::TYPE_ARMY_BA_NO_PREFIX => 'profile_army_ba_no_prefixes',
            self::TYPE_ARMY_RANK => 'profile_army_ranks',
            self::TYPE_ARMY_EMPLOYMENT_TYPE => 'profile_army_employment_types',
            self::TYPE_ARMY_ARMS => 'profile_army_arms',
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
            ],
        ];
    }

    public static function isAllowedForScope(string $scope, string $type): bool
    {
        return in_array($type, self::menuGroups()[$scope] ?? [], true);
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
                if (is_int($value) && $type !== self::TYPE_GENDER) {
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
