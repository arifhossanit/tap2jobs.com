<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class BulkEmailMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $mailSubject, public string $htmlBody) {}

    public function build(): self
    {
        $mail = $this->from(
                getSettingValue('mail_from_address') ?: config('mail.from.address'),
                getSettingValue('app_name') ?: config('mail.from.name')
            )
            ->subject($this->mailSubject);

        $this->embedEditorImages($mail);
        $mail->view('emails.bulk_email', ['htmlBody' => $this->htmlBody]);

        // Files inserted through this editor are both linked in the body and attached to the email.
        preg_match_all('/<a\b[^>]*href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/is', $this->htmlBody, $links, PREG_SET_ORDER);
        $attached = [];
        $uploadUrlPrefix = parse_url(Storage::disk('public')->url('bulk-email/'), PHP_URL_PATH);
        foreach ($links as $link) {
            $path = parse_url(html_entity_decode($link[1], ENT_QUOTES | ENT_HTML5), PHP_URL_PATH);
            if (! is_string($path) || ! is_string($uploadUrlPrefix) || ! str_starts_with($path, $uploadUrlPrefix)) {
                continue;
            }
            $fileName = substr($path, strlen($uploadUrlPrefix));
            if ($fileName === '' || $fileName !== basename($fileName)) {
                continue;
            }
            $relativePath = 'bulk-email/'.$fileName;
            if (isset($attached[$relativePath]) || ! Storage::disk('public')->exists($relativePath)) {
                continue;
            }
            $name = basename(trim(strip_tags($link[2]))) ?: basename($relativePath);
            $mail->attach(Storage::disk('public')->path($relativePath), ['as' => $name]);
            $attached[$relativePath] = true;
        }

        return $mail;
    }

    private function embedEditorImages(self $mail): void
    {
        preg_match_all('/<img\b[^>]*\bsrc=["\']([^"\']+)["\'][^>]*>/is', $this->htmlBody, $images);
        $uploadUrlPrefix = parse_url(Storage::disk('public')->url('bulk-email/'), PHP_URL_PATH);

        if (! is_string($uploadUrlPrefix)) {
            return;
        }

        $embedded = [];
        foreach (array_unique($images[1] ?? []) as $source) {
            $path = parse_url(html_entity_decode($source, ENT_QUOTES | ENT_HTML5), PHP_URL_PATH);
            if (! is_string($path) || ! str_starts_with($path, $uploadUrlPrefix)) {
                continue;
            }

            $fileName = substr($path, strlen($uploadUrlPrefix));
            if ($fileName === '' || $fileName !== basename($fileName)) {
                continue;
            }

            $relativePath = 'bulk-email/'.$fileName;
            if (! Storage::disk('public')->exists($relativePath)) {
                continue;
            }

            $contentId = 'bulk-email-'.sha1($relativePath);
            $embedded[$source] = 'cid:'.$contentId;
            $absolutePath = Storage::disk('public')->path($relativePath);

            $mail->withSymfonyMessage(function ($message) use ($absolutePath, $contentId) {
                $message->embedFromPath($absolutePath, $contentId);
            });
        }

        if ($embedded !== []) {
            $this->htmlBody = strtr($this->htmlBody, $embedded);
        }
    }
}
