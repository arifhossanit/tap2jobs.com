<?php

namespace App\Livewire;

use App\Models\JobStage;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class JobStageTable extends LivewireTableComponent
{
    protected $model = JobStage::class;

    public $showButtonOnHeader = true;

    public $buttonComponent = 'employer.job_stages.table_components.add_button';

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setDefaultSort('created_at', 'desc');

        $this->setTableWrapperAttributes([
            'default' => false,
            'class' => 'table-responsive job-stages-table-responsive',
        ]);

        $this->setThAttributes(
            function (Column $column) {
                if ($column->isField('name') || $column->isField('id')) {
                    return ['class' => 'text-center text-nowrap'];
                }
                if ($column->isField('description')) {
                    return [
                        'class' => 'text-center',
                        'style' => 'min-width: 250px;',
                    ];
                }
                return [
                    'class' => 'text-center',
                ];
            });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex == '0') {
                return ['class' => 'align-middle text-nowrap'];
            }
            if ($columnIndex == '1') {
                return [
                    'class' => 'align-middle',
                    'style' => 'min-width: 250px;',
                ];
            }
            if ($columnIndex == '2') {
                return [
                    'class' => 'text-center align-middle text-nowrap',
                    'width' => '15%',
                ];
            }

            return ['class' => 'align-middle'];
        });

        $this->setTableAttributes(
            [
                'default' => false,
                'class' => 'table table-striped',
            ]);

        $this->setQueryStringStatus(false);
    }
    public function placeholder()
    {
        return view('livewire_lazy_load/listing-skeleton');
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.job_stage.job_stage'), 'name')
                ->sortable()
                ->searchable()
                ->view('employer.job_stages.table_components.name'),
            Column::make(__('messages.common.description'), 'description')
                ->sortable()
                ->view('employer.job_stages.table_components.description'),
            Column::make(__('messages.common.action'), 'id')
                ->view('employer.job_stages.table_components.action_button'),
        ];
    }

    public function builder(): Builder
    {
        return JobStage::query()->where('company_id', getLoggedInUser()->company->id)->select('job_stages.*');
    }
}
