<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCityVillageRequest;
use App\Http\Requests\UpdateCityVillageRequest;
use App\Models\Candidate;
use App\Models\CandidateEducation;
use App\Models\CandidateExperience;
use App\Models\City;
use App\Models\CityVillage;
use App\Models\Job;
use App\Models\Thana;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CityVillageController extends AppBaseController
{
    public function index(): View
    {
        $cities = $this->districtOptions();

        return view('city_villages.index', compact('cities'));
    }

    public function store(CreateCityVillageRequest $request): JsonResponse
    {
        $cityId = $request->input('city_id');
        $lastCityVillage = null;
        $createdCount = 0;

        foreach ($request->cityVillageNames() as $name) {
            $exists = CityVillage::where('city_id', $cityId)->where('name', $name)->exists();

            if (! $exists) {
                $lastCityVillage = CityVillage::create([
                    'city_id' => $cityId,
                    'name' => $name,
                ]);
                $createdCount++;
            }
        }

        if ($createdCount === 0 && ! empty($request->cityVillageNames())) {
            $lastCityVillage = CityVillage::where('city_id', $cityId)
                ->where('name', $request->cityVillageNames()[0])
                ->first();
        }

        return $this->sendResponse($lastCityVillage, __('messages.flash.city_village_save'));
    }

    public function edit(CityVillage $cityVillage): JsonResponse
    {
        return $this->sendResponse($cityVillage, __('messages.flash.city_village_retrieved'));
    }

    public function update(UpdateCityVillageRequest $request, CityVillage $cityVillage): JsonResponse
    {
        $cityVillage->update($request->only('city_id', 'name'));

        return $this->sendSuccess(__('messages.flash.city_village_update'));
    }

    public function destroy(CityVillage $cityVillage): JsonResponse
    {
        $activeThana = Thana::where('city_village_id', $cityVillage->id)->exists();
        $activeJob = Job::where('city_village_id', $cityVillage->id)->exists();
        $activeUser = User::where('city_village_id', $cityVillage->id)->exists();
        $activeCandidate = Candidate::where('permanent_city_village_id', $cityVillage->id)->exists();
        $activeExperience = CandidateExperience::where('city_village_id', $cityVillage->id)->exists();
        $activeEducation = CandidateEducation::where('city_village_id', $cityVillage->id)->exists();

        if ($activeThana || $activeJob || $activeUser || $activeCandidate || $activeExperience || $activeEducation) {
            return $this->sendError(__('messages.flash.city_village_cant_delete'));
        }

        $cityVillage->delete();

        return $this->sendSuccess(__('messages.flash.city_village_delete'));
    }

    private function districtOptions(): array
    {
        return City::with('state')->orderBy('name')->get()
            ->mapWithKeys(fn (City $city): array => [
                $city->id => $city->name.($city->state ? ' - '.$city->state->name : ''),
            ])
            ->toArray();
    }
}
