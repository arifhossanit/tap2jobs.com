<?php

namespace App\Repositories;

use App\Models\Candidate;
use App\Models\Company;
use App\Models\FavouriteCompany;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class DashboardRepository
 *
 * @version July 7, 2020, 5:07 am UTC
 */
class DashboardRepository
{
    /**
     * @return mixed
     */
    public function getDashboardAssociatedData()
    {
        $data['totalUsers'] = User::count();
        $data['totalCandidates'] = User::whereOwnerType(Candidate::class)->whereIsActive(User::ACTIVE)->count();
        $data['totalEmployers'] = User::whereOwnerType(Company::class)->whereIsActive(User::ACTIVE)->count();
        $data['totalActiveJobs'] = Job::whereDate('job_expiry_date', '>=', Carbon::now())->whereStatus(Job::STATUS_OPEN)->where('is_suspended', Job::NOT_SUSPENDED)->count();
        $data['totalVerifiedUsers'] = User::where('is_verified', true)->count();
        $data['todayJobs'] = Job::whereDate('created_at', Carbon::today())->count();
        $data['totalBlogs'] = Post::count();
        $data['newJobs'] = Job::where('created_at', '>=', Carbon::today()->subDays(6))->count();
        $data['pendingJobs'] = Job::where('status', Job::SELECT_PANDING)->count();
        $data['expiringJobs'] = Job::whereDate('job_expiry_date', '>=', Carbon::today())
            ->whereDate('job_expiry_date', '<=', Carbon::today()->addDays(7))
            ->where('status', Job::STATUS_OPEN)
            ->where('is_suspended', Job::NOT_SUSPENDED)
            ->count();
        $data['weeklyUsers'] = User::whereIn('owner_type', [Candidate::class, Company::class])
            ->where('is_active', User::ACTIVE)
            ->where('created_at', '>=', Carbon::today()->subDays(6))
            ->count();

        return $data;
    }
    public function getWeeklyChartData(array $input): array
    {
        try {
            $startDate = Carbon::parse($input['start_date'])->startOfDay();
            $endDate = Carbon::parse($input['end_date'])->endOfDay();

            $employers = Company::query()
                ->whereHas('user', function (Builder $query) {
                    $query->where('is_active', User::ACTIVE);
                })
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->groupBy('date')
                ->pluck('total', 'date');

            $candidates = Candidate::query()
                ->whereHas('user', function (Builder $query) {
                    $query->where('is_active', User::ACTIVE);
                })
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->groupBy('date')
                ->pluck('total', 'date');

            $data = [
                'totalEmployerCount' => [],
                'totalCandidateCount' => [],
                'weeklyLabels' => [],
            ];

            foreach (CarbonPeriod::create($startDate->copy()->startOfDay(), $endDate->copy()->startOfDay()) as $date) {
                $dateKey = $date->format('Y-m-d');
                $data['totalEmployerCount'][] = (int) ($employers[$dateKey] ?? 0);
                $data['totalCandidateCount'][] = (int) ($candidates[$dateKey] ?? 0);
                $data['weeklyLabels'][] = $date->format('d-m-y');
            }

            return $data;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
    public function getPostStatisticsChartData(array $input): array
    {
        try {
            $startDate = Carbon::parse($input['start_date'])->startOfDay();
            $endDate = Carbon::parse($input['end_date'])->endOfDay();

            $posts = Post::query()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->groupBy('date')
                ->pluck('total', 'date');

            $data = [
                'totalPostCount' => [],
                'weeklyPostLabels' => [],
            ];

            foreach (CarbonPeriod::create($startDate->copy()->startOfDay(), $endDate->copy()->startOfDay()) as $date) {
                $dateKey = $date->format('Y-m-d');
                $data['totalPostCount'][] = (int) ($posts[$dateKey] ?? 0);
                $data['weeklyPostLabels'][] = $date->format('d-m-y');
            }

            return $data;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
    /**
     * @return mixed
     */
    public function getRegisteredCandidatesData()
    {
        return Candidate::with('user')->whereHas('user', function ($q) {
            $q->where('is_active', '=', 1);
        })->orderByDesc('created_at')->limit(5)->get();
    }

    /**
     * @return mixed
     */
    public function getRegisteredEmployersData()
    {
        return Company::with(['user', 'activeFeatured'])->whereHas('user', function ($q) {
            $q->where('is_active', '=', 1);
        })->orderByDesc('created_at')->limit(5)->get();
    }

    /**
     * @return mixed
     */
    public function getRecentJobsData()
    {
        return Job::with(['company.user', 'jobCategory', 'jobType', 'jobShift', 'activeFeatured'])->orderBy('created_at',
            'desc')->limit(5)->get();
    }

    /**
     * @return mixed
     */
    public function getEmployerDashboardData()
    {
        $user = Auth::user();
        $jobIds = Job::whereCompanyId($user->owner_id)->pluck('id');
        $data['jobApplicationsCount'] = JobApplication::whereIn('job_id', $jobIds)->count();
        $data['totalJobs'] = count($jobIds);
        $data['pausedJobCount'] = Job::whereCompanyId($user->owner_id)->where('status', Job::STATUS_PAUSED)->count();
        $data['closedJobCount'] = Job::whereCompanyId($user->owner_id)->where('status', Job::STATUS_CLOSED)->count();
        $data['jobCount'] = Job::whereCompanyId($user->owner_id)->where('status',
            Job::STATUS_OPEN)->whereDate('job_expiry_date', '>=', Carbon::now()->toDateString())->count();
        $data['followersCount'] = FavouriteCompany::whereCompanyId($user->owner_id)->count();

        return $data;
    }

    /**
     * @return Job[]|Builder[]|Collection
     */
    public function getEmployerRecentJobsData()
    {
        $user = Auth::user();
        $jobs = Job::whereCompanyId($user->owner_id)->orderByDesc('created_at')->limit(5)->get();

        return $jobs;
    }

    /**
     * @return Builder[]|Collection
     */
    public function getEmployerRecentFollowerData()
    {
        $user = Auth::user();
        $followers = FavouriteCompany::with('user')->where('company_id',
            $user->owner_id)->orderByDesc('created_at')->limit(5)->get();

        return $followers;
    }

    /**
     * @throws Exception
     */
    public function getDate(string $startDate, string $endDate): array
    {
        $dateArr = [];
        $subStartDate = '';
        $subEndDate = '';
        if (! ($startDate && $endDate)) {
            $data = [
                'dateArr' => $dateArr,
                'startDate' => $subStartDate,
                'endDate' => $subEndDate,
            ];

            return $data;
        }
        $end = trim(substr($endDate, 0, 10));
        $start = Carbon::parse($startDate)->toDateString();
        /** @var \Illuminate\Support\Carbon $startDate */
        $startDate = Carbon::createFromFormat('Y-m-d', $start);
        /** @var \Illuminate\Support\Carbon $endDate */
        $endDate = Carbon::createFromFormat('Y-m-d', $end);

        while ($startDate <= $endDate) {
            $dateArr[] = $startDate->copy()->format('Y-m-d');
            $startDate->addDay();
        }
        $start = current($dateArr);
        $endDate = end($dateArr);
        $subStartDate = Carbon::parse($start)->startOfDay()->format('Y-m-d H:i:s');
        $subEndDate = Carbon::parse($endDate)->endOfDay()->format('Y-m-d H:i:s');

        $data = [
            'dateArr' => $dateArr,
            'startDate' => $subStartDate,
            'endDate' => $subEndDate,
        ];

        return $data;
    }

    /**
     * @return mixed
     */
    public function getEmployerDashboardChartData(array $input = [])
    {
        $dateS = Carbon::parse($input['start_date']);
        $dateE = Carbon::parse($input['end_date']);
        $jobTitleId = $input['job_status'];
        $gender = $input['gender'];
        $user = getLoggedInUser();
        $jobIds = Job::where('company_id', $user->owner_id)->when($jobTitleId, function (Builder $query) use ($jobTitleId) {
            $query->where('id', $jobTitleId);
        })->pluck('id');

        $jobApplications = JobApplication::when($gender != '', function (Builder $query) use ($gender) {
            $query->whereHas('candidate.user', function (Builder $query) use ($gender) {
                $query->where('gender', '=', $gender);
            });
        })->whereIn('job_id', $jobIds)->whereBetween('created_at', [
            $dateS->copy()->startOfDay(),
            $dateE->copy()->endOfDay(),
        ])
            ->groupBy('date')
            ->orderBy('date')
            ->get([
                DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date'),
                DB::raw('count(*) as total'),
            ])
            ->keyBy('date')
            ->map(function ($item) {
                $item->date = Carbon::parse($item->date);

                return $item;
            });
        $period = CarbonPeriod::create($dateS, $dateE);

        // get all date labels
        $labelsData = array_map(function ($datePeriod) {
            return $datePeriod->format('M d');
        }, iterator_to_array($period));

        // get all job Application in date period
        $jobApplicationData = array_map(function ($datePeriod) use ($jobApplications) {
            $date = $datePeriod->format('Y-m-d');

            return $jobApplications->has($date) ? $jobApplications->get($date)->total : 0;
        }, iterator_to_array($period));

        $data['jobApplicationCounts'] = $jobApplicationData;
        $data['totalJobApplication'] = array_sum($jobApplicationData);
        $data['dateLabels'] = $labelsData;

        return $data;
    }
}
