<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStateRequest;
use App\Http\Requests\UpdateStateRequest;
use App\Imports\StatesImport;
use App\Models\City;
use App\Models\Country;
use App\Models\Job;
use App\Models\State;
use App\Repositories\StateRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class StateController extends AppBaseController
{
    /**
     * @var StateRepository
     */
    private $stateRepository;

    public function __construct(StateRepository $stateRepository)
    {
        $this->stateRepository = $stateRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return Application|Factory|Response|View
     */
    public function index(): View
    {
        $countries = Country::orderBy('name')->pluck('name', 'id');

        return view('states.index', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateStateRequest $request): JsonResponse
    {
        $countryId = $request->input('country_id');
        $rawNames = str_replace(["\r\n", "\n", "\r"], ',', $request->input('name'));
        $names = array_values(array_unique(array_filter(array_map('trim', explode(',', $rawNames)))));

        $lastCreatedState = null;
        $createdCount = 0;

        foreach ($names as $name) {
            if (empty($name)) {
                continue;
            }
            $exists = State::where('country_id', $countryId)->where('name', $name)->exists();
            if (! $exists) {
                $lastCreatedState = State::create([
                    'country_id' => $countryId,
                    'name' => $name,
                ]);
                $createdCount++;
            }
        }

        if ($createdCount === 0 && ! empty($names)) {
            $lastCreatedState = State::where('country_id', $countryId)->where('name', $names[0])->first();
        }

        return $this->sendResponse($lastCreatedState, __('messages.flash.state_save'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(State $state): JsonResponse
    {
        return $this->sendResponse($state, 'State successfully retrieved.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStateRequest $request, State $state): JsonResponse
    {
        $input = $request->all();
        $this->stateRepository->update($input, $state->id);

        return $this->sendSuccess(__('messages.flash.state_update'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'file' => ['required', 'file', function ($attribute, $value, $fail) {
                $ext = strtolower($value->getClientOriginalExtension());
                if (!in_array($ext, ['csv', 'xls', 'xlsx'])) {
                    $fail('The file field must be a file of type: csv, xls, xlsx.');
                }
            }],
        ]);

        $import = new StatesImport($request->input('country_id'));
        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'States import completed with validation errors. Please fix the failed rows and try again.',
                ], 422);
            }

            flash('States import completed with validation errors. Please fix the failed rows and try again.')->error();

            return back()->withFailures($import->failures());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'States imported successfully.',
            ]);
        }

        flash('States imported successfully.')->success();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @throws \Exception
     */
    public function destroy(State $state): JsonResponse
    {
        if (City::where('state_id', $state->id)->count() > 0) {
            return $this->sendError(__('messages.flash.state_cant_delete'));
        }
        if (Job::where('state_id', $state->id)->count() > 0) {
            return $this->sendError(__('messages.flash.state_cant_delete'));
        }

        $state->delete();

        return $this->sendSuccess(__('messages.flash.state_delete'));
    }
}
