<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateRetiredArmyEmployment extends Model
{
    public $table = 'candidate_retired_army_employments';

    public static $rules = [
        'ba_no_prefix' => 'nullable',
        'ba_no' => 'required|max:50',
        'rank' => 'required|max:100',
        'type' => 'required|max:100',
        'arms' => 'required|max:100',
        'trade' => 'nullable|max:150',
        'course' => 'nullable|max:150',
        'date_of_commission' => 'required|date',
        'date_of_retirement' => 'required|date|after_or_equal:date_of_commission',
    ];

    public $fillable = [
        'candidate_id',
        'ba_no_prefix',
        'ba_no',
        'rank',
        'type',
        'arms',
        'trade',
        'course',
        'date_of_commission',
        'date_of_retirement',
    ];

    protected $casts = [
        'candidate_id' => 'integer',
        'ba_no_prefix' => 'string',
        'ba_no' => 'string',
        'rank' => 'string',
        'type' => 'string',
        'arms' => 'string',
        'trade' => 'string',
        'course' => 'string',
        'date_of_commission' => 'date',
        'date_of_retirement' => 'date',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
}
