<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use App\Repositories\JobNotificationRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laracasts\Flash\Flash;

class JobNotificationController extends AppBaseController
{
    /**
     * @var JobNotificationRepository
     */
    private $jobNotificationRepository;

    public function __construct(JobNotificationRepository $jobNotificationRepository)
    {
        $this->jobNotificationRepository = $jobNotificationRepository;
    }

    /**
     * @param  Request  $request
     * @return Application|Factory|JsonResponse|View
     */
    public function index(): View
    {
        $data = $this->jobNotificationRepository->getJobNotificationData();

        return view('job_notification.index')->with($data);
    }

    public function store(Request $request): JsonResponse
    {
        $input = $request->validate([
            'candidate_id' => ['required', 'array', 'min:1'],
            'candidate_id.*' => ['required', 'integer', 'exists:candidates,id'],
            'job_id' => ['required', 'array', 'min:1'],
            'job_id.*' => ['required', 'integer', 'exists:jobs,id'],
        ]);

        $this->jobNotificationRepository->sendJobNotification($input);

        return $this->sendSuccess(__('messages.flash.job_notification'));
    }

    public function getEmployerJobs(Request $request, $id = null): JsonResponse
    {
        if (! empty($id)) {
            // Need to paginate the relation or fetch from Job model directly
            $employerJobs = Job::where('company_id', $id)
                ->whereDate('job_expiry_date', '>=', Carbon::now()->toDateString())
                ->where('status', '=', '1')
                ->where('is_suspended', Job::NOT_SUSPENDED)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $employerJobs = Job::whereDate('job_expiry_date', '>=', Carbon::now()->toDateString())
                ->where('status', '1')
                ->where('is_suspended', Job::NOT_SUSPENDED)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        $html = view('job_notification.job_list', ['jobs' => $employerJobs])->render();

        return $this->sendResponse(['html' => $html], 'Employer jobs retrieved successfully.');
    }
}

