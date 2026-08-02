<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateExtraCurricular extends Model
{
    public $table = 'candidate_extra_curriculars';

    public $fillable = [
        'candidate_id',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
        'candidate_id' => 'integer',
        'description' => 'string',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
