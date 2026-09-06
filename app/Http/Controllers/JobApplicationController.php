<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobApplicationSchedule;
use App\Models\JobStage;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Repositories\JobApplicationRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Mail\CandidateStatusUpdateMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

/**
 * Class JobApplicationController
 */
class JobApplicationController extends AppBaseController
{
    /** @var JobApplicationRepository */
    private $jobApplicationRepository;

    /**
     * JobApplicationController constructor.
     */
    public function __construct(JobApplicationRepository $jobApplicationRepo)
    {
        $this->jobApplicationRepository = $jobApplicationRepo;
    }

    private function ownedApplication($id): JobApplication
    {
        validator(['application_id' => $id], ['application_id' => 'required|integer'])->validate();
        return JobApplication::whereHas('job', function ($query) {
            $query->where('company_id', Auth::user()->owner_id);
        })->findOrFail($id);
    }

    private function ownedSlot($id): JobApplicationSchedule
    {
        validator(['slot_id' => $id], ['slot_id' => 'required|integer'])->validate();
        return JobApplicationSchedule::whereHas('jobApplication.job', function ($query) {
            $query->where('company_id', Auth::user()->owner_id);
        })->findOrFail($id);
    }

    public function index(int $jobId, Request $request): View
    {
        $userId = Auth::user()->owner_id;
        $companyId = Job::whereCompanyId($userId)->pluck('id')->toArray();

        if (! in_array($jobId, $companyId)) {
            return view('errors.404');
        }

        $input = $request->all();
        $input['job_id'] = $jobId;
        $job = Job::with('city')->findOrFail($jobId);
        $jobStage = JobStage::whereCompanyId(getLoggedInUser()->owner_id)->pluck('name', 'id');
        $statusArray = JobApplication::STATUS;

        return view('employer.job_applications.index', compact('jobId', 'statusArray', 'job', 'jobStage'));
    }

    /**
     * Remove the specified Job Application from storage.
     *
     *
     * @throws Exception
     */
    public function destroy(JobApplication $jobApplication, Request $request): JsonResponse
    {
        $this->ownedApplication($jobApplication->id);
        $jobId = $request->get('jobId');
        $jobCandidateId = JobApplication::whereJobId($jobId)->pluck('id')->toArray();
        if (! in_array($jobApplication->id, $jobCandidateId)) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $this->jobApplicationRepository->delete($jobApplication->id);

        return $this->sendSuccess(__('messages.flash.job_application_delete'));
    }

    /**
     * @return mixed
     */
    public function changeJobApplicationStatus($id, $status, Request $request)
    {
        validator(['status' => $status], ['status' => 'required|integer|in:1,2,3,4'])->validate();
        $this->ownedApplication($id);
        $jobId = $request->get('jobId');

        $jobCandidateId = JobApplication::whereJobId($jobId)->pluck('id')->toArray();

        if (! in_array($id, $jobCandidateId)) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $jobApplication = JobApplication::with(['candidate.user', 'job.company.user'])->findOrFail($id);
        $candidateUser = $jobApplication->candidate->user;
        $candidateUserId = $candidateUser->id;
        $jobTitle = $jobApplication->job->job_title;
        $companyName = $jobApplication->job->company->user->full_name ?? config('app.name');

        if (! in_array($jobApplication->status, [JobApplication::REJECTED, JobApplication::COMPLETE])) {
            $jobApplication->update(['status' => $status]);

            $statusText = null;
            $messageBody = '';

            if ($status == JobApplication::REJECTED) {
                $statusText = 'Rejected';
                $messageBody = "We regret to inform you that your application for \"{$jobTitle}\" has been rejected.";
                if (NotificationSetting::where('key', 'CANDIDATE_REJECTED_FOR_JOB')->first()?->value == 1) {
                    addNotification([
                        Notification::CANDIDATE_REJECTED_FOR_JOB,
                        $candidateUserId,
                        Notification::CANDIDATE,
                        'Your application is Rejected for '.$jobTitle,
                        [
                            'job_application_id' => $jobApplication->id,
                            'job_id' => $jobApplication->job_id,
                        ],
                    ]);
                }
            } elseif ($status == JobApplication::COMPLETE) {
                $statusText = 'Selected / Hired';
                $messageBody = "Congratulations! You have been selected for the position of \"{$jobTitle}\".";
                if (NotificationSetting::where('key', 'CANDIDATE_SELECTED_FOR_JOB')->first()?->value == 1) {
                    addNotification([
                        Notification::CANDIDATE_SELECTED_FOR_JOB,
                        $candidateUserId,
                        Notification::CANDIDATE,
                        'You are selected for '.$jobTitle,
                        [
                            'job_application_id' => $jobApplication->id,
                            'job_id' => $jobApplication->job_id,
                        ],
                    ]);
                }
            } elseif ($status == JobApplication::SHORT_LIST) {
                $statusText = 'Shortlisted';
                $messageBody = "Great news! Your application for \"{$jobTitle}\" has been shortlisted.";
                if (NotificationSetting::where('key', 'CANDIDATE_SHORTLISTED_FOR_JOB')->first()?->value == 1) {
                    addNotification([
                        Notification::CANDIDATE_SHORTLISTED_FOR_JOB,
                        $candidateUserId,
                        Notification::CANDIDATE,
                        'Your application is Shortlisted for '.$jobTitle,
                        [
                            'job_application_id' => $jobApplication->id,
                            'job_id' => $jobApplication->job_id,
                        ],
                    ]);
                }
            }

            // Dispatch Queueable Email to Candidate
            if (! empty($candidateUser->email) && isset($statusText)) {
                try {
                    Mail::to($candidateUser->email)->send(new CandidateStatusUpdateMail([
                        'candidate_name' => $candidateUser->full_name,
                        'job_title' => $jobTitle,
                        'company_name' => $companyName,
                        'status_text' => $statusText,
                        'message_body' => $messageBody,
                        'subject' => "Application {$statusText} for {$jobTitle}",
                    ]));
                } catch (\Exception $e) {
                    \Log::error('Queueable status email error: ' . $e->getMessage());
                }
            }

            return $this->sendSuccess(__('messages.flash.status_change'));
        }

        return $this->sendError(JobApplication::STATUS[$jobApplication->status].' job cannot be '.JobApplication::STATUS[$status]);
    }

