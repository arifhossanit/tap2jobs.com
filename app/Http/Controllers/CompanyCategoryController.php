<?php

namespace App\Http\Controllers;

use App\Models\CompanyCategory;
use App\Models\CompanySize;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CompanyCategoryController extends AppBaseController
{
    public function index(): View
    {
        $companyCategories = CompanyCategory::query()
            ->withCount('companySizes')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('company_categories.index', compact('companyCategories'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string'],
        ]);

        $rawNames = str_replace(["\r\n", "\n", "\r"], ',', $request->input('name'));
        $names = array_values(array_unique(array_filter(array_map('trim', explode(',', $rawNames)))));

        $lastCompanyCategory = null;
        $createdCount = 0;

        foreach ($names as $name) {
            $lastCompanyCategory = CompanyCategory::firstOrCreate(
                ['name' => $name],
                [
                    'sort_order' => $request->input('sort_order', 0),
                    'is_active' => $request->boolean('is_active', true),
                ]
            );

            if ($lastCompanyCategory->wasRecentlyCreated) {
                $createdCount++;
            }
        }

        if ($createdCount === 0 && ! empty($names)) {
            $lastCompanyCategory = CompanyCategory::where('name', $names[0])->first();
        }

        return $this->sendResponse($lastCompanyCategory, 'Company Category saved successfully.');
    }

    public function edit(CompanyCategory $companyCategory): JsonResponse
    {
        return $this->sendResponse($companyCategory, 'Company Category retrieved successfully.');
    }

    public function update(Request $request, CompanyCategory $companyCategory): JsonResponse
    {
        $input = $request->validate([
            'name' => [
                'required',
                'string',
                'max:170',
                Rule::unique('company_categories', 'name')->ignore($companyCategory->id),
            ],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $input['sort_order'] = $input['sort_order'] ?? 0;
        $input['is_active'] = $request->boolean('is_active');

        $companyCategory->update($input);

        return $this->sendSuccess('Company Category updated successfully.');
    }

    public function destroy(CompanyCategory $companyCategory): JsonResponse
    {
        if (CompanySize::where('company_category_id', $companyCategory->id)->exists()) {
            return $this->sendError('Company Category cannot be deleted because it is used by one or more company sizes.');
        }

        $companyCategory->delete();

        return $this->sendSuccess('Company Category deleted successfully.');
    }
}
