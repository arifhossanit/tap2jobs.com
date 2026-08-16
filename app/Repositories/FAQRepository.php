<?php

namespace App\Repositories;

use App\Models\FAQ;

/**
 * Class FAQRepository
 */
class FAQRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'faq_category_id',
        'title',
        'title_en',
        'title_bn',
    ];

    /**
     * Return searchable fields
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return FAQ::class;
    }
}