    /**
     * @param  JobApplication  $jobApplication
     * @return Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function downloadMedia(Request $request)
    {
        try {
            $jobApplicationId = $request->jobApplication;
            $jobApplication = JobApplication::where('id', $jobApplicationId)->whereHas('job', function ($q) {
                $q->where('company_id', getLoggedInUser()->company->id);
            })->first();
            if ($jobApplication) {
                [$file, $headers] = $this->jobApplicationRepository->downloadMedia($jobApplication);

                return response($file, 200, $headers);
            } else {
                return view('errors.404');
            }
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    public function changeJobStage(Request $request): JsonResponse
    {
        $jobApplication = $this->ownedApplication($request->get('job_application_id'));
        $request->validate(['job_stage' => 'required|integer']);
        $stage = JobStage::whereCompanyId(Auth::user()->owner_id)->findOrFail($request->get('job_stage'));
        $jobApplication->update(['job_stage_id' => $request->get('job_stage')]);


        $stageName = $stage ? $stage->name : 'New Stage';
        $jobTitle = $jobApplication->job->job_title;
        $candidateUser = $jobApplication->candidate->user;
        $companyName = $jobApplication->job->company->user->full_name ?? config('app.name');

        // Add In-App Bell Notification
        addNotification([
            Notification::CANDIDATE_SHORTLISTED_FOR_JOB,
            $candidateUser->id,
            Notification::CANDIDATE,
            "Your application for \"{$jobTitle}\" advanced to stage: {$stageName}",
            [
                'job_application_id' => $jobApplication->id,
                'job_id' => $jobApplication->job_id,
            ],
        ]);

        // Dispatch Queueable Email to Candidate
        if (! empty($candidateUser->email)) {
            try {
                Mail::to($candidateUser->email)->send(new CandidateStatusUpdateMail([
                    'candidate_name' => $candidateUser->full_name,
                    'job_title' => $jobTitle,
                    'company_name' => $companyName,
                    'status_text' => "Advanced to Stage: {$stageName}",
                    'message_body' => "Your application for \"{$jobTitle}\" has advanced to the stage: {$stageName}.",
                    'subject' => "Application Stage Updated for {$jobTitle}",
                ]));
            } catch (\Exception $e) {
                \Log::error('Queueable stage email error: ' . $e->getMessage());
            }
        }

        return $this->sendSuccess(__('messages.flash.job_stage_change'));
    }

    /**
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function viewSlotsScreen(Request $request): View
    {
        try {
            $applicationId = $request->route('jobApplicationId');

            $CustomerJobId = JobApplication::with(['candidate.user', 'job'])->where('id', $applicationId)->whereHas('job', function ($q) {
                $q->where('company_id', getLoggedInUser()->company->id);
            })->first();

            if ($CustomerJobId) {
                $jobApplication = $CustomerJobId;

                /** @var JobStage $jobStage */
                $jobStage = JobStage::whereCompanyId(getLoggedInUser()->owner_id)->pluck('name', 'id');
                $lastStage = JobApplicationSchedule::whereJobApplicationId($applicationId)->latest()->first();

                /** @var JobApplicationSchedule $jobApplicationSchedules */
                $jobApplicationSchedules = JobApplicationSchedule::whereJobApplicationId($applicationId);
                $lastRecord = $jobApplicationSchedules->latest()->first();

