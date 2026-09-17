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
use Intervention\Image\ImageManagerStatic as InterventionImage;


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
    public function jobDetails(string $slug)
    {
        $job = Job::with([
            'jobsTag', 'jobsSkill', 'company.user', 'jobCategory', 'jobCategories', 'degreeLevel', 'degreeTitle',
            'jobType', 'currency', 'salaryPeriod', 'country', 'state', 'city', 'thana',
            'locations.country', 'locations.state', 'locations.city', 'locations.thana',
        ])->where('slug', $slug)->first();

        if (! $job) {
            $legacyJob = Job::whereJobId($slug)->first();
            if ($legacyJob) {
                return redirect()->to($legacyJob->front_url, 301);
            }
        }

        $skill = Job::with('jobCategory', 'jobCategories', 'jobShift', 'jobsSkill', 'company')->where('slug', $slug)
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

        if ($job->status == Job::STATUS_DRAFT && (! Auth::check() || Auth::user()->hasRole('Candidate'))) {
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
        $data['isJobExpired'] = $job->isExpired();
        $data['isJobApplyable'] = $job->isApplyable();
        $data['shouldIndexJob'] = $job->isApplyable();

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
        $shareUrl = $job->front_url;
        $companyName = trim(implode(' ', array_filter([
            $job->company?->user?->first_name,
            $job->company?->user?->last_name,
        ])));
        $shareTitle = html_entity_decode(strip_tags($job->job_title), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $shareText = $companyName !== '' ? $shareTitle.' - '.$companyName : $shareTitle;
        $education = collect([$job->degreeLevel?->name, $job->degreeTitle?->name])->filter()->implode(', ');
        $shareDescription = implode(' | ', array_filter([
            $companyName !== '' ? 'Company: '.$companyName : null,
            $job->district_thana_location ? 'Location: '.$job->district_thana_location : null,
            $education !== '' ? 'Education: '.$education : null,
            $job->formatted_experience ? 'Experience: '.$job->formatted_experience : null,
            $job->job_expiry_date ? 'Deadline: '.$job->job_expiry_date->format('d M Y') : null,
        ]));
        $shareMessage = $shareText."\n".$shareDescription."\n".$shareUrl;
        $shareImage = $this->ensureOgImage($job);

        $data['metaKeywords'] = collect([$shareTitle, $companyName, $job->district_thana_location])
            ->merge($job->selected_job_categories->pluck('name'))
            ->merge($job->selected_job_categories->flatMap(fn ($category) => $category->search_tags ?? []))
            ->merge($job->jobsTag->pluck('name'))
            ->merge($job->jobsSkill->pluck('name'))
            ->map(fn ($keyword) => trim(strip_tags(html_entity_decode((string) $keyword, ENT_QUOTES | ENT_HTML5, 'UTF-8'))))
            ->filter()
            ->unique(fn ($keyword) => mb_strtolower($keyword))
            ->values()
            ->implode(', ');

        $share = [
            'url' => $shareUrl,
            'title' => $shareTitle,
            'description' => $shareDescription,
            'image' => asset('uploads/og-images/'.basename($shareImage)),
            'message' => $shareMessage,
        ];
        $data['jobPostingSchema'] = $data['shouldIndexJob']
            ? $this->buildJobPostingSchema($job, $shareUrl, $shareDescription)
            : null;
        $breadcrumbCategory = $job->selected_job_categories
            ->first(fn ($category) => (int) $category->status === \App\Models\JobCategory::STATUS_ACTIVE && filled($category->slug));
        $data['breadcrumbCategory'] = $breadcrumbCategory;
        $data['jobBreadcrumbSchema'] = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_values(array_filter([
                ['@type' => 'ListItem', 'position' => 1, 'name' => __('web.home'), 'item' => route('front.home')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => __('web.jobs'), 'item' => route('front.search.jobs')],
                $breadcrumbCategory ? [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => html_entity_decode(strip_tags($breadcrumbCategory->name), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                    'item' => route('front.job-categories.show', $breadcrumbCategory),
                ] : null,
                [
                    '@type' => 'ListItem',
                    'position' => $breadcrumbCategory ? 4 : 3,
                    'name' => $shareTitle,
                    'item' => $shareUrl,
                ],
            ])),
        ];
        $url = [
            'facebook' => 'https://www.facebook.com/sharer/sharer.php?'.http_build_query(['u' => $shareUrl], '', '&', PHP_QUERY_RFC3986),
            'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?'.http_build_query(['url' => $shareUrl], '', '&', PHP_QUERY_RFC3986),

            'whatsapp' => 'https://wa.me/?'.http_build_query(['text' => $shareMessage], '', '&', PHP_QUERY_RFC3986),
            'gmail' => 'https://mail.google.com/mail/?'.http_build_query([
                'view' => 'cm',
                'fs' => '1',
                'su' => 'Job Opportunity: '.$shareTitle,
                'body' => $shareMessage,
            ], '', '&', PHP_QUERY_RFC3986),

        ];

        return view('front_web.jobs.job_details', compact('job', 'url', 'share'))->with($data);
    }

    private function buildJobPostingSchema(Job $job, string $url, string $fallbackDescription): array
    {
        $companyName = trim((string) ($job->company?->company_name ?: $job->company?->user?->full_name));
        $descriptionParts = array_filter([
            $job->description,
            $job->key_responsibilities,
            $job->compensation_and_other_benefits,
        ]);
        $description = preg_replace(
            '/\s+/u',
            ' ',
            trim(strip_tags(html_entity_decode(implode(' ', $descriptionParts), ENT_QUOTES | ENT_HTML5, 'UTF-8')))
        );

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'JobPosting',
            'title' => html_entity_decode(strip_tags($job->job_title), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            'description' => $description ?: $fallbackDescription,
            'identifier' => [
                '@type' => 'PropertyValue',
                'name' => $companyName ?: getAppName(),
                'value' => $job->job_id,
            ],
            'datePosted' => $job->created_at?->toAtomString(),
            'validThrough' => $job->job_expiry_date?->copy()->endOfDay()->toAtomString(),
            'url' => $url,
            'hiringOrganization' => array_filter([
                '@type' => 'Organization',
                'name' => $companyName ?: getAppName(),
                'sameAs' => $job->company?->website ?: ($job->company?->unique_id
                    ? route('front.company.details', $job->company->slug)
                    : null),
                'logo' => $job->company?->company_url,
            ]),
        ];

        $employmentType = $this->schemaEmploymentType($job);
        if ($employmentType) {
            $schema['employmentType'] = $employmentType;
        }

        $isFullyRemote = $job->work_from_home && ! $job->work_from_office && ! $job->hybrid;
        if ($isFullyRemote) {
            $schema['jobLocationType'] = 'TELECOMMUTE';
            $countryCode = strtoupper((string) ($job->country?->short_code ?: 'BD'));
            $schema['applicantLocationRequirements'] = [
                '@type' => 'Country',
                'name' => $countryCode,
            ];
        } else {
            $locations = $job->locations->map(function ($location) {
                return [
                    '@type' => 'Place',
                    'address' => array_filter([
                        '@type' => 'PostalAddress',
                        'streetAddress' => $location->address ?: $location->city_village_name,
                        'addressLocality' => $location->city?->name,
                        'addressRegion' => $location->state?->name,
                        'addressCountry' => strtoupper((string) $location->country?->short_code),
                    ]),
                ];
            })->values()->all();

            if (empty($locations)) {
                $locations[] = [
                    '@type' => 'Place',
                    'address' => array_filter([
                        '@type' => 'PostalAddress',
                        'streetAddress' => $job->address ?: $job->city_village_name,
                        'addressLocality' => $job->city?->name,
                        'addressRegion' => $job->state?->name,
                        'addressCountry' => strtoupper((string) $job->country?->short_code),
                    ]),
                ];
            }

            $schema['jobLocation'] = count($locations) === 1 ? $locations[0] : $locations;
        }

        if (! $job->hide_salary && ($job->salary_from > 0 || $job->salary_to > 0) && $job->currency?->currency_code) {
            $salaryValue = ['@type' => 'QuantitativeValue'];
            if ($job->salary_from > 0) {
                $salaryValue['minValue'] = $job->salary_from;
            }
            if ($job->salary_to > 0) {
                $salaryValue['maxValue'] = $job->salary_to;
            }
            $salaryValue['unitText'] = $this->schemaSalaryUnit($job->salaryPeriod?->period);
            $schema['baseSalary'] = [
                '@type' => 'MonetaryAmount',
                'currency' => strtoupper($job->currency->currency_code),
                'value' => $salaryValue,
            ];
        }

        return array_filter($schema, fn ($value) => $value !== null && $value !== '');
    }

    private function schemaEmploymentType(Job $job): ?string
    {
        $status = strtolower((string) $job->employment_status);
        $type = strtolower((string) $job->jobType?->name);
        $value = $status.' '.$type;

        return match (true) {
            str_contains($value, 'part') => 'PART_TIME',
            str_contains($value, 'intern') => 'INTERN',
            str_contains($value, 'freelance') => 'CONTRACTOR',
            str_contains($value, 'contract'), str_contains($value, 'project') => 'CONTRACTOR',
            str_contains($value, 'temporary') => 'TEMPORARY',
            str_contains($value, 'full'), str_contains($value, 'permanent') => 'FULL_TIME',
            default => null,
        };
    }

    private function schemaSalaryUnit(?string $period): string
    {
        $period = strtolower((string) $period);

        return match (true) {
            str_contains($period, 'hour') => 'HOUR',
            str_contains($period, 'day') => 'DAY',
            str_contains($period, 'week') => 'WEEK',
            str_contains($period, 'year'), str_contains($period, 'annual') => 'YEAR',
            default => 'MONTH',
        };
    }

    public function jobOgImage(string $uniqueJobId)
    {
        $job = Job::with(['company.user', 'degreeLevel', 'degreeTitle'])
            ->whereJobId($uniqueJobId)
            ->firstOrFail();

        $imagePath = $this->ensureOgImage($job);

        return response()->file($imagePath, [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    private function ensureOgImage(Job $job): string
    {
        $directory = public_path('uploads/og-images');
        $filename = 'job-'.$job->job_id.'-'.($job->updated_at?->timestamp ?: $job->created_at->timestamp).'.jpg';
        $imagePath = $directory.DIRECTORY_SEPARATOR.$filename;

        if (is_file($imagePath)) {
            return $imagePath;
        }

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $companyName = trim(implode(' ', array_filter([
            $job->company?->user?->first_name,
            $job->company?->user?->last_name,
        ])));
        $title = html_entity_decode(strip_tags($job->job_title), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $location = $job->district_thana_location ?: 'Location not specified';
        $deadline = $job->job_expiry_date ? $job->job_expiry_date->format('d M Y') : 'Not specified';
        $fontPath = public_path('fonts/Poppins-Regular.ttf');
        $boldFontPath = public_path('fonts/Poppins-Bold.ttf');

        $image = InterventionImage::canvas(1200, 630, '#f5fbf8');
        $image->rectangle(0, 0, 1200, 630, function ($draw) {
            $draw->background('#209776');
        });
        $image->rectangle(21, 21, 1179, 609, function ($draw) {
            $draw->background('#ffffff');
        });
        $image->text('TAP2JOBS', 65, 85, function ($font) use ($boldFontPath) {
            $font->file($boldFontPath);
            $font->size(28);
            $font->color('#209776');
        });
        $image->text('JOB OPPORTUNITY', 65, 135, function ($font) use ($boldFontPath) {
            $font->file($boldFontPath);
            $font->size(22);
            $font->color('#6b7280');
        });

        $titleLines = $this->wrapOgText($title, 34);
        foreach ($titleLines as $index => $line) {
            $image->text($line, 65, 205 + ($index * 52), function ($font) use ($boldFontPath) {
                $font->file($boldFontPath);
                $font->size(38);
                $font->color('#172b24');
            });
        }

        $details = array_filter([
            $companyName !== '' ? 'Company: '.$companyName : null,
            'Location: '.$location,
            $job->formatted_experience ? 'Experience: '.$job->formatted_experience : null,
            'Deadline: '.$deadline,
        ]);
        foreach ($details as $index => $detail) {
            $image->text($detail, 65, 385 + ($index * 35), function ($font) use ($fontPath) {
                $font->file($fontPath);
                $font->size(24);
                $font->color('#4b5563');
            });
        }

        $temporaryPath = $imagePath.'.'.uniqid('', true).'.tmp';
        $image->save($temporaryPath, 90, 'jpg');
        rename($temporaryPath, $imagePath);

        return $imagePath;
    }

    private function wrapOgText(string $text, int $length): array
    {
        $words = preg_split('/\s+/', trim($text));
        $lines = [];
        $line = '';

        foreach ($words as $word) {
            $candidate = trim($line.' '.$word);
            if ($line !== '' && mb_strlen($candidate) > $length) {
                $lines[] = $line;
                $line = $word;
            } else {
                $line = $candidate;
            }
        }

        if ($line !== '') {
            $lines[] = $line;
        }

        return array_slice($lines, 0, 3);
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
