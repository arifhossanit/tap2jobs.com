<?php

namespace App\Livewire;

use App\Models\Job;
use App\Models\JobApplication;
use App\Repositories\JobRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class JobTable extends LivewireTableComponent
{
    /**
     * @var string
     */
    protected $model = Job::class;
    protected ?string $bulkDeleteModel = Job::class;
    protected string $tableName = 'jobs';

    /**
     * @var bool
     */
    public $showButtonOnHeader = true;
    public $showFilterOnHeader = true;
    public $featured = Job::SELECT_FEATURD;
    public $suspended = Job::SELECT_IS_SUSPENDED;
    public $freelance = Job::SELECT_IS_FREELANCER;
    public  $status = Job::SELECT_JOBS_ACTIVE;
    public ?string $deadline = null;
    public ?string $expiryAlert = null;
    protected $listeners = ['resetPage', 'refreshDatatable' => '$refresh', 'changeFeaturedFilter','changeSuspendedFilter','changeFreelanceFilter','changeStatusFilter'];

    public array $filterComponents = ['jobs.table-components.filter', [Job::IS_FEATURED, Job::IS_SUSPENDED, Job::IS_FREELANCER, Job::JOBS_ACTIVE]];

    /**
     * @var string
     */
    public $buttonComponent = 'jobs.table-components.add_button';

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setDefaultSort('created_at', 'desc');

        $this->setTableAttributes([
            'default' => false,
            'class' => 'table table-striped',
        ]);

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('job_title')) {
                return [
                    'style' => 'width:35%',
                ];
            }

            return [
                'class' => 'text-center',
            ];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex == '6') {
                return [
                    'class' => 'text-center',
                    'width' => '15%',

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
            Column::make(__('messages.job.job_title'), 'job_title')
                ->sortable()
                ->searchable()
                ->view('jobs.table-components.job_title'),
            Column::make(__('messages.job.is_featured'), 'hide_salary')
                ->sortable()
                ->view('jobs.table-components.is_featured'),
            Column::make(__('messages.job.is_suspended'), 'is_suspended')
                ->sortable()
                ->view('jobs.table-components.is_suspended'),
            Column::make(__('messages.common.created_on'), 'created_at')
                ->sortable()
                ->searchable()
                ->view('jobs.table-components.created_on'),
            Column::make(__('messages.job.job_expiry_date'), 'job_expiry_date')
                ->sortable()
                ->view('jobs.table-components.expired_at'),
            Column::make(__('messages.common.last_change_by'), 'last_change')
                ->sortable()
                ->view('jobs.table-components.last_change'),
            Column::make(__('messages.common.action'), 'id')
                ->view('jobs.table-components.action_buttons'),
        ];
    }

    public function builder(): Builder
    {
        $query =  Job::with('company', 'jobCategory', 'jobType', 'jobShift', 'activeFeatured', 'featured', 'admin')->whereNot('status', job::SELECT_PANDING);
        $deadline = $this->deadline ?: request()->query('deadline');
        $legacyExpiryAlert = $this->expiryAlert ?: request()->query('expiry_alert');

        if (! $deadline && in_array($legacyExpiryAlert, ['expire_today', 'expire_tomorrow', 'expire_in_7_days'], true)) {
            $deadline = [
                'expire_today' => 'today',
                'expire_tomorrow' => 'tomorrow',
                'expire_in_7_days' => '7_days',
            ][$legacyExpiryAlert];
        }

        if (in_array($deadline, ['today', 'tomorrow', '7_days'], true)) {
            $expiryDate = match ($deadline) {
                'today' => Carbon::today()->toDateString(),
                'tomorrow' => Carbon::tomorrow()->toDateString(),
                '7_days' => Carbon::today()->addDays(7)->toDateString(),
            };

            return $query->whereDate('job_expiry_date', $expiryDate)
                ->status(Job::STATUS_OPEN)
                ->where('is_suspended', Job::NOT_SUSPENDED)
                ->select('jobs.*');
        }

        $query->when($this->featured != Job::SELECT_FEATURD, function($q) {
            (int) $this->featured === Job::YES
                ? $q->whereHas('featured')
                : $q->doesntHave('featured');
        });
        $query->when($this->suspended != Job::SELECT_IS_SUSPENDED, function($q) {
         $q->where('is_suspended', $this->suspended);
        });
        $query->when($this->freelance != Job::SELECT_IS_FREELANCER, function($q) {
         $q->where('is_freelance', $this->freelance);
        });
        $query->when($this->status != Job::SELECT_JOBS_ACTIVE, function($q) {
         if($this->status) {
                  $q->where('job_expiry_date', '<=', date('Y-m-d'));
         }else {
                  $q->where('job_expiry_date', '>=', Carbon::tomorrow()->toDateString())->status(Job::STATUS_OPEN)->where('is_suspended', Job::NOT_SUSPENDED);
         }
});
        return $query->select('jobs.*');
    }

    protected function isBulkDeleteBlocked($record): bool
    {
        return $record->appliedJobs()
            ->whereIn('status', [
                JobApplication::STATUS_APPLIED,
                JobApplication::STATUS_DRAFT,
            ])
            ->exists();
    }

    protected function deleteBulkDeleteRecord($record): void
    {
        app(JobRepository::class)->delete($record->id);
    }

