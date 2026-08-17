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

    public const PAGE_ALL = 'all';
    public const PAGE_CANDIDATE_REGISTER = 'candidate_register';
    public const PAGE_EMPLOYER_REGISTER = 'employer_register';
    public const PAGE_CANDIDATE_LOGIN = 'candidate_login';
    public const PAGE_EMPLOYER_LOGIN = 'employer_login';
    public const PAGE_HOME = 'home';
    public const PAGE_BLOG = 'blog';
    public const PAGE_BLOG_DETAILS = 'blog_details';
    public const PAGE_JOBS = 'jobs';
    public const PAGE_JOB_DETAILS = 'job_details';

    public const PAGES = [
        self::PAGE_ALL => 'all',
        self::PAGE_CANDIDATE_REGISTER => 'candidate_register',
        self::PAGE_EMPLOYER_REGISTER => 'employer_register',
        self::PAGE_CANDIDATE_LOGIN => 'candidate_login',
        self::PAGE_EMPLOYER_LOGIN => 'employer_login',
        self::PAGE_HOME => 'home',
        self::PAGE_BLOG => 'blog',
        self::PAGE_BLOG_DETAILS => 'blog_details',
        self::PAGE_JOBS => 'jobs',
        self::PAGE_JOB_DETAILS => 'job_details',
    ];

    public const POSITIONS = [
        self::POSITION_HEADER => 'header',
        self::POSITION_REGISTER_LEFT => 'register_left',
        self::POSITION_REGISTER_RIGHT => 'register_right',
    ];

    public const ALL = 2;

    public const ACTIVE = 1;

    public const DEACTIVE = 0;

    public const MEDIA_STATUS_READY = 'ready';

    public const MEDIA_STATUS_PROCESSING = 'processing';

    public const MEDIA_STATUS_FAILED = 'failed';

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
        'page',
        'is_active',
        'sort_order',
        'media_processing_status',
        'media_processing_error',
        'media_processed_at',
    ];

    public static $rules = [
        'title' => 'nullable|max:150',
        'description' => 'nullable|max:500',
        'link_url' => 'nullable|url|max:255',
        'cta_text' => 'nullable|max:50',
        'position' => 'required|in:header,register_left,register_right',
        'page' => 'nullable|array',
        'page.*' => 'in:all,candidate_register,employer_register,candidate_login,employer_login,home,blog,blog_details,jobs,job_details',
        'sort_order' => 'nullable|integer|min:0',
        'ad_image' => 'nullable|file|mimes:jpeg,jpg,png,webp,mp4,webm,ogg|max:51200',
    ];

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'link_url' => 'string',
        'cta_text' => 'string',
        'position' => 'string',
        'page' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'media_processed_at' => 'datetime',
    ];

    protected $appends = ['ad_image_url', 'ad_media_url', 'ad_media_type', 'ad_media_ready', 'page_array'];

    public function getPageArrayAttribute(): array
    {
        $page = $this->page;
        if (is_array($page)) {
            return $page;
        }
        if (is_string($page) && ! empty($page)) {
            $decoded = json_decode($page, true);
            if (is_array($decoded)) {
                return $decoded;
            }
            return [$page];
        }

        return [self::PAGE_ALL];
    }

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

    public function getAdMediaReadyAttribute(): bool
    {
        return $this->media_processing_status !== self::MEDIA_STATUS_PROCESSING
            && $this->media_processing_status !== self::MEDIA_STATUS_FAILED;
    }

    public function isVideoAd(): bool
    {
        $media = $this->getFirstMedia(self::PATH);

        return ! empty($media) && $this->isVideoMedia($media);
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

    public function scopeTargetPage(Builder $query, ?string $page): Builder
    {
        if (empty($page)) {
            return $query;
        }

        return $query->where(function ($q) use ($page) {
            $q->whereNull('page')
                ->orWhere('page', self::PAGE_ALL)
                ->orWhere('page', $page)
                ->orWhere('page', 'LIKE', '%"'.self::PAGE_ALL.'"%')
                ->orWhere('page', 'LIKE', '%"'.$page.'"%')
                ->orWhere('page', 'LIKE', '%'.self::PAGE_ALL.'%')
                ->orWhere('page', 'LIKE', '%'.$page.'%');
        });
    }

    public function scopeMediaReady(Builder $query): Builder
    {
        return $query->where('media_processing_status', self::MEDIA_STATUS_READY);
    }

    public function getPositionLabelAttribute(): string
    {
        return __('messages.ad.positions.'.$this->position);
    }

    public function getPageLabelAttribute(): string
    {
        $pages = $this->page_array;
        $allPages = array_values(array_diff(array_keys(self::PAGES), ['all']));
        $hasAll = in_array(self::PAGE_ALL, $pages, true) || count(array_intersect($pages, $allPages)) === count($allPages);

        if ($hasAll) {
            return __('messages.ad.pages.all');
        }

        $labels = array_map(function ($p) {
            return __('messages.ad.pages.'.$p);
        }, $pages);

        return implode(', ', $labels);
    }
}
