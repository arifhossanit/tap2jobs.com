<?php

namespace App\Livewire;

use App\Models\ConsultationLead;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ConsultationLeadTable extends LivewireTableComponent
{
    protected $model = ConsultationLead::class;

    protected string $tableName = 'consultation-leads';

    protected $listeners = [
        'resetPage',
        'refreshDatatable' => '$refresh',
        'changeConsultationLeadStatusFilter',
        'changeConsultationLeadCategoryFilter',
        'resetConsultationLeadFilters',
    ];

    public string $status = '';

    public string $companyCategoryId = '';

    public $showButtonOnHeader = true;

    public $showFilterOnHeader = true;

    public $buttonComponent = 'consultation_leads.table_components.export_buttons';

    public array $filterComponents = ['consultation_leads.table_components.filter', '', ''];

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setDefaultSort('created_at', 'desc');

        $this->setTableAttributes([
            'default' => false,
            'class' => 'table table-striped',
        ]);

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('id')) {
                return [
                    'class' => 'text-center',
                ];
            }

            return [];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('id')) {
                return [
                    'class' => 'text-center',
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
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Contact', 'phone')
                ->sortable()
                ->searchable()
                ->view('consultation_leads.table_components.contact'),
            Column::make('Company', 'company_name')
                ->sortable()
                ->searchable()
                ->view('consultation_leads.table_components.company'),
            Column::make('Size', 'company_size_id')
                ->sortable()
                ->view('consultation_leads.table_components.size'),
            Column::make('Category', 'company_category_id')
                ->sortable()
                ->view('consultation_leads.table_components.category'),
            Column::make('Type', 'consultation_type')
                ->sortable()
                ->view('consultation_leads.table_components.type'),
            Column::make('Lead Source', 'source_page')
                ->view('consultation_leads.table_components.lead_source'),
            Column::make('Status', 'status')
                ->sortable()
                ->view('consultation_leads.table_components.status'),
            Column::make('Submitted', 'created_at')
                ->sortable()
                ->view('consultation_leads.table_components.submitted'),
            Column::make(__('messages.common.action'), 'id')
                ->view('consultation_leads.table_components.action_button'),
        ];
    }

    public function builder(): Builder
    {
        return ConsultationLead::query()
            ->with(['ad', 'companySize.companyCategory', 'companyCategory'])
            ->when($this->status !== '', function (Builder $query) {
                $query->where('status', $this->status);
            })
            ->when($this->companyCategoryId !== '', function (Builder $query) {
                $query->where('company_category_id', $this->companyCategoryId);
            })
            ->select('consultation_leads.*');
    }

    public function changeConsultationLeadStatusFilter(string $status = ''): void
    {
        $this->status = $status;
        $this->setBuilder($this->builder());
        $this->resetPagination();
    }

    public function changeConsultationLeadCategoryFilter(string $companyCategoryId = ''): void
    {
        $this->companyCategoryId = $companyCategoryId;
        $this->setBuilder($this->builder());
        $this->resetPagination();
    }

    public function resetConsultationLeadFilters(): void
    {
        $this->status = '';
        $this->companyCategoryId = '';
        $this->setBuilder($this->builder());
        $this->resetPagination();
    }

    public function resetPagination(): void
    {
        $this->resetPage('consultation-leadsPage');
    }
}
