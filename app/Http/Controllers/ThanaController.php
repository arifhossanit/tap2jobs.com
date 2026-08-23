<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateThanaRequest;
use App\Http\Requests\UpdateThanaRequest;
use App\Imports\ThanasImport;
use App\Models\Candidate;
use App\Models\CandidateEducation;
use App\Models\CandidateExperience;
use App\Models\City;
use App\Models\Job;
use App\Models\Thana;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ThanaController extends AppBaseController
{
    public function index(): View
    {
        $cities = City::with('state')->orderBy('name')->get()
            ->mapWithKeys(fn (City $city): array => [
                $city->id => $city->name.($city->state ? ' - '.$city->state->name : ''),
            ])
            ->toArray();

        return view('thanas.index', compact('cities'));
    }

    public function store(CreateThanaRequest $request): JsonResponse
    {
        $cityId = $request->input('city_id');
        $rawNames = str_replace(["\r\n", "\n", "\r"], ',', $request->input('name'));
        $names = array_values(array_unique(array_filter(array_map('trim', explode(',', $rawNames)))));

        $lastCreatedThana = null;
        $createdCount = 0;

        foreach ($names as $name) {
            if (empty($name)) {
                continue;
            }

            $exists = Thana::where('city_id', $cityId)->where('name', $name)->exists();

            if (! $exists) {
                $lastCreatedThana = Thana::create([
                    'city_id' => $cityId,
                    'name' => $name,
                ]);
                $createdCount++;
            }
        }

        if ($createdCount === 0 && ! empty($names)) {
            $lastCreatedThana = Thana::where('city_id', $cityId)->where('name', $names[0])->first();
        }

        return $this->sendResponse($lastCreatedThana, __('messages.flash.thana_save'));
    }

    public function edit(Thana $thana): JsonResponse
    {
        return $this->sendResponse($thana, __('messages.flash.thana_retrieved'));
    }

    public function update(UpdateThanaRequest $request, Thana $thana): JsonResponse
    {
        $thana->update($request->only('city_id', 'name'));

        return $this->sendSuccess(__('messages.flash.thana_update'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'city_id' => 'nullable|exists:cities,id',
            'file' => ['required', 'file', function ($attribute, $value, $fail) {
                $ext = strtolower($value->getClientOriginalExtension());
                if (! in_array($ext, ['csv', 'xls', 'xlsx'])) {
                    $fail('The file field must be a file of type: csv, xls, xlsx.');
                }
            }],
        ]);

        $import = new ThanasImport($request->input('city_id'));
        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Thanas import completed with validation errors. Please fix the failed rows and try again.',
                ], 422);
            }

            flash('Thanas import completed with validation errors. Please fix the failed rows and try again.')->error();

            return back()->withFailures($import->failures());
        }

        $message = 'Thanas imported successfully. Imported: '.$import->importedCount().', skipped duplicates: '.$import->skippedCount().'.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        flash($message)->success();

        return back();
    }

    public function destroy(Thana $thana): JsonResponse
    {
        $activeJob = Job::where('thana_id', $thana->id)->exists();
        $activeUser = User::where('thana_id', $thana->id)->exists();
        $activeCandidate = Candidate::where('permanent_thana_id', $thana->id)->exists();
        $activeExperience = CandidateExperience::where('thana_id', $thana->id)->exists();
        $activeEducation = CandidateEducation::where('thana_id', $thana->id)->exists();

        if ($activeJob || $activeUser || $activeCandidate || $activeExperience || $activeEducation) {
            return $this->sendError(__('messages.flash.thana_cant_delete'));
        }

        $thana->delete();

        return $this->sendSuccess(__('messages.flash.thana_delete'));
    }
}
