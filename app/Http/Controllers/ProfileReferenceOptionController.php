<?php

namespace App\Http\Controllers;

use App\Imports\ProfileReferenceOptionsImport;
use App\Models\ProfileReferenceOption;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ProfileReferenceOptionController extends AppBaseController
{
    public function index(string $scope, string $type): View|RedirectResponse
    {
        $this->guardScopeType($scope, $type);

        $dedicatedRouteName = ProfileReferenceOption::dedicatedRouteName($scope, $type);

        if (request()->routeIs('profileReferenceOptions.index') && $dedicatedRouteName) {
            return redirect()->route($dedicatedRouteName.'.index');
        }

        return view('profile_reference_options.index', [
            'typeLabels' => ProfileReferenceOption::typeLabels(),
            'scopeLabels' => ProfileReferenceOption::scopeLabels(),
            'scope' => $scope,
            'type' => $type,
            'dedicatedRouteName' => $dedicatedRouteName,
            'title' => (ProfileReferenceOption::scopeLabels()[$scope] ?? $scope).' - '.(ProfileReferenceOption::typeLabels()[$type] ?? $type),
            'options' => ProfileReferenceOption::records($type, $scope),
        ]);
    }

    public function store(Request $request, string $scope, string $type): JsonResponse
    {
        $this->guardScopeType($scope, $type);

        $request->validate([
            'label' => ['required', 'string'],
        ]);

        $rawLabels = str_replace(["\r\n", "\n", "\r"], ',', $request->input('label'));
        $labels = array_values(array_unique(array_filter(array_map('trim', explode(',', $rawLabels)))));

        $lastCreatedOption = null;
        $createdCount = 0;

        foreach ($labels as $label) {
            if (empty($label)) {
                continue;
            }

            $value = (count($labels) === 1 && filled($request->input('value')))
                ? trim((string) $request->input('value'))
                : $label;

            $table = ProfileReferenceOption::tableFor($type);
            $exists = \DB::table($table)
                ->where('scope', $scope)
                ->where(function ($q) use ($label, $value) {
                    $q->where('label', $label)->orWhere('value', $value);
                })
                ->exists();

            if (! $exists) {
                $lastCreatedOption = ProfileReferenceOption::createRecord($type, [
                    'scope' => $scope,
                    'label' => $label,
                    'value' => $value,
                    'sort_order' => $request->input('sort_order', 0),
                    'is_active' => $request->boolean('is_active', true),
                ]);
                $createdCount++;
            }
        }

        if ($createdCount === 0 && ! empty($labels)) {
            $table = ProfileReferenceOption::tableFor($type);
            $existing = \DB::table($table)->where('scope', $scope)->where('label', $labels[0])->first();
            if ($existing) {
                $lastCreatedOption = ProfileReferenceOption::findRecord($type, $existing->id);
            }
        }

        return $this->sendResponse($lastCreatedOption, 'Reference option saved successfully.');
    }

    public function edit(string $scope, string $type, $id): JsonResponse
    {
        $this->guardScopeType($scope, $type);
        $profileReferenceOption = $this->findOption($type, (int) $id);
        abort_unless($profileReferenceOption->scope === $scope, 404);

        return $this->sendResponse($profileReferenceOption, 'Reference option retrieved successfully.');
    }

    public function update(Request $request, string $scope, string $type, $id): JsonResponse
    {
        $this->guardScopeType($scope, $type);
        $profileReferenceOption = $this->findOption($type, (int) $id);
        abort_unless($profileReferenceOption->scope === $scope, 404);

        $input = $this->validatedInput($request, $scope, $type, $profileReferenceOption);
        $profileReferenceOption->update($input);

        return $this->sendSuccess('Reference option updated successfully.');
    }

    public function import(Request $request, string $scope, string $type)
    {
        $this->guardScopeType($scope, $type);

        $request->validate([
            'file' => ['required', 'file', function ($attribute, $value, $fail) {
                $ext = strtolower($value->getClientOriginalExtension());
                if (!in_array($ext, ['csv', 'xls', 'xlsx'])) {
                    $fail('The file field must be a file of type: csv, xls, xlsx.');
                }
            }],
        ]);

        $import = new ProfileReferenceOptionsImport($scope, $type);
        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            $message = 'Reference options import completed with validation errors. Please fix the failed rows and try again.';

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'errors' => collect($import->failures())->map(fn ($failure) => [
                        'row' => $failure->row(),
                        'attribute' => $failure->attribute(),
                        'errors' => $failure->errors(),
                        'values' => $failure->values(),
                    ])->values(),
                ], 422);
            }

            return back()->withFailures($import->failures());
        }

        $message = 'Reference options imported successfully. Imported: '.$import->importedCount().', skipped duplicates: '.$import->skippedCount().'.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        flash($message)->success();

        return back();
    }

    public function destroy(string $scope, string $type, $id): JsonResponse
    {
        $this->guardScopeType($scope, $type);
        $profileReferenceOption = $this->findOption($type, (int) $id);
        abort_unless($profileReferenceOption->scope === $scope, 404);
        $profileReferenceOption->delete();

        return $this->sendSuccess('Reference option deleted successfully.');
    }

    public function bulkDestroy(Request $request, string $scope, string $type): JsonResponse
    {
        $this->guardScopeType($scope, $type);

        $input = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        $deleted = 0;

        foreach ($input['ids'] as $id) {
            $profileReferenceOption = ProfileReferenceOption::findRecord($type, (int) $id);

            if (! $profileReferenceOption || $profileReferenceOption->scope !== $scope) {
                continue;
            }

            $profileReferenceOption->delete();
            $deleted++;
        }

        $message = $deleted === 1
            ? '1 reference option deleted successfully.'
            : $deleted.' reference options deleted successfully.';

        return $this->sendSuccess($message);
    }

    public function dedicatedIndex(): View|RedirectResponse
    {
        [$scope, $type] = $this->scopeTypeFromDedicatedRoute();

        return $this->index($scope, $type);
    }

    public function dedicatedStore(Request $request): JsonResponse
    {
        [$scope, $type] = $this->scopeTypeFromDedicatedRoute();

        return $this->store($request, $scope, $type);
    }

    public function dedicatedImport(Request $request)
    {
        [$scope, $type] = $this->scopeTypeFromDedicatedRoute();

        return $this->import($request, $scope, $type);
    }

    public function dedicatedBulkDestroy(Request $request): JsonResponse
    {
        [$scope, $type] = $this->scopeTypeFromDedicatedRoute();

        return $this->bulkDestroy($request, $scope, $type);
    }

    public function dedicatedEdit($id): JsonResponse
    {
        [$scope, $type] = $this->scopeTypeFromDedicatedRoute();

        return $this->edit($scope, $type, $id);
    }

    public function dedicatedUpdate(Request $request, $id): JsonResponse
    {
        [$scope, $type] = $this->scopeTypeFromDedicatedRoute();

        return $this->update($request, $scope, $type, $id);
    }

    public function dedicatedDestroy($id): JsonResponse
    {
        [$scope, $type] = $this->scopeTypeFromDedicatedRoute();

        return $this->destroy($scope, $type, $id);
    }

    private function validatedInput(Request $request, string $scope, string $type, ?ProfileReferenceOption $option = null): array
    {
        $value = filled($request->input('value')) ? trim((string) $request->input('value')) : trim((string) $request->input('label'));

        $request->merge([
            'scope' => $scope,
            'value' => $value,
        ]);

        $input = $request->validate([
            'scope' => ['required', Rule::in(array_keys(ProfileReferenceOption::scopeLabels()))],
            'label' => ['required', 'string', 'max:150'],
            'value' => [
                'required',
                'string',
                'max:150',
                Rule::unique(ProfileReferenceOption::tableFor($type), 'value')
                    ->where(fn ($query) => $query->where('scope', $scope))
                    ->ignore($option?->type === $type ? $option->id : null),
            ],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $input['is_active'] = $request->boolean('is_active');
        $input['sort_order'] = $input['sort_order'] ?? 0;

        return $input;
    }

    private function guardScopeType(string $scope, string $type): void
    {
        abort_unless(ProfileReferenceOption::isAllowedForScope($scope, $type), 404);
    }

    private function findOption(string $type, $id): ProfileReferenceOption
    {
        abort_unless(array_key_exists($type, ProfileReferenceOption::tableMap()), 404);

        $option = ProfileReferenceOption::findRecord($type, (int) $id);
        abort_if($option === null, 404);

        return $option;
    }

    private function scopeTypeFromDedicatedRoute(): array
    {
        $routeName = request()->route()?->getName();
        $routeBaseName = Str::before((string) $routeName, '.');

        foreach (ProfileReferenceOption::commonDedicatedRouteNames() as $type => $dedicatedRouteName) {
            if ($dedicatedRouteName === $routeBaseName) {
                return [ProfileReferenceOption::SCOPE_COMMON, $type];
            }
        }

        foreach (ProfileReferenceOption::candidateDedicatedRouteNames() as $type => $dedicatedRouteName) {
            if ($dedicatedRouteName === $routeBaseName) {
                return [ProfileReferenceOption::SCOPE_CANDIDATE, $type];
            }
        }

        foreach (ProfileReferenceOption::employerDedicatedRouteNames() as $type => $dedicatedRouteName) {
            if ($dedicatedRouteName === $routeBaseName) {
                return [ProfileReferenceOption::SCOPE_EMPLOYER, $type];
            }
        }

        abort(404);
    }
}
