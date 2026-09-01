<?php

namespace App\Http\Controllers;

use App\Exports\ConsultationLeadsExport;
use App\Http\Requests\StoreConsultationLeadRequest;
use App\Http\Requests\UpdateConsultationLeadRequest;
use App\Models\Ad;
use App\Models\CompanySize;
use App\Models\ConsultationLead;
use App\Models\ProfileReferenceOption;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ConsultationLeadController extends AppBaseController
{
    public function create(Request $request): View
    {
        $ad = null;
        if ($request->filled('ad_id')) {
            $ad = Ad::query()->find($request->integer('ad_id'));
        }

        $companySizes = CompanySize::query()
            ->with('companyCategory')
            ->orderBy('id')
            ->get();

        $consultationTypes = ProfileReferenceOption::localizedOptions(
            ProfileReferenceOption::TYPE_CONSULTATION_TYPE,
            [ProfileReferenceOption::SCOPE_EMPLOYER]
        );
        $contactMethods = ProfileReferenceOption::localizedOptions(
            ProfileReferenceOption::TYPE_CONSULTATION_CONTACT_METHOD,
            [ProfileReferenceOption::SCOPE_EMPLOYER]
        );

        return view('front_web.consultation.create', compact('ad', 'companySizes', 'consultationTypes', 'contactMethods'));
    }

    public function store(StoreConsultationLeadRequest $request): RedirectResponse
    {
        $input = $request->validated();
        $companySize = null;

        if (! empty($input['company_size_id'])) {
            $companySize = CompanySize::query()->find($input['company_size_id']);
        }

        $input['company_category_id'] = $companySize?->company_category_id;
        $input['status'] = ConsultationLead::STATUS_NEW;
        $input['source_page'] = $input['source_page'] ?? url()->previous();
        $input['clicked_url'] = $input['clicked_url'] ?? $request->fullUrl();

        ConsultationLead::query()->create($input);

        return redirect()
            ->route('consultation.create', $request->only(['ad_id', 'utm_source', 'utm_medium', 'utm_campaign']))
            ->with('success', __('web.consultation.success'));
    }

    public function index(): View
    {
        return view('consultation_leads.index');
    }

    public function archived(): View
    {
        return view('consultation_leads.archived');
    }

    public function export(Request $request, string $format): BinaryFileResponse|StreamedResponse|Response
    {
        $leads = $this->consultationLeadQuery($request)->get();
        $leadSource = getSettingValue('application_name');
        $fileName = 'consultation-leads-'.time();

        if ($format === 'excel') {
            return Excel::download(new ConsultationLeadsExport($leads, $leadSource), $fileName.'.xlsx');
        }

        if ($format === 'pdf') {
            return Pdf::loadView('exports.consultation_leads_pdf', compact('leads', 'leadSource'))
                ->setPaper('a4', 'landscape')
                ->download($fileName.'.pdf');
        }

        return response()->streamDownload(function () use ($leads, $leadSource) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $this->consultationLeadExportHeadings());

            foreach ($leads as $lead) {
                fputcsv($handle, $this->consultationLeadExportRow($lead, $leadSource));
            }

            fclose($handle);
        }, $fileName.'.csv', ['Content-Type' => 'text/csv']);
    }

    public function print(Request $request): View
    {
        $leads = $this->consultationLeadQuery($request)->get();
        $leadSource = getSettingValue('application_name');

        return view('exports.consultation_leads_print', compact('leads', 'leadSource'));
    }

    public function show(ConsultationLead $consultationLead): JsonResponse
    {
        $consultationLead->load(['ad', 'companySize.companyCategory', 'companyCategory', 'employer']);

        return $this->sendResponse($consultationLead, 'Consultation lead retrieved successfully.');
    }

    public function update(UpdateConsultationLeadRequest $request, ConsultationLead $consultationLead): JsonResponse
    {
        $consultationLead->update($request->validated());

        return $this->sendSuccess('Consultation lead updated successfully.');
    }

    public function destroy(ConsultationLead $consultationLead): JsonResponse
    {
        $consultationLead->delete();

        return $this->sendSuccess('Consultation lead archived successfully.');
    }

    public function forceDestroy(int $id): JsonResponse
    {
        $consultationLead = ConsultationLead::query()->onlyTrashed()->findOrFail($id);
        $consultationLead->forceDelete();

        return $this->sendSuccess('Consultation lead permanently deleted successfully.');
    }

    public function restore(int $id): JsonResponse
    {
        $consultationLead = ConsultationLead::query()->onlyTrashed()->findOrFail($id);
        $consultationLead->restore();

        return $this->sendSuccess('Consultation lead restored successfully.');
    }

    private function consultationLeadQuery(Request $request): Builder
    {
        $query = ConsultationLead::query()
            ->with(['ad', 'companySize.companyCategory', 'companyCategory', 'employer'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('company_category_id')) {
            $query->where('company_category_id', $request->integer('company_category_id'));
        }

        return $query;
    }

    private function consultationLeadExportHeadings(): array
    {
        return [
            'Name',
            'Contact',
            'Company',
            'Category',
            'Type',
            'Lead Type',
            'Lead Source',
            'Status',
            'Submitted',
        ];
    }

    private function consultationLeadExportRow($lead, ?string $leadSource): array
    {
        return [
            $lead->name ?: 'N/A',
            trim($lead->phone."\n".$lead->email) ?: 'N/A',
            $lead->company_name ?: 'N/A',
            $lead->company_category_name ?: $lead->companyCategory?->name ?: $lead->companySize?->companyCategory?->name ?: 'N/A',
            $lead->consultation_type ? $lead->consultation_type_label : 'N/A',
            $lead->lead_from_label,
            $lead->source_page ?: $leadSource ?: 'N/A',
            $lead->status_label ?: 'N/A',
            $lead->created_at?->format('d M Y h:i A') ?: 'N/A',
        ];
    }
}
