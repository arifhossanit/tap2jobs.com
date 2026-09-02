<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * JobNotification constructor.
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
        $this->data['company_logo_paths'] = $this->resolveCompanyLogoPaths();
        $this->data['company_logo_data_uris'] = array_map([$this, 'pathToDataUri'], $this->data['company_logo_paths']);

        return $this->from(config('mail.from.address'))
            ->subject('New Job Notification')->markdown('emails.jobs.notification');
    }

    private function resolveCompanyLogoPaths(): array
    {
        $logoPaths = [];
        $fallbackLogoPath = public_path('assets/img/employer-image.png');

        foreach ($this->data['jobs'] ?? [] as $job) {
            $company = $job->company ?? null;
            $media = $company?->user?->getMedia(User::PROFILE)->first();
            $logoPaths[$job->id] = $media && file_exists($media->getPath())
                ? $media->getPath()
                : (file_exists($fallbackLogoPath) ? $fallbackLogoPath : null);
        }

        return $logoPaths;
    }

    private function pathToDataUri(?string $path): ?string
    {
        if (empty($path) || ! file_exists($path)) {
            return null;
        }

        $mimeType = mime_content_type($path) ?: 'image/png';

        return 'data:'.$mimeType.';base64,'.base64_encode((string) file_get_contents($path));
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

