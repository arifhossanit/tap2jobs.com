<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidateStatusUpdateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build(): self
    {
        $subject = $this->data['subject'] ?? 'Application Update';
        $fromAddress = getSettingValue('mail_from_address') ?? config('mail.from.address');
        $fromName = getSettingValue('app_name') ?? config('app.name');

        return $this->from($fromAddress, $fromName)
            ->subject($subject)
            ->markdown('emails.candidate_status_update', [
                'candidateName' => $this->data['candidate_name'] ?? 'Candidate',
                'jobTitle' => $this->data['job_title'] ?? 'Job Position',
                'companyName' => $this->data['company_name'] ?? 'Company',
                'statusText' => $this->data['status_text'] ?? 'Updated',
                'messageBody' => $this->data['message_body'] ?? '',
                'actionUrl' => $this->data['action_url'] ?? url('/candidate/applied-jobs'),
            ]);
    }
}
