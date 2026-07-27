<?php

namespace App\Livewire;

use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class CompanySearch extends Component
{
    use WithPagination;

    public int $page = 0;

    public $searchByCompany = '';

    public $searchByCity = '';

    public $searchByIndustry = '';

    public $isFeatured = '';

    private $perPage = 12;

    public function mount($isFeatured = null)
    {
        $this->isFeatured = $isFeatured;
    }

    public function paginationView()
    {
        return 'livewire.custom-pagination-company';
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

    public function resetFilter()
    {
        $this->searchByCompany = '';
        $this->searchByCity = '';
        $this->searchByIndustry = '';
        $this->resetPage();
    }

    public function updatingSearchByCompany()
    {
        $this->resetPage();
    }

    public function updatingSearchByCity()
    {
        $this->resetPage();
    }

    public function updatingSearchByIndustry()
    {
        $this->resetPage();
    }

    public function render()
    {
        $companies = $this->companies();

        return view('livewire.company-search', compact('companies'));
    }

    public function companies()
    {
        /** @var User $user */
        $query = Company::with(['user.media', 'jobs', 'activeFeatured', 'industry', 'user.city']);
        $query->whereHas('user', function (Builder $q) {
            $q->where('is_active', '=', 1);
            $q->when(! empty($this->searchByCompany), function (Builder $q) {
                $q->where(function (Builder $q) {
                    $q->where('first_name', 'like', '%'.strtolower($this->searchByCompany).'%')
                        ->orWhere('last_name', 'like', '%'.strtolower($this->searchByCompany).'%');
                });
            });
        });

        $query->when(! empty($this->searchByCity), function (Builder $q) {
            $q->where(function (Builder $q) {
                $q->where('location', 'like', '%'.strtolower($this->searchByCity).'%')
                    ->orWhere('location2', 'like', '%'.strtolower($this->searchByCity).'%');
            });
        });

        $query->when(! empty($this->searchByIndustry), function (Builder $q) {
            $q->whereHas('industry', function (Builder $q) {
                $q->where('name', 'like', '%'.strtolower($this->searchByIndustry).'%');
            });
        });
        $query->when(! empty($this->isFeatured), function (Builder $query) {
            $query->has('activeFeatured');
        });
        $query->withCount([
            'jobs' => function (Builder $q) {
                $q->where('status', '!=', Job::STATUS_DRAFT);
                $q->where('status', '!=', Job::STATUS_CLOSED);
                $q->where('job_expiry_date', '>=', Carbon::now()->toDateString());
            },
        ]);

        $all = $query->paginate($this->perPage);
        if ($all->currentPage() > $all->lastPage()) {
            $this->setPage(max($all->lastPage(), 1));
            $this->page = $this->getPage();
            $all = $query->paginate($this->perPage);
        }

        return $all;
    }
}
