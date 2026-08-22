<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCareerLevelRequest;
use App\Http\Requests\UpdateCareerLevelRequest;
use App\Imports\CareerLevelsImport;
use App\Models\Candidate;
use App\Models\CareerLevel;
use App\Models\Job;
use App\Repositories\CareerLevelRepository;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class CareerLevelController extends AppBaseController
{
    /** @var CareerLevelRepository */
    private $careerLevelRepository;

    public function __construct(CareerLevelRepository $careerLevelRepo)
    {
        $this->careerLevelRepository = $careerLevelRepo;
    }

    /**
     * Display a listing of the CareerLevel.
     *
     * @param  Request  $request
     * @return Application|Factory|View
     */
    public function index(): View
    {
        return view('career_levels.index');
    }

    /**
     * Store a newly created CareerLevel in storage.
     */
    public function store(CreateCareerLevelRequest $request): JsonResponse
    {
        $input = $request->all();
        $careerLevel = $this->careerLevelRepository->create($input);

        return $this->sendResponse($careerLevel, __('messages.flash.career_level_save'));
    }

    /**
     * Show the form for editing the specified CareerLevel.
     */
    public function edit(CareerLevel $careerLevel): JsonResponse
    {
        return $this->sendResponse($careerLevel, __('messages.flash.career_level_retrieved'));
    }

    /**
     * Update the specified CareerLevel in storage.
     */
    public function update(UpdateCareerLevelRequest $request, CareerLevel $careerLevel): JsonResponse
    {
        $input = $request->all();
        $this->careerLevelRepository->update($input, $careerLevel->id);

        return $this->sendSuccess(__('messages.flash.career_level_update'));
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

        $import = new CareerLevelsImport;
        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            $message = 'Career levels import completed with validation errors. Please fix the failed rows and try again.';

            return $request->expectsJson()
                ? response()->json(['message' => $message], 422)
                : back()->withFailures($import->failures());
        }

        return $request->expectsJson()
            ? response()->json(['message' => 'Career levels imported successfully.'])
            : back();
    }

    /**
     * Remove the specified CareerLevel from storage.
     *
     *
     * @throws Exception
     */
    public function destroy(CareerLevel $careerLevel): JsonResponse
    {
        $Models = [
            Candidate::class,
            Job::class,
        ];
        $result = canDelete($Models, 'career_level_id', $careerLevel->id);
        if ($result) {
            return $this->sendError(__('messages.flash.career_level_cant_delete'));
        }
        $careerLevel->delete();

        return $this->sendSuccess(__('messages.flash.career_level_delete'));
    }
}
