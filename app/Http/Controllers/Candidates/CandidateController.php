<?php

namespace App\Http\Controllers\Candidates;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CandidateUpdateAddressDetailsRequest;
use App\Http\Requests\CandidateUpdateAwardRequest;
use App\Http\Requests\CandidateUpdateCareerApplicationRequest;
use App\Http\Requests\CandidateUpdateCvPrivacyRequest;
use App\Http\Requests\CandidateUpdateDisabilityInformationRequest;
use App\Http\Requests\CandidateUpdateExtraCurricularRequest;
use App\Http\Requests\CandidateUpdateGeneralInformationRequest;
use App\Http\Requests\CandidateUpdateLinkRequest;
use App\Http\Requests\CandidateUpdateOnlineProfileRequest;
use App\Http\Requests\CandidateUpdateOtherRequest;
use App\Http\Requests\CandidateUpdatePersonalDetailsRequest;
use App\Http\Requests\CandidateUpdatePortfolioRequest;
use App\Http\Requests\CandidateUpdatePreferredAreaRequest;
use App\Http\Requests\CandidateUpdateProfileRequest;
use App\Http\Requests\CandidateUpdateProjectRequest;
use App\Http\Requests\CandidateUpdatePublicationRequest;
use App\Http\Requests\CandidateUpdateReferenceRequest;
use App\Http\Requests\CandidateUpdateRelevantInformationRequest;
use App\Http\Requests\CandidateResumeUploadRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateCandidateProfileRequest;
use App\Models\CandidateEducation;
use App\Models\CandidateAccomplishment;
use App\Models\CandidateExperience;
use App\Models\CandidateExtraCurricular;
use App\Models\CandidateLink;
use App\Models\CandidateReference;
use App\Models\CandidateRetiredArmyEmployment;
use App\Models\CandidateSkill;
use App\Models\CandidateTraining;
use App\Models\EducationBoard;
use App\Models\EducationDegreeTitle;
use App\Models\EducationMajorGroup;
use App\Models\FavouriteCompany;
use App\Models\FavouriteJob;
use App\Models\JobApplication;
use App\Models\JobApplicationSchedule;
use App\Models\RequiredDegreeLevel;
use App\Models\User;
use App\Repositories\Candidates\CandidateRepository;
use App\Services\ApplicationCvService;
use App\Services\CandidateProfileCompletionService;
use App\Services\ResumePreviewService;
use Auth;
use Carbon\Carbon;
use Exception;
use Flash;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class CandidateController extends AppBaseController
{
    /** @var CandidateRepository */
    private $candidateRepository;

    private ApplicationCvService $applicationCvService;

    private ResumePreviewService $resumePreviewService;

    /**
     * CandidateController constructor.
     */
    public function __construct(
        CandidateRepository $candidateRepo,
        ApplicationCvService $applicationCvService,
        ResumePreviewService $resumePreviewService
    )
    {
        $this->candidateRepository = $candidateRepo;
        $this->applicationCvService = $applicationCvService;
        $this->resumePreviewService = $resumePreviewService;
    }

    /**
     * @return Factory|View
     *
     * @throws Exception
     */
    public function editProfile(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();

        $user->phone = preparePhoneNumber($user->phone, $user->region_code);
        $data = $this->candidateRepository->prepareData();
        $data['profileCompletion'] = $user->candidate
            ? app(CandidateProfileCompletionService::class)->calculate($user->candidate)
            : ['percentage' => 0, 'completed' => 0, 'total' => 11, 'color' => '#f04438'];
        $countries = getCountries();
        $states = $cities = null;
        if (! empty($user->country_id)) {
            $states = getStates($user->country_id);
        }
        if (! empty($user->state_id)) {
            $cities = getCities($user->state_id);
        }
        $data['candidateExtraCurriculars'] = collect();
        if (Schema::hasTable('candidate_extra_curriculars')) {
            $data['candidateExtraCurriculars'] = CandidateExtraCurricular::where('candidate_id', $user->owner_id)
                ->orderBy('id')
                ->get();
        }
        $data['candidateLinks'] = collect();
        if (Schema::hasTable('candidate_links')) {
            $data['candidateLinks'] = CandidateLink::where('candidate_id', $user->owner_id)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();
        }
        $data['candidateReferences'] = collect();
        if (Schema::hasTable('candidate_references')) {
            $data['candidateReferences'] = CandidateReference::where('candidate_id', $user->owner_id)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();
        }
        $candidateSkills = $user->candidateSkill()->pluck('skill_id')->toArray();
        $candidateLanguage = $user->candidateLanguage()->pluck('language_id')->toArray();
        $data['candidateLanguageItems'] = $this->candidateLanguageItems($user);
        $data['candidateSkillRows'] = collect();
        if (Schema::hasTable('candidate_skills')) {
            $candidateSkillRelations = ['skill'];
            if (Schema::hasTable('candidate_skill_sources')) {
                $candidateSkillRelations[] = 'sources';
            }
            $data['candidateSkillRows'] = CandidateSkill::with($candidateSkillRelations)
                ->where('user_id', $user->id)
                ->get();
        }
        $sectionAliases = [
            'general' => 'personal-information',
            'career-informations' => 'education-training',
            'cv-builder' => 'other-information',
        ];
        $sectionName = ($request->section === null) ? 'personal-information' : $request->section;
        $sectionName = $sectionAliases[$sectionName] ?? $sectionName;
        $allowedSections = [
            'personal-information',
            'employment',
            'education-training',
            'other-information',
            'accomplishment',
            'resume',
        ];
        abort_unless(in_array($sectionName, $allowedSections, true), 404);
        $data['sectionName'] = $sectionName;
        if ($sectionName == 'personal-information') {
            if (! empty($user->country_id)) {
                $states = getStates($user->country_id);
            }
            if (! empty($user->state_id)) {
                $cities = getCities($user->state_id);
            }
        }
        if ($sectionName == 'accomplishment') {
            $data['candidatePortfolios'] = collect();
            $data['candidatePublications'] = collect();
            $data['candidateAwards'] = collect();
            $data['candidateProjects'] = collect();
            $data['candidateOthers'] = collect();
            if (Schema::hasTable('candidate_accomplishments')) {
                $data['candidatePortfolios'] = CandidateAccomplishment::where('candidate_id', $user->owner_id)
                    ->where('type', CandidateAccomplishment::TYPE_PORTFOLIO)
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get();
                $data['candidatePublications'] = CandidateAccomplishment::where('candidate_id', $user->owner_id)
                    ->where('type', CandidateAccomplishment::TYPE_PUBLICATION)
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get();
                $data['candidateAwards'] = CandidateAccomplishment::where('candidate_id', $user->owner_id)
                    ->where('type', CandidateAccomplishment::TYPE_AWARD)
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get();
                $data['candidateProjects'] = CandidateAccomplishment::where('candidate_id', $user->owner_id)
                    ->where('type', CandidateAccomplishment::TYPE_PROJECT)
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get();
                $data['candidateOthers'] = CandidateAccomplishment::where('candidate_id', $user->owner_id)
                    ->where('type', CandidateAccomplishment::TYPE_OTHER)
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get();
            }
        }

        if ($sectionName == 'resume' && $user->candidate !== null) {
            $this->applicationCvService->ensure($user->candidate, true);
        }

        if ($sectionName == 'education-training' || $sectionName == 'other-information' || $sectionName == 'employment') {
            $data['candidateExperiences'] = CandidateExperience::with('expertises')->where('candidate_id',
                $user->owner_id)->orderBy('sort_order')->orderByDesc('id')->get();
            foreach ($data['candidateExperiences'] as $experience) {
                $experience->country = getCountryName($experience->country_id);
            }
            if ($sectionName == 'employment') {
                $data['candidateRetiredArmyEmployment'] = CandidateRetiredArmyEmployment::where('candidate_id', $user->owner_id)->first();
            }
            if ($sectionName == 'education-training' || $sectionName == 'other-information') {
                $data['candidateEducations'] = CandidateEducation::with('degreeLevel')->where('candidate_id',
                    $user->owner_id)->orderByDesc('id')->get();
                foreach ($data['candidateEducations'] as $education) {
                    $education->country = getCountryName($education->country_id);
                }
                $hasEducationLookupSchema = Schema::hasColumn('education_degree_levels', 'code')
                    && Schema::hasTable('education_degree_titles')
                    && Schema::hasTable('education_major_groups')
                    && Schema::hasTable('education_boards');

                if ($hasEducationLookupSchema) {
                    $degreeLevels = RequiredDegreeLevel::whereNotNull('code')
                        ->orderBy('sort_order')
                        ->orderBy('name')
                        ->get();
                    $data['degreeLevels'] = $degreeLevels->pluck('name', 'id');
                    $data['educationLevelMeta'] = $degreeLevels
                        ->keyBy('id')
                        ->map(function (RequiredDegreeLevel $level) {
                            return [
                                'code' => $level->code,
                                'show_board' => (bool) $level->show_board,
                                'show_major' => (bool) $level->show_major,
                                'show_summary_checkbox' => (bool) $level->show_summary_checkbox,
                            ];
                        });
                    $data['educationDegreeTitleOptions'] = EducationDegreeTitle::with('degreeLevel')
                        ->where('is_active', true)
                        ->orderBy('sort_order')
                        ->get()
                        ->groupBy('degreeLevel.code')
                        ->map(fn ($items) => $items->pluck('name')->values());
                    $data['educationMajorGroupOptions'] = EducationMajorGroup::with('degreeLevel')
                        ->where('is_active', true)
                        ->orderBy('sort_order')
                        ->get()
                        ->groupBy('degreeLevel.code')
                        ->map(fn ($items) => $items->pluck('name')->values());
                    $data['educationBoardOptions'] = EducationBoard::where('is_active', true)
                        ->orderBy('sort_order')
                        ->pluck('name')
                        ->values();
                } else {
                    $data['degreeLevels'] = RequiredDegreeLevel::orderBy('name')->pluck('name', 'id');
                    $data['educationLevelMeta'] = collect();
                    $data['educationDegreeTitleOptions'] = collect();
                    $data['educationMajorGroupOptions'] = collect();
                    $data['educationBoardOptions'] = collect();
                }
                $data['candidateTrainings'] = CandidateTraining::where('candidate_id', $user->owner_id)
                    ->orderBy('sort_order')
                    ->orderByDesc('id')
                    ->get();
            }
        }

        return view("candidate.profile.$sectionName",
            compact('user', 'data', 'countries', 'states', 'cities', 'candidateSkills', 'candidateLanguage'));
    }

    /**
     * @throws Exception
     */
    public function showFavouriteJobs(): View
    {
        return view('candidate.favourite_jobs.index');
    }

    public function deleteFavouriteJob(FavouriteJob $favouriteJob): JsonResponse
    {
        $userId = getLoggedInUserId();
        $fevouriteJobId = FavouriteJob::whereUserId($userId)->pluck('id')->toArray();

        if (! in_array($favouriteJob->id, $fevouriteJobId)) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $favouriteJob->delete();

        return $this->sendSuccess(__('messages.flash.fav_job_remove'));
    }

    /**
     * @return RedirectResponse|Redirector
     *
     * @throws \Throwable
     */
    public function updateProfile(CandidateUpdateProfileRequest $request): RedirectResponse
    {
        $this->candidateRepository->updateProfile($request->validated());
        $this->applicationCvService->ensure(Auth::user()->candidate->fresh(), true);

        Flash::success(__('messages.flash.candidate_profile'));


        return redirect(route('candidate.profile'));
    }

    /**
     * @throws \Throwable
     */    private function flashProfileCompletion(?\App\Models\Candidate $candidate): void
    {
        if (! $candidate) {
            return;
        }

        $completion = app(\App\Services\CandidateProfileCompletionService::class)->calculate($candidate);
        $percentage = $completion['percentage'] ?? 0;

        session()->flash('profile_incomplete', [
            'percentage' => $percentage,
            'profile_url' => route('candidate.profile'),
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function updatePersonalDetails(CandidateUpdatePersonalDetailsRequest $request)
    {
        $input = $request->validated();

        if ($request->hasFile('image')) {
            $input['image'] = $request->file('image');
        }

        $this->candidateRepository->updatePersonalDetails($input);
        $this->flashProfileCompletion(Auth::user()->candidate?->fresh());

        if ($request->ajax() || $request->wantsJson()) {
            return $this->sendSuccess(__('messages.flash.candidate_profile'));
        }

        Flash::success(__('messages.flash.candidate_profile'));

        return redirect(route('candidate.profile', ['section' => 'personal-information']));
    }

    /**
     * @throws \Throwable
     */
    public function updateAddressDetails(CandidateUpdateAddressDetailsRequest $request)
    {
        $this->candidateRepository->updateAddressDetails($request->validated());
        $this->flashProfileCompletion(Auth::user()->candidate?->fresh());

        if ($request->ajax() || $request->wantsJson()) {
            return $this->sendSuccess(__('messages.flash.candidate_profile'));
        }

        Flash::success(__('messages.flash.candidate_profile'));

        return redirect(route('candidate.profile', ['section' => 'personal-information']));
    }

    /**
     * @throws \Throwable
     */
    public function updateCareerApplication(CandidateUpdateCareerApplicationRequest $request)
    {
        $this->candidateRepository->updateCareerApplication($request->validated());
        $this->applicationCvService->ensure(Auth::user()->candidate->fresh(), true);

        return $this->sendResponse($this->extraCurricularResponse($extraCurricular), __('messages.flash.candidate_profile'));
    }

    public function updateExtraCurricular(
        CandidateExtraCurricular $candidateExtraCurricular,
        CandidateUpdateExtraCurricularRequest $request
    ): JsonResponse {
        if ($candidateExtraCurricular->candidate_id !== Auth::user()->owner_id) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $extraCurricular = $this->candidateRepository->updateExtraCurricular(
            $candidateExtraCurricular,
            $request->validated()
        );

        return $this->sendResponse($this->extraCurricularResponse($extraCurricular), __('messages.flash.candidate_profile'));
    }

    public function destroyExtraCurricular(CandidateExtraCurricular $candidateExtraCurricular): JsonResponse
    {
        if ($candidateExtraCurricular->candidate_id !== Auth::user()->owner_id) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $candidateExtraCurricular->delete();

        return $this->sendSuccess(__('messages.flash.candidate_profile'));
    }

    private function extraCurricularResponse(CandidateExtraCurricular $extraCurricular): array
    {
        return [
            'id' => $extraCurricular->id,
            'description' => $extraCurricular->description,
            'update_url' => route('candidate-profile.extracurricular-activities.update', $extraCurricular),
            'delete_url' => route('candidate-profile.extracurricular-activities.destroy', $extraCurricular),
        ];
    }

    public function storeLink(CandidateUpdateLinkRequest $request): JsonResponse
    {
        if ($this->candidateRepository->candidateLinkCount() >= 5) {
            return $this->sendError('You can add maximum 5 link accounts.');
        }

        if ($this->candidateRepository->candidateLinkPlatformExists($request->platform)) {
            return $this->sendError('This account type has already been added.');
        }

        $candidateLink = $this->candidateRepository->createLink($request->validated());

        return $this->sendResponse($this->linkResponse($candidateLink), __('messages.flash.candidate_profile'));
    }

    public function updateLink(CandidateLink $candidateLink, CandidateUpdateLinkRequest $request): JsonResponse
    {
        if ($candidateLink->candidate_id !== Auth::user()->owner_id) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        if ($this->candidateRepository->candidateLinkPlatformExists($request->platform, $candidateLink->id)) {
            return $this->sendError('This account type has already been added.');
        }

        $candidateLink = $this->candidateRepository->updateLink($candidateLink, $request->validated());

        return $this->sendResponse($this->linkResponse($candidateLink), __('messages.flash.candidate_profile'));
    }

    public function destroyLink(CandidateLink $candidateLink): JsonResponse
    {
        if ($candidateLink->candidate_id !== Auth::user()->owner_id) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $this->candidateRepository->deleteLink($candidateLink);

        return $this->sendSuccess(__('messages.flash.candidate_profile'));
    }

    private function linkResponse(CandidateLink $candidateLink): array
    {
        return [
            'id' => $candidateLink->id,
            'platform' => $candidateLink->platform,
            'url' => $candidateLink->url,
            'update_url' => route('candidate-profile.links.update', $candidateLink),
            'delete_url' => route('candidate-profile.links.destroy', $candidateLink),
        ];
    }

    public function storeReference(CandidateUpdateReferenceRequest $request): JsonResponse
    {
        $candidateReference = $this->candidateRepository->createReference($request->validated());

        return $this->sendResponse($this->referenceResponse($candidateReference), __('messages.flash.candidate_profile'));
    }

    public function updateReference(
        CandidateReference $candidateReference,
        CandidateUpdateReferenceRequest $request
    ): JsonResponse {
        if ($candidateReference->candidate_id !== Auth::user()->owner_id) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $candidateReference = $this->candidateRepository->updateReference($candidateReference, $request->validated());

        return $this->sendResponse($this->referenceResponse($candidateReference), __('messages.flash.candidate_profile'));
    }

    public function destroyReference(CandidateReference $candidateReference): JsonResponse
    {
        if ($candidateReference->candidate_id !== Auth::user()->owner_id) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $this->candidateRepository->deleteReference($candidateReference);

        return $this->sendSuccess(__('messages.flash.candidate_profile'));
    }

    private function referenceResponse(CandidateReference $candidateReference): array
    {
        return [
            'id' => $candidateReference->id,
            'name' => $candidateReference->name,
            'designation' => $candidateReference->designation,
            'organization' => $candidateReference->organization,
            'email' => $candidateReference->email,
            'relation' => $candidateReference->relation,
            'mobile' => $candidateReference->mobile,
            'officePhone' => $candidateReference->office_phone,
            'residentialPhone' => $candidateReference->residential_phone,
            'address' => $candidateReference->address,
            'update_url' => route('candidate-profile.references.update', $candidateReference),
            'delete_url' => route('candidate-profile.references.destroy', $candidateReference),
        ];
    }

    public function storePortfolio(CandidateUpdatePortfolioRequest $request): JsonResponse
    {
        if ($this->candidateRepository->portfolioCount() >= 2) {
            return $this->sendError('You can add maximum 2 portfolios.');
        }

        $portfolio = $this->candidateRepository->createPortfolio($request->validated());

        return $this->sendResponse($this->portfolioResponse($portfolio), __('messages.flash.candidate_profile'));
    }

    public function updatePortfolio(
        CandidateAccomplishment $candidateAccomplishment,
        CandidateUpdatePortfolioRequest $request
    ): JsonResponse {
        if (
            $candidateAccomplishment->candidate_id !== Auth::user()->owner_id
            || $candidateAccomplishment->type !== CandidateAccomplishment::TYPE_PORTFOLIO
        ) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $portfolio = $this->candidateRepository->updatePortfolio($candidateAccomplishment, $request->validated());

        return $this->sendResponse($this->portfolioResponse($portfolio), __('messages.flash.candidate_profile'));
    }

    public function destroyPortfolio(CandidateAccomplishment $candidateAccomplishment): JsonResponse
    {
        if (
            $candidateAccomplishment->candidate_id !== Auth::user()->owner_id
            || $candidateAccomplishment->type !== CandidateAccomplishment::TYPE_PORTFOLIO
        ) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $this->candidateRepository->deletePortfolio($candidateAccomplishment);

        return $this->sendSuccess(__('messages.flash.candidate_profile'));
    }

    private function portfolioResponse(CandidateAccomplishment $portfolio): array
    {
        return [
            'id' => $portfolio->id,
            'title' => $portfolio->title,
            'url' => $portfolio->url,
            'description' => $portfolio->description,
            'update_url' => route('candidate-profile.portfolios.update', $portfolio),
            'delete_url' => route('candidate-profile.portfolios.destroy', $portfolio),
        ];
    }

    public function storePublication(CandidateUpdatePublicationRequest $request): JsonResponse
    {
        if ($this->candidateRepository->publicationCount() >= 5) {
            return $this->sendError('You can add maximum 5 publications.');
        }

        $publication = $this->candidateRepository->createPublication($request->validated());

        return $this->sendResponse($this->publicationResponse($publication), __('messages.flash.candidate_profile'));
    }

    public function updatePublication(
        CandidateAccomplishment $candidateAccomplishment,
        CandidateUpdatePublicationRequest $request
    ): JsonResponse {
        if (
            $candidateAccomplishment->candidate_id !== Auth::user()->owner_id
            || $candidateAccomplishment->type !== CandidateAccomplishment::TYPE_PUBLICATION
        ) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $publication = $this->candidateRepository->updatePublication($candidateAccomplishment, $request->validated());

        return $this->sendResponse($this->publicationResponse($publication), __('messages.flash.candidate_profile'));
    }

    public function destroyPublication(CandidateAccomplishment $candidateAccomplishment): JsonResponse
    {
        if (
            $candidateAccomplishment->candidate_id !== Auth::user()->owner_id
            || $candidateAccomplishment->type !== CandidateAccomplishment::TYPE_PUBLICATION
        ) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $this->candidateRepository->deletePublication($candidateAccomplishment);

        return $this->sendSuccess(__('messages.flash.candidate_profile'));
    }

    private function publicationResponse(CandidateAccomplishment $publication): array
    {
        return [
            'id' => $publication->id,
            'title' => $publication->title,
            'issued_on' => optional($publication->issued_on)->format('d M Y'),
            'issued_on_value' => optional($publication->issued_on)->format('Y-m-d'),
            'url' => $publication->url,
            'description' => $publication->description,
            'update_url' => route('candidate-profile.publications.update', $publication),
            'delete_url' => route('candidate-profile.publications.destroy', $publication),
        ];
    }

    public function storeAward(CandidateUpdateAwardRequest $request): JsonResponse
    {
        if ($this->candidateRepository->awardCount() >= 5) {
            return $this->sendError('You can add maximum 5 awards.');
        }

        $award = $this->candidateRepository->createAward($request->validated());

        return $this->sendResponse($this->awardResponse($award), __('messages.flash.candidate_profile'));
    }

    public function updateAward(
        CandidateAccomplishment $candidateAccomplishment,
        CandidateUpdateAwardRequest $request
    ): JsonResponse {
        if (
            $candidateAccomplishment->candidate_id !== Auth::user()->owner_id
            || $candidateAccomplishment->type !== CandidateAccomplishment::TYPE_AWARD
        ) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $award = $this->candidateRepository->updateAward($candidateAccomplishment, $request->validated());

        return $this->sendResponse($this->awardResponse($award), __('messages.flash.candidate_profile'));
    }

    public function destroyAward(CandidateAccomplishment $candidateAccomplishment): JsonResponse
    {
        if (
            $candidateAccomplishment->candidate_id !== Auth::user()->owner_id
            || $candidateAccomplishment->type !== CandidateAccomplishment::TYPE_AWARD
        ) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $this->candidateRepository->deleteAward($candidateAccomplishment);

        return $this->sendSuccess(__('messages.flash.candidate_profile'));
    }

    private function awardResponse(CandidateAccomplishment $award): array
    {
        return [
            'id' => $award->id,
            'title' => $award->title,
            'issued_on' => optional($award->issued_on)->format('d M Y'),
            'issued_on_value' => optional($award->issued_on)->format('Y-m-d'),
            'url' => $award->url,
            'description' => $award->description,
            'update_url' => route('candidate-profile.awards.update', $award),
            'delete_url' => route('candidate-profile.awards.destroy', $award),
        ];
    }

    public function storeProject(CandidateUpdateProjectRequest $request): JsonResponse
    {
        if ($this->candidateRepository->projectCount() >= 5) {
            return $this->sendError('You can add maximum 5 projects.');
        }

        $project = $this->candidateRepository->createProject($request->validated());

        return $this->sendResponse($this->projectResponse($project), __('messages.flash.candidate_profile'));
    }

    public function updateProject(
        CandidateAccomplishment $candidateAccomplishment,
        CandidateUpdateProjectRequest $request
    ): JsonResponse {
        if (
            $candidateAccomplishment->candidate_id !== Auth::user()->owner_id
            || $candidateAccomplishment->type !== CandidateAccomplishment::TYPE_PROJECT
        ) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $project = $this->candidateRepository->updateProject($candidateAccomplishment, $request->validated());

        return $this->sendResponse($this->projectResponse($project), __('messages.flash.candidate_profile'));
    }

    public function destroyProject(CandidateAccomplishment $candidateAccomplishment): JsonResponse
    {
        if (
            $candidateAccomplishment->candidate_id !== Auth::user()->owner_id
            || $candidateAccomplishment->type !== CandidateAccomplishment::TYPE_PROJECT
        ) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $this->candidateRepository->deleteProject($candidateAccomplishment);

        return $this->sendSuccess(__('messages.flash.candidate_profile'));
    }

    private function projectResponse(CandidateAccomplishment $project): array
    {
        return [
            'id' => $project->id,
            'title' => $project->title,
            'issued_on' => optional($project->issued_on)->format('d M Y'),
            'issued_on_value' => optional($project->issued_on)->format('Y-m-d'),
            'url' => $project->url,
            'description' => $project->description,
            'update_url' => route('candidate-profile.projects.update', $project),
            'delete_url' => route('candidate-profile.projects.destroy', $project),
        ];
    }

    public function storeOther(CandidateUpdateOtherRequest $request): JsonResponse
    {
        if ($this->candidateRepository->otherCount() >= 5) {
            return $this->sendError('You can add maximum 5 other accomplishments.');
        }

        $other = $this->candidateRepository->createOther($request->validated());

        return $this->sendResponse($this->otherResponse($other), __('messages.flash.candidate_profile'));
    }

    public function updateOther(
        CandidateAccomplishment $candidateAccomplishment,
        CandidateUpdateOtherRequest $request
    ): JsonResponse {
        if (
            $candidateAccomplishment->candidate_id !== Auth::user()->owner_id
            || $candidateAccomplishment->type !== CandidateAccomplishment::TYPE_OTHER
        ) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $other = $this->candidateRepository->updateOther($candidateAccomplishment, $request->validated());

        return $this->sendResponse($this->otherResponse($other), __('messages.flash.candidate_profile'));
    }

    public function destroyOther(CandidateAccomplishment $candidateAccomplishment): JsonResponse
    {
        if (
            $candidateAccomplishment->candidate_id !== Auth::user()->owner_id
            || $candidateAccomplishment->type !== CandidateAccomplishment::TYPE_OTHER
        ) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $this->candidateRepository->deleteOther($candidateAccomplishment);

        return $this->sendSuccess(__('messages.flash.candidate_profile'));
    }

    private function otherResponse(CandidateAccomplishment $other): array
    {
        return [
            'id' => $other->id,
            'title' => $other->title,
            'issued_on' => optional($other->issued_on)->format('d M Y'),
            'issued_on_value' => optional($other->issued_on)->format('Y-m-d'),
            'url' => $other->url,
            'description' => $other->description,
            'update_url' => route('candidate-profile.others.update', $other),
            'delete_url' => route('candidate-profile.others.destroy', $other),
        ];
    }

    public function updateProfileImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|mimes:jpeg,jpg,png|max:1024',
        ]);

        $this->candidateRepository->profileUpdate([
            'image' => $request->file('image'),
        ]);

        return $this->sendResponse([
            'avatar' => Auth::user()->fresh()->avatar,
        ], __('messages.flash.candidate_profile'));
    }

    public function deleteProfileImage(): JsonResponse
    {
        Auth::user()->clearMediaCollection(User::PROFILE);

        return $this->sendResponse([
            'avatar' => Auth::user()->fresh()->avatar,
        ], __('messages.flash.media_delete'));
    }

    /**
     * @throws \Throwable
     */
    public function updateGeneralInformation(CandidateUpdateGeneralInformationRequest $request): JsonResponse
    {
        $user = $this->candidateRepository->updateGeneralInformation($request->validated());
        $user['candidateSkill'] = $user->candidateSkill()->pluck('name')->toArray();
        $user['candidateLanguageItems'] = $this->candidateLanguageItems($user)
            ->map(function ($language) {
                return [
                    'id' => $language->id,
                    'name' => $language->language,
                    'level' => $language->proficiency_level,
                    'reading' => $language->reading_level ?: $language->proficiency_level,
                    'writing' => $language->writing_level ?: $language->proficiency_level,
                    'speaking' => $language->speaking_level ?: $language->proficiency_level,
                ];
            });
        $candidateSkillRelations = ['skill'];
        if (Schema::hasTable('candidate_skill_sources')) {
            $candidateSkillRelations[] = 'sources';
        }
        $user['candidateSkillItems'] = CandidateSkill::with($candidateSkillRelations)
            ->where('user_id', $user->id)
            ->get()
            ->map(function (CandidateSkill $candidateSkill) {
                return [
                    'id' => $candidateSkill->skill_id,
                    'name' => optional($candidateSkill->skill)->name,
                    'sources' => $candidateSkill->relationLoaded('sources')
                        ? $candidateSkill->sources->pluck('source')->values()->toArray()
                        : [],
                ];
            })
            ->values();

        return $this->sendResponse($user, __('messages.flash.candidate_profile'));
    }

    private function candidateLanguageItems(User $user)
    {
        if (! Schema::hasTable('candidate_language')) {
            return collect();
        }

        $query = DB::table('candidate_language')
            ->join('languages', 'languages.id', '=', 'candidate_language.language_id')
            ->where('candidate_language.user_id', $user->id)
            ->select([
                'languages.id',
                'languages.language',
            ]);

        foreach (['proficiency_level', 'reading_level', 'writing_level', 'speaking_level'] as $column) {
            if (Schema::hasColumn('candidate_language', $column)) {
                $query->addSelect("candidate_language.$column");
            } else {
                $query->addSelect(DB::raw("NULL as $column"));
            }
        }

        return $query->orderBy('candidate_language.id')->get();
    }

    /**
     * @throws \Throwable
     */
    public function updateOnlineProfile(CandidateUpdateOnlineProfileRequest $request): JsonResponse
    {
        $user = $this->candidateRepository->updateGeneralInformation($request->validated());
        $user['onlineProfileLayout'] = view('candidate.profile.career_informations.show_online_profile',
            compact('user'))->render();
        $user['editonlineProfileLayout'] = view('candidate.profile.career_informations.edit_online_profile',
            compact('user'))->render();

        return $this->sendResponse($user, __('messages.flash.candidate_profile'));
    }

    /**
     * @return array|string
     *
     * @throws \Throwable
     */
    public function getCVTemplate()
    {
        $user = Auth::user();
        $data['user'] = $user;
        $data['candidateExperiences'] = CandidateExperience::with('expertises')->where('candidate_id',
            $user->owner_id)->orderBy('sort_order')->orderByDesc('id')->get();
        foreach ($data['candidateExperiences'] as $experience) {
            $experience->country = getCountryName($experience->country_id);
        }
        $data['candidateEducations'] = CandidateEducation::with('degreeLevel')->where('candidate_id',
            $user->owner_id)->orderByDesc('id')->get();
        foreach ($data['candidateEducations'] as $education) {
            $education->country = getCountryName($education->country_id);
        }

        $data['user']->phone = empty($data['user']->phone) ? 'N/A' : $data['user']->phone;

        return view('candidate.profile.cv_template')->with($data)->render();
    }

    /**
     * @return mixed
     */
    public function uploadResume(CandidateResumeUploadRequest $request)
    {
        $this->candidateRepository->uploadResume($request->validated());

        return $this->sendSuccess(__('messages.flash.resume_update'));
    }

    public function selectDefaultResume(Request $request): JsonResponse
    {
        $candidate = Auth::user()->candidate;
        $validated = $request->validate([
            'resume_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('media', 'id')->where(function ($query) use ($candidate) {
                    return $query->where('model_type', \App\Models\Candidate::class)
                        ->where('model_id', $candidate->id)
                        ->where('collection_name', \App\Models\Candidate::RESUME_PATH);
                }),
            ],
        ]);

        $resume = Media::whereKey($validated['resume_id'])
            ->where('model_type', \App\Models\Candidate::class)
            ->where('model_id', $candidate->id)
            ->where('collection_name', \App\Models\Candidate::RESUME_PATH)
            ->firstOrFail();

        $this->applicationCvService->makeDefault($candidate, $resume);

        return $this->sendSuccess(__('messages.candidate_profile.default_cv_updated'));
    }

    public function updateCvPrivacy(CandidateUpdateCvPrivacyRequest $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $candidate = Auth::user()->candidate;
        $candidate->update($request->validated());
        $this->applicationCvService->ensure($candidate->fresh(), true);

        if ($request->ajax() || $request->wantsJson()) {
            return $this->sendSuccess(__('messages.candidate_profile.cv_privacy_updated'));
        }

        Flash::success(__('messages.candidate_profile.cv_privacy_updated'));

        return redirect(route('candidate.profile', ['section' => 'resume']));
    }

    public function downloadResume(int $media): Media
    {
        $candidate = Auth::user()->candidate;

        /** @var Media $mediaItem */
        $mediaItem = Media::whereKey($media)
            ->where('model_type', \App\Models\Candidate::class)
            ->where('model_id', $candidate->id)
            ->where('collection_name', \App\Models\Candidate::RESUME_PATH)
            ->firstOrFail();

        if ($mediaItem->getCustomProperty(ApplicationCvService::APPLICATION_CV_PROPERTY, false)) {
            return $this->applicationCvService->ensure($candidate->fresh(), true);
        }

        return $mediaItem;
    }

    public function previewResume(int $media): Response
    {
        $candidate = Auth::user()->candidate;

        /** @var Media $mediaItem */
        $mediaItem = Media::whereKey($media)
            ->where('model_type', \App\Models\Candidate::class)
            ->where('model_id', $candidate->id)
            ->where('collection_name', \App\Models\Candidate::RESUME_PATH)
            ->firstOrFail();

        if ($mediaItem->getCustomProperty(ApplicationCvService::APPLICATION_CV_PROPERTY, false)) {
            $mediaItem = $this->applicationCvService->ensure($candidate->fresh(), true);
        }

        return $this->resumePreviewService->preview($mediaItem);
    }

    /**
     * @throws Exception
     */
    public function showFavouriteCompanies(): View
    {
        return view('candidate.favourite_companies.index');
    }

    /**
     * @return Factory|View
     */
    public function editJobAlert(): View
    {
        $data = $this->candidateRepository->getJobAlerts();

        return view('candidate.job_alert.edit')->with($data);
    }

    /**
     * @return RedirectResponse|Redirector
     */
    public function updateJobAlert(Request $request): RedirectResponse
    {
        $this->candidateRepository->updateJobAlerts($request->all());
        Flash::success(__('messages.flash.job_alert'));

        return redirect(route('candidate.job.alert'));
    }

    public function showChangePassword(): View
    {
        return view('candidate.change_password.index');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $input = $request->all();

        try {
            $user = $this->candidateRepository->changePassword($input);

            if (! $request->ajax() && ! $request->wantsJson()) {
                Flash::success(__('messages.flash.password_update'));

                return redirect()->route('candidate.change-password.form');
            }

            return $this->sendSuccess(__('messages.flash.password_update'));
        } catch (Exception $e) {
            if (! $request->ajax() && ! $request->wantsJson()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['password_current' => $e->getMessage()]);
            }

            return $this->sendError($e->getMessage(), 422);
        }
    }

    /**
     * Show the form for editing the specified User.
     */
    public function editCandidateProfile(): JsonResponse
    {
        $user = User::with('candidate')->where('id', '=', Auth::id())->first();

        return $this->sendResponse($user, __('messages.flash.candidate_retrieved'));
    }

    public function profileUpdate(UpdateCandidateProfileRequest $request): JsonResponse
    {
        $input = $request->validated();

        try {
            $employer = $this->candidateRepository->profileUpdate($input);
            Flash::success(__('messages.flash.candidate_profile'));

            return $this->sendResponse($employer, __('messages.flash.candidate_profile'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function showCandidateAppliedJob(): View
    {
        return view('candidate.applied_job.index');
    }

    /**
     * @return mixed
     *
     * @throws Exception
     */
    public function deletedResume(Media $media)
    {
        $mediaFile = Media::query()
            ->whereKey($media->id)
            ->where('model_type', \App\Models\Candidate::class)
            ->where('model_id', getLoggedInUser()->candidate->id)
            ->where('collection_name', \App\Models\Candidate::RESUME_PATH)
            ->first();

        if ($mediaFile) {
            if ($mediaFile->getCustomProperty(ApplicationCvService::APPLICATION_CV_PROPERTY, false)) {
                return $this->sendError(__('messages.candidate_profile.application_cv_delete_error'));
            }

            $wasDefault = (bool) $mediaFile->getCustomProperty('is_default', false);
            $mediaFile->delete();

            if ($wasDefault) {
                $candidate = getLoggedInUser()->candidate;
                $applicationCv = $this->applicationCvService->ensure($candidate);
                $this->applicationCvService->makeDefault($candidate, $applicationCv);
            }
        } else {
            return $this->sendError(__('messages.common.seems_message'));
        }

        return $this->sendSuccess(__('messages.flash.media_delete'));
    }

    /**
     * @return mixed
     */
    public function showAppliedJobs(JobApplication $jobApplication)
    {
        $candidateId = getLoggedInUser()->candidate->id;
        $jobCandidateId = JobApplication::whereCandidateId($candidateId)->pluck('id')->toArray();
        if (! in_array($jobApplication->id, $jobCandidateId)) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        return $this->sendResponse($jobApplication, __('messages.flash.retrieved'));
    }

    public function showScheduleSlotBook(JobApplication $jobApplication): JsonResponse
    {
        $candidateId = getLoggedInUser()->candidate->id;

        if ($jobApplication->candidate_id !== $candidateId) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        /** @var JobApplicationSchedule $jobApplicationSchedules */
        $jobApplicationSchedules = JobApplicationSchedule::with([
            'jobStage',
            'jobApplication.job.company' => function ($query) {
                $query->without('job.company.user.city', 'job.company.user.state', 'job.company.user.country',
                    'job.company.user.media');
            },
        ])->whereJobApplicationId($jobApplication->id);

        /** @var JobApplication $job */
        $job = JobApplication::with([
            'candidate.user' => function ($query) {
                $query->without('user.media', 'user.city', 'user.state', 'user.country');
            },
        ], 'jobStage.company.user')->without('job')->whereId($jobApplication->id)->first();

        $data = [];

        foreach ($jobApplicationSchedules->get() as $jobApplicationSchedule) {
            $stageName = $jobApplicationSchedule->jobStage?->name ?? 'Stage';
            $slotDateTime = ! empty($jobApplicationSchedule->date) 
                ? Carbon::parse($jobApplicationSchedule->date)->translatedFormat('jS M Y') . ' • ' . $jobApplicationSchedule->time 
                : '';

            $data[] = [
                'notes' => ! empty($jobApplicationSchedule->notes) ? $jobApplicationSchedule->notes : '',
                'company_name' => $jobApplicationSchedule->jobApplication?->job?->company?->user?->full_name ?? '',
                'stage_name' => $stageName,
                'slot_date_time' => $slotDateTime,
                'schedule_created_at' => Carbon::parse($jobApplicationSchedule->created_at)->translatedFormat('jS M Y, h:i A'),
            ];
        }
        $lastRecord = $jobApplicationSchedules->latest()->first();
        if (empty($lastRecord)) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $data['rejectedSlot'] = $lastRecord->status == JobApplicationSchedule::STATUS_REJECTED;

        $allJobSchedule = JobApplicationSchedule::whereJobApplicationId($jobApplication->id)
            ->where('batch', $lastRecord->batch)
            ->where('stage_id', $lastRecord->stage_id)
            ->get();

        if (! ($allJobSchedule->whereIn('status', JobApplicationSchedule::STATUS_SEND)->count() > 0)) {
            foreach ($allJobSchedule as $jobApplicationSchedule) {
                if ($jobApplicationSchedule->status == JobApplicationSchedule::STATUS_NOT_SEND) {
                    $data[] = [
                        'notes' => ! empty($jobApplicationSchedule->notes) ? $jobApplicationSchedule->notes : __('messages.job_stage.new_slot_send'),
                        'schedule_date' => Carbon::parse($jobApplicationSchedule->date)->translatedFormat('jS M Y'),
                        'schedule_time' => $jobApplicationSchedule->time,
                        'job_Schedule_Id' => $jobApplicationSchedule->id,
                        'isAllRejected' => $jobApplicationSchedule->status == JobApplicationSchedule::STATUS_REJECTED,
                    ];
                }
            }
        }
        $data['selectSlot'] = $allJobSchedule->whereIn('status', JobApplicationSchedule::STATUS_SEND)->toArray();
        $employerCancelNote = $allJobSchedule->where('employer_cancel_slot_notes')->first();
        $data['employer_cancel_note'] = isset($employerCancelNote) ? $employerCancelNote->employer_cancel_slot_notes : '';
        $data['employer_fullName'] = $job->candidate->user->full_name ?? '';
        $data['company_fullName'] = ! empty($job->jobStage?->company) ? $job->jobStage->company->user->full_name : '';
        $data['isSlotRejected'] = $jobApplicationSchedules->where('status',
            JobApplicationSchedule::STATUS_REJECTED)->count();
        $data['scheduleSelect'] = $allJobSchedule->where('status', JobApplicationSchedule::STATUS_SEND)->count();

        return $this->sendResponse($data, __('messages.flash.job_schedule_send'));
    }

    public function choosePreference(JobApplication $jobApplication, Request $request): JsonResponse
    {
        $candidateId = getLoggedInUser()->candidate->id;
        if ($jobApplication->candidate_id !== $candidateId) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $isRejectSlot = $request->filled('rejectSlot');

        if (! $isRejectSlot) {
            $request->validate([
                'slot_book' => 'required',
            ], [
                'slot_book.required' => __('messages.flash.slot_preference_field'),
            ]);
        }

        $request->validate([
            'choose_slot_notes' => 'required',
        ], [
            'choose_slot_notes.required' => 'Notes Field is required',
        ]);
        $scheduleId = $request->get('schedule_id');
        $slotNotes = $request->get('choose_slot_notes');
        if (! $isRejectSlot) {
            $schedule = JobApplicationSchedule::where('id', $scheduleId)
                ->where('job_application_id', $jobApplication->id)
                ->first();

            if (empty($schedule)) {
                return $this->sendError(__('messages.common.seems_message'));
            }

            $schedule->update(['status' => JobApplicationSchedule::STATUS_SEND, 'rejected_slot_notes' => $slotNotes]);
        } else {
            $jobApplicationSchedules = JobApplicationSchedule::whereJobApplicationId($jobApplication->id);
            $lastRecord = $jobApplicationSchedules->latest()->first();
            if (empty($lastRecord)) {
                return $this->sendError(__('messages.common.seems_message'));
            }

            JobApplicationSchedule::where([
                ['job_application_id', $jobApplication->id],
                ['stage_id', $lastRecord->stage_id],
                ['batch', $lastRecord->batch],
                ['status', JobApplicationSchedule::STATUS_NOT_SEND],
            ])->update([
                'status' => JobApplicationSchedule::STATUS_REJECTED,
                'rejected_slot_notes' => $slotNotes,
            ]);
        }

        if ($isRejectSlot) {
            return $this->sendSuccess(__('messages.flash.slot_reject'));
        }

        return $this->sendSuccess(__('messages.flash.slot_choose'));
    }

    public function destroyFavouriteCompany($id)
    {
        $favouriteCompany = FavouriteCompany::findOrFail($id);
        $userId = getLoggedInUser()->id;
        $fevCompanyId = FavouriteCompany::whereUserId($userId)->pluck('id')->toArray();

        if (! in_array($favouriteCompany->id, $fevCompanyId)) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $favouriteCompany->delete();

        return $this->sendSuccess(__('messages.flash.fav_company_delete'));
    }
}
