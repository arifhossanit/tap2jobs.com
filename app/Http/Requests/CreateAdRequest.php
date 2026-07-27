<?php

namespace App\Http\Requests;

use App\Models\Ad;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateAdRequest
 */
class CreateAdRequest extends FormRequest
{
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
        return Ad::$rules;
    }

    /**
     * @return array|string[]
     */
    public function messages(): array
    {
        return [
            'ad_image.mimes' => __('messages.image_slider.image_extension_message'),
            'position.required' => __('messages.ad.position_required'),
            'link_url.url' => __('messages.ad.valid_url'),
        ];
    }
}
