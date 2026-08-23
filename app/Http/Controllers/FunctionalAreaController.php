<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFunctionalAreaRequest;
use App\Http\Requests\UpdateFunctionalAreaRequest;
use App\Imports\FunctionalAreasImport;
use App\Models\Candidate;
use App\Models\FunctionalArea;
use App\Models\Job;
use App\Repositories\FunctionalAreaRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class FunctionalAreaController extends AppBaseController
{
    /** @var FunctionalAreaRepository */
    private $functionalAreaRepository;

    public function __construct(FunctionalAreaRepository $functionalAreaRepo)
    {
        $this->functionalAreaRepository = $functionalAreaRepo;
    }

    /**
     * Display a listing of the FunctionalArea.
     *
     * @param  Request  $request
     * @return Factory|View
     */
    public function index(): View
    {
        return view('functional_areas.index');
    }

    /**
     * Store a newly created FunctionalArea in storage.
     */
    public function store(CreateFunctionalAreaRequest $request): JsonResponse
    {
        $input = $request->all();
        $functionalArea = $this->functionalAreaRepository->create($input);

        return $this->sendResponse($functionalArea, __('messages.flash.functional_area_save'));
    }

    /**
     * Show the form for editing the specified FunctionalArea.
     */
    public function edit(FunctionalArea $functionalArea): JsonResponse
    {
        return $this->sendResponse($functionalArea, 'Functional Area successfully retrieved.');
    }

    /**
     * Update the specified FunctionalArea in storage.
     */
    public function update(UpdateFunctionalAreaRequest $request, FunctionalArea $functionalArea): JsonResponse
    {
        $input = $request->all();
        $this->functionalAreaRepository->update($input, $functionalArea->id);

        return $this->sendSuccess(__('messages.flash.functional_area_update'));
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

        $import = new FunctionalAreasImport;
        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            $message = 'Functional areas import completed with validation errors. Please fix the failed rows and try again.';

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

        $message = 'Functional areas imported successfully. Imported: '.$import->importedCount().', skipped duplicates: '.$import->skippedCount().'.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        flash($message)->success();

        return back();
    }

    /**
     * Remove the specified FunctionalArea from storage.
     *
     *
     * @throws Exception
     */
    public function destroy(FunctionalArea $functionalArea): JsonResponse
    {
        $Models = [
            Candidate::class,
            Job::class,
        ];
        $result = canDelete($Models, 'functional_area_id', $functionalArea->id);
        if ($result) {
            return $this->sendError(__('messages.flash.functional_area_cant_delete'));
        }
        $functionalArea->delete();

        return $this->sendSuccess(__('messages.flash.functional_area_delete'));
    }
}
