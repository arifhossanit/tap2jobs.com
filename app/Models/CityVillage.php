<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CityVillage extends Model
{
    protected $table = 'city_villages';

    const CITY = '';

    protected $fillable = [
        'city_id',
        'name',
    ];

    protected $casts = [
        'id' => 'integer',
        'city_id' => 'integer',
        'name' => 'string',
    ];

    public static $rules = [
        'city_id' => 'required|exists:cities,id',
        'name' => 'required|max:720',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function thanas(): HasMany
    {
        return $this->hasMany(Thana::class, 'city_village_id');
    }
}
