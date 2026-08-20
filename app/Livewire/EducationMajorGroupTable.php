<?php

namespace App\Livewire;

use App\Models\EducationMajorGroup;
use Rappasoft\LaravelLivewireTables\Views\Column;

class EducationMajorGroupTable extends LivewireTableComponent
{
    protected $model = EducationMajorGroup::class;

    protected ?string $bulkDeleteModel = EducationMajorGroup::class;
    public $showButtonOnHeader = true;
    public $buttonComponent = 'education_major_groups.table-components.add_button';
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
                ->view('education_major_groups.table-components.action_button'),
        ];
    }
}
