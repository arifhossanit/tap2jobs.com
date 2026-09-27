<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Repositories\PrivacyPolicyRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Laracasts\Flash\Flash;

/**
 * Class PrivacyPolicyController
 */
class PrivacyPolicyController extends AppBaseController
{
    /** @var PrivacyPolicyRepository */
    private $privacyPolicyRepository;

    /**
     * PrivacyPolicyController constructor.
     */
    public function __construct(PrivacyPolicyRepository $privacyPolicyRepository)
    {
        $this->privacyPolicyRepository = $privacyPolicyRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index(Request $request): View
    {
        $privacyPolicy = Setting::pluck('value', 'key')->toArray();
        $sectionName = 'privacy_policy';

        return view('privacy_policy.index', compact('privacyPolicy', 'sectionName'));
    }

    public function termsConditions(): View
    {
        $privacyPolicy = Setting::pluck('value', 'key')->toArray();
        $sectionName = 'terms_conditions';

        return view('privacy_policy.index', compact('privacyPolicy', 'sectionName'));
    }

    public function update(Request $request): RedirectResponse
    {
        $input = $request->only(['privacy_policy', 'terms_conditions']);
        foreach ($input as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                $setting->value = $value;
                $setting->save();
            }
        }

        Flash::success(__('messages.flash.policy_update'));

        return Redirect::back();
    }
}
