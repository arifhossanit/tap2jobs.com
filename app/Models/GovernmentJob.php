<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GovernmentJob extends Model
{
    protected $fillable = [
        'title',
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

    public function getCircularUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->circular_path);
    }

    public function getIsPdfAttribute(): bool
    {
        return $this->circular_mime_type === 'application/pdf';
    }
}
