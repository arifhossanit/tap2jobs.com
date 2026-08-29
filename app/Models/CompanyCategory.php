<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyCategory extends Model
{
    public $table = 'company_categories';

    public $fillable = [
        'name',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function companySizes(): HasMany
    {
        return $this->hasMany(CompanySize::class, 'company_category_id');
    }
}
