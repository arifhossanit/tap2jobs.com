<?php

namespace App\Http\Requests;

use App\Models\EmailTemplate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateEMailTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:150', 'not_regex:/[<>]/'],
            'body' => ['required', 'string'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $plainText = preg_replace(
                    '/[\s\x{00A0}]+/u',
                    '',
                    html_entity_decode(strip_tags((string) $this->input('body')))
                );

                if ($plainText === '') {
                    $validator->errors()->add('body', __('validation.required', ['attribute' => 'body']));
                }

                $template = $this->route('emailTemplate');
                if (! $template instanceof EmailTemplate) {
                    return;
                }

                preg_match_all('/\{\{[A-Za-z0-9_]+\}\}/', (string) $template->variables, $declaredMatches);
                $compatibilityVariables = match ($template->template_name) {
                    'Job Notification' => ['{{date}}', '{{jobs}}'],
                    'Job Alert' => ['{{candidate_name}}', '{{job_name}}'],
                    'Password Reset Email' => ['{{reset_expire_minutes}}'],
                    default => [],
                };
                $declaredMatches[0] = array_merge($declaredMatches[0], $compatibilityVariables);
                preg_match_all(
                    '/\{\{[A-Za-z0-9_]+\}\}/',
                    $this->input('subject').' '.$this->input('body'),
                    $usedMatches
                );

                $unknownVariables = array_values(array_diff(
                    array_unique($usedMatches[0]),
                    array_unique($declaredMatches[0])
                ));

                if ($unknownVariables !== []) {
                    $validator->errors()->add(
                        'body',
                        'Unknown short code(s): '.implode(', ', $unknownVariables)
                    );
                }

                if (str_contains((string) $this->input('subject'), '{{jobs}}')) {
                    $validator->errors()->add(
                        'subject',
                        'The {{jobs}} short code can only be used in the email body.'
                    );
                }
            },
        ];
    }
}