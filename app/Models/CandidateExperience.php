<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\CandidateExperience
 *
 * @property int $id
 * @property int $candidate_id
 * @property string $experience_title
 * @property string $company
 * @property string $country
 * @property string|null $state
 * @property string|null $city
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property bool $currently_working
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|CandidateExperience newModelQuery()
 * @method static Builder|CandidateExperience newQuery()
 * @method static Builder|CandidateExperience query()
 * @method static Builder|CandidateExperience whereCandidateId($value)
 * @method static Builder|CandidateExperience whereCity($value)
 * @method static Builder|CandidateExperience whereCompany($value)
 * @method static Builder|CandidateExperience whereCountry($value)
 * @method static Builder|CandidateExperience whereCreatedAt($value)
 * @method static Builder|CandidateExperience whereCurrentlyWorking($value)
 * @method static Builder|CandidateExperience whereDescription($value)
 * @method static Builder|CandidateExperience whereEndDate($value)
 * @method static Builder|CandidateExperience whereExperienceTitle($value)
 * @method static Builder|CandidateExperience whereId($value)
 * @method static Builder|CandidateExperience whereStartDate($value)
 * @method static Builder|CandidateExperience whereState($value)
 * @method static Builder|CandidateExperience whereUpdatedAt($value)
 *
 * @mixin Eloquent
 *
 * @property-read \App\Models\Candidate $candidate
 * @property int|null $country_id
 * @property int|null $state_id
 * @property int|null $city_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CandidateExperience whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CandidateExperience whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CandidateExperience whereStateId($value)
 */
class CandidateExperience extends Model
{
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'experience_title' => 'required|max:150',
        'company' => 'required|max:150',
        'company_business' => 'required|max:150',
        'department' => 'nullable|max:150',
        'country_id' => 'required|exists:countries,id',
        'state_id' => 'nullable|exists:states,id',
        'city_id' => 'nullable|exists:cities,id',
        'start_date' => 'required|date',
        'end_date' => 'required_unless:currently_working,1|nullable|date|after_or_equal:start_date',
        'currently_working' => 'nullable|boolean',
        'description' => 'nullable|max:3000',
        'company_location' => 'nullable|max:150',
        'area_of_expertise' => 'required|array|max:10',
        'area_of_expertise.0' => 'required|max:150',
        'area_of_expertise.*' => 'nullable|max:150',
        'expertise_duration' => 'nullable|array|max:10',
        'expertise_duration.*' => 'nullable|integer|min:0',
    ];

    public $table = 'candidate_experiences';

    public $fillable = [
        'candidate_id',
        'experience_title',
        'department',
        'company',
        'company_business',
        'country_id',
        'state_id',
        'city_id',
        'start_date',
        'end_date',
        'currently_working',
        'description',
        'company_location',
        'sort_order',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'candidate_id' => 'integer',
        'experience_title' => 'string',
        'department' => 'string',
        'company' => 'string',
        'company_business' => 'string',
        'country_id' => 'integer',
        'state_id' => 'integer',
        'city_id' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'currently_working' => 'boolean',
        'description' => 'string',
        'company_location' => 'string',
        'sort_order' => 'integer',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    public function expertises(): HasMany
    {
        return $this->hasMany(CandidateExperienceExpertise::class, 'candidate_experience_id')
            ->orderBy('sort_order');
    }
}
