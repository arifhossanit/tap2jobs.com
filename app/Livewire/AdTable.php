<?php

namespace App\Livewire;

use App\Models\Ad;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AdTable extends LivewireTableComponent
{
    protected $model = Ad::class;

    protected string $tableName = 'ads';

    public $status = Ad::ALL;

    protected $listeners = ['resetPage', 'refreshDatatable' => '$refresh', 'changeStatusFilter'];

    public $showButtonOnHeader = true;

    public $showFilterOnHeader = true;

    public $buttonComponent = 'ads.table_components.add_button';

    public array $filterComponents = ['ads.table_components.filter', Ad::STATUS];

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setDefaultSort('sort_order', 'asc');

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('is_active') || $column->isField('id')) {
                return [
                    'class' => 'text-center',
                ];
            }

            return [];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if (in_array($columnIndex, [3, 4])) {
                return [
                    'class' => 'text-center',
                ];
            }

            return [];
        });

        $this->setTableAttributes([
            'default' => false,
            'class' => 'table table-striped',
        ]);

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
            Column::make(__('messages.ad.image'), 'created_at')
                ->view('ads.table_components.image'),

            Column::make(__('messages.candidate_profile.title'), 'title')
                ->sortable()
                ->searchable()
                ->view('ads.table_components.title'),

            Column::make(__('messages.ad.position'), 'position')
                ->sortable()
                ->view('ads.table_components.position'),

            Column::make(__('messages.image_slider.is_active'), 'is_active')
                ->sortable()
                ->view('ads.table_components.status'),

            Column::make(__('messages.common.action'), 'id')
                ->view('ads.table_components.action_button'),
        ];
    }

    public function builder(): Builder
    {
        $query = Ad::with('media');
        $query->when($this->status != Ad::ALL, function ($q) {
            if ($this->status) {
                $q->where('is_active', 1);
            } else {
                $q->where('is_active', 0);
            }
        });

        return $query->select('ads.*');
    }

    public function changeStatusFilter($status)
    {
        $this->status = $status;
        $this->setBuilder($this->builder());
        $this->resetPagination();
    }

    public function resetPagination()
    {
        $this->resetPage('adsPage');
    }
}
