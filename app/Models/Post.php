<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Post
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $postAssignCategories
 * @property-read int|null $post_assign_category_count
 * @property-read mixed $post_image_url
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[] $media
 * @property-read int|null $media_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Post whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * @property-read \App\Models\User $user
 * @property-read mixed $blog_image_url
 * @property-read int|null $post_assign_categories_count
 */
class Post extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const PATH = 'posts';

    public $table = 'posts';

    /**
     * @var array
     */
    protected $appends = ['blog_image_url'];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required|max:180',
        'description' => 'required',
        'image' => 'nullable|mimes:png,jpg,jepg',
        'slug' => 'nullable|string|max:180|unique:posts,slug',
        'meta_title' => 'nullable|string|max:180',
        'meta_description' => 'nullable|string|max:255',
    ];

    /**
     * @var string[]
     */
    public $fillable = [
        'title',
        'slug',
        'meta_title',
        'meta_description',
        'description',
        'created_by',
        'is_default',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'slug' => 'string',
        'meta_title' => 'string',
        'meta_description' => 'string',
        'description' => 'string',
        'created_by' => 'integer',
        'is_default' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            if (! filled($post->slug)) {
                $post->slug = $post->makeUniqueSlug($post->title);
            } else {
                $post->slug = $post->makeUniqueSlug($post->slug);
            }
        });
    }

    private function makeUniqueSlug(string $value): string
    {
        $base = Str::slug(html_entity_decode(strip_tags($value))) ?: 'blog';
        $slug = $base;
        $suffix = 2;
        while (static::where('slug', $slug)->when($this->exists, fn ($query) => $query->whereKeyNot($this->getKey()))->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    /**
     * @return mixed
     */
    public function getBlogImageUrlAttribute()
    {
        /** @var Media $media */
        $media = $this->media->first();
        if (! empty($media)) {
            return $media->getFullUrl();
        }

        return asset('front_web/images/blog-1.png');
    }

    public function postAssignCategories(): BelongsToMany
    {
        return $this->belongsToMany(PostCategory::class, 'post_assigned_categories', 'post_id', 'post_categories_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class, 'post_id');
    }
}
