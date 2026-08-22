<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRequiredDegreeLevelRequest;
use App\Http\Requests\UpdateRequiredDegreeLevelRequest;
use App\Imports\RequiredDegreeLevelsImport;
use App\Models\CandidateEducation;
use App\Models\EducationDegreeTitle;
use App\Models\EducationMajorGroup;
use App\Models\Job;
use App\Models\RequiredDegreeLevel;
use App\Repositories\RequiredDegreeLevelRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class RequiredDegreeLevelController extends AppBaseController
{
    /** @var RequiredDegreeLevelRepository */
    private $requiredDegreeLevelRepository;

    public function __construct(RequiredDegreeLevelRepository $requiredDegreeLevelRepo)
    {
        $this->requiredDegreeLevelRepository = $requiredDegreeLevelRepo;
    }

    /**
     * Display a listing of the JobType.
     *
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index(): View
    {
        return view('required_degree_levels.index');
    }

    /**
     * Store a newly created RequiredDegreeLevel in storage.
     */
    public function store(CreateRequiredDegreeLevelRequest $request): JsonResponse
    {
        $input = $request->all();
        $degreeLevel = $this->requiredDegreeLevelRepository->create($input);

        return $this->sendResponse($degreeLevel, __('messages.flash.degree_level_save'));
    }

    /**
     * Display the specified RequiredDegreeLevel.
     */
    public function show(RequiredDegreeLevel $requiredDegreeLevel): JsonResponse
    {
        return $this->sendResponse($requiredDegreeLevel, __('messages.flash.degree_level_retrieve'));
    }

    /**
     * Show the form for editing the specified RequiredDegreeLevel.
     */
    public function edit(RequiredDegreeLevel $requiredDegreeLevel): JsonResponse
    {
        return $this->sendResponse($requiredDegreeLevel, 'Degree Level Successfully.');
    }

    /**
     * Update the specified RequiredDegreeLevel in storage.
     */
    public function update(UpdateRequiredDegreeLevelRequest $request, RequiredDegreeLevel $requiredDegreeLevel): JsonResponse
    {
        $input = $request->all();
        $this->requiredDegreeLevelRepository->update($input, $requiredDegreeLevel->id);

        return $this->sendSuccess(__('messages.flash.degree_level_update'));
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

        $import = new RequiredDegreeLevelsImport;
        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            $message = 'Degree levels import completed with validation errors. Please fix the failed rows and try again.';

            return $request->expectsJson()
                ? response()->json(['message' => $message], 422)
                : back()->withFailures($import->failures());
        }

        return $request->expectsJson()
            ? response()->json(['message' => 'Degree levels imported successfully.'])
            : back();
    }

    /**
     * Remove the specified RequiredDegreeLevel from storage.
     *
     *
     * @throws Exception
     */
    public function destroy(RequiredDegreeLevel $requiredDegreeLevel): JsonResponse
    {
        $degreeLevelModels = [
            CandidateEducation::class,
            Job::class,
        ];
        $requiredDegreeLevelModels = [
            EducationDegreeTitle::class,
            EducationMajorGroup::class,
        ];
        $result = canDelete($degreeLevelModels, 'degree_level_id', $requiredDegreeLevel->id)
            || canDelete($requiredDegreeLevelModels, 'required_degree_level_id', $requiredDegreeLevel->id);
        if ($result) {
            return $this->sendError(__('messages.flash.degree_level_cant_delete'));
        }
        $requiredDegreeLevel->delete();

        return $this->sendSuccess(__('messages.flash.degree_level_delete'));
    }
}
