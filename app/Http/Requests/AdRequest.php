<?php

namespace App\Http\Requests;

use App\Models\Ad;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class AdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $pages = $this->input('page', []);

        if (is_array($pages) && in_array(Ad::PAGE_ALL, $pages, true)) {
            $this->merge(['page' => [Ad::PAGE_ALL]]);
        }
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'link_url' => ['nullable', 'url', 'max:255'],
            'cta_text' => ['nullable', 'string', 'max:50'],
            'position' => ['required', Rule::in(array_keys(Ad::POSITIONS))],
            'page' => ['required', 'array', 'min:1'],
            'page.*' => ['string', Rule::in(array_keys(Ad::PAGES))],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'ad_image' => ['nullable', 'file', 'mimes:jpeg,jpg,png,webp,svg,mp4,webm,ogg', 'max:51200'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $position = (string) $this->input('position');
            $pages = (array) $this->input('page', []);
            $validPages = Ad::validTargetPagesForPosition($position);

            if (empty($position) || empty($pages) || empty($validPages)) {
                return;
            }

            $invalidPages = array_diff($pages, $validPages);

            if (! empty($invalidPages)) {
                $labels = collect($invalidPages)
                    ->map(fn ($page) => __('messages.ad.pages.'.$page))
                    ->implode(', ');

                $validator->errors()->add(
                    'page',
                    'The selected target page is not available for this position: '.$labels.'.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'ad_image.mimes' => __('messages.ad.media_extension_message'),
            'ad_image.max' => __('messages.ad.media_size_message'),
            'position.required' => __('messages.ad.position_required'),
            'page.required' => __('messages.ad.page_required'),
            'page.min' => __('messages.ad.page_required'),
            'link_url.url' => __('messages.ad.valid_url'),
        ];
    }
}
