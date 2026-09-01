<?php

namespace App\Http\Controllers;

use App\Exports\CandidatesExport;
use App\Http\Requests\CreateCandidateRequest;
use App\Http\Requests\UpdateCandidateRequest;
use App\Models\Candidate;
use App\Models\Country;
use App\Models\SalaryCurrency;
use App\Models\State;
use App\ReportedToCandidate;
use App\Repositories\Candidates\CandidateRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Flash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CandidateController extends AppBaseController
{
    /** @var CandidateRepository */
    private $candidateRepository;

    public function __construct(CandidateRepository $candidateRepo)
    {
        $this->candidateRepository = $candidateRepo;
    }

    /**
     * Display a listing of the Candidate.
     *
     * @param  Request  $request
     * @return Application|Factory|View
     *
     * @throws Exception
     */
    public function index(): View
    {
        return view('candidates.index');
    }

    /**
     * Show the form for creating a new Candidate.
     *
     * @return Application|Factory|View
     */
    public function create(): View
    {
        $data = $this->candidateRepository->prepareData();
        $countries = Country::pluck('name', 'id');
        $states = State::toBase()->pluck('name', 'id');
        $userThanas = old('city_id') ? getThanas(old('city_id')) : [];

        return view('candidates.create', compact('data', 'countries', 'states', 'userThanas'));
    }

    public function export(Request $request, string $format): BinaryFileResponse|StreamedResponse|Response
    {
        $candidates = $this->candidateExportQuery($request)->get();
        $fileName = 'candidates-'.time();

        if ($format === 'excel') {
            return Excel::download(new CandidatesExport($candidates), $fileName.'.xlsx');
        }

        if ($format === 'pdf') {
            return Pdf::loadView('exports.candidates_pdf', compact('candidates'))
                ->setPaper('a4', 'landscape')
                ->download($fileName.'.pdf');
        }

        return response()->streamDownload(function () use ($candidates) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $this->candidateExportHeadings());

            foreach ($candidates as $candidate) {
                fputcsv($handle, $this->candidateExportRow($candidate));
            }

            fclose($handle);
        }, $fileName.'.csv', ['Content-Type' => 'text/csv']);
    }

    public function print(Request $request): View
    {
        $candidates = $this->candidateExportQuery($request)->get();

        return view('exports.candidates_print', compact('candidates'));
    }

    /**
     * Store a newly created Candidate in storage.
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function store(CreateCandidateRequest $request): RedirectResponse
    {
        $input = $request->all();
        $candidate = $this->candidateRepository->store($input);

        Flash::success(__('messages.flash.candidate_save'));

        return redirect(route('candidates.index'));
    }

    /**
     * Display the specified Candidate.
     *
     * @return Application|Factory|View
     */
    public function show(Candidate $candidate): View
    {
        $currency = SalaryCurrency::pluck('currency_name', 'id');

        return view('candidates.show', compact('currency'))->with('candidate', $candidate);
    }

    /**
     * Show the form for editing the specified Candidate.
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function edit(Candidate $candidate): View
    {
        $user = $candidate->user;
        $user->phone = preparePhoneNumber($user->phone, $user->region_code);
        $data = $this->candidateRepository->prepareData();
        $data['candidateSkills'] = $user->candidateSkill()->pluck('skill_id')->toArray();
        $data['candidateLanguage'] = $user->candidateLanguage()->pluck('language_id')->toArray();
        $userStates = $userCities = $userThanas = null;
        $countries = Country::pluck('name', 'id');
        $states = State::toBase()->pluck('name', 'id');
        if (! empty($user->country_id)) {
            $userStates = getStates($user->country_id);
        }
        if (! empty($user->state_id)) {
            $userCities = getCities($user->state_id);
        }
        if (! empty($user->city_id)) {
            $userThanas = getThanas($user->city_id);
        }

        return view('candidates.edit', compact('candidate', 'user', 'data', 'countries', 'states', 'userStates', 'userCities', 'userThanas'));
    }

    /**
     * Update the specified Candidate in storage.
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function update(Candidate $candidate, UpdateCandidateRequest $request): RedirectResponse
    {
        $input = $request->all();
        if (empty($candidate)) {
            Flash::error(__('messages.flash.candidate_not_found'));

            return redirect(route('candidates.index'));
        }

        $candidate = $this->candidateRepository->updateCandidate($candidate, $input);

        Flash::success(__('messages.flash.candidate_update'));

        return redirect(route('candidates.index'));
    }

    /**
     * Remove the specified Candidate from storage.
     *
     *
     * @throws Exception
     */
    public function destroy(Candidate $candidate): JsonResponse
    {
        if ($candidate->user->hasRole('Candidate')) {
            $candidate->user->delete();
            $candidate->delete();

            return $this->sendSuccess(__('messages.flash.candidate_delete'));
        } else {
            return $this->sendError(__('messages.common.seems_message'));
        }
    }

    /**
     * @return mixed
     */
    public function changeStatus($id)
    {
        $candidate = Candidate::findOrFail($id);

        $status = ! $candidate->user->is_active;
        $candidate->user->update(['is_active' => $status]);

        if ($candidate) {
            if (Auth::user()->hasRole('Admin')) {
                $candidate->last_change = Auth::user()->id;
                $candidate->save();
            }
        }

        return $this->sendSuccess(__('messages.flash.status_update'));
    }

    public function reportCandidate(Request $request): JsonResponse
    {
        $input = $request->all();
        $this->candidateRepository->storeReportCandidate($input);

        return $this->sendSuccess(__('messages.flash.candidate_reported'));
    }

    /**
     * @param  Request  $request
     * @return Application|Factory|View
     *
     * @throws Exception
     */
    public function showReportedCandidates(): View
    {
        return view('candidate.reported_candidate.reported_candidates');
    }

    /**
     * @param  ReportedToCompany  $reportedToCompany
     * @return mixed
     *
     * @throws Exception
     */
    public function showReportedCandiateNote(Request $request)
    {
        $data = $this->candidateRepository->getReportedToCandidate($request->reportedToCandidate);
        $data['date'] = \Carbon\Carbon::parse($data->created_at)->formatLocalized('%d %b, %Y');

        return $this->sendResponse($data, 'Retrieved successfully.');
    }

    /**
     * @param  ReportedToCompany  $reportedToCompany
     * @return mixed
     *
     * @throws Exception
     */
    public function deleteReportedCandidate(ReportedToCandidate $reportedToCandidate)
    {
        $reportedToCandidate->delete();

        return $this->sendSuccess(__('messages.flash.reported_candidate_delete'));
    }

    /**
     * @return mixed
     */
    public function changeIsEmailVerified(Candidate $candidate)
    {
        if (empty($candidate->user->email_verified_at)) {
            $candidate->user->update([
                'email_verified_at' => Carbon::now(),
                'is_verified' => 1,
            ]);
        } else {
            $candidate->user->update(['email_verified_at' => null]);
        }

        if (Auth::user()->hasRole('Admin')) {
            $candidate->last_change = Auth::user()->id;
            $candidate->save();
        }

        return $this->sendSuccess(__('messages.flash.email_verify'));
    }

    /**
     * @return mixed
     */
    public function resendEmailVerification(Candidate $candidate)
    {
       $candidate->user->sendEmailVerificationNotification();
        if (Auth::user()->hasRole('Admin')) {
            $candidate->last_change = Auth::user()->id;
            $candidate->save();
        }

        return $this->sendSuccess(__('messages.flash.verification_mail'));
    }

    public function candidateExportExcel(): BinaryFileResponse
    {
        return Excel::download(new CandidatesExport(), 'candidates-'.time().'.xlsx');
    }

    public function resumes(): View
    {
        return view('resumes.index');
    }

    public function downloadResume($media)
    {
        try {
            $mediaFile = Media::query()
                ->whereKey($media)
                ->where('model_type', Candidate::class)
                ->where('collection_name', Candidate::RESUME_PATH)
                ->when(! Auth::user()->hasRole('Admin'), function ($query) {
                    $query->where('model_id', getLoggedInUser()->candidate->id);
                })
                ->first();

            if ($mediaFile) {
                return $mediaFile;
            }

            return view('errors.404');
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    public function deleteResume($id)
    {
        $media = Media::query()
            ->where('model_id', $id)
            ->where('model_type', Candidate::class)
            ->where('collection_name', Candidate::RESUME_PATH)
            ->firstOrFail();
        $media->delete();

        return $this->sendSuccess(__('messages.flash.resume_delete'));
    }

    private function candidateExportQuery(Request $request): Builder
    {
        $query = Candidate::query()
            ->with([
                'user.candidateSkill',
                'user.candidateLanguage',
                'user.country',
                'user.state',
                'user.city',
                'user.thana',
                'industry',
                'maritalStatus',
                'careerLevel',
                'functionalArea',
                'admin',
            ])
            ->latest();

        if ($request->filled('status') && (int) $request->get('status') !== Candidate::ALL) {
            $query->whereHas('user', function (Builder $userQuery) use ($request) {
                $userQuery->where('is_active', (int) $request->get('status') === Candidate::ACTIVE ? 1 : 0);
            });
        }

        if ($request->filled('immediate') && (int) $request->get('immediate') !== Candidate::ALL) {
            $query->where('immediate_available', (int) $request->get('immediate') === Candidate::IMMEDIATE_AVAILABLE ? 1 : 0);
        }

        return $query->select('candidates.*');
    }

    private function candidateExportHeadings(): array
    {
        return [
            'Name',
            'Email',
            'Phone',
            'Experience',
            'Birth Date',
            'Gender',
            'Country',
            'State',
            'City',
            'Thana',
            'Immediate Available',
            'Skills',
            'Languages',
            'Current Salary',
            'Expected Salary',
            'Status',
        ];
    }

    private function candidateExportRow(Candidate $candidate): array
    {
        return [
            $candidate->user?->full_name ?: 'N/A',
            $candidate->user?->email ?: 'N/A',
            $candidate->user?->phone ?: 'N/A',
            ! empty($candidate->experience) ? $candidate->experience.' Year' : 'N/A',
            $candidate->user?->dob ? Carbon::parse($candidate->user->dob)->format('d-m-y') : 'N/A',
            $candidate->user?->gender === 0 ? __('messages.common.male') : ($candidate->user?->gender === 1 ? __('messages.common.female') : 'N/A'),
            $candidate->user?->country_name ?: 'N/A',
            $candidate->user?->state_name ?: 'N/A',
            $candidate->user?->city_name ?: 'N/A',
            $candidate->user?->thana_name ?: 'N/A',
            $candidate->immediate_available ? __('messages.candidate.immediate_available') : __('messages.candidate.not_immediate_available'),
            $candidate->user?->candidateSkill?->pluck('name')->implode(', ') ?: 'No skills',
            $candidate->user?->candidateLanguage?->pluck('language')->implode(', ') ?: 'No languages',
            ! empty($candidate->current_salary) ? number_format($candidate->current_salary) : 'N/A',
            ! empty($candidate->expected_salary) ? number_format($candidate->expected_salary) : 'N/A',
            $candidate->user?->is_active ? __('messages.common.active') : __('messages.common.de_active'),
        ];
    }
}
