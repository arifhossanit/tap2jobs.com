<?php

namespace App\Http\Controllers;

use App\Models\EducationBoard;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

    public function destroy(EducationBoard $educationBoard): JsonResponse
    {
        $educationBoard->delete();

        return $this->sendSuccess(__('messages.flash.education_board_delete') ?? 'Education Board deleted successfully.');
    }
}
