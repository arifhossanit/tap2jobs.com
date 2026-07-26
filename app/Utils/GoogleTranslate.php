<?php

namespace App\Utils;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class GoogleTranslate
 *
 * A simple helper to translate text using Google Translate API
 */
class GoogleTranslate
{
    /**
     * Translate text from source language to target language
     *
     * @param  string  $text
     * @param  string  $target  Target language code (e.g., 'bn')
     * @param  string  $source  Source language code (e.g., 'en')
     * @return string
     */
    public static function translate(string $text, string $target = 'bn', string $source = 'en'): string
    {
        if (empty(trim($text))) {
            return $text;
        }

        try {
            $apiKey = config('services.google_translate.api_key');
            
            if (!empty($apiKey)) {
                return self::translateWithApiKey($text, $target, $source, $apiKey);
            }
            
            return self::translateFree($text, $target, $source);
        } catch (\Exception $e) {
            Log::error('Google Translate error: ' . $e->getMessage());
            return $text;
        }
    }

    /**
     * Translate using Google Cloud Translation API with API key
     */
    private static function translateWithApiKey(string $text, string $target, string $source, string $apiKey): string
    {
        $url = 'https://translation.googleapis.com/language/translate/v2';
        
        $response = Http::get($url, [
            'q' => $text,
            'target' => $target,
            'source' => $source,
            'key' => $apiKey,
            'format' => 'text',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['data']['translations'][0]['translatedText'])) {
                return $data['data']['translations'][0]['translatedText'];
            }
        }

        return $text;
    }

    /**
     * Translate using free Google Translate endpoint (no API key required)
     * This uses the same endpoint that the Google Translate website uses
     */
    private static function translateFree(string $text, string $target, string $source): string
    {
        $url = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=' 
            . $source . '&tl=' . $target . '&dt=t&q=' . urlencode($text);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $decoded = json_decode($response, true);
            if (is_array($decoded) && isset($decoded[0])) {
                $translated = '';
                foreach ($decoded[0] as $segment) {
                    if (isset($segment[0])) {
                        $translated .= $segment[0];
                    }
                }
                if (!empty(trim($translated))) {
                    return $translated;
                }
            }
        }

        return $text;
    }
}