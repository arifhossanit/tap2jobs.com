<?php

namespace App\Livewire;

use App\Models\EducationDegreeTitle;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class EducationDegreeTitleTable extends LivewireTableComponent
{
    protected $model = EducationDegreeTitle::class;

    protected ?string $bulkDeleteModel = EducationDegreeTitle::class;
    public $showButtonOnHeader = true;
    public $buttonComponent = 'education_degree_titles.table-components.add_button';
    public $showFilterOnHeader = false;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableAttributes([
            'default' => false,
            'class' => 'table table-striped',
        ]);
        $this->setThAttributes(function (Column $column) {
            return ['class' => 'text-center'];
        });
        $this->setQueryStringStatus(false);
    }

    public function builder(): Builder
    {
        return EducationDegreeTitle::query()
            ->with('degreeLevel')
            ->leftJoin('education_degree_levels', 'education_degree_titles.required_degree_level_id', '=', 'education_degree_levels.id')
            ->select('education_degree_titles.*')
            ->orderBy('education_degree_levels.sort_order')
            ->orderBy('education_degree_levels.name')
            ->orderBy('education_degree_titles.sort_order')
            ->orderBy('education_degree_titles.name');
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
