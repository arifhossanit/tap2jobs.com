<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIndustryRequest;
use App\Http\Requests\UpdateIndustryRequest;
use App\Imports\IndustriesImport;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\Industry;
use App\Repositories\IndustryRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class IndustryController extends AppBaseController
{
    /** @var IndustryRepository */
    private $industryRepository;

    public function __construct(IndustryRepository $industryRepo)
    {
        $this->industryRepository = $industryRepo;
    }

    /**
     * Display a listing of the Industry.
     *
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index(): View
    {
        return view('industries.index');
    }

    /**
     * Store a newly created Industry in storage.
     */
    public function store(CreateIndustryRequest $request): JsonResponse
    {
        $input = $request->all();
        $industry = $this->industryRepository->create($input);

        return $this->sendResponse($industry, __('messages.flash.industry_save'));
    }

    /**
     * Store an industry suggested from the employer profile editor.
     */
    public function storeForEmployer(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'industry_type_id' => ['nullable', 'integer', 'exists:industry_types,id'],
            'name' => ['required', 'string', 'max:150', Rule::unique('industries', 'name')],
        ]);

        $industry = Industry::create([
            'industry_type_id' => $validated['industry_type_id'] ?? null,
            'name' => trim($validated['name']),
            'description' => trim($validated['name']),
            'created_by' => Auth::id(),
            'is_default' => false,
        ]);

        return $this->sendResponse(
            $industry->only(['id', 'name', 'industry_type_id']),
            'Industry added successfully.'
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Industry $industry): JsonResponse
    {
        return $this->sendResponse($industry, 'Industry Retrieved Successfully.');
    }

    /**
     * Show the form for editing the specified Industry.
     */
    public function show(Industry $industry): JsonResponse
    {
        return $this->sendResponse($industry, 'Industry Retrieved Successfully.');
    }

    /**
     * Update the specified Industry in storage.
     */
    public function update(UpdateIndustryRequest $request, Industry $industry): JsonResponse
    {
        $input = $request->all();
        $this->industryRepository->update($input, $industry->id);

        return $this->sendSuccess(__('messages.flash.industry_update'));
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

        $import = new IndustriesImport;
        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            $message = 'Industries import completed with validation errors. Please fix the failed rows and try again.';

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'errors' => collect($import->failures())->map(fn ($failure) => [
                        'row' => $failure->row(),
                        'attribute' => $failure->attribute(),
                        'errors' => $failure->errors(),
                        'values' => $failure->values(),
                    ])->values(),
                ], 422);
            }

            return back()->withFailures($import->failures());
        }

        $message = 'Industries imported successfully. Imported: '.$import->importedCount().', skipped duplicates: '.$import->skippedCount().'.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        flash($message)->success();

        return back();
    }

    /**
     * Remove the specified Industry from storage.
     *
     *
     * @throws Exception
     */
    public function destroy(Industry $industry): JsonResponse
    {
        $Models = [
            Candidate::class,
            Company::class,
        ];
        $result = canDelete($Models, 'industry_id', $industry->id);
        if ($result) {
            return $this->sendError(__('messages.flash.industry_cant_delete'));
        }
        $industry->delete();

        return $this->sendSuccess(__('messages.flash.industry_delete'));
    }
}


