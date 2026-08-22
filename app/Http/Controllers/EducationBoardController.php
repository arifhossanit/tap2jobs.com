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
            'name' => 'required|max:120|unique:education_boards,name',
        ]);

        $board = EducationBoard::create([
            'name' => $request->name,
            'is_active' => true,
        ]);

        return $this->sendResponse($board, __('messages.flash.education_board_save') ?? 'Education Board saved successfully.');
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
            'file' => 'required|mimes:xlsx,xls,csv',
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
