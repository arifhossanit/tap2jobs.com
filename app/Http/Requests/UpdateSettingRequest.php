<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'sectionName' => ['required', Rule::in(['general', 'front_office_details', 'social_settings', 'about_us', 'env_setting'])],
        ];

        switch ($this->input('sectionName')) {
            case 'general':
                $rules += [
                    'application_name' => ['required', 'string', 'max:255'],
                    'company_url' => ['required', 'url:http,https', 'max:2048'],
                    'company_description' => ['required', 'string'],
                    'logo' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
                    'footer_logo' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
                    'favicon' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:1024'],
                    'default_language' => ['required', 'string', 'exists:languages,iso_code'],
                    'default_country_id' => ['nullable', 'exists:countries,id'],
                    'default_country_code' => ['nullable', 'string', 'max:10'],
                    'enable_google_recaptcha' => ['nullable', 'boolean'],
                    'job_approved' => ['nullable', 'boolean'],
                ];
                break;

            case 'front_office_details':
                $rules += [
                    'address' => ['required', 'string', 'max:1000'],
                    'phone' => ['required', 'string', 'regex:/^[0-9]{4,20}$/'],
                    'region_code' => ['required', 'string', 'regex:/^\+?[0-9]{1,4}$/'],
                    'email' => ['required', 'email:filter', 'max:255'],
                ];
                break;

            case 'social_settings':
                $rules += [
                    'facebook_url' => ['nullable', 'url:http,https', 'max:2048', 'regex:~^https?://(?:www\.)?facebook\.com(?:/|$)~i'],
                    'instagram_url' => ['nullable', 'url:http,https', 'max:2048', 'regex:~^https?://(?:www\.)?instagram\.com(?:/|$)~i'],
                    'linkedIn_url' => ['nullable', 'url:http,https', 'max:2048', 'regex:~^https?://(?:[a-z]{2,3}\.)?linkedin\.com(?:/|$)~i'],
                ];
                break;

            case 'about_us':
                $rules['about_us'] = ['required', 'string'];
                break;

            case 'env_setting':
                foreach ([
                    'facebook_app_id', 'facebook_app_secret', 'facebook_redirect',
                    'pusher_app_id', 'pusher_app_key', 'pusher_app_secret', 'pusher_app_cluster',
                    'stripe_key', 'stripe_secret', 'stripe_webhook_key',
                    'paypal_client_id', 'paypal_secret',
                    'paystack_key', 'paystack_secret', 'paystack_payment_url',
                    'linkedin_client_id', 'linkedin_client_secret',
                    'google_client_id', 'google_client_secret', 'google_redirect',
                ] as $field) {
                    $rules[$field] = ['nullable', 'string', 'max:2048'];
                }
                $rules['cookie_consent_enabled'] = ['nullable', 'boolean'];
                break;
        }

        return $rules;
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($this->input('sectionName') !== 'about_us') {
                    return;
                }

                $plainText = preg_replace('/[\s\x{00A0}]+/u', '', html_entity_decode(strip_tags((string) $this->input('about_us'))));

                if ($plainText === '') {
                    $validator->errors()->add('about_us', __('js.about_us_required'));
                }
            },
        ];
    }
}
