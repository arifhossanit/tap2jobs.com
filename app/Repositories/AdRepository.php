<?php

namespace App\Repositories;

use App\Models\Ad;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as InterventionImage;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class AdRepository
 */
class AdRepository extends BaseRepository
{
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
        try {
            return DB::transaction(function () use ($input) {
                $image = $input['ad_image'] ?? null;
                unset($input['ad_image']);

                /** @var Ad $ad */
                $ad = $this->create($input);

                if (! empty($image)) {
                    $preparedMedia = $this->prepareMediaForUpload($image);
                    $ad->addMedia($preparedMedia)->toMediaCollection(Ad::PATH, config('app.media_disc'));
                    $this->deletePreparedMedia($preparedMedia);
                }

                return true;
            });
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateAd(array $input, int $adId)
    {
        try {
            return DB::transaction(function () use ($input, $adId) {
                $image = $input['ad_image'] ?? null;
                unset($input['ad_image']);

                /** @var Ad $ad */
                $ad = $this->update($input, $adId);

                if (! empty($image)) {
                    $ad->clearMediaCollection(Ad::PATH);
                    $preparedMedia = $this->prepareMediaForUpload($image);
                    $ad->addMedia($preparedMedia)->toMediaCollection(Ad::PATH, config('app.media_disc'));
                    $this->deletePreparedMedia($preparedMedia);
                }

                return true;
            });
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    private function prepareMediaForUpload(UploadedFile $file): UploadedFile|string
    {
        if (! str_starts_with((string) $file->getMimeType(), 'image/')) {
            return $file;
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

    private function deletePreparedMedia(UploadedFile|string $media): void
    {
        if (is_string($media) && File::exists($media)) {
            File::delete($media);
        }
    }
}
