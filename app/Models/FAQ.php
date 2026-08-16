<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\FAQ
 *
 * @property int $id
 * @property int|null $faq_category_id
 * @property string $title
 * @property string $description
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FAQ newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FAQ newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FAQ query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FAQ whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FAQ whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FAQ whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FAQ whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FAQ whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class FAQ extends Model
{
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'faq_category_id' => 'nullable|integer',
        'title_en' => 'required|max:150',
        'title_bn' => 'required|max:150',
        'description_en' => 'required',
        'description_bn' => 'required',
    ];

    public $table = 'faqs';

    /**
     * @var string[]
     */
    public $fillable = [
        'faq_category_id',
        'title',
        'title_en',
        'title_bn',
        'description',
        'description_en',
        'description_bn',
        'sort_order',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'faq_category_id' => 'integer',
        'title' => 'string',
        'title_en' => 'string',
        'title_bn' => 'string',
        'description' => 'string',
        'description_en' => 'string',
        'description_bn' => 'string',
        'sort_order' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(FAQCategory::class, 'faq_category_id');
    }

    public function localizedTitle(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        return $locale === 'bn'
            ? ($this->title_bn ?: $this->title_en ?: $this->title)
            : ($this->title_en ?: $this->title ?: $this->title_bn);
    }

    public function localizedDescription(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        return $locale === 'bn'
            ? ($this->description_bn ?: $this->description_en ?: $this->description)
            : ($this->description_en ?: $this->description ?: $this->description_bn);
    }
}
