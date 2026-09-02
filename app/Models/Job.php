<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Job
 *
 * @property int $id
 * @property string $job_title
 * @property string $description
 * @property string|null $compensation_and_other_benefits
 * @property string $country
 * @property string $state
 * @property string $city
 * @property string $salary_from
 * @property string $salary_to
 * @property int $currency_id
 * @property int $salary_period_id
 * @property int $job_type_id
 * @property int $career_level_id
 * @property int $functional_area_id
 * @property int $job_shift_id
 * @property int $degree_level_id
 * @property int|null $degree_title_id
 * @property int $position_id
 * @property string $job_expiry_date
 * @property int $no_preference
 * @property int $hide_salary
 * @property int $is_freelance
 * @property int $is_suspended
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CareerLevel $careerLevel
 * @property-read SalaryCurrency $currency
 * @property-read RequiredDegreeLevel $degreeLevel
 * @property-read EducationDegreeTitle|null $degreeTitle
 * @property-read FunctionalArea $functionalArea
 * @property-read JobShift $jobShift
 * @property-read JobType $jobType
 * @property-read Position $position
 * @property-read SalaryPeriod $salaryPeriod
 *
 * @method static Builder|Job newModelQuery()
 * @method static Builder|Job newQuery()
 * @method static Builder|Job query()
 * @method static Builder|Job whereCareerLevelId($value)
 * @method static Builder|Job whereCity($value)
 * @method static Builder|Job whereCountry($value)
 * @method static Builder|Job whereCreatedAt($value)
 * @method static Builder|Job whereCurrencyId($value)
 * @method static Builder|Job whereDegreeLevelId($value)
 * @method static Builder|Job whereDescription($value)
 * @method static Builder|Job whereFunctionalAreaId($value)
 * @method static Builder|Job whereHideSalary($value)
 * @method static Builder|Job whereId($value)
 * @method static Builder|Job whereIsFreelance($value)
 * @method static Builder|Job whereIsFeatured($value)
 * @method static Builder|Job whereIsSuspended($value)
 * @method static Builder|Job whereJobExpiryDate($value)
 * @method static Builder|Job whereJobShiftId($value)
 * @method static Builder|Job whereJobTitle($value)
 * @method static Builder|Job whereJobTypeId($value)
 * @method static Builder|Job whereNoPreference($value)
 * @method static Builder|Job wherePositionId($value)
 * @method static Builder|Job whereSalaryFrom($value)
 * @method static Builder|Job whereSalaryPeriodId($value)
 * @method static Builder|Job whereSalaryTo($value)
 * @method static Builder|Job whereState($value)
 * @method static Builder|Job whereUpdatedAt($value)
 *
 * @mixin Eloquent
 *
 * @property int $company_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\JobApplication[] $appliedJobs
 * @property-read int|null $applied_jobs_count
 * @property-read \App\Models\Company $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\JobSkill[] $jobsSkill
 * @property-read int|null $jobs_skill_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Job whereCompanyId($value)
 *
 * @property string $job_id
 * @property int $job_category_id
 * @property-read \App\Models\JobCategory $jobCategory
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Job whereJobCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Job whereJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Job status($status)
 *
 * @property int $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Job whereStatus($value)
 *
 * @property int|null $country_id
 * @property int|null $state_id
 * @property int|null $city_id
 * @property-read mixed $city_name
 * @property-read mixed $country_name
 * @property-read mixed $state_name
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Job whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Job whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Job whereStateId($value)
 *
 * @property int|null $experience
 * @property string $experience_unit
 * @property string $experience_requirement
 * @property bool $freshers_encouraged
 * @property int $vacancy
 * @property-read \App\Models\FeaturedRecord|null $activeFeatured
 * @property-read \App\Models\FeaturedRecord|null $featured
 * @property-read string $full_location
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $jobsTag
 * @property-read int|null $jobs_tag_count
 *
 * @method static Builder|Job whereExperience($value)
 * @method static Builder|Job wherePosition($value)
 *
 * @property int $is_created_by_admin
 *
 * @method static Builder|Job whereIsCreatedByAdmin($value)
 */
class Job extends Model
{
    public const EXPERIENCE_UNIT_MONTH = 'month';
    public const EXPERIENCE_UNIT_YEAR = 'year';
    public const EXPERIENCE_UNIT_MONTH_YEAR = 'month_year';

