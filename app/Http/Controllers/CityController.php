<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Imports\CitiesImport;
use App\Models\City;
use App\Models\Job;
use App\Models\State;
use App\Models\User;
use App\Models\Candidate;
use App\Models\CandidateExperience;
use App\Models\CandidateEducation;
use App\Repositories\CityRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class CityController extends AppBaseController
{
    private $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function index(): View
    {
        $states = State::toBase()->orderBy('name')->pluck('name', 'id');

        return view('cities.index', compact('states'));
    }

    public function store(CreateCityRequest $request): JsonResponse
    {
        $stateId = $request->input('state_id');
        $rawNames = str_replace(["\r\n", "\n", "\r"], ',', $request->input('name'));

        $names = array_values(array_unique(array_filter(array_map('trim', explode(',', $rawNames)))));

        $lastCreatedCity = null;
        $createdCount = 0;

        foreach ($names as $name) {
            if (empty($name)) {
                continue;
            }
            $exists = City::where('state_id', $stateId)->where('name', $name)->exists();
            if (! $exists) {
                $lastCreatedCity = City::create([
                    'state_id' => $stateId,
                    'name' => $name,
                ]);
                $createdCount++;
            }
        }

        if ($createdCount === 0 && ! empty($names)) {
            $lastCreatedCity = City::where('state_id', $stateId)->where('name', $names[0])->first();
        }

        return $this->sendResponse($lastCreatedCity, __('messages.flash.city_save'));
    }

    public function edit(City $city): JsonResponse
    {
        return $this->sendResponse($city, __('messages.flash.city_retrieved'));
    }

    public function update(UpdateCityRequest $request, City $city): JsonResponse
    {
        $input = $request->all();
        $this->cityRepository->update($input, $city->id);

        return $this->sendSuccess(__('messages.flash.city_update'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'state_id' => 'required|exists:states,id',
            'file' => ['required', 'file', function ($attribute, $value, $fail) {
                $ext = strtolower($value->getClientOriginalExtension());
                if (!in_array($ext, ['csv', 'xls', 'xlsx'])) {
                    $fail('The file field must be a file of type: csv, xls, xlsx.');
                }
            }],
        ]);

        $import = new CitiesImport($request->input('state_id'));
        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Cities import completed with validation errors. Please fix the failed rows and try again.',
                ], 422);
            }

            flash('Cities import completed with validation errors. Please fix the failed rows and try again.')->error();

            return back()->withFailures($import->failures());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Cities imported successfully.',
            ]);
        }

        flash('Cities imported successfully.')->success();

        return back();
    }

    public function destroy(City $city): JsonResponse
    {
        $activeJob = Job::where('city_id', $city->id)->exists();
        $activeUser = User::where('city_id', $city->id)->exists();
        $activeCandidate = Candidate::where('permanent_city_id', $city->id)->exists();
        $activeExperience = CandidateExperience::where('city_id', $city->id)->exists();
        $activeEducation = CandidateEducation::where('city_id', $city->id)->exists();

        if ($activeJob || $activeUser || $activeCandidate || $activeExperience || $activeEducation) {
            return $this->sendError(__('messages.flash.city_cant_delete'));
        }

        try {
            $city->delete();
        } catch (\Exception $e) {
            return $this->sendError(__('messages.flash.city_cant_delete'));
        }

        return $this->sendSuccess(__('messages.flash.city_delete'));
    }
}
