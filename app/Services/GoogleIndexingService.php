<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GoogleIndexingService
{
    public const URL_UPDATED = 'URL_UPDATED';
    public const URL_DELETED = 'URL_DELETED';

    public function enabled(): bool
    {
        return (bool) config('seo.indexing_api.enabled') && is_file($this->credentialsPath());
    }

    public function notify(string $url, string $type): array
    {
        if (! in_array($type, [self::URL_UPDATED, self::URL_DELETED], true)) {
            throw new RuntimeException('Unsupported Google Indexing API notification type.');
        }

        if (! $this->enabled()) {
            return ['skipped' => true, 'reason' => 'Google Indexing API is not configured.'];
        }

        return Http::acceptJson()
            ->withToken($this->accessToken())
            ->timeout((int) config('seo.indexing_api.timeout', 15))
            ->retry(2, 500)
            ->post(config('seo.indexing_api.endpoint'), compact('url', 'type'))
            ->throw()
            ->json();
    }

    private function accessToken(): string
    {
        $credentials = $this->credentials();
        $cacheKey = 'seo:google-indexing-token:'.sha1($credentials['client_email']);

        return Cache::remember($cacheKey, now()->addMinutes(50), function () use ($credentials) {
            $now = now()->timestamp;
            $tokenUri = $credentials['token_uri'] ?? 'https://oauth2.googleapis.com/token';
            $assertion = $this->signedJwt([
                'iss' => $credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/indexing',
                'aud' => $tokenUri,
                'iat' => $now,
                'exp' => $now + 3600,
            ], $credentials['private_key']);

            $response = Http::asForm()
                ->acceptJson()
                ->timeout((int) config('seo.indexing_api.timeout', 15))
                ->retry(2, 500)
                ->post($tokenUri, [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $assertion,
                ])
                ->throw()
                ->json();

            if (empty($response['access_token'])) {
                throw new RuntimeException('Google OAuth response did not contain an access token.');
            }

            return $response['access_token'];
        });
    }

    private function credentials(): array
    {
        $credentials = json_decode((string) file_get_contents($this->credentialsPath()), true);

        if (! is_array($credentials) || empty($credentials['client_email']) || empty($credentials['private_key'])) {
            throw new RuntimeException('Invalid Google service-account credentials file.');
        }

        return $credentials;
    }

    private function credentialsPath(): string
    {
        $path = (string) config('seo.indexing_api.credentials_path');

        if ($path === '') {
            return '';
        }

        return str_starts_with($path, '/') || preg_match('/^[A-Za-z]:[\\\\\/]/', $path)
            ? $path
            : base_path($path);
    }

    private function signedJwt(array $claims, string $privateKey): string
    {
        $header = $this->base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT'], JSON_THROW_ON_ERROR));
        $payload = $this->base64UrlEncode(json_encode($claims, JSON_THROW_ON_ERROR));
        $unsignedToken = $header.'.'.$payload;
        $key = openssl_pkey_get_private($privateKey);

        if ($key === false || ! openssl_sign($unsignedToken, $signature, $key, OPENSSL_ALGO_SHA256)) {
            throw new RuntimeException('Unable to sign the Google service-account JWT.');
        }

        return $unsignedToken.'.'.$this->base64UrlEncode($signature);
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}
