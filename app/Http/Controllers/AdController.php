<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAdRequest;
use App\Http\Requests\UpdateAdRequest;
use App\Models\Ad;
use App\Repositories\AdRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AdController extends AppBaseController
{
    private $adRepository;

    public function __construct(AdRepository $adRepository)
    {
        $this->adRepository = $adRepository;
    }

    public function index(): View
    {
        $positions = collect(Ad::POSITIONS)->mapWithKeys(function ($value) {
            return [$value => __('messages.ad.positions.'.$value)];
        })->toArray();

        $pages = collect(Ad::PAGES)->mapWithKeys(function ($value) {
            return [$value => __('messages.ad.pages.'.$value)];
        })->toArray();

        return view('ads.index', compact('positions', 'pages'));
    }

    public function store(CreateAdRequest $request): JsonResponse
    {
        $input = $request->validated();
        $input['page'] = array_values(array_unique($input['page']));
        $input['is_active'] = $request->boolean('is_active');
        $input['sort_order'] = isset($input['sort_order']) && $input['sort_order'] !== ''
            ? (int) $input['sort_order']
            : 0;
        if ($request->hasFile('ad_image')) {
            $input['ad_image'] = $request->file('ad_image');
        }
        $this->adRepository->store($input);

        return $this->sendSuccess(__('messages.flash.ad_save'));
    }

    public function edit(Ad $ad): JsonResponse
    {
        return $this->sendResponse($ad, __('messages.flash.ad_retrieved'));
    }

    public function update(UpdateAdRequest $request, Ad $ad): JsonResponse
    {
        $input = $request->validated();
        $input['page'] = array_values(array_unique($input['page']));
        $input['is_active'] = $request->boolean('is_active');
        $input['sort_order'] = isset($input['sort_order']) && $input['sort_order'] !== ''
            ? (int) $input['sort_order']
            : 0;
        if ($request->hasFile('ad_image')) {
            $input['ad_image'] = $request->file('ad_image');
        } else {
            unset($input['ad_image']);
        }
        $this->adRepository->updateAd($input, $ad->id);

        return $this->sendSuccess(__('messages.flash.ad_update'));
    }

    public function destroy(Ad $ad): JsonResponse
    {
        $ad->delete();

        return $this->sendSuccess(__('messages.flash.ad_delete'));
    }

    public function changeIsActive(Ad $ad)
    {
        $isActive = $ad->is_active;
        $ad->update(['is_active' => ! $isActive]);

        return $this->sendSuccess(__('messages.flash.status_change'));
    }
}
