<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateReference extends Model
{
    public $table = 'candidate_references';

    public $fillable = [
        'candidate_id',
        'name',
        'designation',
        'organization',
        'email',
        'relation',
        'mobile',
        'office_phone',
        'residential_phone',
        'address',
        'sort_order',
    ];

    protected $casts = [
        'id' => 'integer',
        'candidate_id' => 'integer',
        'name' => 'string',
        'designation' => 'string',
        'organization' => 'string',
        'email' => 'string',
        'relation' => 'string',
        'mobile' => 'string',
        'office_phone' => 'string',
        'residential_phone' => 'string',
        'address' => 'string',
        'sort_order' => 'integer',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
