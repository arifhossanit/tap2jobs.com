<?php

namespace App\Livewire;

use App\Models\FavouriteJob;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class FavoriteJobs extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['refreshDatatable' => '$refresh', 'removeJob'];

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

    public function removeJob(int $id)
    {
        $favouriteJob = FavouriteJob::findOrFail($id);
        $favouriteJob->delete();
        $this->dispatch('deleted');
    }

    /**
     * @return View
     */
    public function render()
    {
        $favouriteJobs = $this->getFavouriteJobs();

        return view('livewire.favorite-jobs', compact('favouriteJobs'));
    }

    public function getFavouriteJobs(): LengthAwarePaginator
    {
        $query = FavouriteJob::with(['job.company.user', 'job'])
            ->where('user_id', getLoggedInUserId())
            ->orderByDesc('created_at');

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
