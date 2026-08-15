<?php

namespace App\Livewire;

use App\Models\Candidate;
use App\Services\CandidateJobMatchService;
use App\Services\CandidateProfileCompletionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CandidateDashboard extends Component
{
    public $user;
    public $candidate;
    public $resumes;
    public $followings;
    public $matchingJobs;
    public $profileCompletion;

    public function mount()
    {
        $this->user = Auth::user();
        $this->candidate = $this->user->candidate ?: Candidate::find($this->user->owner_id);
        $this->resumes = $this->candidate
            ? $this->candidate->getMedia(Candidate::RESUME_PATH)->count()
            : 0;
        $this->followings = $this->user->followings()->count();
        $this->matchingJobs = $this->candidate
            ? app(CandidateJobMatchService::class)->topMatches($this->candidate)
            : collect();
        $this->profileCompletion = $this->candidate
            ? app(CandidateProfileCompletionService::class)->calculate($this->candidate)
            : ['percentage' => 0, 'completed' => 0, 'total' => 10, 'color' => '#f04438'];
    }

    public function placeholder()
    {
        return view('livewire_lazy_load.candidate_dashboard');
    }

    public function render()
    {
        return view('livewire.candidate-dashboard');
    }
}
