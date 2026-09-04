<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobLocation extends Model
{
    protected $fillable = [
        'job_id',
        'country_id',
        'state_id',
        'city_id',
        'thana_id',
        'city_village_name',
        'address',
        'is_primary',
    ];

    protected $casts = [
        'job_id' => 'integer',
        'country_id' => 'integer',
        'state_id' => 'integer',
        'city_id' => 'integer',
        'thana_id' => 'integer',
        'is_primary' => 'boolean',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function thana(): BelongsTo
    {
        return $this->belongsTo(Thana::class, 'thana_id');
    }

    public function getFullLocationAttribute(): string
    {
        return collect([
            $this->thana?->name,
            $this->city_village_name,
            $this->city?->name,
            $this->state?->name,
            $this->country?->name,
        ])->filter()->implode(', ');
    }

    public function getDistrictThanaLocationAttribute(): string
    {
        $district = $this->city?->name;
        $thana = $this->thana?->name;

        if ($district && $thana) {
            return $district.' ('.$thana.')';
        }

        return $district ?: $this->full_location;
    }
}