<?php

namespace App\Mail;

use App\Support\MailLogo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailToCandidate extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $this->data = array_merge($this->data, MailLogo::data());

        return $this->from(config('mail.from.address'))
            ->subject($this->data['subject'] ?? 'New Job Alert')->markdown('emails.jobs.email_candidate');
    }
}
