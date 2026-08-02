<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\CandidateEducation
 *
 * @property int $id
 * @property int $candidate_id
 * @property int $degree_level_id
 * @property string $country
 * @property string|null $state
 * @property string|null $city
 * @property string $institute
 * @property string $result
 * @property int $year
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Candidate $candidate
 *
 * @method static Builder|CandidateEducation newModelQuery()
 * @method static Builder|CandidateEducation newQuery()
 * @method static Builder|CandidateEducation query()
 * @method static Builder|CandidateEducation whereCandidateId($value)
 * @method static Builder|CandidateEducation whereCity($value)
 * @method static Builder|CandidateEducation whereCountry($value)
 * @method static Builder|CandidateEducation whereCreatedAt($value)
 * @method static Builder|CandidateEducation whereDegreeLevelId($value)
 * @method static Builder|CandidateEducation whereId($value)
 * @method static Builder|CandidateEducation whereInstitute($value)
 * @method static Builder|CandidateEducation whereResult($value)
 * @method static Builder|CandidateEducation whereState($value)
 * @method static Builder|CandidateEducation whereUpdatedAt($value)
 * @method static Builder|CandidateEducation whereYear($value)
 *
 * @mixin Eloquent
 *
 * @property string $degree_title
 * @property-read \App\Models\RequiredDegreeLevel $degreeLevel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CandidateEducation whereDegreeTitle($value)
 *
 * @property int|null $country_id
 * @property int|null $state_id
 * @property int|null $city_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CandidateEducation whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CandidateEducation whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CandidateEducation whereStateId($value)
 */
class CandidateEducation extends Model
{
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'degree_level_id' => 'required|exists:required_degree_levels,id',
        'degree_title' => 'required|max:150',
        'country_id' => 'required|exists:countries,id',
        'state_id' => 'nullable|exists:states,id',
        'city_id' => 'nullable|exists:cities,id',
        'major' => 'nullable|max:150',
        'board' => 'nullable|max:100',
        'institute' => 'required|max:150',
        'foreign_institute' => 'nullable|boolean',
        'foreign_university_country' => 'required_if:foreign_institute,1|nullable|max:120',
        'show_summary' => 'nullable|boolean',
        'result' => 'nullable|max:150',
        'marks_percentage' => 'nullable|numeric|min:0|max:100',
        'cgpa' => 'required_if:result,Grade|nullable|numeric|min:0|max:100',
        'scale' => 'required_if:result,Grade|nullable|integer|min:1|max:100',
        'year' => 'nullable|integer|min:1900|max:2100',
        'duration' => 'nullable|max:100',
        'achievement' => 'nullable|max:3000',
    ];

    public $table = 'candidate_educations';

    public $fillable = [
        'candidate_id',
        'degree_level_id',
        'degree_title',
        'major',
        'board',
        'country_id',
        'state_id',
        'city_id',
        'institute',
        'foreign_institute',
        'foreign_university_country',
        'show_summary',
        'result',
        'marks_percentage',
        'cgpa',
        'scale',
        'year',
        'duration',
        'achievement',
        'sort_order',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'candidate_id' => 'integer',
        'degree_level_id' => 'integer',
        'degree_title' => 'string',
        'major' => 'string',
        'board' => 'string',
        'country_id' => 'integer',
        'state_id' => 'integer',
        'city_id' => 'integer',
        'institute' => 'string',
        'foreign_institute' => 'boolean',
        'foreign_university_country' => 'string',
        'show_summary' => 'boolean',
        'result' => 'string',
        'marks_percentage' => 'decimal:2',
        'cgpa' => 'decimal:2',
        'scale' => 'integer',
        'year' => 'integer',
        'duration' => 'string',
        'achievement' => 'string',
        'sort_order' => 'integer',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    public function degreeLevel(): BelongsTo
    {
        return $this->belongsTo(RequiredDegreeLevel::class, 'degree_level_id');
    }
}
