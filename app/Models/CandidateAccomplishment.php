<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateAccomplishment extends Model
{
    public const TYPE_PORTFOLIO = 'portfolio';
    public const TYPE_PUBLICATION = 'publication';
    public const TYPE_AWARD = 'award';
    public const TYPE_PROJECT = 'project';
    public const TYPE_OTHER = 'other';

    public $table = 'candidate_accomplishments';

    public $fillable = [
        'candidate_id',
        'type',
        'title',
        'issued_on',
        'url',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'id' => 'integer',
        'candidate_id' => 'integer',
        'type' => 'string',
        'title' => 'string',
        'issued_on' => 'date',
        'url' => 'string',
        'description' => 'string',
        'sort_order' => 'integer',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
