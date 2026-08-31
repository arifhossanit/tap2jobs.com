<?php

namespace App\Http\Controllers\Web;

use App\Models\Job;
use App\Models\Candidate;
use App\Models\JobApplication;
use Illuminate\View\View;
use App\Models\Notification;
use App\Mail\EmailToEmployer;
use App\Models\EmailTemplate;
use App\Models\NotificationSetting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Laracasts\Flash\Flash;
use App\Http\Requests\ApplyJobRequest;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\AppBaseController;
use App\Repositories\JobApplicationRepository;

class JobApplicationController extends AppBaseController
{
    /** @var JobApplicationRepository */
    private $jobApplicationRepository;

    public function __construct(JobApplicationRepository $jobApplicationRepo)
    {
        $this->jobApplicationRepository = $jobApplicationRepo;
    }

    /**
     * @return Factory|View
     */
    public function showApplyJobForm(string $jobId)
    {
        if (auth()->check() && auth()->user()->hasRole('Candidate')) {
            $user = auth()->user();
            $candidate = $user->candidate ?: Candidate::find($user->owner_id);
            if ($candidate) {
                $completionService = app(\App\Services\CandidateProfileCompletionService::class);
                $profileCompletion = $completionService->calculate($candidate);

                if ($profileCompletion['percentage'] < \App\Services\CandidateProfileCompletionService::MINIMUM_APPLICATION_PERCENTAGE) {
                    $job = Job::whereJobId($jobId)->first();
                    $redirectUrl = $job ? route('front.job.details', $job->job_id) : route('front.search.jobs');

                    return redirect($redirectUrl)->with('profile_incomplete', [
                        'percentage' => $profileCompletion['percentage'],
                        'profile_url' => route('candidate.profile'),
                    ]);
                }
            }
        }

        $data = $this->jobApplicationRepository->showApplyJobForm($jobId);

        if (count($data['resumes']) <= 0) {
            return redirect()->back()->with('warning', __('messages.flash.there_are_no'));
        }

        return view('front_web.jobs.apply_job.apply_job')->with($data);
    }

    /**
     * @return mixed
     */
    public function applyJob(ApplyJobRequest $request)
    {
        $input = $request->all();

        if (auth()->check() && auth()->user()->hasRole('Candidate')) {
            $user = auth()->user();
            $candidate = $user->candidate ?: Candidate::find($user->owner_id);
            if ($candidate) {
                $completionService = app(\App\Services\CandidateProfileCompletionService::class);
                $profileCompletion = $completionService->calculate($candidate);

                if ($profileCompletion['percentage'] < \App\Services\CandidateProfileCompletionService::MINIMUM_APPLICATION_PERCENTAGE) {
                    return $this->sendError(
                        __('messages.flash.profile_incomplete_warning', ['percentage' => $profileCompletion['percentage']]),
                        422,
                        [
                            'profile_incomplete' => true,
                            'percentage' => $profileCompletion['percentage'],
                            'profile_url' => route('candidate.profile'),
                        ]
                    );
                }
            }
        }

        $this->jobApplicationRepository->store($input);

        /** @var Job $job */
        $job = Job::with('company.user')->findOrFail($input['job_id']);
        if ($input['application_type'] === 'draft') {
            return $this->sendResponse($job->job_id, __('messages.flash.job_application_draft'));
        }

        $employerId = $job->company->user->id;

        if ((int) NotificationSetting::where('key', 'JOB_APPLICATION_SUBMITTED')->value('value') === 1) {
            addNotification([
                Notification::JOB_APPLICATION_SUBMITTED,
                $employerId,
                Notification::EMPLOYER,
                'Job Application submitted for '.$job->job_title,
                ['job_id' => $job->id],
            ]);
        }

        $candidateUniqueId = Candidate::whereUserId(getLoggedInUserId())->value('unique_id');
        $templateBody = EmailTemplate::whereTemplateName('Candidate Job Applied')->first();
        if ($templateBody && $job->company->user->email) {
            $keyVariable = [
                '{{employer_fullName}}', '{{candidate_name}}', '{{candidate_details_url}}', '{{job_title}}', '{{from_name}}',
            ];
            $value = [
                $job->company->user->full_name, getLoggedInUser()->full_name,
                asset('/candidate-details/'.$candidateUniqueId), $job->job_title, config('app.name'),
            ];
            $data['body'] = str_replace($keyVariable, $value, $templateBody->body);

            $recipientEmail = $job->company->user->email;
            $jobId = $job->id;
            $candidateUserId = getLoggedInUserId();

            dispatch(function () use ($recipientEmail, $data, $jobId, $candidateUserId) {
                try {
                    Mail::to($recipientEmail)->queue(new EmailToEmployer($data));
                } catch (\Throwable $exception) {
                    Log::warning('Job application email could not be sent.', [
                        'job_id' => $jobId,
                        'candidate_user_id' => $candidateUserId,
                        'error' => $exception->getMessage(),
                    ]);
                }
            })->afterResponse();
        }

        return $this->sendResponse($job->job_id, __('messages.flash.job_applied'));
    }

    public function discardDraft(string $jobId)
    {
        $candidate = Candidate::findOrFail(auth()->user()->owner_id);
        $job = Job::whereJobId($jobId)->firstOrFail();

        $draft = JobApplication::where('job_id', $job->id)
            ->where('candidate_id', $candidate->id)
            ->where('status', JobApplication::STATUS_DRAFT)
            ->first();

        if (! $draft) {
            if (request()->expectsJson()) {
                return $this->sendError(__('messages.flash.job_application_draft_not_found'), 404);
            }

            Flash::error(__('messages.flash.job_application_draft_not_found'));

            return redirect()->route('front.job.details', $job->job_id);
        }

        $draft->delete();

        if (request()->expectsJson()) {
            return $this->sendSuccess(__('messages.flash.job_application_delete'));
        }

        Flash::success(__('messages.flash.job_application_delete'));

        return redirect()->route('front.job.details', $job->job_id);
    }
}
