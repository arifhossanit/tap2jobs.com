<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailToEmployer extends Mailable implements ShouldQueue
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
        $this->data['logo_data_uri'] = $this->pathToDataUri($this->data['logo_path']);

        return $this->from(config('mail.from.address'))
            ->subject($this->data['subject'] ?? 'Job Applied by Candidate')->markdown('emails.jobs.email_employer');
    }

    private function resolveLogoPath(): ?string
    {
        $logoPath = parse_url(getLogoUrl(), PHP_URL_PATH);

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

    private function pathToDataUri(?string $path): ?string
    {
        if (empty($path) || ! file_exists($path)) {
            return null;
        }

        $mimeType = mime_content_type($path) ?: 'image/png';

        return 'data:'.$mimeType.';base64,'.base64_encode((string) file_get_contents($path));
    }
}
