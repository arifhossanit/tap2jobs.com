<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
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
        $rules = Post::$rules;
        $rules['slug'] = ['nullable', 'string', 'max:180', Rule::unique('posts', 'slug')->ignore($this->route('post'))];

        return $rules;
    }
}
