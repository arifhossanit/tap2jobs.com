<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCityRequest;
use App\Http\Requests\UpdateCityRequest;
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
        $input = $request->all();
        $state = $this->cityRepository->create($input);

        return $this->sendResponse($state, __('messages.flash.city_save'));
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
