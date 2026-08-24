<?php

namespace App\Livewire;

use App\Models\FavouriteCompany;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Followers extends Component
{
    use WithPagination;

    public $searchByFollowers = '';

    /**
     * @var int
     */
    private $perPage = 6;

    public function paginationView(): string
    {
        return 'livewire.custom-pagenation-jobs';
    }

    public function nextPage($lastPage)
    {
        $currentPage = $this->getPage();
        if ($currentPage < $lastPage) {
            $this->setPage($currentPage + 1);
        }
    }

    public function previousPage($pageName = 'page')
    {
        $currentPage = $this->getPage();
        if ($currentPage > 1) {
            $this->setPage($currentPage - 1);
        }
    }

    public function updatingSearchByFollowers()
    {
        $this->resetPage();
    }

    /**
     * @return Factory|View
     */
    public function render()
    {
        $followers = $this->searchFollowers();

        return view('livewire.followers', compact('followers'));
    }

    public function searchFollowers(): LengthAwarePaginator
    {
        $query = FavouriteCompany::with(['user'])->where('company_id',
            getLoggedInUser()->owner_id)->orderBy('created_at', 'desc');

        if (! empty($this->searchByFollowers)) {
            $query->whereHas('user', function (Builder $query) {
                $query->where('first_name', 'like', '%'.strtolower($this->searchByFollowers).'%');
                $query->orWhere('email', 'like', '%'.strtolower($this->searchByFollowers).'%');
                $query->orWhere('phone', 'like', '%'.strtolower($this->searchByFollowers).'%');
            });
        }

        $all = $query->paginate($this->perPage);
        $currentPage = $all->currentPage();
        $lastPage = $all->lastPage();
        if ($currentPage > $lastPage && $lastPage > 0) {
            $this->setPage($lastPage);
            $all = $query->paginate($this->perPage);
        }

        return $all;
    }
}
