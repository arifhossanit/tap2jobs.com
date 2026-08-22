<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateJobShiftRequest;
use App\Http\Requests\UpdateJobShiftRequest;
use App\Imports\JobShiftsImport;
use App\Models\Job;
use App\Models\JobShift;
use App\Repositories\JobShiftRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class JobShiftController extends AppBaseController
{
    /** @var JobShiftRepository */
    private $jobShiftRepository;

    public function __construct(JobShiftRepository $jobShiftRepo)
    {
        $this->jobShiftRepository = $jobShiftRepo;
    }

    /**
     * Display a listing of the JobShift.
     *
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index(): View
    {
        return view('job_shifts.index');
    }

    /**
     * Store a newly created JobShift in storage.
     */
    public function store(CreateJobShiftRequest $request): JsonResponse
    {
        $rawShifts = str_replace(["\r\n", "\n", "\r"], ',', $request->shift);
        $shifts = array_values(array_unique(array_filter(array_map('trim', explode(',', $rawShifts)))));

        $lastJobShift = null;
        $createdCount = 0;

        foreach ($shifts as $shift) {
            if (empty($shift)) {
                continue;
            }

            $exists = JobShift::where('shift', $shift)->exists();
            if (! $exists) {
                $input = $request->all();
                $input['shift'] = $shift;
                $lastJobShift = $this->jobShiftRepository->create($input);
                $createdCount++;
            }
        }

        if ($createdCount === 0 && ! empty($shifts)) {
            $lastJobShift = JobShift::where('shift', $shifts[0])->first();
        }

        return $this->sendResponse($lastJobShift, __('messages.flash.job_shift_save'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobShift $jobShift): JsonResponse
    {
        return $this->sendResponse($jobShift, 'Job Shift Retrieved Successfully.');
    }

    /**
     * Show the form for editing the specified JobShift.
     */
    public function show(JobShift $jobShift): JsonResponse
    {
        return $this->sendResponse($jobShift, __('messages.flash.job_shift_retrieve'));
    }

    /**
     * Update the specified JobShift in storage.
     */
    public function update(UpdateJobShiftRequest $request, JobShift $jobShift): JsonResponse
    {
        $input = $request->all();
        $this->jobShiftRepository->update($input, $jobShift->id);

        return $this->sendSuccess(__('messages.flash.job_shift_update'));
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

        $import = new JobShiftsImport;
        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Job Shifts import completed with validation errors. Please fix the failed rows and try again.',
                ], 422);
            }

            flash('Job Shifts import completed with validation errors. Please fix the failed rows and try again.')->error();

            return back()->withFailures($import->failures());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Job Shifts imported successfully.',
            ]);
        }

        flash('Job Shifts imported successfully.')->success();

        return back();
    }

    /**
     * Remove the specified JobShift from storage.
     *
     *
     * @throws Exception
     */
    public function destroy(JobShift $jobShift): JsonResponse
    {
        $jobModels = [
            Job::class,
        ];
        $result = canDelete($jobModels, 'job_shift_id', $jobShift->id);
        if ($result) {
            return $this->sendError(__('messages.flash.job_shift_cant_delete'));
        }
        $jobShift->delete();

        return $this->sendSuccess(__('messages.flash.job_shift_delete'));
    }
}
