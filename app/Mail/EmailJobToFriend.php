<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailJobToFriend extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
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
        $this->data['logo_path'] = $this->resolveLogoPath();

        return $this->from(config('mail.from.address'))
            ->subject('New Job Details')
            ->markdown('emails.jobs.email_job');
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
}
