<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateJobCategoryRequest;
use App\Http\Requests\UpdateJobCategoryRequest;
use App\Imports\JobCategoriesImport;
use App\Models\Job;
use App\Models\JobCategory;
use App\Repositories\JobCategoryRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class JobCategoryController extends AppBaseController
{
    /** @var JobCategoryRepository */
    private $jobCategoryRepository;

    public function __construct(JobCategoryRepository $jobCategoryRepo)
    {
        $this->jobCategoryRepository = $jobCategoryRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index(): View
    {
        $featured = JobCategory::FEATURED;
        $jobCategories = JobCategory::all();

        return view('job_categories.index', compact('featured', 'jobCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store(CreateJobCategoryRequest $request)
    {
        $jobCategory = $this->jobCategoryRepository->store($request->validated());

        return $this->sendResponse($jobCategory, __('messages.flash.job_category_save'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobCategory $jobCategory): JsonResponse
    {
        return $this->sendResponse($jobCategory, 'Job Category Retrieved Successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function show(JobCategory $jobCategory): JsonResponse
    {
        return $this->sendResponse($jobCategory, 'Job Category Retrieved Successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @return mixed
     */
    public function update(UpdateJobCategoryRequest $request, JobCategory $jobCategory)
    {
        $input = $request->all();

        $this->jobCategoryRepository->updateJobCategory($input, $jobCategory->id);

        return $this->sendSuccess(__('messages.flash.job_category_update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @throws Exception
     */
    public function destroy(JobCategory $jobCategory): JsonResponse
    {
        $jobModels = [
            Job::class,
        ];
        $result = canDelete($jobModels, 'job_category_id', $jobCategory->id);
        if ($result) {
            return $this->sendError(__('messages.flash.job_category_cant_delete'));
        }
        $jobCategory->delete();

        return $this->sendSuccess(__('messages.flash.job_category_delete'));
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

        $import = new JobCategoriesImport;
        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Job Categories import completed with validation errors. Please fix the failed rows and try again.',
                    'errors' => collect($import->failures())->map(fn ($failure) => [
                        'row' => $failure->row(),
                        'attribute' => $failure->attribute(),
                        'errors' => $failure->errors(),
                        'values' => $failure->values(),
                    ])->values(),
                ], 422);
            }

            flash('Job Categories import completed with validation errors. Please fix the failed rows and try again.')->error();

            return back()->withFailures($import->failures());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Job Categories imported successfully. Imported: '.$import->importedCount().', skipped duplicates: '.$import->skippedCount().'.',
            ]);
        }

        flash('Job Categories imported successfully. Imported: '.$import->importedCount().', skipped duplicates: '.$import->skippedCount().'.')->success();

        return back();
    }

    /**
     * @return mixed
     */
    public function changeStatus(JobCategory $jobCategory)
    {
        $isFeatured = $jobCategory->is_featured;
        $jobCategory->update(['is_featured' => ! $isFeatured]);

        return $this->sendSuccess(__('messages.flash.status_change'));
    }

    public function changeStatusValue(JobCategory $jobCategory)
    {
        $status = (int) $jobCategory->status === JobCategory::STATUS_ACTIVE
            ? JobCategory::STATUS_INACTIVE
            : JobCategory::STATUS_ACTIVE;

        $jobCategory->update(['status' => $status]);

        return $this->sendSuccess(__('messages.flash.status_change'));
    }
}
