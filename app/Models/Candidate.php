<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class Candidate
 *
 * @version July 20, 2020, 5:48 am UTC
 *
 * @property int $id
 * @property int $user_id
 * @property string $unique_id
 * @property string|null $father_name
 * @property int $marital_status_id
 * @property string|null $nationality
 * @property string|null $national_id_card
 * @property int|null $experience
 * @property int|null $career_level_id
 * @property int|null $industry_id
 * @property int|null $functional_area_id
 * @property float|null $current_salary
 * @property float|null $expected_salary
 * @property string|null $salary_currency
 * @property string|null $address
 * @property int $immediate_available
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CareerLevel|null $careerLevel
 * @property-read FunctionalArea|null $functionalArea
 * @property-read Industry|null $industry
 * @property-read MaritalStatus $maritalStatus
 * @property-read Collection|Media[] $media
 * @property-read int|null $media_count
 * @property-read User $user
 * @property-read mixed $candidate_url
 *
 * @method static Builder|Candidate newModelQuery()
 * @method static Builder|Candidate newQuery()
 * @method static Builder|Candidate query()
 * @method static Builder|Candidate whereAddress($value)
 * @method static Builder|Candidate whereCareerLevelId($value)
 * @method static Builder|Candidate whereCreatedAt($value)
 * @method static Builder|Candidate whereCurrentSalary($value)
 * @method static Builder|Candidate whereExpectedSalary($value)
 * @method static Builder|Candidate whereExperience($value)
 * @method static Builder|Candidate whereFatherName($value)
 * @method static Builder|Candidate whereFunctionalAreaId($value)
 * @method static Builder|Candidate whereId($value)
 * @method static Builder|Candidate whereImmediateAvailable($value)
 * @method static Builder|Candidate whereIndustryId($value)
 * @method static Builder|Candidate whereMaritalStatusId($value)
 * @method static Builder|Candidate whereNationalIdCard($value)
 * @method static Builder|Candidate whereNationality($value)
 * @method static Builder|Candidate whereSalaryCurrency($value)
 * @method static Builder|Candidate whereUpdatedAt($value)
 * @method static Builder|Candidate whereUserId($value)
 * @method static Builder|Candidate whereUniqueId($value)
 *
 * @mixin Eloquent
 *
 * @property int $job_alert
 * @property-read mixed $city_name
 * @property-read mixed $country_name
 * @property-read string $full_location
 * @property-read mixed $state_name
 * @property-read Collection|\App\Models\JobType[] $jobAlerts
 * @property-read int|null $job_alerts_count
 * @property-read Collection|\App\Models\JobApplication[] $jobApplications
 * @property-read int|null $job_applications_count
 * @property-read Collection|\App\Models\JobApplication[] $penddingJobApplications
 * @property-read int|null $pendding_job_applications_count
 *
 * @method static Builder|Candidate whereJobAlert($value)
 *
 * @property string|null $available_at
 *
 * @method static Builder|Candidate whereAvailableAt($value)
 */
class Candidate extends Model implements HasMedia
{
    use InteractsWithMedia;

    public $table = 'candidates';

    const RESUME_PATH = 'resumes';

    public const CANDIDATE_LOGIN_TYPE = 1;

    public const CANDIDATE_EMP_TYPE = 2;

    const ALL = 2;
    const ACTIVE = 1;
    const DEACTIVE = 0;

    const STATUS = [
        self::ALL => 'all',
        self::ACTIVE => 'active',
        self::DEACTIVE => 'deactive',
    ];

    const IMMEDIATE_AVAILABLE = 1;
    const Not_IMMEDIATE_AVAILABLE = 0;
    const IMMEDIATE = [
        self::ALL => 'all',
        self::IMMEDIATE_AVAILABLE => 'immediate_available',
        self::Not_IMMEDIATE_AVAILABLE => 'not_immediate_available',
    ];

