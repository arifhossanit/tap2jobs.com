<?php

namespace App\Http\Controllers;

use App\Imports\EducationMajorGroupsImport;
use App\Models\EducationMajorGroup;
use App\Models\RequiredDegreeLevel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class EducationMajorGroupController extends AppBaseController
{
    public function index(): View
    {
        $degreeLevels = RequiredDegreeLevel::orderBy('name')->pluck('name', 'id');
        return view('education_major_groups.index', compact('degreeLevels'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'required_degree_level_id' => 'nullable|exists:education_degree_levels,id',
            'name' => 'required|max:170',
        ]);

        $majorGroup = EducationMajorGroup::create([
            'required_degree_level_id' => $request->required_degree_level_id,
            'name' => $request->name,
            'is_active' => true,
        ]);

        return $this->sendResponse($majorGroup, 'Major/Group saved successfully.');
    }

    public function edit(EducationMajorGroup $educationMajorGroup): JsonResponse
    {
        return $this->sendResponse($educationMajorGroup, 'Major/Group retrieved successfully.');
    }

    public function update(Request $request, EducationMajorGroup $educationMajorGroup): JsonResponse
    {
        $request->validate([
            'required_degree_level_id' => 'nullable|exists:education_degree_levels,id',
            'name' => 'required|max:170',
        ]);

        $educationMajorGroup->update([
            'required_degree_level_id' => $request->required_degree_level_id,
            'name' => $request->name,
        ]);

        return $this->sendSuccess('Major/Group updated successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $import = new EducationMajorGroupsImport;
        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Major/Groups import completed with validation errors. Please fix the failed rows and try again.',
                ], 422);
            }

            flash('Major/Groups import completed with validation errors. Please fix the failed rows and try again.')->error();

            return back()->withFailures($import->failures());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Major/Groups imported successfully.',
            ]);
        }

        flash('Major/Groups imported successfully.')->success();

        return back();
    }

    public function destroy(EducationMajorGroup $educationMajorGroup): JsonResponse
    {
        $educationMajorGroup->delete();

        return $this->sendSuccess('Major/Group deleted successfully.');
    }
}
