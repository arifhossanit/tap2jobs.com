<?php

namespace App\Livewire;

use App\Models\ProfileReferenceOption;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
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
        $isConsultationType = $this->type === ProfileReferenceOption::TYPE_CONSULTATION_TYPE;

        $this->setPrimaryKey('id');
        $this->setDefaultSort('sort_order', 'asc');
        $this->setTableAttributes([
            'default' => false,
            'class' => 'table table-striped'.($isConsultationType ? ' consultation-type-sortable' : ''),
        ]);
        $this->setThAttributes(function (Column $column) {
            return ['class' => ($column->isField('sort_order') || $column->isField('id')) ? 'text-center' : ''];
        });
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('label')) {
                return ['width' => '70%'];
            }

            if ($column->isField('sort_order') || $column->isField('id')) {
                return ['class' => 'text-center', 'width' => '30%'];
            }

            return [];
        });
        $this->setQueryStringStatus(false);

        if ($isConsultationType) {
            $this->setPaginationDisabled();
        }
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

    public function moveSortOrder(int $id, string $direction): void
    {
        if (! in_array($direction, ['up', 'down'], true)) {
            return;
        }

        $table = ProfileReferenceOption::tableFor($this->type);

        DB::transaction(function () use ($id, $direction, $table) {
            $records = (new ProfileReferenceOption())
                ->setTable($table)
                ->newQuery()
                ->where('scope', $this->scope)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            $currentIndex = $records->search(fn ($record) => (int) $record->id === $id);

            if ($currentIndex === false) {
                return;
            }

            $targetIndex = $direction === 'up' ? $currentIndex - 1 : $currentIndex + 1;

            if (! $records->has($targetIndex)) {
                return;
            }

            $current = $records[$currentIndex];
            $records[$currentIndex] = $records[$targetIndex];
            $records[$targetIndex] = $current;

            foreach ($records->values() as $index => $record) {
                $record->update(['sort_order' => $index + 1]);
            }
        });
    }

    public function reorderConsultationTypes(array $ids): void
    {
        if ($this->type !== ProfileReferenceOption::TYPE_CONSULTATION_TYPE) return;
        $ids = array_values(array_unique(array_map('intval', $ids)));
        $table = ProfileReferenceOption::tableFor($this->type);
        $validIds = (new ProfileReferenceOption())->setTable($table)->newQuery()->where('scope', $this->scope)->pluck('id')->map(fn ($id) => (int) $id)->all();
        if (count($ids) !== count($validIds) || array_diff($ids, $validIds) || array_diff($validIds, $ids)) return;
        DB::transaction(function () use ($ids, $table) {
            foreach ($ids as $index => $id) {
                (new ProfileReferenceOption())->setTable($table)->newQuery()->where('scope', $this->scope)->whereKey($id)->update(['sort_order' => $index + 1]);
            }
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.common.name'), 'label')
                ->sortable()
                ->searchable()
                ->view('profile_reference_options.table-components.name'),
            Column::make('Sort Order', 'sort_order')
                ->sortable()
                ->view('profile_reference_options.table-components.sort_order'),
            Column::make(__('messages.common.action'), 'id')
                ->view('profile_reference_options.table-components.action_button'),
        ];
    }
}
