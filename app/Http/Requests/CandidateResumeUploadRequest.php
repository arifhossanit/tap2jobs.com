<?php

namespace App\Http\Requests;

use App\Services\ResumeFileSecurityService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use RuntimeException;

class CandidateResumeUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx', 'max:10240'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $file = $this->file('file');
            if (! $file || ! $file->isValid() || $validator->errors()->has('file')) {
                return;
            }

            try {
                app(ResumeFileSecurityService::class)->assertSafeUpload($file);
            } catch (RuntimeException $exception) {
                $validator->errors()->add('file', $exception->getMessage());
            }
        });
    }
}
