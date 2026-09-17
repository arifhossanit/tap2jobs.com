<?php

namespace App\Http\Requests;

use App\Models\JobCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateJobCategoryRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['search_tags' => $this->normalizedSearchTags()]);
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
        return [
            'name' => ['required', 'string', 'max:160', Rule::unique('job_categories', 'name')],
            'description' => 'nullable',
            'slug' => ['nullable', 'string', 'max:180', 'alpha_dash:ascii', Rule::unique('job_categories', 'slug')],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'search_tags' => ['nullable', 'array'],
            'search_tags.*' => ['string', 'max:60', 'distinct:ignore_case'],
            'customer_image' => 'nullable|mimes:png,jpg,jpeg',
        ];
    }

    private function normalizedSearchTags(): array
    {
        $tags = is_array($this->input('search_tags'))
            ? $this->input('search_tags')
            : preg_split('/[,\r\n]+/', (string) $this->input('search_tags'));

        return collect($tags)
            ->map(fn ($tag) => trim(strip_tags((string) $tag)))
            ->filter()
            ->unique(fn ($tag) => mb_strtolower($tag))
            ->values()
            ->all();
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return  [
            'customer_image.mimes' => __('messages.image_type'),
        ];
    }
}
