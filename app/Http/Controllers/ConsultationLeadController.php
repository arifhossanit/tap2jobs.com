<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConsultationLeadRequest;
use App\Http\Requests\UpdateConsultationLeadRequest;
use App\Models\Ad;
use App\Models\CompanySize;
use App\Models\ConsultationLead;
use App\Models\ProfileReferenceOption;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

        $consultationTypes = ProfileReferenceOption::options(
            ProfileReferenceOption::TYPE_CONSULTATION_TYPE,
            [ProfileReferenceOption::SCOPE_EMPLOYER]
        );
        $contactMethods = ProfileReferenceOption::options(
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
            ->with('success', 'Thanks. Your consultation request has been submitted successfully.');
    }

    public function index(Request $request): View
    {
        $query = ConsultationLead::query()
            ->with(['ad', 'companySize.companyCategory', 'companyCategory'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('company_category_id')) {
            $query->where('company_category_id', $request->integer('company_category_id'));
        }

        $consultationLeads = $query->paginate(20)->withQueryString();
        $statuses = ConsultationLead::STATUSES;
        $companyCategories = \App\Models\CompanyCategory::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        return view('consultation_leads.index', compact('consultationLeads', 'statuses', 'companyCategories'));
    }

    public function show(ConsultationLead $consultationLead): JsonResponse
    {
        $consultationLead->load(['ad', 'companySize.companyCategory', 'companyCategory']);

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

        return $this->sendSuccess('Consultation lead deleted successfully.');
    }
}
