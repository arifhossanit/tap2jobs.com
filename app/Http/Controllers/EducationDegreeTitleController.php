<?php

namespace App\Http\Controllers;

use App\Imports\EducationDegreeTitlesImport;
use App\Models\EducationDegreeTitle;
use App\Models\RequiredDegreeLevel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class EducationDegreeTitleController extends AppBaseController
{
    public function index(): View
    {
        $degreeLevels = RequiredDegreeLevel::orderBy('name')->pluck('name', 'id');
        return view('education_degree_titles.index', compact('degreeLevels'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'required_degree_level_id' => 'required|exists:education_degree_levels,id',
            'name' => 'required|string',
        ]);

        $degreeLevelId = $request->required_degree_level_id;
        $rawNames = str_replace(["\r\n", "\n", "\r"], ',', $request->name);

        $names = array_values(array_unique(array_filter(array_map('trim', explode(',', $rawNames)))));

        $lastCreatedTitle = null;
        $createdCount = 0;

        foreach ($names as $name) {
            if (empty($name)) {
                continue;
            }
            $exists = EducationDegreeTitle::where('required_degree_level_id', $degreeLevelId)->where('name', $name)->exists();
            if (! $exists) {
                $lastCreatedTitle = EducationDegreeTitle::create([
                    'required_degree_level_id' => $degreeLevelId,
                    'name' => $name,
                    'is_active' => true,
                ]);
                $createdCount++;
            }
        }

        if ($createdCount === 0 && ! empty($names)) {
            $lastCreatedTitle = EducationDegreeTitle::where('required_degree_level_id', $degreeLevelId)->where('name', $names[0])->first();
        }

        return $this->sendResponse($lastCreatedTitle, 'Degree Title saved successfully.');
    }

    public function edit(EducationDegreeTitle $educationDegreeTitle): JsonResponse
    {
        return $this->sendResponse($educationDegreeTitle, 'Degree Title retrieved successfully.');
    }

    public function update(Request $request, EducationDegreeTitle $educationDegreeTitle): JsonResponse
    {
        $request->validate([
            'required_degree_level_id' => 'required|exists:education_degree_levels,id',
            'name' => 'required|max:170',
        ]);

        $educationDegreeTitle->update([
            'required_degree_level_id' => $request->required_degree_level_id,
            'name' => $request->name,
        ]);

        return $this->sendSuccess('Degree Title updated successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'required_degree_level_id' => 'required|exists:education_degree_levels,id',
            'file' => ['required', 'file', function ($attribute, $value, $fail) {
                $ext = strtolower($value->getClientOriginalExtension());
                if (!in_array($ext, ['csv', 'xls', 'xlsx'])) {
                    $fail('The file field must be a file of type: csv, xls, xlsx.');
                }
            }],
        ]);

        $import = new EducationDegreeTitlesImport($request->input('required_degree_level_id'));
        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Degree Titles import completed with validation errors. Please fix the failed rows and try again.',
                ], 422);
            }

            flash('Degree Titles import completed with validation errors. Please fix the failed rows and try again.')->error();

            return back()->withFailures($import->failures());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Degree Titles imported successfully.',
            ]);
        }

        flash('Degree Titles imported successfully.')->success();

        return back();
    }

    public function destroy(EducationDegreeTitle $educationDegreeTitle): JsonResponse
    {
        $educationDegreeTitle->delete();

        return $this->sendSuccess('Degree Title deleted successfully.');
    }
}
