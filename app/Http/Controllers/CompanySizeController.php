<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCompanySizeRequest;
use App\Http\Requests\UpdateCompanySizeRequest;
use App\Imports\CompanySizesImport;
use App\Models\Company;
use App\Models\CompanySize;
use App\Repositories\CompanySizeRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class CompanySizeController extends AppBaseController
{
    /** @var CompanySizeRepository */
    private $companySizeRepository;

    public function __construct(CompanySizeRepository $companySizeRepo)
    {
        $this->companySizeRepository = $companySizeRepo;
    }

    /**
     * Display a listing of the CompanySize.
     *
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index(): View
    {
        return view('company_sizes.index');
    }

    /**
     * Store a newly created CompanySize in storage.
     */
    public function store(CreateCompanySizeRequest $request): JsonResponse
    {
        $rawSizes = str_replace(["\r\n", "\n", "\r"], ',', $request->size);
        $sizes = array_values(array_unique(array_filter(array_map('trim', explode(',', $rawSizes)))));

        $lastCompanySize = null;
        $createdCount = 0;

        foreach ($sizes as $size) {
            if (empty($size)) {
                continue;
            }

            $exists = CompanySize::where('size', $size)->exists();
            if (! $exists) {
                $input = $request->all();
                $input['size'] = $size;
                $lastCompanySize = $this->companySizeRepository->create($input);
                $createdCount++;
            }
        }

        if ($createdCount === 0 && ! empty($sizes)) {
            $lastCompanySize = CompanySize::where('size', $sizes[0])->first();
        }

        return $this->sendResponse($lastCompanySize, __('messages.flash.company_size_save'));
    }

    /**
     * Show the form for editing the specified CompanySize.
     */
    public function edit(CompanySize $companySize): JsonResponse
    {
        return $this->sendResponse($companySize, __('messages.flash.retrieved'));
    }

    /**
     * Update the specified CompanySize in storage.
     */
    public function update(UpdateCompanySizeRequest $request, CompanySize $companySize): JsonResponse
    {
        $input = $request->all();
        $this->companySizeRepository->update($input, $companySize->id);

        return $this->sendSuccess(__('messages.flash.company_size_update'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', function ($attribute, $value, $fail) {
                $ext = strtolower($value->getClientOriginalExtension());
                if (!in_array($ext, ['csv', 'xls', 'xlsx'])) {
                    $fail('The file field must be a file of type: csv, xls, xlsx.');
                }
            }],
        ]);

        $import = new CompanySizesImport;
        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Company Sizes import completed with validation errors. Please fix the failed rows and try again.',
                ], 422);
            }

            flash('Company Sizes import completed with validation errors. Please fix the failed rows and try again.')->error();

            return back()->withFailures($import->failures());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Company Sizes imported successfully.',
            ]);
        }

        flash('Company Sizes imported successfully.')->success();

        return back();
    }

    /**
     * Remove the specified CompanySize from storage.
     *
     *
     * @throws Exception
     */
    public function destroy(CompanySize $companySize): JsonResponse
    {
        $companyModels = [
            Company::class,
        ];
        $result = canDelete($companyModels, 'company_size_id', $companySize->id);
        if ($result) {
            return $this->sendError(__('messages.flash.company_size_cant_delete'));
        }
        $companySize->delete();

        return $this->sendSuccess(__('messages.flash.company_size_delete'));
    }
}
