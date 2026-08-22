<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateJobTypeRequest;
use App\Http\Requests\UpdateJobTypeRequest;
use App\Imports\JobTypesImport;
use App\Models\Job;
use App\Models\JobType;
use App\Repositories\JobTypeRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class JobTypeController extends AppBaseController
{
    /** @var JobTypeRepository */
    private $jobTypeRepository;

    public function __construct(JobTypeRepository $jobTypeRepo)
    {
        $this->jobTypeRepository = $jobTypeRepo;
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
        return view('job_types.index');
    }

    /**
     * Store a newly created JobType in storage.
     */
    public function store(CreateJobTypeRequest $request): JsonResponse
    {
        $rawNames = str_replace(["\r\n", "\n", "\r"], ',', $request->name);
        $names = array_values(array_unique(array_filter(array_map('trim', explode(',', $rawNames)))));

        $lastJobType = null;
        $createdCount = 0;

        foreach ($names as $name) {
            if (empty($name)) {
                continue;
            }

            $exists = JobType::where('name', $name)->exists();
            if (! $exists) {
                $input = $request->all();
                $input['name'] = $name;
                $lastJobType = $this->jobTypeRepository->create($input);
                $createdCount++;
            }
        }

        if ($createdCount === 0 && ! empty($names)) {
            $lastJobType = JobType::where('name', $names[0])->first();
        }

        return $this->sendResponse($lastJobType, __('messages.flash.job_type_save'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobType $jobType): JsonResponse
    {
        return $this->sendResponse($jobType, 'Job Type Retrieved Successfully.');
    }

    /**
     * Show the form for editing the specified JobType.
     */
    public function show(JobType $jobType): JsonResponse
    {
        return $this->sendResponse($jobType, __('messages.flash.job_type_retrieve'));
    }

    /**
     * Update the specified JobType in storage.
     */
    public function update(UpdateJobTypeRequest $request, JobType $jobType): JsonResponse
    {
        $input = $request->all();
        $this->jobTypeRepository->update($input, $jobType->id);

        return $this->sendSuccess(__('messages.flash.job_type_update'));
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

        $import = new JobTypesImport;
        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Job Types import completed with validation errors. Please fix the failed rows and try again.',
                ], 422);
            }

            flash('Job Types import completed with validation errors. Please fix the failed rows and try again.')->error();

            return back()->withFailures($import->failures());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Job Types imported successfully.',
            ]);
        }

        flash('Job Types imported successfully.')->success();

        return back();
    }

    /**
     * Remove the specified JobType from storage.
     *
     *
     * @throws Exception
     */
    public function destroy(JobType $jobType): JsonResponse
    {
        $jobModels = [
            Job::class,
        ];
        $result = canDelete($jobModels, 'job_type_id', $jobType->id);
        if ($result) {
            return $this->sendError(__('messages.flash.job_type_cant_delete'));
        }
        $jobType->delete();

        return $this->sendSuccess(__('messages.flash.job_type_delete'));
    }
}
