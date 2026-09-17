<?php

namespace App\Http\Requests;

use App\Models\JobCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobCategoryRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $tags = is_array($this->input('search_tags'))
            ? $this->input('search_tags')
            : preg_split('/[,\r\n]+/', (string) $this->input('search_tags'));

        $this->merge([
            'search_tags' => collect($tags)
                ->map(fn ($tag) => trim(strip_tags((string) $tag)))
                ->filter()
                ->unique(fn ($tag) => mb_strtolower($tag))
                ->values()
                ->all(),
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = JobCategory::$rules;
        $rules['name'] = 'required|max:160|unique:job_categories,name,'.$this->route('jobCategory')->id;
        $rules['customer_image'] = 'nullable|mimes:jpeg,jpg,png';
        $rules['slug'] = [
            'required',
            'string',
            'max:180',
            'alpha_dash:ascii',
            Rule::unique('job_categories', 'slug')->ignore($this->route('jobCategory')->id),
        ];
        $rules['seo_title'] = ['nullable', 'string', 'max:180'];
        $rules['meta_description'] = ['nullable', 'string', 'max:255'];
        $rules['search_tags'] = ['nullable', 'array'];
        $rules['search_tags.*'] = ['string', 'max:60', 'distinct:ignore_case'];

        return $rules;
    }

    public function messages(): array
    {
        $messages['customer_image.mimes'] = __('messages.image_type');

        return $messages;
    }
}
