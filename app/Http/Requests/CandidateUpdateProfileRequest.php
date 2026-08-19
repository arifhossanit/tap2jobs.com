<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CandidateUpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $currentSalary = removeCommaFromNumbers($this->request->get('current_salary'));
        $expectedSalary = removeCommaFromNumbers($this->request->get('expected_salary'));

        $this->request->set('current_salary', $currentSalary);
        $this->request->set('expected_salary', $expectedSalary);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $id = Auth::user()->id;

        return [
            'candidateSkills' => 'nullable',
            'first_name' => 'required|max:150',
            'last_name' => 'required|max:150',
            'father_name' => 'max:150',
            'mother_name' => 'nullable|max:150',
            'religion' => 'nullable|max:100',
            'email' => 'required|email:filter|unique:users,email,'.$id,
            'dob' => 'nullable|date',
            'phone' => 'nullable',
            'secondary_mobile' => 'nullable|max:30',
            'marital_status_id' => 'required',
            'nationality' => 'max:150',
            'national_id_card' => 'max:150',
            'passport_number' => 'nullable|max:150',
            'passport_issue_date' => 'nullable|date',
            'alternate_email' => 'nullable|email:filter|max:150',
            'emergency_contact' => 'nullable|max:30',
            'blood_group' => 'nullable|max:10',
            'height' => 'nullable|numeric|min:0|max:999',
            'weight' => 'nullable|numeric|min:0|max:999',
            'objective' => 'nullable|max:2000',
            'job_level' => 'nullable|in:entry,mid,top',
            'job_nature' => 'nullable|in:full_time,part_time,contract,internship,freelance',
            'preferred_functional_categories' => 'nullable|array|max:10',
            'preferred_functional_categories.*' => 'integer|exists:functional_areas,id',
            'preferred_special_skills' => 'nullable|array|max:10',
            'preferred_special_skills.*' => 'integer|exists:skills,id',
            'preferred_job_locations_inside' => 'nullable|array|max:64',
            'preferred_job_locations_inside.*' => 'integer|exists:states,id',
            'preferred_job_locations_outside' => 'nullable|array|max:100',
            'preferred_job_locations_outside.*' => 'integer|exists:countries,id',
            'preferred_organization_types' => 'nullable|array|max:50',
            'preferred_organization_types.*' => 'integer|exists:ownership_types,id',
            'career_summary' => 'nullable|max:3000',
            'special_qualification' => 'nullable|max:2000',
            'keywords' => 'nullable|max:1000',
            'has_disability_id' => 'nullable|boolean',
            'disability_id_number' => 'nullable|max:100',
            'disability_id_show_on_profile' => 'nullable|boolean',
            'disability_difficulty_seeing' => 'nullable|in:no_difficulty,some_difficulty,a_lot_of_difficulty,cannot_do',
            'disability_difficulty_hearing' => 'nullable|in:no_difficulty,some_difficulty,a_lot_of_difficulty,cannot_do',
            'disability_difficulty_remembering' => 'nullable|in:no_difficulty,some_difficulty,a_lot_of_difficulty,cannot_do',
            'disability_difficulty_walking' => 'nullable|in:no_difficulty,some_difficulty,a_lot_of_difficulty,cannot_do',
            'disability_difficulty_communicating' => 'nullable|in:no_difficulty,some_difficulty,a_lot_of_difficulty,cannot_do',
            'disability_difficulty_self_care' => 'nullable|in:no_difficulty,some_difficulty,a_lot_of_difficulty,cannot_do',
            'current_salary' => 'nullable|numeric|min:0|max:999999999',
            'expected_salary' => 'nullable|numeric|min:0|max:999999999',
            'password' => 'nullable|min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'nullable|min:6',
            'candidateLanguage' => 'nullable',
            'image' => 'nullable|mimes:jpeg,jpg,png',

        ];
    }
}
