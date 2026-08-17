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
        $input = $request->all();
        $pages = $request->input('page', []);
        
        if (in_array('all', $pages)) {
            $input['page'] = ['all'];
        } else {
            $input['page'] = array_values(array_unique($pages));
        }
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

    public function edit(Ad $ad): JsonResponse
    {
        return $this->sendResponse($ad, __('messages.flash.ad_retrieved'));
    }

    public function update(Request $request, Ad $ad): JsonResponse
    {
        $request->validate([
            'title' => 'nullable|max:150',
            'description' => 'nullable|max:500',
            'link_url' => 'nullable|url|max:255',
            'cta_text' => 'nullable|max:50',
            'position' => 'required|in:header,register_left,register_right',
            'page' => 'nullable|array',
            'page.*' => 'in:all,candidate_register,employer_register,candidate_login,employer_login,home',
            'sort_order' => 'nullable|integer|min:0',
            'ad_image' => 'nullable|file|mimes:jpeg,jpg,png,webp,mp4,webm,ogg|max:51200',
        ], [
            'ad_image.mimes' => __('messages.ad.media_extension_message'),
            'ad_image.max' => __('messages.ad.media_size_message'),
            'position.required' => __('messages.ad.position_required'),
            'link_url.url' => __('messages.ad.valid_url'),
        ]);
        
        $input = $request->all();
        $pages = $request->input('page', []);
        
        if (in_array('all', $pages)) {
            $input['page'] = ['all'];
        } else {
            $input['page'] = array_values(array_unique($pages));
        }
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