    public const EXPERIENCE_UNITS = [
        self::EXPERIENCE_UNIT_MONTH => 'messages.job.month',
        self::EXPERIENCE_UNIT_YEAR => 'messages.job.year',
        self::EXPERIENCE_UNIT_MONTH_YEAR => 'messages.job.month_year',
    ];

    public const EMPLOYMENT_STATUS_FULL_TIME = 'full_time';
    public const EMPLOYMENT_STATUS_PART_TIME = 'part_time';
    public const EMPLOYMENT_STATUS_CONTRACTUAL = 'contractual';
    public const EMPLOYMENT_STATUS_INTERNSHIP = 'internship';
    public const EMPLOYMENT_STATUS_FREELANCE = 'freelance';

    public const JOB_NATURE_PERMANENT = 'permanent';
    public const JOB_NATURE_TEMPORARY = 'temporary';
    public const JOB_NATURE_PROJECT_BASED = 'project_based';
    public const JOB_NATURE_PROBATIONARY = 'probationary';
    public const JOB_NATURE_COMMISSION_BASED = 'commission_based';

    public const EMPLOYMENT_STATUSES = [
        self::EMPLOYMENT_STATUS_FULL_TIME => 'messages.job.full_time',
        self::EMPLOYMENT_STATUS_PART_TIME => 'messages.job.part_time',
        self::EMPLOYMENT_STATUS_CONTRACTUAL => 'messages.job.contractual',
        self::EMPLOYMENT_STATUS_INTERNSHIP => 'messages.job.internship',
        self::EMPLOYMENT_STATUS_FREELANCE => 'messages.job.freelance',
    ];

    public const JOB_NATURES = [
        self::JOB_NATURE_PERMANENT => 'messages.job.permanent',
        self::JOB_NATURE_TEMPORARY => 'messages.job.temporary',
        self::JOB_NATURE_PROJECT_BASED => 'messages.job.project_based',
        self::JOB_NATURE_PROBATIONARY => 'messages.job.probationary',
        self::JOB_NATURE_COMMISSION_BASED => 'messages.job.commission_based',
    ];

    const NO_PREFERENCE = [
        2 => 'Both',
        1 => 'Male',
        0 => 'Female',
    ];

    const GENDER = [
        0 => 'male',
        1 => 'female',
    ];

    const IS_SUSPENDED = [
        self::SELECT_IS_SUSPENDED => 'is_suspended',
        self::YES => 'yes',
        self::NO => 'no',
    ];


    const IS_FEATURED = [
        self::SELECT_FEATURD => 'select_featured_job',
        self::YES => 'yes',
        self::NO => 'no',
    ];
    const  SELECT_FEATURD = 2;
    const  SELECT_IS_SUSPENDED = 2;
    const SELECT_IS_FREELANCER = 2;
    const SELECT_JOBS_ACTIVE = 2;
    const YES = 1;
    const NO = 0;
    const ACTIVE = 0;
    const EXPIRE = 1;
    const STATUS_DRAFT = 0;

    const STATUS_OPEN = 1;

    const STATUS_CLOSED = 2;

    const STATUS_PAUSED = 3;

    const SELECT_STATUS = 4;

    const SELECT_PANDING = 5;

    const STATUS_SUSPENDED = 4;

    const NOT_SUSPENDED = 0;


    const STATUS = [
        self::SELECT_STATUS => 'select_status',
        self::STATUS_DRAFT => 'drafted',
        self::STATUS_OPEN => 'live',
        self::STATUS_CLOSED => 'closed',
        self::STATUS_PAUSED => 'paused',
        self::SELECT_PANDING => 'pending',
    ];

    const STATUS_ARRAY = [
        0 => 'Drafted',
        1 => 'Live',
        2 => 'Closed',
        3 => 'Paused',
    ];

    const FAVORITE_JOB_STATUS = [
        1 => 'Live',
        2 => 'Closed',
        3 => 'Paused',
    ];

    const STATUS_COLOR = [
        0 => 'warning',
        1 => 'success',
        2 => 'danger',
        3 => 'primary',
        5 => 'info',
    ];


    const IS_FREELANCER = [
        self::SELECT_IS_FREELANCER => 'is_freelance',
        self::YES => 'yes',
        self::NO => 'no',
    ];

