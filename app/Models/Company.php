<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class Company
 *
 * @version June 22, 2020, 12:34 pm UTC
 *
 * @property int $id
 * @property string $ceo
 * @property int $no_of_offices
 * @property int $industry_id
 * @property int $ownership_type_id
 * @property int $company_size_id
 * @property int $established_in
 * @property string|null $details
 * @property string $website
 * @property string $unique_id
 * @property string $location
 * @property string $location2
 * @property string|null $fax
 * @property string|null $facebook_url
 * @property string|null $twitter_url
 * @property string|null $linkedin_url
 * @property string|null $google_plus_url
 * @property string|null $pinterest_url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CompanySize $companySize
 * @property-read Industry $industry
 * @property-read OwnerShipType $ownerShipType
 *
 * @method static Builder|Company newModelQuery()
 * @method static Builder|Company newQuery()
 * @method static Builder|Company query()
 * @method static Builder|Company whereCeo($value)
 * @method static Builder|Company whereCompanySizeId($value)
 * @method static Builder|Company whereCreatedAt($value)
 * @method static Builder|Company whereDetails($value)
 * @method static Builder|Company whereEstablishedIn($value)
 * @method static Builder|Company whereFacebookUrl($value)
 * @method static Builder|Company whereFax($value)
 * @method static Builder|Company whereGooglePlusUrl($value)
 * @method static Builder|Company whereId($value)
 * @method static Builder|Company whereIndustryId($value)
 * @method static Builder|Company whereIsFeatured($value)
 * @method static Builder|Company whereLinkedinUrl($value)
 * @method static Builder|Company whereLocation($value)
 * @method static Builder|Company whereLocation2($value)
 * @method static Builder|Company whereNoOfOffices($value)
 * @method static Builder|Company whereOwnershipTypeId($value)
 * @method static Builder|Company wherePinterestUrl($value)
 * @method static Builder|Company whereTwitterUrl($value)
 * @method static Builder|Company whereUpdatedAt($value)
 * @method static Builder|Company whereWebsite($value)
 * @method static Builder|Company whereUniqueId($value)
 *
 * @mixin Eloquent
 *
 * @property-read User|null $user
 * @property int|null $user_id
 * @property-read mixed $company_url
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Job[] $jobs
 * @property-read int|null $jobs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[] $media
 * @property-read int|null $media_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company whereUserId($value)
 *
 * @property-read \App\Models\FeaturedRecord|null $activeFeatured
 * @property-read \App\Models\FeaturedRecord|null $featured
 * @property-read mixed $city_name
 * @property-read mixed $country_name
 * @property-read mixed $state_name
 */
class Company extends Model implements HasMedia
{
    use InteractsWithMedia;

    public $table = 'companies';

    public const COMPANY_LOGIN_TYPE = 0;

    public const JOB_FORM_COMPANIES_CACHE_KEY = 'job_form_active_companies';

    public const ISACTIVE = 1;

    public const DEACTIVE = 0;
    public const ALL = 2;

    public const CREATED_BY_ADMIN = 'admin';
    public const CREATED_BY_EMPLOYER = 'employer';
    public const CREATED_BY_ADMIN_DEMO = 'admin_demo';

    public const CREATED_BY_LABELS = [
        self::CREATED_BY_ADMIN => 'Admin',
        self::CREATED_BY_EMPLOYER => 'Employer',
        self::CREATED_BY_ADMIN_DEMO => 'Admin (Demo)',
    ];

    const BTN_BTN_COLOR = [
        'btn btn-green btn-small-effect',
        'btn btn-purple btn-small btn-effect',
        'btn btn-blue btn-small btn-effect',
        'btn btn-orange btn-small btn-effect',
        'btn btn-red btn-small btn-effect',
        'btn btn-blue-grey btn-small btn-effect',
        'btn btn-green btn-small btn-effect',
    ];

    const IS_FEATURED = [
        self::ALL => 'select_featured_company',
        self::ISACTIVE => 'yes',
        self::DEACTIVE => 'no',
    ];

