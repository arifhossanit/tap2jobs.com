<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Imports\LanguagesImport;
use App\Models\Language;
use App\Repositories\LanguageRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class LanguageController extends AppBaseController
{
    /** @var LanguageRepository */
    private $languageRepository;

    public function __construct(LanguageRepository $languageRepo)
    {
        $this->languageRepository = $languageRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws \Exception
     */
    public function index(): View
    {
        return view('languages.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateLanguageRequest $request): JsonResponse
    {
        $rawLanguages = str_replace(["\r\n", "\n", "\r"], ',', $request->input('language'));
        $names = array_values(array_unique(array_filter(array_map('trim', explode(',', $rawLanguages)))));

        $givenIso = trim((string) $request->input('iso_code'));
        $lastCreatedLang = null;
        $createdCount = 0;

        foreach ($names as $name) {
            if (empty($name)) {
                continue;
            }

            if (count($names) === 1 && ! empty($givenIso)) {
                $isoCode = strtolower($givenIso);
            } else {
                $cleanName = preg_replace('/[^a-zA-Z]/', '', $name);
                $isoCode = strtolower(substr($cleanName, 0, 2));
                if (strlen($isoCode) < 2) {
                    $isoCode = strtolower(substr(md5($name), 0, 2));
                }

                $originalIso = $isoCode;
                $counter = 1;
                while (Language::where('iso_code', $isoCode)->where('language', '!=', $name)->exists()) {
                    $isoCode = $originalIso . $counter;
                    $counter++;
                }
            }

            $exists = Language::where('language', $name)->exists();

            if (! $exists) {
                $lastCreatedLang = Language::create([
                    'language' => $name,
                    'iso_code' => $isoCode,
                ]);

                $path = base_path('lang/').$isoCode;
                if (! \File::exists($path)) {
                    \File::makeDirectory($path, 0755, true, true);
                }

                $createdCount++;
            }
        }

        if ($createdCount === 0 && ! empty($names)) {
            $lastCreatedLang = Language::where('language', $names[0])->first();
        }

        Artisan::call('lang:js');

        return $this->sendResponse($lastCreatedLang, __('messages.flash.language_save'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Language $language): JsonResponse
    {
        return $this->sendResponse($language, 'Language Retrieved Successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function show(Language $language): JsonResponse
    {
        return $this->sendResponse($language, __('messages.flash.language_retrieve'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLanguageRequest $request, Language $language): JsonResponse
    {
        $input = $request->all();
        $this->languageRepository->update($input, $language->id);
        Artisan::call('lang:js');

        return $this->sendSuccess(__('messages.flash.language_update'));
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

        $import = new LanguagesImport;
        Excel::import($import, $request->file('file'));

        Artisan::call('lang:js');

        if ($import->failures()->isNotEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Languages import completed with validation errors. Please fix the failed rows and try again.',
                ], 422);
            }

            flash('Languages import completed with validation errors. Please fix the failed rows and try again.')->error();

            return back()->withFailures($import->failures());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Languages imported successfully.',
            ]);
        }

        flash('Languages imported successfully.')->success();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @throws \Exception
     */
    public function destroy(Language $language): JsonResponse
    {
        $languageIds = $language->candidate()->pluck('language_id')->toArray();
        if (in_array($language->id, $languageIds)) {
            return $this->sendError(__('messages.language.language_cant_delete'));
        }

        $path = base_path('lang/').$language->iso_code;
        $language->delete();

        if (\File::exists($path)) {
            \File::deleteDirectory($path);
            $language->delete();
        } else {
            return $this->sendError(__('messages.language.language_not_deleted'));
        }

        Artisan::call('lang:js');

        return $this->sendSuccess(__('messages.flash.language_delete'));
    }
}
