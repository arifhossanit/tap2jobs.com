<?php

namespace App\Livewire;

use App\Models\EducationDegreeTitle;
use Rappasoft\LaravelLivewireTables\Views\Column;

class EducationDegreeTitleTable extends LivewireTableComponent
{
    protected $model = EducationDegreeTitle::class;
    public $showButtonOnHeader = true;
    public $buttonComponent = 'education_degree_titles.table-components.add_button';
    public $showFilterOnHeader = false;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('name', 'asc');
        $this->setTableAttributes([
            'default' => false,
            'class' => 'table table-striped',
        ]);
        $this->setThAttributes(function (Column $column) {
            return ['class' => 'text-center'];
        });
        $this->setQueryStringStatus(false);
    }

    public function placeholder()
    {
        return view('livewire_lazy_load/listing-skeleton');
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.required_degree_levels'), 'degreeLevel.name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('education_degree_titles.table-components.action_button'),
        ];
    }
}
