<?php

namespace App\Livewire;

use App\Models\ProfileReferenceOption;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Throwable;

class ProfileReferenceOptionTable extends LivewireTableComponent
{
    protected $model = ProfileReferenceOption::class;

    protected ?string $bulkDeleteModel = ProfileReferenceOption::class;

    public string $scope;

    public string $type;

    public $showButtonOnHeader = true;

    public $buttonComponent = 'profile_reference_options.table-components.add_button';

    public $showFilterOnHeader = false;

    public function mount(string $scope, string $type): void
    {
        $this->scope = $scope;
        $this->type = $type;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('label', 'asc');
        $this->setTableAttributes([
            'default' => false,
            'class' => 'table table-striped',
        ]);
        $this->setThAttributes(function (Column $column) {
            return ['class' => 'text-center'];
        });
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('label')) {
                return ['width' => '70%'];
            }

            if ($columnIndex == '1') {
                return ['class' => 'text-center', 'width' => '30%'];
            }

            return [];
        });
        $this->setQueryStringStatus(false);
    }

    public function builder(): Builder
    {
        return (new ProfileReferenceOption())
            ->setTable(ProfileReferenceOption::tableFor($this->type))
            ->newQuery()
            ->where('scope', $this->scope);
    }

    public function bulkDelete(): void
    {
        $selectedIds = array_values(array_unique($this->getSelected()));

        if (empty($selectedIds)) {
            $this->dispatchBulkActionFeedback('error', 'Please select at least one record.');

            return;
        }

        $deleted = 0;
        $failed = 0;

        foreach ($selectedIds as $id) {
            try {
                $record = (new ProfileReferenceOption())
                    ->setTable(ProfileReferenceOption::tableFor($this->type))
                    ->newQuery()
                    ->where('scope', $this->scope)
                    ->find($id);

                if (! $record) {
                    $failed++;
                    continue;
                }

                $record->delete();
                $deleted++;
            } catch (Throwable $exception) {
                report($exception);
                $failed++;
            }
        }

        $this->clearSelected();

        if ($failed > 0) {
            $this->dispatchBulkActionFeedback('error', $this->recordCountText($deleted).' deleted. '.$this->recordCountText($failed).' could not be deleted.');
        } elseif ($deleted > 0) {
            $message = $deleted === 1
                ? '1 selected record deleted successfully.'
                : $deleted.' selected records deleted successfully.';

            $this->dispatchBulkActionFeedback('success', $message);
        } else {
            $this->dispatchBulkActionFeedback('error', 'No selected records could be deleted.');
        }
    }

    public function placeholder()
    {
        return view('livewire_lazy_load/listing-skeleton');
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.common.name'), 'label')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('profile_reference_options.table-components.action_button'),
        ];
    }
}
