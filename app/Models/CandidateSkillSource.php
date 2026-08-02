<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateSkillSource extends Model
{
    public $table = 'candidate_skill_sources';

    public $fillable = [
        'candidate_skill_id',
        'source',
    ];

    protected $casts = [
        'id' => 'integer',
        'candidate_skill_id' => 'integer',
        'source' => 'string',
    ];

    public function candidateSkill(): BelongsTo
    {
        return $this->belongsTo(CandidateSkill::class);
    }
}
