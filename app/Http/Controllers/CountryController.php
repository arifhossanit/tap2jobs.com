<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Models\Country;
use App\Models\Job;
use App\Models\State;
use App\Repositories\CountryRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use App\Imports\CountriesImport;
use Maatwebsite\Excel\Facades\Excel;

class CountryController extends AppBaseController
{
    private $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function index(): View
    {
        return view('countries.index');
    }

    public function store(CreateCountryRequest $request): JsonResponse
    {
        $input = $request->all();
        $input['short_code'] = strtoupper($input['short_code']);
        $country = $this->countryRepository->create($input);

        return $this->sendResponse($country, __('messages.flash.country_save'));
    }

    public function edit(Country $country): JsonResponse
    {
        return $this->sendResponse($country, __('messages.flash.retrieved'));
    }

    public function update(UpdateCountryRequest $request, Country $country): JsonResponse
    {
        $input = $request->all();
        $input['short_code'] = strtoupper($input['short_code']);

        $this->countryRepository->update($input, $country->id);

        return $this->sendSuccess(__('messages.flash.country_update'));
    }

    public function destroy(Country $country): JsonResponse
    {
        if (State::where('country_id', $country->id)->count() > 0) {
            return $this->sendError(__('messages.flash.country_cant_delete'));
        }
        if (Job::whereCountryId($country->id)->count() > 0) {
            return $this->sendError(__('messages.flash.country_cant_delete'));
        }
        $country->delete();

        return $this->sendSuccess(__('messages.flash.country_delete'));
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

        $import = new CountriesImport;
        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Countries import completed with validation errors. Please fix the failed rows and try again.',
                ], 422);
            }

            flash('Countries import completed with validation errors. Please fix the failed rows and try again.')->error();

            return back()->withFailures($import->failures());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Countries imported successfully.',
            ]);
        }

        flash('Countries imported successfully.')->success();

        return back();
    }
}
