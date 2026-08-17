<?php

namespace App\Repositories;

use App\Jobs\OptimizeAdVideoJob;
use App\Models\Ad;
use App\Services\SvgSanitizer;
use Exception;
use Illuminate\Container\Container as Application;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as InterventionImage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

/**
 * Class AdRepository
 */
class AdRepository extends BaseRepository
{
    public function __construct(Application $app, private readonly SvgSanitizer $svgSanitizer)
    {
        parent::__construct($app);
    }

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'position',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     */
    public function model()
    {
        return Ad::class;
    }

    public function store($input)
    {
        $image = $input['ad_image'] ?? null;
        $preparedMedia = null;
        unset($input['ad_image']);

        try {
            if (! empty($image)) {
                $preparedMedia = $this->prepareMediaForUpload($image);
            }

            $videoOptimizationPayload = DB::transaction(function () use ($input, $image, $preparedMedia) {

                /** @var Ad $ad */
                $ad = $this->create($input);

                if (! empty($image) && ! empty($preparedMedia)) {
                    $media = $ad->addMedia($preparedMedia)->toMediaCollection(Ad::PATH, config('app.media_disc'));

                    if ($this->isVideoUpload($image)) {
                        $ad->update([
                            'media_processing_status' => Ad::MEDIA_STATUS_READY,
                            'media_processing_error' => null,
                            'media_processed_at' => now(),
                        ]);

                        return [$ad->id, $media->id];
                    }
                }

                $ad->update([
                    'media_processing_status' => Ad::MEDIA_STATUS_READY,
                    'media_processing_error' => null,
                    'media_processed_at' => now(),
                ]);

                return null;
            });

            if (! empty($videoOptimizationPayload)) {
                OptimizeAdVideoJob::dispatch($videoOptimizationPayload[0], $videoOptimizationPayload[1]);
            }

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } finally {
            if (! empty($preparedMedia)) {
                $this->deletePreparedMedia($preparedMedia);
            }
        }
    }

    public function updateAd(array $input, int $adId)
    {
        $image = $input['ad_image'] ?? null;
        $preparedMedia = null;
        $oldMediaIds = [];
        unset($input['ad_image']);

        try {
            if (! empty($image)) {
                $preparedMedia = $this->prepareMediaForUpload($image);
            }

            $videoOptimizationPayload = DB::transaction(function () use ($input, $adId, $image, $preparedMedia, &$oldMediaIds) {

                /** @var Ad $ad */
                $ad = $this->update($input, $adId);

                if (! empty($image) && ! empty($preparedMedia)) {
                    $oldMedia = $ad->getMedia(Ad::PATH);
                    // Snapshot scalar IDs before addMedia mutates the model's loaded media relation.
                    $oldMediaIds = $oldMedia->pluck('id')->map(fn ($id) => (int) $id)->all();
                    $minimumOldOrder = (int) ($oldMedia->min('order_column') ?? 0);
                    $media = $ad->addMedia($preparedMedia)->toMediaCollection(Ad::PATH, config('app.media_disc'));
                    $media->order_column = $minimumOldOrder - 1;
                    $media->save();

                    if ($this->isVideoUpload($image)) {
                        $ad->update([
                            'media_processing_status' => Ad::MEDIA_STATUS_READY,
                            'media_processing_error' => null,
                            'media_processed_at' => now(),
                        ]);

                        return [$ad->id, $media->id];
                    }

                    $ad->update([
                        'media_processing_status' => Ad::MEDIA_STATUS_READY,
                        'media_processing_error' => null,
                        'media_processed_at' => now(),
                    ]);
                }

                return null;
            });

            // The replacement is already committed and displayed first. Only then remove
            // the previous files, so a failed upload can never erase the existing ad.
            $this->deleteOldMedia($oldMediaIds, $adId);

            if (! empty($videoOptimizationPayload)) {
                OptimizeAdVideoJob::dispatch($videoOptimizationPayload[0], $videoOptimizationPayload[1]);
            }

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } finally {
            if (! empty($preparedMedia)) {
                $this->deletePreparedMedia($preparedMedia);
            }
        }
    }

    private function prepareMediaForUpload(UploadedFile $file): UploadedFile|string
    {
        if ($this->isSvgUpload($file)) {
            return $this->prepareSvgForUpload($file);
        }

        if (! str_starts_with((string) $file->getMimeType(), 'image/')) {
            $directory = storage_path('app/tmp/auth-ads');
            File::ensureDirectoryExists($directory);

            $extension = strtolower($file->getClientOriginalExtension() ?: 'mp4');
            $videoPath = $directory.'/'.Str::uuid().'.'.$extension;
            File::copy($file->getRealPath(), $videoPath);

            return $videoPath;
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $extension = $extension === 'jpg' ? 'jpeg' : $extension;
        $allowedExtensions = ['jpeg', 'png', 'webp'];
        $extension = in_array($extension, $allowedExtensions, true) ? $extension : 'jpeg';
        $quality = $extension === 'png' ? 9 : 82;

        $directory = storage_path('app/tmp/auth-ads');
        File::ensureDirectoryExists($directory);

        $optimizedPath = $directory.'/'.Str::uuid().'.'.$extension;

        InterventionImage::make($file->getRealPath())
            ->orientate()
            ->resize(900, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode($extension, $quality)
            ->save($optimizedPath);

        return $optimizedPath;
    }

    private function prepareSvgForUpload(UploadedFile $file): string
    {
        $contents = File::get($file->getRealPath());
        $sanitized = $this->svgSanitizer->sanitize($contents);

        $directory = storage_path('app/tmp/auth-ads');
        File::ensureDirectoryExists($directory);

        $svgPath = $directory.'/'.Str::uuid().'.svg';
        File::put($svgPath, $sanitized);

        return $svgPath;
    }

    private function deletePreparedMedia(UploadedFile|string $media): void
    {
        if (is_string($media) && File::exists($media)) {
            File::delete($media);
        }
    }

    private function isVideoUpload(UploadedFile $file): bool
    {
        return str_starts_with((string) $file->getMimeType(), 'video/');
    }

    private function deleteOldMedia(array $oldMediaIds, int $adId): void
    {
        if ($oldMediaIds === []) {
            return;
        }

        Media::query()->whereIn('id', $oldMediaIds)->get()->each(function (Media $media) use ($adId) {
            try {
                $media->delete();
            } catch (Throwable $exception) {
                Log::warning('Unable to remove replaced ad media.', [
                    'ad_id' => $adId,
                    'media_id' => $media->id,
                    'error' => $exception->getMessage(),
                ]);
            }
        });
    }

    private function isSvgUpload(UploadedFile $file): bool
    {
        return strtolower($file->getClientOriginalExtension()) === 'svg'
            || $file->getMimeType() === 'image/svg+xml';
    }
}
