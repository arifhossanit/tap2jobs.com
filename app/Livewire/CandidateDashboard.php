<?php

namespace App\Livewire;

use App\Models\Candidate;
use App\Models\JobApplication;
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
    public $applicationStats;

    public function mount()
    {
        $this->user = Auth::user();
        $this->candidate = $this->user->candidate ?: Candidate::find($this->user->owner_id);
        $this->resumes = $this->candidate
            ? $this->candidate->getMedia(Candidate::RESUME_PATH)->count()
            : 0;
        $this->followings = $this->user->followings()->count();
        $this->applicationStats = $this->candidate
            ? [
                'applied' => JobApplication::where('candidate_id', $this->candidate->id)
                    ->where('status', JobApplication::STATUS_APPLIED)
                    ->count(),
                'ongoing' => JobApplication::where('candidate_id', $this->candidate->id)
                    ->where('status', JobApplication::SHORT_LIST)
                    ->count(),
                'hired' => JobApplication::where('candidate_id', $this->candidate->id)
                    ->where('status', JobApplication::COMPLETE)
                    ->count(),
                'drafts' => JobApplication::where('candidate_id', $this->candidate->id)
                    ->where('status', JobApplication::STATUS_DRAFT)
                    ->count(),
            ]
            : ['applied' => 0, 'ongoing' => 0, 'hired' => 0, 'drafts' => 0];
        $this->matchingJobs = $this->candidate
            ? app(CandidateJobMatchService::class)->topMatches($this->candidate)
            : collect();
        $this->profileCompletion = $this->candidate
            ? app(CandidateProfileCompletionService::class)->calculate($this->candidate)
            : ['percentage' => 0, 'completed' => 0, 'total' => 11, 'color' => '#f04438'];
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
