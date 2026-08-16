<?php

namespace App\Livewire;

use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class JobSearch extends Component
{
    use WithPagination;

    public $searchByLocation = '';

    public $types = [];

    public $category = '';

    public $salaryFrom = '';

    public $salaryTo = '';

    public $title = '';

    public $skill = '';

    public $gender = '';

    public $careerLevel = '';

    public $functionalArea = '';

    public $company = '';

    public $jobExperience = '';

    public $jobExperienceFrom = '';

    public $jobExperienceTo = '';

    public bool $freshersOnly = false;

    public $featuredJob = '';

    private $perPage = 10;

    public int $page = 0;

    protected $listeners = ['changeFilter', 'changeSalaryRange', 'changeExperienceRange', 'resetFilter'];

    public function paginationView()
    {
        return 'livewire.custom-pagination-company';
    }

    public function mount(Request $request)
    {
        if (! empty($request->get('keywords'))) {
            $this->title = $request->get('keywords');
        }
        if (! empty($request->get('location'))) {
            $this->searchByLocation = $request->get('location');
        }
        if (! empty($request->get('categories'))) {
            $this->category = $request->get('categories');
        }
        if (! empty($request->get('company'))) {
            $this->company = $request->get('company');
        }
        if (! empty($request->get('job_type'))) {
            $this->types = [(int) $request->get('job_type')];
        }
        if ($request->filled('jobExperience')) {
            $this->jobExperience = max(0, (int) $request->get('jobExperience'));
        }
        if ($request->filled('jobExperienceFrom')) {
            $this->jobExperienceFrom = max(0, (int) $request->get('jobExperienceFrom'));
        }
        if ($request->filled('jobExperienceTo')) {
            $this->jobExperienceTo = max(0, (int) $request->get('jobExperienceTo'));
        }

        $this->featuredJob = $request->is_featured;
    }

    public function nextPage($lastPage)
    {
        if ($this->page < $lastPage) {
            $this->page = $this->page + 1;
            $this->setPage($this->page);
        }
    }

    public function previousPage()
    {
        if ($this->page > 1) {
            $this->page = $this->page - 1;
            $this->setPage($this->page);
        }
    }

    public function updatingSearchByLocation()
    {
        $this->resetPage();
    }

    public function changeFilter($param, $value)
    {
        if (! property_exists($this, $param)) {
            return;
        }

        $this->resetPage();
        $this->$param = in_array($param, ['jobExperience', 'jobExperienceFrom', 'jobExperienceTo']) && $value !== ''
            ? max(0, (int) $value)
            : $value;
    }

    public function changeExperienceRange($from, $to, $maximum): void
    {
        $this->resetPage();
        $from = max(0, (int) $from);
        $to = max($from, (int) $to);
        $maximum = max(1, (int) $maximum);

        $this->jobExperience = '';
        $this->jobExperienceFrom = $from > 0 ? $from : '';
        $this->jobExperienceTo = $to < $maximum ? $to : '';
    }

    public function changeSalaryRange($from, $to, $maximum = 150000): void
    {
        $this->resetPage();
        $from = max(0, (int) $from);
        $to = max($from, (int) $to);
        $maximum = max(1, (int) $maximum);

        $this->salaryFrom = $from > 0 ? $from : '';
        $this->salaryTo = $to < $maximum ? $to : '';
    }

    public function resetFilter()
    {
        $this->reset();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $jobs = $this->searchJobs();

        return view('livewire.job-search', compact('jobs'));
    }

    /**
     * @return mixed
     */
    public function searchJobs()
    {
        /** @var Job $query */
        $query = Job::with([
            'company', 'country', 'state', 'city', 'jobShift', 'company.user', 'jobsSkill', 'jobCategory',
        ]);

        $query->when(! empty($this->types), function (Builder $q) {
            $q->whereIn('job_type_id', $this->types);
        });

        $query->when(! empty($this->category), function (Builder $q) {
            $q->where('job_category_id', '=', $this->category);
        });

        $query->when(! empty($this->salaryFrom), function (Builder $q) {
            $q->where('salary_to', '>=', $this->salaryFrom);
        });

        $query->when(! empty($this->salaryTo), function (Builder $q) {
            $q->where('salary_from', '<=', $this->salaryTo);
        });

        $query->when(! empty($this->careerLevel), function (Builder $q) {
            $q->where('career_level_id', '=', $this->careerLevel);
        });

        $query->when(! empty($this->functionalArea), function (Builder $q) {
            $q->where('functional_area_id', '=', $this->functionalArea);
        });

        $query->when($this->gender != '', function (Builder $q) {
            $q->where('no_preference', '=', $this->gender);
        });

        $query->when(! empty($this->skill), function (Builder $q) {
            $q->whereHas('jobsSkill', function (Builder $q) {
                $q->where('skill_id', '=', $this->skill);
            });
        });
        $query->when(! empty($this->company), function (Builder $q) {
            $q->whereHas('company', function (Builder $q) {
                $q->where('id', '=', $this->company);
            });
        });

        $query->when($this->jobExperienceFrom !== '', function (Builder $q) {
            $q->where('experience', '>=', (int) $this->jobExperienceFrom);
        });

        $query->when($this->jobExperienceTo !== '', function (Builder $q) {
            $q->where('experience', '<=', (int) $this->jobExperienceTo);
        });

        $query->when($this->jobExperience !== '' && $this->jobExperienceFrom === '' && $this->jobExperienceTo === '', function (Builder $q) {
            $q->where('experience', '<=', (int) $this->jobExperience);
        });

        $query->when($this->freshersOnly, function (Builder $q) {
            $q->where('freshers_encouraged', true);
        });

        $query->when(! empty($this->featuredJob), function (Builder $q) {
            $q->has('activeFeatured')
            ->whereStatus(Job::STATUS_OPEN)
            ->whereDate('job_expiry_date', '>=', Carbon::now()->toDateString())
            ->where('is_suspended', '=', Job::NOT_SUSPENDED);
        });

        $query->when(! empty($this->searchByLocation), function (Builder $q) {
            $q->where(function (Builder $q) {
                $q->where('job_title', 'like', '%'.$this->searchByLocation.'%');
                $q->orWhereHas(
                    'country',
                    function (Builder $q) {
                        $q->where('name', 'like', '%'.$this->searchByLocation.'%');
                    }
                )->orWhereHas(
                    'state',
                    function (Builder $q) {
                        $q->where('name', 'like', '%'.$this->searchByLocation.'%');
                    }
                )->orWhereHas(
                    'city',
                    function (Builder $q) {
                        $q->where('name', 'like', '%'.$this->searchByLocation.'%');
                    }
                )->orWhereHas(
                    'company.user',
                    function (Builder $q) {
                        $q->where('first_name', 'like', '%'.$this->searchByLocation.'%')
                            ->orWhere('last_name', 'like', '%'.$this->searchByLocation.'%');
                    }
                )->orWhereHas(
                    'jobsSkill',
                    function (Builder $q) {
                        $q->where('name', 'like', '%'.$this->searchByLocation.'%');
                    }
                );
            });
        });

        $query->when(! empty($this->title), function (Builder $q) {
            $q->where(function (Builder $q) {
                $q->where('job_title', 'like', '%'.$this->title.'%')
                    ->orWhereHas('jobsSkill', function (Builder $q) {
                        $q->where('name', 'like', '%'.$this->title.'%');
                    })
                    ->orWhereHas('company.user', function (Builder $q) {
                        $q->where('first_name', 'like', '%'.$this->title.'%')
                            ->orWhere('last_name', 'like', '%'.$this->title.'%');
                    });
            });
        });

        $query->whereStatus(Job::STATUS_OPEN)->where('status', '!=',Job::STATUS_DRAFT)->whereIsSuspended(Job::NOT_SUSPENDED)->whereDate('job_expiry_date', '>=', Carbon::tomorrow()->toDateString());

        $all = $query->paginate($this->perPage);
        $currentPage = $all->currentPage();
        $lastPage = $all->lastPage();
        if ($currentPage > $lastPage) {
            $this->page = $lastPage;
            $all = $query->paginate($this->perPage);
        }

        return $all;
    }
}
