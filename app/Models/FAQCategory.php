<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FAQCategory extends Model
{
    public $table = 'faq_categories';

    public $fillable = [
        'name',
        'name_en',
        'name_bn',
        'slug',
        'audience',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'name_en' => 'string',
        'name_bn' => 'string',
        'slug' => 'string',
        'audience' => 'string',
        'icon' => 'string',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function faqs(): HasMany
    {
        return $this->hasMany(FAQ::class, 'faq_category_id');
    }

    public function localizedName(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        return $locale === 'bn'
            ? ($this->name_bn ?: $this->name_en ?: $this->name)
            : ($this->name_en ?: $this->name ?: $this->name_bn);
    }
}
