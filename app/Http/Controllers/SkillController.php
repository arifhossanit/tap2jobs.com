<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSkillRequest;
use App\Http\Requests\UpdateSkillRequest;
use App\Imports\SkillsImport;
use App\Models\Skill;
use App\Repositories\SkillRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class SkillController extends AppBaseController
{
    /** @var SkillRepository */
    private $skillRepository;

    public function __construct(SkillRepository $skillRepository)
    {
        $this->skillRepository = $skillRepository;
    }

    /**
     * Display a listing of the Skill.
     *
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index(): View
    {
        return view('skills.index');
    }

    /**
     * Store a newly created Skill in storage.
     */
    public function store(CreateSkillRequest $request): JsonResponse
    {
        $input = $request->all();
        $skill = $this->skillRepository->create($input);

        return $this->sendResponse($skill, __('messages.flash.skill_save'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Skill $skill): JsonResponse
    {
        return $this->sendResponse($skill, 'Skill Retrieved Successfully.');
    }

    /**
     * Show the form for editing the specified Skill.
     */
    public function show(Skill $skill): JsonResponse
    {
        return $this->sendResponse($skill, 'Skill Retrieved Successfully.');
    }

    /**
     * Update the specified Skill in storage.
     */
    public function update(UpdateSkillRequest $request, Skill $skill): JsonResponse
    {
        $input = $request->all();
        $this->skillRepository->update($input, $skill->id);

        return $this->sendSuccess(__('messages.flash.skill_update'));
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

        $import = new SkillsImport;
        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            $message = 'Skills import completed with validation errors. Please fix the failed rows and try again.';

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

        $message = 'Skills imported successfully. Imported: '.$import->importedCount().', skipped duplicates: '.$import->skippedCount().'.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        flash($message)->success();

        return back();
    }

    /**
     * Remove the specified Skill from storage.
     *
     *
     * @throws Exception
     */
    public function destroy(Skill $skill): JsonResponse
    {
        $candidateskillIds = $skill->candidate()->pluck('skill_id')->toArray();
        $jobskillIds = $skill->jobs()->pluck('skill_id')->toArray();
        if (in_array($skill->id, $candidateskillIds) || in_array($skill->id, $jobskillIds)) {
            return $this->sendError(__('messages.flash.skill_cant_delete'));
        } else {
            $skill->delete();
        }

        return $this->sendSuccess(__('messages.flash.skill_delete'));
    }
}
