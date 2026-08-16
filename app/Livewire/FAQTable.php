<?php

namespace App\Livewire;

use App\Models\FAQ;
use App\Models\FAQCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Rappasoft\LaravelLivewireTables\Views\Column;

class FAQTable extends LivewireTableComponent
{
    protected $model = FAQ::class;
    protected string $tableName = 'faqs';

    public $showButtonOnHeader = true;
    public $showFilterOnHeader = true;
    public $buttonComponent = 'faqs.table-components.add_button';

    public $category_id = '';
    public $audience = '';

    protected $listeners = [
        'resetPage',
        'refreshDatatable' => '$refresh',
        'changeCategoryFilter',
        'changeAudienceFilter',
    ];

    public array $filterComponents = [];

    public function mount(): void
    {
        $faqCategories = Schema::hasTable('faq_categories')
            ? FAQCategory::orderBy('audience')->orderBy('sort_order')->orderBy('name')
                ->get()
                ->mapWithKeys(function ($cat) {
                    $audienceLabel = ucfirst($cat->audience ?? 'Candidate');
                    return [$cat->id => "{$cat->name} ({$audienceLabel})"];
                })
                ->toArray()
            : [];

        $this->filterComponents = [
            'faqs.table-components.filter',
            $faqCategories,
        ];
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setDefaultSort('created_at', 'desc');

        $this->setTableAttributes([
            'default' => false,
            'class' => 'table table-striped',
        ]);

        $this->setThAttributes(function (Column $column) {
            return [
                'class' => 'text-center',
            ];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex == '3') {
                return [
                    'class' => 'text-center',
                    'width' => '15%',
                ];
            }

            return [];
        });

        $this->setQueryStringStatus(false);
        $this->setFilterPillsStatus(false);
    }

    public function placeholder()
    {
        return view('livewire_lazy_load/listing-skeleton-filter');
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.faq.title'), 'title')
                ->sortable()
                ->searchable()
                ->view('faqs.table-components.title'),
            Column::make('Category', 'category.name')
                ->sortable(function (Builder $query, string $direction) {
                    return $query->orderBy(
                        FAQCategory::select('name')->whereColumn('faq_categories.id', 'faqs.faq_category_id'),
                        $direction
                    );
                })
                ->searchable(function (Builder $query, string $searchTerm) {
                    return $query->whereHas('category', function (Builder $q) use ($searchTerm) {
                        $q->where('name', 'like', "%{$searchTerm}%");
                    });
                })
                ->view('faqs.table-components.category'),
            Column::make(__('messages.common.created_date'), 'created_at')
                ->sortable()
                ->searchable()
                ->view('faqs.table-components.created_at'),
            Column::make(__('messages.common.action'), 'id')
                ->view('faqs.table-components.action_button'),
        ];
    }

    public function builder(): Builder
    {
        $query = FAQ::with('category');

        if (!empty($this->category_id)) {
            $query->where('faq_category_id', $this->category_id);
        }

        if (!empty($this->audience)) {
            $query->whereHas('category', function (Builder $q) {
                $q->where('audience', $this->audience);
            });
        }

        return $query->select('faqs.*');
    }

    public function changeCategoryFilter($categoryId)
    {
        $this->category_id = $categoryId;
        $this->setBuilder($this->builder());
        $this->resetPagination();
    }

    public function changeAudienceFilter($audience)
    {
        $this->audience = $audience;
        $this->setBuilder($this->builder());
        $this->resetPagination();
    }

    public function resetPagination()
    {
        $this->resetPage('faqsPage');
    }
}
