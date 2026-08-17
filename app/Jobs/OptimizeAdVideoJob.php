<?php

namespace App\Jobs;

use App\Models\Ad;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\Process\Process;

class OptimizeAdVideoJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 1;

    public int $timeout = 900;

    public function __construct(private readonly int $adId, private readonly int $mediaId)
    {
        $this->onConnection('database');
        $this->onQueue('media');
    }

    public function handle(): void
    {
        $ad = Ad::with('media')->find($this->adId);
        $media = Media::find($this->mediaId);

        if (! $ad || ! $media || ! $ad->media->contains('id', $media->id)) {
            return;
        }

        $sourcePath = $media->getPath();
        if (! File::exists($sourcePath)) {
            $this->markFailed($ad, 'Source video file was not found.');

            return;
        }

        if (! $this->ffmpegIsAvailable()) {
            $ad->update([
                'media_processing_status' => Ad::MEDIA_STATUS_READY,
                'media_processing_error' => 'FFmpeg is not installed or not available in PATH. Original video is being used.',
                'media_processed_at' => now(),
            ]);

            return;
        }

        $ad->update([
            'media_processing_status' => Ad::MEDIA_STATUS_PROCESSING,
            'media_processing_error' => null,
            'media_processed_at' => null,
        ]);

        $outputDirectory = storage_path('app/tmp/ad-videos');
        File::ensureDirectoryExists($outputDirectory);

        $outputPath = $outputDirectory.'/ad-'.$ad->id.'-'.now()->timestamp.'.mp4';

        try {
            $process = new Process([
                config('services.ffmpeg.binary', 'ffmpeg'),
                '-y',
                '-i',
                $sourcePath,
                '-vf',
                'scale=min(1280\,iw):-2',
                '-c:v',
                'libx264',
                '-preset',
                'veryfast',
                '-crf',
                '28',
                '-movflags',
                '+faststart',
                '-c:a',
                'aac',
                '-b:a',
                '96k',
                $outputPath,
            ]);

            $process->setTimeout($this->timeout);
            $process->mustRun();

            if (! File::exists($outputPath) || File::size($outputPath) === 0) {
                throw new Exception('Optimized video output was not created.');
            }

            $oldMedia = $ad->getMedia(Ad::PATH);
            $oldMediaIds = $oldMedia->pluck('id')->map(fn ($id) => (int) $id)->all();
            $minimumOldOrder = (int) ($oldMedia->min('order_column') ?? 0);
            $optimizedMedia = $ad->addMedia($outputPath)
                ->usingFileName('ad-'.$ad->id.'-optimized.mp4')
                ->withCustomProperties(['optimized' => true])
                ->toMediaCollection(Ad::PATH, config('app.media_disc'));
            $optimizedMedia->order_column = $minimumOldOrder - 1;
            $optimizedMedia->save();

            Media::query()->whereIn('id', $oldMediaIds)->get()->each->delete();

            $ad->update([
                'media_processing_status' => Ad::MEDIA_STATUS_READY,
                'media_processing_error' => null,
                'media_processed_at' => now(),
            ]);
        } catch (Exception $exception) {
            $ad->update([
                'media_processing_status' => Ad::MEDIA_STATUS_READY,
                'media_processing_error' => mb_substr($exception->getMessage(), 0, 1000),
                'media_processed_at' => now(),
            ]);
        } finally {
            if (File::exists($outputPath)) {
                File::delete($outputPath);
            }
        }
    }

    private function markFailed(Ad $ad, string $message): void
    {
        $ad->update([
            'media_processing_status' => Ad::MEDIA_STATUS_FAILED,
            'media_processing_error' => mb_substr($message, 0, 1000),
            'media_processed_at' => null,
        ]);
    }

    private function ffmpegIsAvailable(): bool
    {
        $process = new Process([config('services.ffmpeg.binary', 'ffmpeg'), '-version']);
        $process->setTimeout(10);
        $process->run();

        return $process->isSuccessful();
    }
}
