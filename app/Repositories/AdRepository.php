<?php

namespace App\Repositories;

use App\Models\Ad;
use Exception;
use Illuminate\Support\Facades\DB;
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
                    $ad->addMedia($image)->toMediaCollection(Ad::PATH, config('app.media_disc'));
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
                    $ad->addMedia($image)->toMediaCollection(Ad::PATH, config('app.media_disc'));
                }

                return true;
            });
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
