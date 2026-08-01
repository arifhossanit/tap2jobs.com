<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateExperienceExpertise extends Model
{
    public $table = 'candidate_experience_expertises';

    public $fillable = [
        'candidate_experience_id',
        'name',
        'duration_months',
        'sort_order',
    ];

    protected $casts = [
        'candidate_experience_id' => 'integer',
        'name' => 'string',
        'duration_months' => 'integer',
        'sort_order' => 'integer',
    ];

    public function experience(): BelongsTo
    {
        return $this->belongsTo(CandidateExperience::class, 'candidate_experience_id');
    }
}
