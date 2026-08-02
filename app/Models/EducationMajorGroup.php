<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationMajorGroup extends Model
{
    public $table = 'education_major_groups';

    public $fillable = [
        'required_degree_level_id',
        'name',
        'is_custom',
        'created_by',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'required_degree_level_id' => 'integer',
        'name' => 'string',
        'is_custom' => 'boolean',
        'created_by' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function degreeLevel(): BelongsTo
    {
        return $this->belongsTo(RequiredDegreeLevel::class, 'required_degree_level_id');
    }
}
