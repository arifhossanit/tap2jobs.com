<?php

namespace App\Livewire;

use App\Models\EducationBoard;
use Rappasoft\LaravelLivewireTables\Views\Column;

class EducationBoardTable extends LivewireTableComponent
{
    protected $model = EducationBoard::class;
    public $showButtonOnHeader = true;
    public $buttonComponent = 'education_boards.table-components.add_button';
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
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('name')) {
                return ['width' => '70%'];
            }
            if ($columnIndex == '1') {
                return ['class' => 'text-center', 'width' => '30%'];
            }
            return [];
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
            Column::make(__('messages.common.name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('education_boards.table-components.action_button'),
        ];
    }
}
