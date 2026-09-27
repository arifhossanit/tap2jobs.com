<?php

namespace App\Jobs;

use App\Mail\BulkEmailMessage;
use App\Models\User;
use App\Services\CandidateProfileCompletionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class SendBulkEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 10;
    public int $backoff = 10;
    public string $candidateProfileFilter = 'all';

    public function __construct(
        public ?string $email,
        public string $subject,
        public string $body,
        public ?int $userId = null,
        string $candidateProfileFilter = 'all'
    ) {
        $this->candidateProfileFilter = $candidateProfileFilter;
    }

    public function handle(): void
    {
        $user = null;
        if ($this->userId !== null) {
            $user = User::query()->setEagerLoads([])
                ->with(['candidate' => fn ($query) => $query->setEagerLoads([])])
                ->whereKey($this->userId)
                ->whereNotNull('email_verified_at')
                ->first(['id', 'first_name', 'last_name', 'email']);
            if (! $user) {
                return;
            }

            if ($this->candidateProfileFilter !== 'all') {
                if (! $user->candidate) {
                    return;
                }

                $user->candidate->setRelation('user', $user);
                $percentage = app(CandidateProfileCompletionService::class)
                    ->calculate($user->candidate)['percentage'];

                if ($this->candidateProfileFilter === 'below_30' && $percentage > 30) {
                    return;
                }

                if ($this->candidateProfileFilter === '30_to_below_80'
                    && ($percentage <= 30 || $percentage > 80)) {
                    return;
                }

                if ($this->candidateProfileFilter === '80_plus' && $percentage <= 80) {
                    return;
                }

                // Preserve the audience selected by jobs queued before the filter was split.
                if ($this->candidateProfileFilter === '30_plus' && $percentage < 30) {
                    return;
                }
            }

            $this->email = $user->email;
        }

        $plainValues = [
            '{{Name}}' => $user ? trim($user->first_name.' '.$user->last_name) : '',
            '{{first_name}}' => $user?->first_name ?? '',
            '{{last_name}}' => $user?->last_name ?? '',
            '{{email}}' => $this->email ?? '',
        ];
        $htmlValues = array_map(
            fn (string $value) => e($value),
            $plainValues
        );

        $subject = strtr($this->subject, $plainValues);
        $body = strtr($this->body, $htmlValues);

        try {
            Mail::to($this->email)->send(new BulkEmailMessage($subject, $body));
        } catch (TransportExceptionInterface $exception) {
            if (str_contains(strtolower($exception->getMessage()), 'too many emails per second')) {
                $this->release(10);

                return;
            }

            throw $exception;
        }
    }
}
