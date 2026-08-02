<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationDegreeTitle extends Model
{
    public $table = 'education_degree_titles';

    public $fillable = [
        'required_degree_level_id',
        'name',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'required_degree_level_id' => 'integer',
        'name' => 'string',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function degreeLevel(): BelongsTo
    {
        return $this->belongsTo(RequiredDegreeLevel::class, 'required_degree_level_id');
    }
}
