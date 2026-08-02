<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationBoard extends Model
{
    public $table = 'education_boards';

    public $fillable = [
        'name',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'name' => 'string',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];
}
