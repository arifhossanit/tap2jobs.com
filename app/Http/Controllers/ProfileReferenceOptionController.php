<?php

namespace App\Http\Controllers;

use App\Models\ProfileReferenceOption;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileReferenceOptionController extends AppBaseController
{
    public function index(string $scope, string $type): View
    {
        $this->guardScopeType($scope, $type);

        return view('profile_reference_options.index', [
            'typeLabels' => ProfileReferenceOption::typeLabels(),
            'scopeLabels' => ProfileReferenceOption::scopeLabels(),
            'scope' => $scope,
            'type' => $type,
            'title' => (ProfileReferenceOption::scopeLabels()[$scope] ?? $scope).' - '.(ProfileReferenceOption::typeLabels()[$type] ?? $type),
            'options' => ProfileReferenceOption::records($type, $scope),
        ]);
    }

    public function store(Request $request, string $scope, string $type): JsonResponse
    {
        $this->guardScopeType($scope, $type);

        $input = $this->validatedInput($request, $scope, $type);
        $option = ProfileReferenceOption::createRecord($type, $input);

        return $this->sendResponse($option, 'Reference option saved successfully.');
    }

    public function edit(string $scope, string $type, int $id): JsonResponse
    {
        $this->guardScopeType($scope, $type);
        $profileReferenceOption = $this->findOption($type, $id);
        abort_unless($profileReferenceOption->scope === $scope, 404);

        return $this->sendResponse($profileReferenceOption, 'Reference option retrieved successfully.');
    }

    public function update(Request $request, string $scope, string $type, int $id): JsonResponse
    {
        $this->guardScopeType($scope, $type);
        $profileReferenceOption = $this->findOption($type, $id);
        abort_unless($profileReferenceOption->scope === $scope, 404);

        $input = $this->validatedInput($request, $scope, $type, $profileReferenceOption);
        $profileReferenceOption->update($input);

        return $this->sendSuccess('Reference option updated successfully.');
    }

    public function destroy(string $scope, string $type, int $id): JsonResponse
    {
        $this->guardScopeType($scope, $type);
        $profileReferenceOption = $this->findOption($type, $id);
        abort_unless($profileReferenceOption->scope === $scope, 404);
        $profileReferenceOption->delete();

        return $this->sendSuccess('Reference option deleted successfully.');
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

    private function findOption(string $type, int $id): ProfileReferenceOption
    {
        abort_unless(array_key_exists($type, ProfileReferenceOption::tableMap()), 404);

        $option = ProfileReferenceOption::findRecord($type, $id);
        abort_if($option === null, 404);

        return $option;
    }
}