    const STATUS = [
         self::ALL => 'all',
         self::ISACTIVE => 'active',
         self::DEACTIVE => 'deactive',
    ];

    public $fillable = [
        'ceo',
        'company_name',
        'company_name_bn',
        'contact_person_name',
        'contact_person_designation',
        'company_summary',
        'company_summary_bn',
        'trade_license_no',
        'rl_no',
        'employee_range',
        'industry_ids',
        'industry_id',
        'ownership_type_id',
        'company_size_id',
        'established_in',
        'details',
        'website',
        'location',
        'location2',
        'company_address_bn',
        'billing_address',
        'billing_phone',
        'billing_region_code',
        'billing_email',
        'has_disability_facilities',
        'disability_inclusion_policy',
        'disability_inclusion_support',
        'disability_inclusion_training',
        'disability_facilities',
        'no_of_offices',
        'fax',
        'user_id',
        'unique_id',
        'last_change',
        'created_by',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ceo' => 'string',
        'company_name' => 'string',
        'company_name_bn' => 'string',
        'contact_person_name' => 'string',
        'contact_person_designation' => 'string',
        'company_summary' => 'string',
        'company_summary_bn' => 'string',
        'trade_license_no' => 'string',
        'rl_no' => 'string',
        'employee_range' => 'string',
        'industry_ids' => 'array',
        'industry_id' => 'integer',
        'ownership_type_id' => 'integer',
        'company_size_id' => 'integer',
        'established_in' => 'integer',
        'details' => 'string',
        'website' => 'string',
        'location' => 'string',
        'location2' => 'string',
        'company_address_bn' => 'string',
        'billing_address' => 'string',
        'billing_phone' => 'string',
        'billing_region_code' => 'string',
        'billing_email' => 'string',
        'has_disability_facilities' => 'boolean',
        'disability_inclusion_policy' => 'boolean',
        'disability_inclusion_support' => 'boolean',
        'disability_inclusion_training' => 'boolean',
        'disability_facilities' => 'array',
        'no_of_offices' => 'integer',
        'fax' => 'string',
        'user_id' => 'integer',
        'unique_id' => 'string',
        'last_change' => 'integer',
        'created_by' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ceo' => 'required|max:180',
        'industry_id' => 'required',
        'ownership_type_id' => 'required',
        'company_size_id' => 'required',
        'established_in' => 'required',
        'website' => 'nullable|url',
        'location' => 'required',
        'no_of_offices' => 'required|numeric|min:1|max:1000',
    ];

    /**
     * @var array
     */
    protected $appends = ['company_url', 'created_by_label'];

    protected $with = ['user'];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::JOB_FORM_COMPANIES_CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::JOB_FORM_COMPANIES_CACHE_KEY));
    }
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

    /**
     * @return mixed
     */
    public function getCompanyUrlAttribute()
    {
        if (! $this->user) {
            return asset('assets/img/employer-image.png');
        }

        /** @var Media $media */
        $media = $this->user->getMedia(User::PROFILE)->first();
        if (! empty($media)) {
            return $media->getFullUrl();
        }

        return asset('assets/img/employer-image.png');
    }

    public function getCreatedByLabelAttribute(): string
    {
        return self::CREATED_BY_LABELS[$this->created_by] ?? 'Admin';
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class, 'industry_id');
    }

    public function ownerShipType(): BelongsTo
    {
        return $this->belongsTo(OwnerShipType::class, 'ownership_type_id');
    }

    public function admin(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'last_change');
    }

    public function companySize(): BelongsTo
    {
        return $this->belongsTo(CompanySize::class, 'company_size_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'company_id');
    }

    public function featured(): MorphOne
    {
        return $this->morphOne(FeaturedRecord::class, 'owner');
    }

    public function activeFeatured(): MorphOne
    {
        return $this->morphOne(FeaturedRecord::class, 'owner')->where('end_time', '>', \Carbon\Carbon::now());
    }
}
