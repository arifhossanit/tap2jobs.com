<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Imports\JobTagsImport;
use App\Models\Tag;
use App\Repositories\JobTagRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class TagController extends AppBaseController
{
    /** @var JobTagRepository */
    private $jobTagRepository;

    public function __construct(JobTagRepository $jobTagRepo)
    {
        $this->jobTagRepository = $jobTagRepo;
    }

    /**
     * Display a listing of the JobTag.
     *
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index(): View
    {
        return view('job_tags.index');
    }

    /**
     * Store a newly created JobTag in storage.
     */
    public function store(CreateTagRequest $request): JsonResponse
    {
        $rawNames = str_replace(["\r\n", "\n", "\r"], ',', $request->name);
        $names = array_values(array_unique(array_filter(array_map('trim', explode(',', $rawNames)))));

        $lastJobTag = null;
        $createdCount = 0;

        foreach ($names as $name) {
            if (empty($name)) {
                continue;
            }

            $exists = Tag::where('name', $name)->exists();
            if (! $exists) {
                $input = $request->all();
                $input['name'] = $name;
                $lastJobTag = $this->jobTagRepository->create($input);
                $createdCount++;
            }
        }

        if ($createdCount === 0 && ! empty($names)) {
            $lastJobTag = Tag::where('name', $names[0])->first();
        }

        return $this->sendResponse($lastJobTag, __('messages.flash.job_tag_save'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag): JsonResponse
    {
        return $this->sendResponse($tag, __('messages.flash.job_tag_retrieve'));
    }

    /**
     * Show the form for editing the specified JobTag.
     */
    public function show(Tag $tag): JsonResponse
    {
        return $this->sendResponse($tag, __('messages.flash.job_tag_retrieve'));
    }

    /**
     * Update the specified JobTag in storage.
     *
     * @param  Tag  $jobTag
     */
    public function update(UpdateTagRequest $request, Tag $tag): JsonResponse
    {
        $input = $request->all();
        $this->jobTagRepository->update($input, $tag->id);

        return $this->sendSuccess(__('messages.flash.job_tag_update'));
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

        $import = new JobTagsImport;
        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Job Tags import completed with validation errors. Please fix the failed rows and try again.',
                ], 422);
            }

            flash('Job Tags import completed with validation errors. Please fix the failed rows and try again.')->error();

            return back()->withFailures($import->failures());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Job Tags imported successfully.',
            ]);
        }

        flash('Job Tags imported successfully.')->success();

        return back();
    }

    /**
     * Remove the specified JobTag from storage.
     *
     * @param  Tag  $jobTag
     *
     * @throws Exception
     */
    public function destroy(Tag $tag): JsonResponse
    {
        $jobTag = $tag->jobs()->pluck('tag_id')->toArray();
        if (in_array($tag->id, $jobTag)) {
            return $this->sendError(__('messages.flash.job_tag_cant_delete'));
        } else {
            $tag->delete();
        }

        return $this->sendSuccess(__('messages.flash.job_tag_delete'));
    }
}
