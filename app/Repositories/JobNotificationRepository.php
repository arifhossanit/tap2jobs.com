<?php

namespace App\Repositories;

use App\Mail\JobNotification;
use App\Models\Candidate;
use App\Models\EmailTemplate;
use App\Models\Job;
use Arr;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class JobNotificationRepository
 */
class JobNotificationRepository
{
    /**
     * @return mixed
     */
    public function getJobNotificationData()
    {
        $data['candidates'] = \DB::table('users')
            ->join('candidates', 'candidates.user_id', '=', 'users.id')
            ->where('users.is_active', true)
            ->select('candidates.id', \DB::raw("CONCAT(users.first_name, ' ', IFNULL(users.last_name, '')) as full_name"))
            ->pluck('full_name', 'id');

        $now = Carbon::now()->toDateString();
        $data['jobs'] = Job::whereDate('job_expiry_date', '>=', $now)->where('status', '1')->where('is_suspended', Job::NOT_SUSPENDED)->orderBy('created_at',
            'desc')->paginate(10);

        $data['companies'] = \DB::table('users')
            ->join('companies', 'companies.user_id', '=', 'users.id')
            ->where('users.is_active', true)
            ->select('companies.id', \DB::raw("CONCAT(users.first_name, ' ', IFNULL(users.last_name, '')) as full_name"))
            ->pluck('full_name', 'id');

        return $data;
    }

    public function sendJobNotification($input)
    {
        $candidateIds = Arr::get($input, 'candidate_id', []);
        $jobIds = Arr::get($input, 'job_id', []);

        $candidates = Candidate::with('user')
            ->whereIn('id', $candidateIds)
            ->whereHas('user', function (Builder $q) {
                $q->where('is_active', true)->whereNotNull('email');
            })
            ->get();
        $jobs = Job::whereIn('id', $jobIds)->with('company')->get();
        /** @var EmailTemplate|null $templateBody */
        $templateBody = EmailTemplate::whereTemplateName('Job Notification')->first();

        if ($candidates->isEmpty() || $jobs->isEmpty() || empty($templateBody?->body)) {
            throw new UnprocessableEntityHttpException(__('messages.common.something_went_wrong'));
        }

        try {
            $delay = 0;
            $delayStep = 10;

            foreach ($candidates as $candidate) {
                $keyVariable = ['{{candidate_name}}', '{{from_name}}', '{{app_url}}', '{{date}}'];
                $value = [$candidate->user->full_name, config('app.name'), config('app.url'), now()->format('d-m-Y')];
                $body = str_replace($keyVariable, $value, $templateBody->body);
                $data['footer'] = \Str::after($body, '{{jobs}}');
                $data['body'] = \Str::before($body, '{{jobs}}');
                $data['jobs'] = $jobs;

                Mail::to($candidate->user->email)->later(now()->addSeconds($delay), new JobNotification($data));
                $delay += $delayStep;
            }

            return true;
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}

