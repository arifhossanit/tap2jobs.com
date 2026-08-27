<?php

namespace App\Livewire;

use App\Models\Job;
use App\Models\Candidate;
use App\Services\CandidateJobMatchService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public $filter = '';

    public bool $overseas = false;

    public bool $workFromHome = false;

    public bool $matchingOnly = false;

    public array $matchingJobIds = [];

    private $perPage = 10;

    protected $listeners = ['changeFilter', 'changeSalaryRange', 'changeExperienceRange', 'resetFilter'];

    public function paginationView()
    {
        return 'livewire.custom-pagination-jobs';
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
        if ($request->filled('filter')) {
            $this->filter = $request->get('filter');
        }
        if ($request->get('overseas') == '1') {
            $this->overseas = true;
        }
        if ($request->get('work_from_home') == '1') {
            $this->workFromHome = true;
        }
        if ($request->get('is_fresher') == '1') {
            $this->freshersOnly = true;
            $this->jobExperienceTo = 0;
        }
        if ($request->get('matching') == '1') {
            $this->matchingOnly = true;
            $this->matchingJobIds = $this->candidateMatchingJobIds();
        }

        $this->featuredJob = $request->is_featured;
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

    private function candidateMatchingJobIds(): array
    {
        $user = Auth::user();

        if (! $user || ! $user->hasRole('Candidate')) {
            return [];
        }

        $candidate = $user->candidate ?: Candidate::find($user->owner_id);

        if (! $candidate) {
            return [];
        }

        return app(CandidateJobMatchService::class)
            ->topMatches($candidate, 180)
            ->filter(fn (Job $job) => (int) ($job->match_score ?? 0) > 0)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();
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

        if ($this->matchingOnly) {
            if (empty($this->matchingJobIds)) {
                $query->whereRaw('1 = 0');
            } else {
                $matchingOrderPlaceholders = implode(',', array_fill(0, count($this->matchingJobIds), '?'));

                $query->whereIn('id', $this->matchingJobIds)
                    ->orderByRaw("FIELD(id, {$matchingOrderPlaceholders})", $this->matchingJobIds);
            }
        }

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
            $q->where(function (Builder $sub) {
                $sub->where('freshers_encouraged', true)
                    ->orWhere('experience', 0);
            });
        });

        $query->when($this->filter === 'new', function (Builder $q) {
            $q->where('created_at', '>=', Carbon::now()->subDays(7));
        });

        $query->when($this->filter === 'deadline_tomorrow', function (Builder $q) {
            $q->whereDate('job_expiry_date', '=', Carbon::tomorrow()->toDateString());
        });

        $query->when($this->overseas, function (Builder $q) {
            $q->where(function (Builder $sub) {
                $sub->whereNull('country_id')
                    ->orWhereHas('country', function (Builder $cq) {
                        $cq->where('short_code', '!=', 'BD');
                    });
            });
        });

        $query->when($this->workFromHome, function (Builder $q) {
            $q->where(function (Builder $sub) {
                $sub->where('is_freelance', 1)
                    ->orWhere('job_title', 'like', '%Remote%')
                    ->orWhere('job_title', 'like', '%Work from Home%')
                    ->orWhereHas('jobType', function (Builder $jq) {
                        $jq->where('name', 'like', '%Freelance%');
                    });
            });
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

        $minimumExpiryDate = $this->matchingOnly ? Carbon::now()->toDateString() : Carbon::tomorrow()->toDateString();

        $query->whereStatus(Job::STATUS_OPEN)
            ->where('status', '!=', Job::STATUS_DRAFT)
            ->whereIsSuspended(Job::NOT_SUSPENDED)
            ->whereDate('job_expiry_date', '>=', $minimumExpiryDate);

        $all = $query->paginate($this->perPage);
        $currentPage = $all->currentPage();
        $lastPage = $all->lastPage();
        if ($currentPage > $lastPage) {
            $lastPage = max(1, $lastPage);
            $this->setPage($lastPage);
            $all = $query->paginate($this->perPage, ['*'], 'page', $lastPage);
        }

        return $all;
    }
}
