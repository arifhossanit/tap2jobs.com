<?php

namespace App\Livewire;

use App\Models\City;
use App\Models\CityVillage;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CityVillageTable extends LivewireTableComponent
{
    protected $model = CityVillage::class;

    protected ?string $bulkDeleteModel = CityVillage::class;

    protected array $bulkDeleteBlockedChecks = [
        ['App\Models\Thana', 'city_village_id'],
        ['App\Models\Job', 'city_village_id'],
        ['App\Models\User', 'city_village_id'],
        ['App\Models\Candidate', 'permanent_city_village_id'],
        ['App\Models\CandidateExperience', 'city_village_id'],
        ['App\Models\CandidateEducation', 'city_village_id'],
    ];

    protected string $tableName = 'city_villages';

    public $showButtonOnHeader = true;

    public $showFilterOnHeader = true;

    public $city = CityVillage::CITY;

    public $buttonComponent = 'city_villages.table-components.add_button';

    protected $listeners = ['resetPage', 'refreshDatatable' => '$refresh', 'changeCityVillageDistrictFilter'];

    public array $filterComponents = ['city_villages.table-components.filter', CityVillage::CITY];

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
            Column::make(__('messages.city_village.city_village_name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.city.city_name'), 'city.name')
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy(City::select('name')->whereColumn('city_villages.city_id', 'cities.id'), $direction)
                        ->orderBy('city_villages.name', 'asc');
                })
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('city_villages.table-components.action_button'),
        ];
    }

    public function builder(): Builder
    {
        $query = CityVillage::with('city');

        $query->when(! empty($this->city), fn ($q) => $q->where('city_id', $this->city));

        return $query->select('city_villages.*');
    }

    public function changeCityVillageDistrictFilter($city): void
    {
        $this->city = $city;
        $this->setBuilder($this->builder());
        $this->resetPagination();
    }

    public function resetPagination(): void
    {
        $this->resetPage('cityVillagesPage');
    }
}
