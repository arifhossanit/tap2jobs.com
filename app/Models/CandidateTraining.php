<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateTraining extends Model
{
    public static $rules = [
        'title' => 'required|max:150',
        'country' => 'required|max:100',
        'topics' => 'nullable|max:1000',
        'year' => 'required|integer|min:1900|max:2100',
        'institute' => 'required|max:150',
        'duration' => 'required|max:100',
        'location' => 'nullable|max:150',
    ];

    public $table = 'candidate_trainings';

    public $fillable = [
        'candidate_id',
        'title',
        'country',
        'topics',
        'year',
        'institute',
        'duration',
        'location',
        'sort_order',
    ];

    protected $casts = [
        'candidate_id' => 'integer',
        'title' => 'string',
        'country' => 'string',
        'topics' => 'string',
        'year' => 'integer',
        'institute' => 'string',
        'duration' => 'string',
        'location' => 'string',
        'sort_order' => 'integer',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
}
