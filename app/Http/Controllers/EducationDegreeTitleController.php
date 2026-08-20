<?php

namespace App\Http\Controllers;

use App\Models\EducationDegreeTitle;
use App\Models\RequiredDegreeLevel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
            'name' => 'required|max:170',
        ]);

        $degreeTitle = EducationDegreeTitle::create([
            'required_degree_level_id' => $request->required_degree_level_id,
            'name' => $request->name,
            'is_active' => true,
        ]);

        return $this->sendResponse($degreeTitle, 'Degree Title saved successfully.');
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

    public function destroy(EducationDegreeTitle $educationDegreeTitle): JsonResponse
    {
        $educationDegreeTitle->delete();

        return $this->sendSuccess('Degree Title deleted successfully.');
    }
}