                $isStageMatch = false;
                if (! empty($lastRecord)) {
                    $isStageMatch = ! ($lastRecord->stage_id == $jobApplication->job_stage_id);
                } else {
                    $isStageMatch = true;
                }

                $isSelectedRejectedSlot = 1;
                if (isset($lastRecord)) {
                    /** @var JobApplicationSchedule $isSelectedRejectedSlot */
                    $isSelectedRejectedSlot = JobApplicationSchedule::whereJobApplicationId($applicationId)
                        ->whereStageId($lastRecord->stage_id)
                        ->whereBatch($lastRecord->batch)
                        ->whereIn('status',
                            [JobApplicationSchedule::STATUS_SELECTED, JobApplicationSchedule::STATUS_REJECTED])
                        ->count();
                }

                return view('employer.job_applications.view_slot_screen',
                    compact('jobStage', 'lastStage', 'isSelectedRejectedSlot', 'isStageMatch', 'applicationId', 'jobApplication'));
            } else {
                return view('errors.404');
            }
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    public function interviewSlotStore($jobId, Request $request): JsonResponse
    {
        $application = $this->ownedApplication($request->input('job_application_id'));
        $input = $request->validate([
            'date' => 'required|array|min:1|max:100',
            'date.*' => 'required|date',
            'time' => 'required|array|min:1|max:100',
            'time.*' => 'required|date_format:H:i',
            'notes' => 'nullable|array',
            'notes.*' => 'nullable|string|max:5000',
        ]);
        $slots = [];
        foreach ($input['time'] as $index => $time) {
            abort_unless(isset($input['date'][$index]), 422, 'Each slot requires a date.');
            $date = Carbon::parse($input['date'][$index])->toDateString();
            $key = $date.' '.$time;
            if (isset($slots[$key])) {
                return $this->sendError(__('messages.flash.slot_already_taken'));
            }
            $slots[$key] = ['date' => $date, 'time' => $time, 'notes' => $input['notes'][$index] ?? null];
        }

        return DB::transaction(function () use ($application, $slots) {
            JobApplication::whereKey($application->id)->lockForUpdate()->firstOrFail();
            foreach ($slots as $slot) {
                if (JobApplicationSchedule::whereJobApplicationId($application->id)
                    ->whereDate('date', $slot['date'])->where('time', $slot['time'])->exists()) {
                    return $this->sendError(__('messages.flash.slot_already_taken'));
                }
            }
            $last = JobApplicationSchedule::whereJobApplicationId($application->id)->latest()->first();
            $sameStage = $last && $last->stage_id == $application->job_stage_id;
            $batch = $sameStage ? $last->batch + 1 : 1;
            foreach ($slots as $slot) {
                JobApplicationSchedule::create($slot + [
                    'job_application_id' => $application->id,
                    'status' => JobApplicationSchedule::STATUS_NOT_SEND,
                    'batch' => $batch,
                    'stage_id' => $application->job_stage_id,
                ]);
            }
            return $this->sendResponse((bool) ($last && ! $sameStage), __('messages.flash.slot_create'));
        });
    }

    public function batchSlotStore(Request $request): JsonResponse
    {
        $application = $this->ownedApplication($request->input('job_application_id'));
        $input = $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:5000',
            'batch' => 'required|integer|min:1',
        ]);
        $input['date'] = Carbon::parse($input['date'])->toDateString();
        return DB::transaction(function () use ($application, $input) {
            JobApplication::whereKey($application->id)->lockForUpdate()->firstOrFail();
            if (JobApplicationSchedule::whereJobApplicationId($application->id)
                ->whereDate('date', $input['date'])->where('time', $input['time'])->exists()) {
                return $this->sendError(__('messages.flash.slot_already_taken'));
            }
            JobApplicationSchedule::create($input + [
                'job_application_id' => $application->id,
                'status' => JobApplicationSchedule::STATUS_NOT_SEND,
                'stage_id' => $application->job_stage_id,
            ]);
            return $this->sendSuccess(__('messages.flash.slot_create'));
        });
    }

    public function editSlot($jobId, Request $request): JsonResponse
    {
        try {
            $slotId = $request->slot;
            $slot = JobApplicationSchedule::whereHas('jobApplication.job', function ($q) {
                $q->where('company_id', getLoggedInUser()->company->id);
            })->findorFail($slotId);

            if ($slot) {
                return $this->sendResponse($slot, 'Slot retrieved successfully');
            } else {
                return $this->sendError(__('messages.common.seems_message'));
            }
        } catch (\Exception $e) {
            return $this->sendError(__('messages.common.seems_message'));
        }
    }

    public function updateSlot(Request $request, $jobId, JobApplicationSchedule $slot): JsonResponse
    {
        $slot = $this->ownedSlot($slot->id);
        $input = $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:5000',
        ]);
        $input['date'] = Carbon::parse($input['date'])->toDateString();
        return DB::transaction(function () use ($slot, $input) {
            JobApplication::whereKey($slot->job_application_id)->lockForUpdate()->firstOrFail();
            if (JobApplicationSchedule::whereJobApplicationId($slot->job_application_id)
                ->where('id', '!=', $slot->id)->whereDate('date', $input['date'])
                ->where('time', $input['time'])->exists()) {
                return $this->sendError(__('messages.flash.slot_already_taken'));
            }
            $slot->update($input);
            return $this->sendSuccess(__('messages.flash.slot_update'));
        });
    }

    public function slotDestroy($jobId, Request $request): JsonResponse
    {
        try {
            $slotId = $request->slot;
            $slot = JobApplicationSchedule::whereHas('jobApplication.job', function ($q) {
                $q->where('company_id', getLoggedInUser()->company->id);
            })->findorFail($slotId);

            if ($slot) {
                if ($slot->status == 1) {
                    return $this->sendError(__('messages.flash.assigned_slot_not_delete'));
                } else {
                    $slot->delete();

                    return $this->sendSuccess(__('messages.flash.slot_delete'));
                }
            } else {
                return $this->sendError(__('messages.common.seems_message'));
            }
        } catch (\Exception $e) {
            return $this->sendError(__('messages.common.seems_message'));
        }
    }

    public function getScheduleHistory(Request $request): JsonResponse
    {
        $this->ownedApplication($request->get('jobApplicationId'));
        $jobApplicationSchedules = JobApplicationSchedule::with('jobApplication.candidate')
            ->where('job_application_id', $request->get('jobApplicationId'));

        $data = [];
        foreach ($jobApplicationSchedules->get() as $jobApplicationSchedule) {
            $data[] = [
                'notes' => ! empty($jobApplicationSchedule->notes) ? $jobApplicationSchedule->notes : __('messages.job_stage.new_slot_send'),
                'company_name' => getLoggedInUser()->full_name,
                'schedule_date' => Carbon::parse($jobApplicationSchedule->date)->translatedFormat('jS M Y'),
                'schedule_time' => $jobApplicationSchedule->time,
                'status' => $jobApplicationSchedule->status,
                'rejected_slot_notes' => $jobApplicationSchedule->rejected_slot_notes,
                'created_at' => Carbon::parse($jobApplicationSchedule->created_at)->translatedFormat('jS M Y, h:m A'),
            ];
        }
        $rejectedSots = $jobApplicationSchedules->where('status', JobApplicationSchedule::STATUS_REJECTED)->get();
        foreach ($rejectedSots as $rejectSlot) {
            $data['candidate_name'] = $rejectSlot->jobApplication->candidate->user->full_name;
        }

        return $this->sendResponse($data, __('messages.flash.job_schedule_send'));
    }

    public function cancelSelectedSlot(Request $request): JsonResponse
    {
        if (empty($request->get('cancelSlotNote'))) {
            return $this->sendError(__('messages.flash.cancel_reason_require'));
        }

        $request->validate(['cancelSlotNote' => 'required|array|min:1', 'cancelSlotNote.*' => 'required|string|max:5000']);
        $cancelSlotNote = implode(',', $request->get('cancelSlotNote'));

        /** @var JobApplicationSchedule $jobApplicationSchedules */
        $jobApplicationSchedules = $this->ownedSlot($request->get('slotId'));
        $jobApplicationSchedules->update([
            'status' => JobApplicationSchedule::STATUS_REJECTED,
            'employer_cancel_slot_notes' => $cancelSlotNote,
        ]);

        return $this->sendSuccess(__('messages.flash.slot_cancel'));
    }

    /**
     * @param  Request  $request
     * @return Application|Factory|\Illuminate\Contracts\View\View
     *
     * @throws Exception
     */
    public function showAllSelectedCandidate(): View
    {
        $status = [JobApplication::COMPLETE => 'Hired', JobApplication::SHORT_LIST => 'Ongoing'];

        return view('selected_candidate.index', compact('status'));
    }

    /**
     * @param $jobId
     */
    public function checkStage($jobApplicationId): JsonResponse
    {
        $data = [];
        $jobApplication = $this->ownedApplication($jobApplicationId);
        $data['current_stage'] = $jobApplication->job_stage_id;
        $data['current_stage_cleared'] = JobApplicationSchedule::whereJobApplicationId($jobApplication->id)->whereStatus(JobApplicationSchedule::STATUS_SEND)->exists();
        $data['job_stages'] = JobStage::whereCompanyId(getLoggedInUser()->owner_id)->pluck('name', 'id');

        return $this->sendResponse($data, 'Job stages retrieved successfully');
    }
}
