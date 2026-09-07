<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GovernmentJob extends Model
{
    protected static function booted(): void
    {
        static::saving(function (GovernmentJob $governmentJob) {
            if (! $governmentJob->slug || $governmentJob->isDirty('title')) {
                $baseSlug = Str::slug($governmentJob->title) ?: 'government-job';
                $slug = $baseSlug;
                $suffix = 2;

                while (static::where('slug', $slug)
                    ->when($governmentJob->exists, fn ($query) => $query->whereKeyNot($governmentJob->getKey()))
                    ->exists()) {
                    $slug = $baseSlug.'-'.$suffix;
                    $suffix++;
                }

                $governmentJob->slug = $slug;
            }
        });
    }
    protected $fillable = [
        'title',
        'slug',
        'organization_name',
        'source_name',
        'published_at',
        'application_deadline',
        'circular_path',
        'circular_mime_type',
        'application_url',
        'is_published',
    ];

    protected $casts = [
        'published_at' => 'date',
        'application_deadline' => 'date',
        'is_published' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
    public function getCircularUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->circular_path);
    }

    public function getIsPdfAttribute(): bool
    {
        return $this->circular_mime_type === 'application/pdf';
    }
}
