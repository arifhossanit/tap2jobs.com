<?php

namespace App\Notifications;

use App\Models\EmailTemplate;
use Auth;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserVerifyNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    public $user;            //you'll need this to address the user

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user = '')
    {
        $this->user = $user ?: Auth::user();         //if user is not supplied, get from session
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        $url = $this->verificationUrl($notifiable);     //verificationUrl required for the verification link
        $user = $this->user;
        /** @var EmailTemplate $templateBody */
        $templateBody = EmailTemplate::whereTemplateName('Verify Email')->first();
        if (! $templateBody) {
            return (new MailMessage)
                ->subject('Verify Email Address')
                ->line('Please click the button below to verify your email address.')
                ->action('Verify Email Address', $url);
        }

        $keyVariable = ['{{user_name}}', '{{verify_url}}', '{{from_name}}'];
        $value = [$user->full_name, $url, config('app.name')];
        $body = str_replace($keyVariable, $value, $templateBody->body);
        $data['body'] = $body;
        $data['logo_path'] = $this->resolveLogoPath();

        return (new MailMessage)
            ->subject($templateBody->subject)
            ->view('emails.verify_email', $data);
    }

    private function resolveLogoPath(): ?string
    {
        $logoUrl = getLogoUrl();
        $logoPath = parse_url($logoUrl, PHP_URL_PATH);

        if (! empty($logoPath)) {
            $publicLogoPath = public_path(ltrim($logoPath, '/'));

            if (file_exists($publicLogoPath)) {
                return $publicLogoPath;
            }
        }

        $settingLogo = getSettingValue('logo');

        if (! empty($settingLogo) && filter_var($settingLogo, FILTER_VALIDATE_URL) === false) {
            $publicLogoPath = public_path(ltrim($settingLogo, '/'));

            if (file_exists($publicLogoPath)) {
                return $publicLogoPath;
            }
        }

        $fallbackLogoPath = public_path('assets/img/infyom-logo.png');

        return file_exists($fallbackLogoPath) ? $fallbackLogoPath : null;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
