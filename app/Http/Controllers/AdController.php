<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAdRequest;
use App\Models\Ad;
use App\Repositories\AdRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdController extends AppBaseController
{
    /** @var AdRepository */
    private $adRepository;

    public function __construct(AdRepository $adRepository)
    {
        $this->adRepository = $adRepository;
    }

    /**
     * Display a listing of ads.
     *
     * @throws Exception
     */
    public function index(): View
    {
        $positions = collect(Ad::POSITIONS)->mapWithKeys(function ($value) {
            return [$value => __('messages.ad.positions.'.$value)];
        })->toArray();

        return view('ads.index', compact('positions'));
    }

    /**
     * Store a newly created ad in storage.
     */
    public function store(CreateAdRequest $request): JsonResponse
    {
        $input = $request->all();
        $input['is_active'] = (isset($input['is_active'])) ? 1 : 0;
        $input['sort_order'] = isset($input['sort_order']) && $input['sort_order'] !== ''
            ? (int) $input['sort_order']
            : 0;
        if ($request->hasFile('ad_image')) {
            $input['ad_image'] = $request->file('ad_image');
        }
        $this->adRepository->store($input);

        return $this->sendSuccess(__('messages.flash.ad_save'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ad $ad): JsonResponse
    {
        return $this->sendResponse($ad, __('messages.flash.ad_retrieved'));
    }

    /**
     * Update the specified ad in storage.
     */
    public function update(Request $request, Ad $ad): JsonResponse
    {
        $request->validate([
            'title' => 'nullable|max:150',
            'description' => 'nullable|max:500',
            'link_url' => 'nullable|url|max:255',
            'cta_text' => 'nullable|max:50',
            'position' => 'required|in:header,register_left,register_right',
            'sort_order' => 'nullable|integer|min:0',
            'ad_image' => 'nullable|mimes:jpeg,jpg,png',
        ], [
            'ad_image.mimes' => __('messages.image_slider.image_extension_message'),
            'position.required' => __('messages.ad.position_required'),
            'link_url.url' => __('messages.ad.valid_url'),
        ]);

        $input = $request->all();
        $input['is_active'] = (isset($input['is_active'])) ? 1 : 0;
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

    /**
     * Remove the specified ad from storage.
     *
     * @throws Exception
     */
    public function destroy(Ad $ad): JsonResponse
    {
        $ad->delete();

        return $this->sendSuccess(__('messages.flash.ad_delete'));
    }

    /**
     * @return mixed
     */
    public function changeIsActive(Ad $ad)
    {
        $isActive = $ad->is_active;
        $ad->update(['is_active' => ! $isActive]);

        return $this->sendSuccess(__('messages.flash.status_change'));
    }
}