    const JOBS_ACTIVE = [
        self::SELECT_JOBS_ACTIVE => 'select_job_status',
        self::ACTIVE => 'active',
        self::EXPIRE => 'expire',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'company_id' => 'sometimes|required',
        'job_title' => 'required|max:180',
        'currency_id' => 'required',
        'salary_period_id' => 'required',
        'job_type_id' => 'required',
        'employment_status' => 'nullable|in:permanent,temporary,project_based,probationary,commission_based,full_time,part_time,contractual,internship,freelance',
        'work_from_office' => 'nullable|boolean',
        'work_from_home' => 'nullable|boolean',
        'hybrid' => 'nullable|boolean',
        'functional_area_id' => 'required',
        'experience' => 'required|integer|min:0|max:60',
        'experience_unit' => 'required|in:month,year,month_year',
        'experience_requirement' => 'required|string|max:100',
        'freshers_encouraged' => 'required|boolean',
        'vacancy' => 'required|integer|min:1|max:4294967295',
        'country_id' => 'required',
        'job_category_id' => 'required',
        'state_id' => 'required',
        'city_id' => 'required',
        'thana_id' => 'nullable',
        'salary_from' => 'required|min:0|max:9999999999',
        'salary_to' => 'required|min:0|max:9999999999',
        'job_expiry_date' => 'required',
    ];

    protected $table = 'jobs';

    protected $fillable = [
        'job_id',
        'job_title',
        'description',
        'salary_from',
        'salary_to',
        'company_id',
        'job_category_id',
        'currency_id',
        'salary_period_id',
        'job_type_id',
        'employment_status',
        'work_from_office',
        'work_from_home',
        'hybrid',
        'career_level_id',
        'functional_area_id',
        'job_shift_id',
        'degree_level_id',
        'degree_title_id',
        'experience',
        'experience_unit',
        'experience_requirement',
        'freshers_encouraged',
        'vacancy',
        'job_expiry_date',
        'no_preference',
        'hide_salary',
        'is_freelance',
        'is_suspended',
        'country_id',
        'state_id',
        'city_id',
        'thana_id',
        'city_village_name',
        'address',
        'status',
        'is_created_by_admin',
        'last_change',
        'key_responsibilities',
        'compensation_and_other_benefits',
        'reject_reason',
    ];

    public $casts = [
        'id' => 'integer',
        'job_id' => 'string',
        'job_title' => 'string',
        'company_id' => 'integer',
        'job_category_id' => 'integer',
        'currency_id' => 'integer',
        'salary_period_id' => 'integer',
        'job_type_id' => 'integer',
        'employment_status' => 'string',
        'work_from_office' => 'boolean',
        'work_from_home' => 'boolean',
        'hybrid' => 'boolean',
        'career_level_id' => 'integer',
        'functional_area_id' => 'integer',
        'job_shift_id' => 'integer',
        'degree_level_id' => 'integer',
        'degree_title_id' => 'integer',
        'experience' => 'integer',
        'experience_unit' => 'string',
        'experience_requirement' => 'string',
        'freshers_encouraged' => 'boolean',
        'vacancy' => 'integer',
        'salary_from' => 'double',
        'salary_to' => 'double',
        'country_id' => 'integer',
        'city_id' => 'integer',
        'state_id' => 'integer',
        'thana_id' => 'integer',
        'city_village_name' => 'string',
        'address' => 'string',
        'description' => 'string',
        'compensation_and_other_benefits' => 'string',
        'job_expiry_date' => 'date',
        'no_preference' => 'integer',
        'hide_salary' => 'boolean',
        'is_freelance' => 'boolean',
        'is_suspended' => 'boolean',
        'status' => 'integer',
        'is_created_by_admin' => 'integer',
        'last_change' => 'integer',
    ];

    protected $appends = ['country_name', 'state_name', 'city_name', 'city_village_name', 'thana_name'];

    protected $with = ['country', 'state', 'city', 'thana', 'activeFeatured'];

    public function getFormattedExperienceAttribute(): string
    {
        $requirement = trim((string) $this->experience_requirement);

        if (preg_match('/\pL/u', $requirement)) {
            return $requirement;
        }

        if ($this->experience_unit === self::EXPERIENCE_UNIT_MONTH) {
            return $requirement.' '.__('messages.job.months');
        }

        if ($this->experience_unit === self::EXPERIENCE_UNIT_YEAR) {
            return $requirement.' '.__('messages.job.years');
        }

        return $requirement;
    }

