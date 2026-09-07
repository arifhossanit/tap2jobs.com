<?php

namespace App\Livewire;

use App\Models\GovernmentJob;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Rappasoft\LaravelLivewireTables\Views\Column;

class GovernmentJobTable extends LivewireTableComponent
{
    protected $model = GovernmentJob::class;
    protected ?string $bulkDeleteModel = GovernmentJob::class;
    protected string $tableName = 'government_jobs';

    public $showButtonOnHeader = true;
    public $showFilterOnHeader = false;
    public $buttonComponent = 'government_jobs.table_components.add_button';

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
        $this->setTableAttributes(['default' => false, 'class' => 'table table-striped']);
        $this->setThAttributes(fn (Column $column) => [
            'class' => $column->isField('title') || $column->isField('organization_name') ? '' : 'text-center',
        ]);
        $this->setTdAttributes(fn (Column $column) => [
            'class' => $column->isField('title') || $column->isField('organization_name') ? '' : 'text-center',
        ]);
        $this->setQueryStringStatus(false);
        $this->setFilterPillsStatus(false);
    }

    public function placeholder()
    {
        return view('livewire_lazy_load/listing-skeleton');
    }

    public function columns(): array
    {
        return [
            Column::make('Title', 'title')->sortable()->searchable(),
            Column::make('Organization', 'organization_name')->sortable()->searchable(),
            Column::make('Source', 'source_name')->sortable()->searchable()
                ->view('government_jobs.table_components.source'),
            Column::make('Published', 'published_at')->sortable()
                ->view('government_jobs.table_components.date'),
            Column::make('Deadline', 'application_deadline')->sortable()
                ->view('government_jobs.table_components.date'),
            Column::make('Status', 'is_published')->sortable()
                ->view('government_jobs.table_components.status'),
            Column::make(__('messages.common.action'), 'id')
                ->view('government_jobs.table_components.action_buttons'),
        ];
    }

    public function builder(): Builder
    {
        return GovernmentJob::query()->select('government_jobs.*');
    }

    protected function deleteBulkDeleteRecord($record): void
    {
        Storage::disk('public')->delete($record->circular_path);
        $record->delete();
    }
}
