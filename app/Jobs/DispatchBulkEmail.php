<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DispatchBulkEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $candidateProfileFilter = 'all';

    public function __construct(
        public string $targetType,
        public array $recipients,
        public string $subject,
        public string $body,
        string $candidateProfileFilter = 'all'
    ) {
        $this->candidateProfileFilter = $candidateProfileFilter;
    }

    public function handle(): void
    {
        $delaySeconds = 0;

        if ($this->targetType === 'custom') {
            foreach ($this->recipients as $email) {
                SendBulkEmail::dispatch($email, $this->subject, $this->body)
                    ->onConnection('database')
                    ->delay(now()->addSeconds($delaySeconds));
                $delaySeconds += 2;
            }
            return;
        }

        $query = User::query()->setEagerLoads([])->whereNotNull('email_verified_at');
        if ($this->targetType === 'existing') {
            $query->role(['Candidate', 'Employer'])->whereIn('id', $this->recipients);
        } else {
            $query->role($this->targetType === 'candidate' ? 'Candidate' : 'Employer');
        }

        $query->select('id')->chunkById(100, function ($users) use (&$delaySeconds) {
            foreach ($users as $user) {
                SendBulkEmail::dispatch(
                    null,
                    $this->subject,
                    $this->body,
                    $user->id,
                    $this->targetType === 'candidate' ? $this->candidateProfileFilter : 'all'
                )
                    ->onConnection('database')
                    ->delay(now()->addSeconds($delaySeconds));
                $delaySeconds += 2;
            }
        });
    }
}
