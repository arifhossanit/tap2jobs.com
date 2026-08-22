<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSalaryPeriodRequest;
use App\Http\Requests\UpdateSalaryPeriodRequest;
use App\Imports\SalaryPeriodsImport;
use App\Models\Job;
use App\Models\SalaryPeriod;
use App\Repositories\SalaryPeriodRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class SalaryPeriodController extends AppBaseController
{
    /** @var SalaryPeriodRepository */
    private $salaryPeriodRepository;

    public function __construct(SalaryPeriodRepository $salaryPeriodRepo)
    {
        $this->salaryPeriodRepository = $salaryPeriodRepo;
    }

    /**
     * Display a listing of the SalaryPeriod.
     *
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index(): View
    {
        return view('salary_periods.index');
    }

    /**
     * Store a newly created SalaryPeriod in storage.
     */
    public function store(CreateSalaryPeriodRequest $request): JsonResponse
    {
        $rawPeriods = str_replace(["\r\n", "\n", "\r"], ',', $request->period);
        $periods = array_values(array_unique(array_filter(array_map('trim', explode(',', $rawPeriods)))));

        $lastPeriod = null;
        $createdCount = 0;

        foreach ($periods as $period) {
            if (empty($period)) {
                continue;
            }

            $exists = SalaryPeriod::where('period', $period)->exists();
            if (! $exists) {
                $input = $request->all();
                $input['period'] = $period;
                $lastPeriod = $this->salaryPeriodRepository->create($input);
                $createdCount++;
            }
        }

        if ($createdCount === 0 && ! empty($periods)) {
            $lastPeriod = SalaryPeriod::where('period', $periods[0])->first();
        }

        return $this->sendResponse($lastPeriod, __('messages.flash.salary_period_save'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalaryPeriod $salaryPeriod): JsonResponse
    {
        return $this->sendResponse($salaryPeriod, 'Salary Period Retrieved Successfully.');
    }

    /**
     * Show the form for editing the specified SalaryPeriod.
     */
    public function show(SalaryPeriod $salaryPeriod): JsonResponse
    {
        return $this->sendResponse($salaryPeriod, __('messages.flash.salary_period_retrieve'));
    }

    /**
     * Update the specified SalaryPeriod in storage.
     */
    public function update(UpdateSalaryPeriodRequest $request, SalaryPeriod $salaryPeriod): JsonResponse
    {
        $input = $request->all();
        $this->salaryPeriodRepository->update($input, $salaryPeriod->id);

        return $this->sendSuccess(__('messages.flash.salary_period_update'));
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

        $import = new SalaryPeriodsImport;
        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Salary Periods import completed with validation errors. Please fix the failed rows and try again.',
                ], 422);
            }

            flash('Salary Periods import completed with validation errors. Please fix the failed rows and try again.')->error();

            return back()->withFailures($import->failures());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Salary Periods imported successfully.',
            ]);
        }

        flash('Salary Periods imported successfully.')->success();

        return back();
    }

    /**
     * Remove the specified SalaryPeriod from storage.
     *
     *
     * @throws Exception
     */
    public function destroy(SalaryPeriod $salaryPeriod): JsonResponse
    {
        $jobModels = [
            Job::class,
        ];
        $result = canDelete($jobModels, 'salary_period_id', $salaryPeriod->id);
        if ($result) {
            return $this->sendError(__('messages.flash.salary_period_cant_delete'));
        }
        $salaryPeriod->delete();

        return $this->sendSuccess(__('messages.flash.salary_period_delete'));
    }
}
