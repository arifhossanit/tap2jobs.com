<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\WebRegisterRequest;
use App\Providers\RouteServiceProvider;
use App\Models\CompanySize;
use App\Models\Country;
use App\Models\Industry;
use App\Models\IndustryType;
use App\Models\User;
use App\Repositories\WebRegisterRepository;
use Flash;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends AppBaseController
{
    /** @var WebRegisterRepository */
    private $webRegisterRepository;

    public function __construct(WebRegisterRepository $webRegisterRepository)
    {
        $this->webRegisterRepository = $webRegisterRepository;
    }

    /**
     * @return Factory|View
     */
    public function candidateRegister(): View
    {
        storeIntendedUrlFromPrevious();
        $isGoogleReCaptchaEnabled = $this->webRegisterRepository->getSettingForReCaptcha();

        return view('front_web.auth.candidate_register', compact('isGoogleReCaptchaEnabled'));
    }

    /**
     * @return Factory|View
     */
    public function employerRegister(): View
    {
        storeIntendedUrlFromPrevious();
        $isGoogleReCaptchaEnabled = $this->webRegisterRepository->getSettingForReCaptcha();
        $countries = getCountries();
        $bangladeshId = Country::where('name', 'Bangladesh')->value('id');
        $states = $bangladeshId ? getStates($bangladeshId) : [];
        $cities = old('state_id') ? getCities(old('state_id')) : [];
        $thanas = old('city_id') ? getThanas(old('city_id')) : [];
        $industryTypes = IndustryType::orderBy('sort_order')->pluck('name', 'id');
        $industryRecords = Industry::whereNull('created_by')
            ->orderBy('name')
            ->get(['id', 'name', 'industry_type_id']);
        $companySizes = CompanySize::all()
            ->sortBy(fn (CompanySize $companySize) => CompanySize::parseRange($companySize->size)[0] ?? PHP_INT_MAX)
            ->pluck('size');

        return view('front_web.auth.employer_register', compact(
            'isGoogleReCaptchaEnabled',
            'countries',
            'bangladeshId',
            'states',
            'cities',
            'thanas',
            'industryTypes',
            'industryRecords',
            'companySizes'
        ));
    }

    public function registrationStates(Request $request): JsonResponse
    {
        $validated = $request->validate(['country_id' => ['required', 'integer', 'exists:countries,id']]);

        return $this->sendResponse(getStates($validated['country_id']), 'States retrieved successfully.');
    }

    public function usernameAvailability(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:100', 'regex:/^[\p{L}\p{M}\p{N}._-]+$/u'],
        ]);
        $available = ! User::where('username', $validated['username'])->exists();

        return response()->json([
            'available' => $available,
            'message' => $available ? '' : 'This Username already exists. Try another.',
        ]);
    }

    public function registrationCities(Request $request): JsonResponse
    {
        $validated = $request->validate(['state_id' => ['required', 'integer', 'exists:states,id']]);

        return $this->sendResponse(getCities($validated['state_id']), 'Cities retrieved successfully.');
    }

    public function registrationThanas(Request $request): JsonResponse
    {
        $validated = $request->validate(['city_id' => ['required', 'integer', 'exists:cities,id']]);

        return $this->sendResponse(getThanas($validated['city_id']), 'Thanas retrieved successfully.');
    }

    /**
     * @throws \Throwable
     */
    public function register(WebRegisterRequest $request): JsonResponse
    {
        $input = $request->all();
        $user = $this->webRegisterRepository->store($input);
        Auth::login($user);

        if ((int) $input['type'] === 1) {
            session()->forget('url.intended');
            $redirectUrl = route('candidate.profile');
            $candidate = $user->candidate;
            $percentage = 0;
            if ($candidate) {
                $completion = app(\App\Services\CandidateProfileCompletionService::class)->calculate($candidate);
                $percentage = $completion['percentage'] ?? 0;
            }
            session()->flash('profile_incomplete', [
                'percentage' => $percentage,
                'profile_url' => route('candidate.profile')
            ]);
        } else {
            $redirectUrl = resolveIntendedRedirectUrl(RouteServiceProvider::EMPLOYER_HOME, $user);
        }

        $userType = ($input['type'] == 1) ? __('messages.notification_settings.candidate') : __('messages.company.employer');
        Flash::success(__('messages.flash.register_success_mail_active'));

        return $this->sendResponse(
            ['redirectUrl' => $redirectUrl],
            "{$userType} ".__('messages.flash.registration_done')
        );
    }
}
