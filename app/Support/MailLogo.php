<?php

namespace App\Support;

class MailLogo
{
    public static function path(): ?string
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

    public static function dataUri(?string $path): ?string
    {
        if (empty($path) || ! file_exists($path)) {
            return null;
        }

        $mimeType = mime_content_type($path) ?: 'image/png';

        return 'data:'.$mimeType.';base64,'.base64_encode((string) file_get_contents($path));
    }

    public static function data(): array
    {
        $path = self::path();

        return ['logo_path' => $path, 'logo_data_uri' => self::dataUri($path)];
    }
}
