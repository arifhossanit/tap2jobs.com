<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Job;
use App\Models\State;
use App\Models\Company;
use App\Models\JobType;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\View\View;
use Laracasts\Flash\Flash;
use App\Models\CmsServices;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\View\Factory;
use App\Repositories\WebHomeRepository;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\ContactFormRequest;
use App\Http\Controllers\AppBaseController;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;

class HomeController extends AppBaseController
{
    /** @var WebHomeRepository */
    private $homeRepository;

    public function __construct(WebHomeRepository $homeRepository)
    {
        $this->homeRepository = $homeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index(): View
    {
        $openJobs = static function ($query) {
            return $query->whereStatus(Job::STATUS_OPEN)
                ->whereIsSuspended(Job::NOT_SUSPENDED)
                ->whereDate('job_expiry_date', '>=', Carbon::tomorrow()->toDateString());
        };

        $data['testimonials'] = $this->homeRepository->getTestimonials();
        $data['dataCounts'] = $this->homeRepository->getDataCounts();
        $data['latestJobs'] = $this->homeRepository->getLatestJobs()->take(4);
        $data['stateJobCounts'] = State::query()
            ->whereHas('country', function ($q) {
                $q->where('short_code', 'BD');
            })
            ->withCount(['jobs' => $openJobs])
            ->orderBy('name')
            ->get();
        $data['quickJobTypes'] = JobType::query()
            ->withCount(['jobs' => $openJobs])
            ->having('jobs_count', '>', 0)
            ->orderByDesc('jobs_count')
            ->orderBy('name')
            ->take(4)
            ->get();

        $internshipType = JobType::where('name', 'like', '%Intern%')->first();
        $contractualType = JobType::where('name', 'like', '%Contract%')->first();
        $partTimeType = JobType::where('name', 'like', '%Part%Time%')->first();

        $data['quickJobTypeIds'] = [
            'internship' => $internshipType ? $internshipType->id : '',
            'contractual' => $contractualType ? $contractualType->id : '',
            'part_time' => $partTimeType ? $partTimeType->id : '',
        ];

        $data['quickLinkCounts'] = [
            'employer_list' => Company::whereHas('user', function ($q) {
                $q->where('is_active', 1);
            })->count(),
            'new_jobs' => $openJobs(Job::query())
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->count(),
            'deadline_tomorrow' => $openJobs(Job::query())
                ->whereDate('job_expiry_date', Carbon::tomorrow()->toDateString())
                ->count(),
            'internship' => $openJobs(Job::query())
                ->whereHas('jobType', function ($q) {
                    $q->where('name', 'like', '%Intern%');
                })->count(),
            'contractual' => $openJobs(Job::query())
                ->whereHas('jobType', function ($q) {
                    $q->where('name', 'like', '%Contract%');
                })->count(),
            'part_time' => $openJobs(Job::query())
                ->whereHas('jobType', function ($q) {
                    $q->where('name', 'like', '%Part%Time%');
                })->count(),
            'overseas' => $openJobs(Job::query())
                ->where(function ($q) {
                    $q->whereNull('country_id')
                        ->orWhereHas('country', function ($cq) {
                            $cq->where('short_code', '!=', 'BD');
                        });
                })->count(),
            'work_from_home' => $openJobs(Job::query())
                ->where(function ($q) {
                    $q->where('is_freelance', 1)
                        ->orWhere('job_title', 'like', '%Remote%')
                        ->orWhere('job_title', 'like', '%Work from Home%')
                        ->orWhereHas('jobType', function ($jq) {
                            $jq->where('name', 'like', '%Freelance%');
                        });
                })->count(),
            'fresher_jobs' => $openJobs(Job::query())
                ->where(function ($q) {
                    $q->where('freshers_encouraged', 1)
                        ->orWhere('experience', 0);
                })->count(),
        ];

        $data['categories'] = $this->homeRepository->getCategories();
        $data['jobCategories'] = $this->homeRepository->getAllJobCategories();
        $data['jobTypes'] = $this->homeRepository->getAllJobTypes();
        $data['featuredCompanies'] = $this->homeRepository->getFeaturedCompanies();
        $data['allCompanies'] = $this->homeRepository->getAllCompanies();
        $data['featuredJobs'] = $this->homeRepository->getFeaturedJobs();
        $data['notices'] = $this->homeRepository->getNotices();
        [$data['imageSliders'], $data['settings'], $data['slider'], $data['imageSliderActive'], $data['headerSliders']] = $this->homeRepository->getImageSlider();
        $data['latestJobsEnable'] = $this->homeRepository->getLatestJobsEnable();
        $data['plans'] = $this->homeRepository->getPlans();
        $data['plansArray'] = array_chunk($data['plans']->toArray(), 3);
        $data['branding'] = $this->homeRepository->getBranding();
        $data['recentBlog'] = $this->homeRepository->getRecentBlog();
        $data['cmsServices'] = CmsServices::pluck('value', 'key')->toArray();
        $data['color'] = Setting::COLOR;

        return view('front_web.home.home')->with($data);
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function sendContactEmail(ContactFormRequest $request): RedirectResponse
    {
        $this->homeRepository->storeInquires($request->validated());
        Flash::success(__('messages.flash.thank_you_for_contacting_us'));

        return redirect(route('front.contact'));
    }

    public function changeLanguage(Request $request): JsonResponse
    {
        $request->validate([
            'languageName' => 'required|in:en,bn',
        ]);

        $language = $request->input('languageName');

        Session::put('languageName', $language);

        /** @var User $user */
        $user = getLoggedInUser();
        if(! empty($user)){
            $user->update(['language' => $language]);
        }
        return $this->sendSuccess(__('messages.flash.language_changed'));
    }

    /**
     * @return array|string
     *
     * @throws Throwable
     */
    public function getJobsSearch(Request $request)
    {
        $searchTerm = strtolower($request->get('searchTerm'));

        $results = $this->homeRepository->jobSearch($searchTerm);

        return response()->json(['results' => $results]);
    }
}
