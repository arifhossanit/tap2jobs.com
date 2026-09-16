<?php

namespace App\Http\Controllers\Web;

use Auth;
use Carbon\Carbon;
use App\Models\Job;
use Illuminate\View\View;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\JobRepository;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\EmailJobToFriendRequest;
use Illuminate\Contracts\Foundation\Application;
use App\Models\Skill;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;


class JobController extends AppBaseController
{
    /** @var JobRepository */
    private $jobRepository;

    public function __construct(JobRepository $jobRepo)
    {
        $this->jobRepository = $jobRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request): View
    {
        $data = $this->jobRepository->prepareJobData();
        $data['input'] = $request->all();

        return view('front_web.jobs.index')->with($data);
    }

    /**
     * @return Application|Factory|View
     */
    public function jobDetails(string $uniqueJobId)
    {
        $job = Job::with(['jobsTag', 'company.user', 'jobCategory', 'jobCategories'])->whereJobId($uniqueJobId)->first();
        $skill = Job::with('jobCategory', 'jobCategories', 'jobShift', 'jobsSkill', 'company')->whereJobId($uniqueJobId)
            ->orderByDesc('created_at')->get();
        $valuee = [];
        $counter = 1;
        foreach ($skill as $key => $value) {
            foreach ($value['jobsSkill'] as $keys => $values) {
                $valuee[$counter] = $values->name;
                $counter++;
            }
        }

        $data['skills'] = $valuee;

        if (empty($job)) {
            Flash::error('Job not found');

            return redirect()->back();
        }

        if ($job->status == Job::STATUS_DRAFT && Auth::user()->hasRole('Candidate')) {
            abort(404);
        }

        $data['resumes'] = null;

        $data['isActive'] = $data['isApplied'] = $data['isJobAddedToFavourite'] = $data['isJobReportedAsAbuse'] = false;
        if (Auth::check() && Auth::user()->hasRole('Candidate')) {
            $data = array_merge($data, $this->jobRepository->getJobDetails($job));
            $user = Auth::user();
            $candidate = $user->candidate ?: \App\Models\Candidate::find($user->owner_id);
            if ($candidate) {
                $data['profileCompletion'] = app(\App\Services\CandidateProfileCompletionService::class)->calculate($candidate);
            }
        }
        $data['jobsCount'] = Job::whereStatus(Job::STATUS_OPEN)->whereCompanyId($job->company_id)->whereDate('job_expiry_date',
            '>=',
            Carbon::now()->toDateString())->count();

        // check job status is active or not
        $data['isActive'] = ($job->status == Job::STATUS_OPEN) ? true : false;

        $relatedCategoryIds = $job->selected_job_categories->pluck('id')->filter()->values()->toArray();
        $relatedJobs = Job::with('jobCategory', 'jobCategories', 'jobShift', 'jobsSkill', 'company')
            ->whereStatus(Job::STATUS_OPEN)
            ->whereIsSuspended(Job::NOT_SUSPENDED)
            ->where(function ($query) use ($relatedCategoryIds, $job) {
                $query->whereIn('job_category_id', $relatedCategoryIds ?: [$job->job_category_id])
                    ->orWhereHas('jobCategories', function ($query) use ($relatedCategoryIds, $job) {
                        $query->whereIn('job_categories.id', $relatedCategoryIds ?: [$job->job_category_id]);
                    });
            })
            ->whereDate('job_expiry_date', '>=', Carbon::now()->toDateString());
        $data['getRelatedJobs'] = $relatedJobs->whereNotIn('id', [$job->id])->orderByDesc('created_at')->take(6)->get();
        $shareUrl = url()->current();
        $companyName = trim(implode(' ', array_filter([
            $job->company?->user?->first_name,
            $job->company?->user?->last_name,
        ])));
        $shareTitle = html_entity_decode(strip_tags($job->job_title), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $shareText = $companyName !== '' ? $shareTitle.' - '.$companyName : $shareTitle;
        $location = trim((string) $job->district_thana_location);
        $shareDescription = 'Apply for '.$shareTitle;
        $shareDescription .= $companyName !== '' ? ' at '.$companyName : '';
        $shareDescription .= $location !== '' ? ' in '.$location : '';
        $shareDescription = Str::limit($shareDescription.'. View job requirements, responsibilities and apply online on '.getAppName().'.', 200);
        $shareMessage = $shareText."\n".$shareDescription."\n".$shareUrl;

        $share = [
            'url' => $shareUrl,
            'title' => $shareTitle,
            'description' => $shareDescription,
            'image' => asset(getSettingValue('logo') ?: 'assets/img/article-image.png'),
            'message' => $shareMessage,
        ];
        $url = [
            'facebook' => 'https://www.facebook.com/sharer/sharer.php?'.http_build_query(['u' => $shareUrl], '', '&', PHP_QUERY_RFC3986),
            'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?'.http_build_query(['url' => $shareUrl], '', '&', PHP_QUERY_RFC3986),

            'whatsapp' => 'https://wa.me/?'.http_build_query(['text' => $shareMessage], '', '&', PHP_QUERY_RFC3986),
            'pinterest' => 'https://www.pinterest.com/pin/create/button/?'.http_build_query(['url' => $shareUrl, 'media' => $share['image'], 'description' => $shareDescription], '', '&', PHP_QUERY_RFC3986),
        ];

        return view('front_web.jobs.job_details', compact('job', 'url', 'share'))->with($data);
    }

    public function saveFavouriteJob(Request $request): JsonResponse
    {
        $input = $request->all();
        $favouriteJob = $this->jobRepository->storeFavouriteJobs($input);
        if ($favouriteJob) {
            return $this->sendResponse($favouriteJob, __('messages.flash.fav_job_added'));
        }

        return $this->sendResponse($favouriteJob, __('messages.flash.fav_job_removed'));
    }

    public function reportJobAbuse(Request $request): JsonResponse
    {
        $input = $request->all();
        $this->jobRepository->storeReportJobAbuse($input);

        return $this->sendSuccess(__('messages.flash.job_abuse_reported'));
    }

    public function emailJobToFriend(EmailJobToFriendRequest $request): JsonResponse
    {
        $input = $request->all();
        $this->jobRepository->emailJobToFriend($input);

        return $this->sendSuccess(__('messages.flash.job_emailed_to'));
    }
}
