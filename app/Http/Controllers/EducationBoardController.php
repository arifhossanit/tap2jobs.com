<?php

namespace App\Http\Controllers;

use App\Imports\EducationBoardsImport;
use App\Models\EducationBoard;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class EducationBoardController extends AppBaseController
{
    public function index(): View
    {
        return view('education_boards.index');
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $rawNames = str_replace(["\r\n", "\n", "\r"], ',', $request->name);
        $names = array_values(array_unique(array_filter(array_map('trim', explode(',', $rawNames)))));

        $lastCreatedBoard = null;
        $createdCount = 0;

        foreach ($names as $name) {
            if (empty($name)) {
                continue;
            }
            $exists = EducationBoard::where('name', $name)->exists();
            if (! $exists) {
                $lastCreatedBoard = EducationBoard::create([
                    'name' => $name,
                    'is_active' => true,
                ]);
                $createdCount++;
            }
        }

        if ($createdCount === 0 && ! empty($names)) {
            $lastCreatedBoard = EducationBoard::where('name', $names[0])->first();
        }

        return $this->sendResponse($lastCreatedBoard, __('messages.flash.education_board_save') ?? 'Education Board saved successfully.');
    }

    public function edit(EducationBoard $educationBoard): JsonResponse
    {
        return $this->sendResponse($educationBoard, 'Education Board retrieved successfully.');
    }

    public function update(Request $request, EducationBoard $educationBoard): JsonResponse
    {
        $request->validate([
            'name' => 'required|max:120|unique:education_boards,name,'.$educationBoard->id,
        ]);

        $educationBoard->update([
            'name' => $request->name,
        ]);

        return $this->sendSuccess(__('messages.flash.education_board_update') ?? 'Education Board updated successfully.');
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

        $import = new EducationBoardsImport;
        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Education Boards import completed with validation errors. Please fix the failed rows and try again.',
                ], 422);
            }

            flash('Education Boards import completed with validation errors. Please fix the failed rows and try again.')->error();

            return back()->withFailures($import->failures());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Education Boards imported successfully.',
            ]);
        }

        flash('Education Boards imported successfully.')->success();

        return back();
    }

    public function destroy(EducationBoard $educationBoard): JsonResponse
    {
        $educationBoard->delete();

        return $this->sendSuccess(__('messages.flash.education_board_delete') ?? 'Education Board deleted successfully.');
    }
}