    public $fillable = [
        'user_id',
        'unique_id',
        'father_name',
        'mother_name',
        'religion',
        'marital_status_id',
        'nationality',
        'national_id_card',
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
        'career_summary',
        'special_qualification',
        'keywords',
        'has_disability_id',
        'disability_id_number',
        'disability_id_show_on_profile',
        'disability_difficulty_seeing',
        'disability_difficulty_hearing',
        'disability_difficulty_remembering',
        'disability_difficulty_walking',
        'disability_difficulty_communicating',
        'disability_difficulty_self_care',
        'experience',
        'career_level_id',
        'industry_id',
        'functional_area_id',
        'current_salary',
        'expected_salary',
        'salary_currency',
        'address',
        'present_address_type',
        'present_post_office',
        'present_state_division',
        'permanent_same_as_present',
        'permanent_address_type',
        'permanent_country_id',
        'permanent_state_id',
        'permanent_state_division',
        'permanent_city_id',
        'permanent_thana_id',
        'permanent_post_office',
        'permanent_address',
        'immediate_available',
        'available_at',
        'last_change',
        'include_sensitive_personal_data_in_cv',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'unique_id' => 'string',
        'father_name' => 'string',
        'mother_name' => 'string',
        'religion' => 'string',
        'marital_status_id' => 'integer',
        'nationality' => 'string',
        'national_id_card' => 'string',
        'passport_number' => 'string',
        'passport_issue_date' => 'date',
        'secondary_mobile' => 'string',
        'alternate_email' => 'string',
        'emergency_contact' => 'string',
        'blood_group' => 'string',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'objective' => 'string',
        'job_level' => 'string',
        'job_nature' => 'string',
        'preferred_functional_categories' => 'array',
        'preferred_special_skills' => 'array',
        'preferred_job_locations_inside' => 'array',
        'preferred_job_locations_outside' => 'array',
        'preferred_organization_types' => 'array',
        'career_summary' => 'string',
        'special_qualification' => 'string',
        'keywords' => 'string',
        'has_disability_id' => 'boolean',
        'disability_id_number' => 'string',
        'disability_id_show_on_profile' => 'boolean',
        'disability_difficulty_seeing' => 'string',
        'disability_difficulty_hearing' => 'string',
        'disability_difficulty_remembering' => 'string',
        'disability_difficulty_walking' => 'string',
        'disability_difficulty_communicating' => 'string',
        'disability_difficulty_self_care' => 'string',
        'experience' => 'integer',
        'career_level_id' => 'integer',
        'industry_id' => 'integer',
        'functional_area_id' => 'integer',
        'current_salary' => 'double',
        'expected_salary' => 'double',
        'salary_currency' => 'string',
        'address' => 'string',
        'present_address_type' => 'string',
        'present_post_office' => 'string',
        'present_state_division' => 'string',
        'permanent_same_as_present' => 'boolean',
        'permanent_address_type' => 'string',
        'permanent_country_id' => 'integer',
        'permanent_state_id' => 'integer',
        'permanent_state_division' => 'string',
        'permanent_city_id' => 'integer',
        'permanent_thana_id' => 'integer',
        'permanent_post_office' => 'string',
        'permanent_address' => 'string',
        'immediate_available' => 'boolean',
        'available_at' => 'date',
        'last_change' => 'integer',
        'include_sensitive_personal_data_in_cv' => 'boolean',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'first_name' => 'required|max:180',
        'last_name' => 'required|max:180',
        'email' => 'required|email:filter|unique:users,email',
        'password' => 'nullable|same:password_confirmation|min:6',
        'gender' => 'required|integer',
        'dob' => 'nullable|date|before_or_equal:today',
        'current_salary' => 'nullable|numeric|min:0|max:999999999',
        'expected_salary' => 'nullable|numeric|min:0|max:999999999',
        'phone' => 'nullable',
        'marital_status_id' => 'required|integer|exists:marital_status,id',
    ];

    protected $appends = ['country_name', 'state_name', 'city_name', 'thana_name', 'full_location', 'candidate_url'];

    protected $with = ['user'];

    public function getCountryNameAttribute()
    {
        if (! empty($this->user->country)) {
            return $this->user->country->name;
        }
    }

    public function getStateNameAttribute()
    {
        if (! empty($this->user->state)) {
            return $this->user->state->name;
        }
    }

    public function getCityNameAttribute()
    {
        if (! empty($this->user->city)) {
            return $this->user->city->name;
        }
    }

    public function getThanaNameAttribute()
    {
        if (! empty($this->user->thana)) {
            return $this->user->thana->name;
        }
    }

    public function getFullLocationAttribute(): string
    {
        $location = '';
        if (! empty($this->user->country)) {
            $location = $this->user->country->name;
        }
        if (! empty($this->user->state)) {
            $location = $location.','.$this->user->state->name;
        }
        if (! empty($this->user->city)) {
            $location = $location.','.$this->user->city->name;
        }
        if (! empty($this->user->thana)) {
            $location = $location.','.$this->user->thana->name;
        }

        return (! empty($location)) ? $location : '' ;
    }

    /**
     * @return mixed
     */
    public function getCandidateUrlAttribute()
    {
        /** @var Media $media */
        $media = $this->user->getMedia(User::PROFILE)->first();
        if (! empty($media)) {
            return $media->getFullUrl();
        }

        return asset('assets/img/employer-image.png');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'last_change');
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class, 'industry_id');
    }

    public function maritalStatus(): BelongsTo
    {
        return $this->belongsTo(MaritalStatus::class, 'marital_status_id');
    }

    public function careerLevel(): BelongsTo
    {
        return $this->belongsTo(CareerLevel::class, 'career_level_id');
    }

    public function extraCurricular(): HasMany
    {
        return $this->hasMany(CandidateExtraCurricular::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(CandidateLink::class);
    }

    public function references(): HasMany
    {
        return $this->hasMany(CandidateReference::class);
    }

    public function accomplishments(): HasMany
    {
        return $this->hasMany(CandidateAccomplishment::class);
    }

    public function functionalArea(): BelongsTo
    {
        return $this->belongsTo(FunctionalArea::class, 'functional_area_id');
    }

    public function jobAlerts(): BelongsToMany
    {
        return $this->belongsToMany(JobType::class, 'jobs_alerts', 'candidate_id', 'job_type_id');
    }

    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'candidate_id');
    }

    public function penddingJobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'candidate_id')->where('status', JobApplication::STATUS_APPLIED);
    }
}
