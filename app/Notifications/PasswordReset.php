<?php

namespace App\Notifications;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordReset extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail(): MailMessage
    {
        /** @var EmailTemplate $templateBody */
        $templateBody = EmailTemplate::whereTemplateName('Password Reset Email')->first();
        if (! $templateBody) {
            return (new MailMessage)
                ->subject('Reset Password Notification')
                ->line('You are receiving this email because we received a password reset request for your account.')
                ->action('Reset Password', url('password/reset', $this->token));
        }
        $keyVariable = ['{{reset_url}}', '{{from_name}}', '{{reset_expire_minutes}}'];
        $value = [url('password/reset', $this->token), config('app.name'), config('auth.passwords.users.expire', 60)];
        $body = str_replace($keyVariable, $value, $templateBody->body);
        $data['body'] = $body;

        return (new MailMessage)
            ->subject(str_replace($keyVariable, $value, $templateBody->subject))
            ->view('emails.password_reset_email', $data);
    }
}