    public function getDescriptionAttribute(?string $value): string
    {
        return $this->normalizeLegacyQuillHtml($value);
    }

    public function getKeyResponsibilitiesAttribute(?string $value): string
    {
        return $this->normalizeLegacyQuillHtml($value);
    }

    public function getCompensationAndOtherBenefitsAttribute(?string $value): string
    {
        return $this->normalizeLegacyQuillHtml($value);
    }

    private function normalizeLegacyQuillHtml(?string $html): string
    {
        return (string) preg_replace_callback(
            '/(data-list|class|contenteditable)=\\\\([^\\\\<>]+)\\\\/',
            static fn (array $matches): string => $matches[1].'="'.$matches[2].'"',
            (string) $html
        );
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function cityVillage(): BelongsTo
    {
        return $this->belongsTo(CityVillage::class, 'city_village_id');
    }

    public function thana(): BelongsTo
    {
        return $this->belongsTo(Thana::class, 'thana_id');
    }

    public function admin(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'last_change');
    }

    public function getCountryNameAttribute()
    {
        if (! empty($this->country)) {
            return $this->country->name;
        }
    }

    public function getStateNameAttribute()
    {
        if (! empty($this->state)) {
            return $this->state->name;
        }
    }

    public function getCityNameAttribute()
    {
        if (! empty($this->city)) {
            return $this->city->name;
        }
    }

    public function getCityVillageNameAttribute()
    {
        return $this->attributes['city_village_name'] ?? null;
    }

    public function getThanaNameAttribute()
    {
        if (! empty($this->thana)) {
            return $this->thana->name;
        }
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function scopeStatus(Builder $query, int $status): Builder
    {
        return $query->where('status', $status);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(SalaryCurrency::class, 'currency_id');
    }

    public function salaryPeriod(): BelongsTo
    {
        return $this->belongsTo(SalaryPeriod::class, 'salary_period_id');
    }

    public function jobType(): BelongsTo
    {
        return $this->belongsTo(JobType::class, 'job_type_id');
    }

    public function careerLevel(): BelongsTo
    {
        return $this->belongsTo(CareerLevel::class, 'career_level_id');
    }

    public function functionalArea(): BelongsTo
    {
        return $this->belongsTo(FunctionalArea::class, 'functional_area_id');
    }

    public function jobShift(): BelongsTo
    {
        return $this->belongsTo(JobShift::class, 'job_shift_id');
    }

    public function degreeLevel(): BelongsTo
    {
        return $this->belongsTo(RequiredDegreeLevel::class, 'degree_level_id');
    }

    public function degreeTitle(): BelongsTo
    {
        return $this->belongsTo(EducationDegreeTitle::class, 'degree_title_id');
    }

    public function jobsSkill(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'jobs_skill', 'job_id', 'skill_id');
    }

    public function jobsTag(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'jobs_tag', 'job_id', 'tag_id');
    }

    public function appliedJobs(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'job_id', 'id');
    }

    public function jobCategory(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    public function getFullLocationAttribute(): string
    {
        $location = '';
        if (! empty($this->city)) {
            $location = $this->city->name.', ';
        }

        if (! empty($this->thana)) {
            $location = $this->thana->name.', '.$location;
        }

        if (! empty($this->state)) {
            $location = $location.$this->state->name.', ';
        }

        if (! empty($this->country)) {
            $location = $location.$this->country->name;
        }

        return $location;
    }

    public function featured(): MorphOne
    {
        return $this->morphOne(FeaturedRecord::class, 'owner');
    }

    public function activeFeatured(): MorphOne
    {
        return $this->morphOne(FeaturedRecord::class, 'owner')->where('end_time', '>', \Carbon\Carbon::now());
    }

    public function workplaces(): HasMany
    {
        return $this->hasMany(JobWorkplace::class);
    }

    public function getSelectedWorkplacesAttribute(): array
    {
        $workplaces = $this->workplaces()->pluck('workplace_value')->toArray();
        if (!empty($workplaces)) {
            return $workplaces;
        }

        $legacyWorkplaces = [];
        if ($this->work_from_office) $legacyWorkplaces[] = 'work_from_office';
        if ($this->work_from_home) $legacyWorkplaces[] = 'work_from_home';
        if ($this->hybrid) $legacyWorkplaces[] = 'hybrid';

        return $legacyWorkplaces;
    }
}

