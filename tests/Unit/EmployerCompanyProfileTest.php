<?php

namespace Tests\Unit;

use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\User;
use Tests\TestCase;

class EmployerCompanyProfileTest extends TestCase
{
    public function test_company_information_form_submits_every_persisted_profile_field(): void
    {
        $form = file_get_contents(resource_path('views/employer/companies/edit_fields.blade.php'));

        foreach ([
            'image', 'name', 'company_name_bn', 'established_in', 'employee_range',
            'country_id', 'state_id', 'city_id', 'location', 'company_address_bn',
            'industry_ids[]', 'details', 'trade_license_no', 'rl_no', 'website',
            'contact_person_name', 'ceo', 'email', 'phone', 'region_code',
            'billing_address', 'billing_phone', 'billing_region_code', 'billing_email',
            'has_disability_facilities', 'disability_inclusion_policy',
            'disability_inclusion_support', 'disability_inclusion_training',
            'disability_facilities[]',
        ] as $field) {
            $this->assertStringContainsString($field, $form, "Missing employer profile field: {$field}");
        }

        $companyFillable = (new Company())->getFillable();
        foreach ([
            'company_name', 'company_name_bn', 'established_in', 'employee_range',
            'location', 'company_address_bn', 'industry_ids', 'industry_id', 'details',
            'trade_license_no', 'rl_no', 'website', 'contact_person_name', 'ceo',
            'contact_person_designation', 'billing_address', 'billing_phone',
            'billing_region_code', 'billing_email', 'has_disability_facilities',
            'disability_inclusion_policy', 'disability_inclusion_support',
            'disability_inclusion_training', 'disability_facilities',
        ] as $field) {
            $this->assertContains($field, $companyFillable, "Company field is not fillable: {$field}");
        }
    }

    public function test_logo_is_validated_forwarded_and_saved_to_the_user_media_collection(): void
    {
        $request = new UpdateCompanyRequest();
        $rules = $request->rules();
        $controller = file_get_contents(app_path('Http/Controllers/CompanyController.php'));
        $repository = file_get_contents(app_path('Repositories/CompanyRepository.php'));

        $this->assertArrayHasKey('image', $rules);
        $this->assertStringContainsString('nullable', $rules['image']);
        $this->assertStringContainsString('mimes:jpeg,jpg,png', $rules['image']);
        $this->assertStringContainsString("hasFile('image')", $controller);
        $this->assertStringContainsString("\$input['image'] = \$request->file('image')", $controller);
        $this->assertStringContainsString('instanceof UploadedFile', $repository);
        $this->assertStringContainsString('toMediaCollection(User::PROFILE', $repository);
        $this->assertSame('profile-pictures', User::PROFILE);
    }

    public function test_employer_account_navigation_uses_synced_offsets_and_complete_hashes(): void
    {
        $view = file_get_contents(resource_path('views/employer/companies/edit.blade.php'));
        $styles = file_get_contents(resource_path('assets/sass/new-custom.scss'));

        $this->assertSame(2, substr_count($view, "__('messages.company.edit_company')"));
        $this->assertStringContainsString("contactDetailsPanel: '#contact-details'", $view);
        $this->assertStringContainsString("billingAddressPanel: '#billing-address'", $view);
        $this->assertStringContainsString('getEmployerAccountScrollOffset() + 8', $view);
        $this->assertStringNotContainsString('getBoundingClientRect().top + window.pageYOffset - 82', $view);
        $this->assertSame(1, substr_count($styles, 'max-height: calc(100vh - 90px);'));
        $this->assertStringContainsString('var(--employer-account-scroll-offset, 82px)', $styles);
    }
}
