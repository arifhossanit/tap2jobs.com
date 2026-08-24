<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Exceptions\DataTableConfigurationException;
use Throwable;

/**
 * Class LivewireTableComponent
 */
class LivewireTableComponent extends DataTableComponent
{
    protected ?string $bulkDeleteModel = null;

    protected array $bulkDeleteBlockedChecks = [];

    protected bool $columnSelectStatus = false;

    public bool $paginationStatus = true;

    public bool $sortingPillsStatus = false;

    public string $emptyMessage = ('messages.flash.no_record');

    protected $listeners = ['resetPage', 'refreshDatatable' => '$refresh'];

    // for table header button
    public $showButtonOnHeader = false;
    public $showFilterOnHeader = false;
    public bool $reordering = false;

    public $buttonComponent = '';

    public function configure(): void
    {
        // TODO: Implement configure() method.
    }

    public function bulkActions(): array
    {
        if (empty($this->bulkDeleteModel)) {
            return $this->bulkActions ?? [];
        }

        $this->bulkActionConfirms['bulkDelete'] = 'Are you sure you want to delete the selected records?';

        return array_merge($this->bulkActions ?? [], [
            'bulkDelete' => 'Delete Selected',
        ]);
    }

    public function bulkDelete(): void
    {
        if (empty($this->bulkDeleteModel)) {
            return;
        }

        $selectedIds = array_values(array_unique($this->getSelected()));

        if (empty($selectedIds)) {
            $this->dispatchBulkActionFeedback('error', 'Please select at least one record.');

            return;
        }

        $deleted = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($selectedIds as $id) {
            try {
                $record = $this->bulkDeleteModel::find($id);

                if (! $record) {
                    $failed++;
                    continue;
                }

                if ($this->isBulkDeleteBlocked($record)) {
                    $skipped++;
                    continue;
                }

                $this->deleteBulkDeleteRecord($record);
                $deleted++;
            } catch (Throwable $exception) {
                report($exception);
                $failed++;
            }
        }

        $this->clearSelected();

        if ($failed > 0) {
            $message = $this->recordCountText($deleted).' deleted.';

            if ($skipped > 0) {
                $message .= ' '.$this->recordCountText($skipped, 'in-use record', 'in-use records').' skipped.';
            }

            $message .= ' '.$this->recordCountText($failed).' could not be deleted.';

            $this->dispatchBulkActionFeedback('error', $message);
        } elseif ($deleted > 0 && $skipped > 0) {
            $this->dispatchBulkActionFeedback('success', $this->recordCountText($deleted).' deleted. '.$this->recordCountText($skipped, 'in-use record', 'in-use records').' skipped.');
        } elseif ($deleted > 0) {
            $message = $deleted === 1
                ? '1 selected record deleted successfully.'
                : $deleted.' selected records deleted successfully.';

            $this->dispatchBulkActionFeedback('success', $message);
        } elseif ($skipped > 0) {
            $message = $skipped === 1
                ? '1 selected record is already in use.'
                : $skipped.' selected records are already in use.';

            $this->dispatchBulkActionFeedback('error', $message);
        } else {
            $this->dispatchBulkActionFeedback('error', 'No selected records could be deleted.');
        }
    }

    protected function dispatchBulkActionFeedback(string $type, string $message): void
    {
        $this->dispatch('bulk-action-feedback', type: $type, message: $message);
    }

    protected function recordCountText(int $count, string $singular = 'record', string $plural = 'records'): string
    {
        return $count.' '.($count === 1 ? $singular : $plural);
    }

    protected function isBulkDeleteBlocked($record): bool
    {
        foreach ($this->bulkDeleteBlockedChecks as $check) {
            [$model, $column] = $check;

            if ($model::where($column, $record->id)->exists()) {
                return true;
            }
        }

        return false;
    }

    protected function deleteBulkDeleteRecord($record): void
    {
        $record->delete();
    }

    public function columns(): array
    {
        // TODO: Implement columns() method.
    }

    /**
     * @throws DataTableConfigurationException
     */
    // public function mountWithPagination(): void
    // {
    //     if ($this->getPerPage()) {
    //         $this->getPerPageAccepted()[] = -1;
    //     }

    //     $this->setPerPage($this->getPerPageAccepted()[0] ?? 10);
    // }

    // public function resetPage($pageName = 'page')
    // {
    //     $rowsPropertyData = $this->getRows()->toArray();
    //     if ($rowsPropertyData['current_page'] > count($rowsPropertyData['links']) - 2) {
    //         $this->setPage($rowsPropertyData['last_page'], $pageName);
    //     } else {
    //         $rowsPropertyData = $this->getRows()->toArray();
    //         $prevPageNum = $rowsPropertyData['current_page'] - 1;
    //         $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
    //         $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;
    //         $this->setPage($pageNum, $pageName);
    //     }
    // }
}
