<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thana extends Model
{
    protected $table = 'thanas';

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
        'name' => 'required|max:180',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'thana_id');
    }
}
