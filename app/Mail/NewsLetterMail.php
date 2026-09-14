<?php

namespace App\Mail;

use App\Support\MailLogo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsLetterMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    private $data;

    /**
     * Create a new message instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $this->data = array_merge($this->data, MailLogo::data());

        return $this->subject($this->data['subject'] ?? $this->data['input']['title'])->markdown('emails.news_letter.news_letter')->with('body',
            $this->data['body'])->with('data', $this->data);
    }
}
