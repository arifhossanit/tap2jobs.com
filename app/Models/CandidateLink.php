<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateLink extends Model
{
    public $table = 'candidate_links';

    public $fillable = [
        'candidate_id',
        'platform',
        'url',
        'sort_order',
    ];

    protected $casts = [
        'id' => 'integer',
        'candidate_id' => 'integer',
        'platform' => 'string',
        'url' => 'string',
        'sort_order' => 'integer',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
