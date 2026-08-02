<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CandidateSkill extends Model
{
    public $table = 'candidate_skills';

    public $fillable = [
        'user_id',
        'skill_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'skill_id' => 'integer',
    ];

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    public function sources(): HasMany
    {
        return $this->hasMany(CandidateSkillSource::class);
    }
}
