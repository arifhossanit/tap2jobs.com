<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Ad
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $link_url
 * @property string|null $cta_text
 * @property string $position
 * @property int $is_active
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $ad_image_url
 * @property-read \Illuminate\Database\Eloquent\Collection|Media[] $media
 * @property-read int|null $media_count
 *
 * @mixin \Eloquent
 */
class Ad extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const PATH = 'ads';

    public const OPTIMIZED_CONVERSION = 'optimized';

    public const POSITION_HEADER = 'header';

    public const POSITION_REGISTER_LEFT = 'register_left';

    public const POSITION_REGISTER_RIGHT = 'register_right';

    public const POSITIONS = [
        self::POSITION_HEADER => 'header',
        self::POSITION_REGISTER_LEFT => 'register_left',
        self::POSITION_REGISTER_RIGHT => 'register_right',
    ];

    public const ALL = 2;

    public const ACTIVE = 1;

    public const DEACTIVE = 0;

    public const STATUS = [
        self::ALL => 'select_status',
        self::ACTIVE => 'active',
        self::DEACTIVE => 'deactive',
    ];

    public $table = 'ads';

    public $fillable = [
        'title',
        'description',
        'link_url',
        'cta_text',
        'position',
        'is_active',
        'sort_order',
    ];

    public static $rules = [
        'title' => 'nullable|max:150',
        'description' => 'nullable|max:500',
        'link_url' => 'nullable|url|max:255',
        'cta_text' => 'nullable|max:50',
        'position' => 'required|in:header,register_left,register_right',
        'sort_order' => 'nullable|integer|min:0',
        'ad_image' => 'nullable|mimes:jpeg,jpg,png,webp,mp4,webm,ogg|max:51200',
    ];

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'link_url' => 'string',
        'cta_text' => 'string',
        'position' => 'string',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = ['ad_image_url', 'ad_media_url', 'ad_media_type'];

    public function getAdImageUrlAttribute()
    {
        return $this->getAdMediaUrlAttribute();
    }

    public function getAdMediaUrlAttribute()
    {
        $media = $this->getFirstMedia(self::PATH);
        if (! empty($media)) {
            $url = $this->isImageMedia($media)
                ? $media->getAvailableFullUrl([self::OPTIMIZED_CONVERSION])
                : $media->getFullUrl();

            $url = str_replace('\\', '/', $url);

            // Collapse accidental double slashes from APP_URL trailing slash + disk url.
            return preg_replace('#([^:])/{2,}#', '$1/', $url);
        }

        return null;
    }

    public function getAdMediaTypeAttribute(): ?string
    {
        $media = $this->getFirstMedia(self::PATH);

        if (empty($media)) {
            return null;
        }

        return $this->isVideoMedia($media) ? 'video' : 'image';
    }

    public function registerMediaConversions(Media $media = null): void
    {
        if ($media && ! $this->isImageMedia($media)) {
            return;
        }

        $this->addMediaConversion(self::OPTIMIZED_CONVERSION)
            ->performOnCollections(self::PATH)
            ->width(900)
            ->quality(82)
            ->format(Manipulations::FORMAT_WEBP)
            ->nonQueued();
    }

    private function isImageMedia(Media $media): bool
    {
        return str_starts_with((string) $media->mime_type, 'image/');
    }

    private function isVideoMedia(Media $media): bool
    {
        return str_starts_with((string) $media->mime_type, 'video/');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForPosition(Builder $query, string $position): Builder
    {
        return $query->where('position', $position);
    }

    public function getPositionLabelAttribute(): string
    {
        return __('messages.ad.positions.'.$this->position);
    }
}
