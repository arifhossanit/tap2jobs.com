<?php

namespace App\Livewire;

use App\Models\City;
use App\Models\Thana;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ThanaTable extends LivewireTableComponent
{
    protected $model = Thana::class;

    protected ?string $bulkDeleteModel = Thana::class;

    protected array $bulkDeleteBlockedChecks = [
        ['App\Models\Job', 'thana_id'],
        ['App\Models\User', 'thana_id'],
        ['App\Models\Candidate', 'permanent_thana_id'],
        ['App\Models\CandidateExperience', 'thana_id'],
        ['App\Models\CandidateEducation', 'thana_id'],
    ];

    protected string $tableName = 'thanas';

    public $showButtonOnHeader = true;

    public $showFilterOnHeader = true;

    public $city = Thana::CITY;

    public $buttonComponent = 'thanas.table-components.add_button';

    protected $listeners = ['resetPage', 'refreshDatatable' => '$refresh', 'changeCityFilter'];

    public array $filterComponents = ['thanas.table-components.filter', Thana::CITY];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('city.name', 'asc');
        $this->setTableAttributes([
            'default' => false,
            'class' => 'table table-striped',
        ]);
        $this->setThAttributes(fn (Column $column): array => ['class' => 'text-center']);
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex == '2') {
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

    public function columns(): array
    {
        return [
            Column::make(__('messages.thana.thana_name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.thana.city_name'), 'city.name')
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy(City::select('name')->whereColumn('thanas.city_id', 'cities.id'), $direction)
                        ->orderBy('thanas.name', 'asc');
                })
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('thanas.table-components.action_button'),
        ];
    }

    public function builder(): Builder
    {
        $query = Thana::with('city');

        $query->when(! empty($this->city), function ($q) {
            $q->where('city_id', $this->city);
        });

        return $query->select('thanas.*');
    }

    public function changeCityFilter($city): void
    {
        $this->city = $city;
        $this->setBuilder($this->builder());
        $this->resetPagination();
    }

    public function resetPagination(): void
    {
        $this->resetPage('thanasPage');
    }
}