//     public function filters(): array
//     {
//         return [
//             SelectFilter::make(__('messages.filter_name.featured_job'))
//                 ->options([
//                     '' => (__('messages.filter_name.select_featured_company')),
//                     'yes' => (__('messages.common.yes')),
//                     'no' => (__('messages.common.no')),
//                 ])
//                 ->filter(
//                     function (Builder $builder, string $value) {
//                         if ($value == 'yes') {
//                             $builder->with('featured')->whereHas('featured');
//                         } else {
//                             $builder->with('featured')->doesntHave('featured');
//                         }
//                     }
//                 ),

//             SelectFilter::make(__('messages.filter_name.suspended_job'))
//                 ->options([
//                     '' => (__('messages.filter_name.select_suspended_job')),
//                     1 => (__('messages.common.yes')),
//                     0 => (__('messages.common.no')),
//                 ])
//                 ->filter(
//                     function (Builder $builder, string $value) {
//                         $builder->where('is_suspended', '=', $value);
//                     }
//                 ),
//             SelectFilter::make(__('messages.filter_name.select_independent_work'))
//                 ->options([
//                     '' => (__('messages.filter_name.select_independent_work')),
//                     1 => (__('messages.common.yes')),
//                     0 => (__('messages.common.no')),
//                 ])
//                 ->filter(
//                     function (Builder $builder, string $value) {
//                         $builder->where('is_freelance', '=', $value);
//                     }
//                 ),
//             SelectFilter::make(__('messages.filter_name.job_status'))
//                 ->options([
//                     '' => (__('messages.filter_name.job_status')),
//                     'active' => (__('messages.common.active')),
//                     'expire' => (__('messages.common.expire')),
//                 ])
//                 ->filter(
//                     function (Builder $builder, string $value) {
//                         if ($value == 'expire') {
//                             $builder->where('job_expiry_date', '<=', date('Y-m-d'));
//                         } else {
//                             $builder->where('job_expiry_date', '>=', Carbon::tomorrow()->toDateString())->status(Job::STATUS_OPEN)->where('is_suspended', Job::NOT_SUSPENDED);
//                         }
//                     }
//                 ),

//         ];
//     }
    public function changeFeaturedFilter($featured)
    {
         $this->featured = $featured;
         $this->setBuilder($this->builder());
         $this->resetPagination();
    }
    public function changeSuspendedFilter($suspended)
    {
         $this->suspended = $suspended;
         $this->setBuilder($this->builder());
         $this->resetPagination();
    }
    public function changeFreelanceFilter($freelance)
    {
         $this->freelance = $freelance;
         $this->setBuilder($this->builder());
         $this->resetPagination();
    }
    public function changeStatusFilter($status)
    {
         $this->status = $status;
         $this->setBuilder($this->builder());
         $this->resetPagination();
    }
    public function resetPagination() {
        $this->resetPage('jobsPage');
    }
}
