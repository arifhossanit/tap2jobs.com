<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\ConsultationLead;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DemoEmployerSeeder extends Seeder
{
    private const PER_LETTER_LIMIT = 20;
    private const DEFAULT_PASSWORD = '123456';
    private const DEFAULT_CSV = 'database/seeders/data/demo-employer-names.csv';

    public function run(): void
    {
        $csvPath = $this->csvPath();
        $names = $this->readNames($csvPath);

        if ($names === []) {
            $this->command?->warn('No employer names found in '.$csvPath);

            return;
        }

        DB::transaction(function () use ($names) {
            $now = Carbon::now();
            $role = Role::firstOrCreate(['name' => 'Employer', 'guard_name' => 'web']);
            $adminId = User::query()
                ->whereHas('roles', fn ($query) => $query->where('name', 'Admin'))
                ->value('id') ?: User::query()->value('id');

            $lookups = $this->lookups($now);

            foreach ($names as $index => $companyName) {
                $serial = $index + 1;
                $slug = Str::limit(Str::slug(Str::ascii($companyName)) ?: 'demo-employer-'.$serial, 60, '');
                $email = $this->uniqueEmail($slug, $serial);
                $username = $this->uniqueUsername($slug, $serial);
                $phone = $this->uniquePhone($serial);
                $contactName = $companyName.' HR';
                $industryIds = [$lookups['industry_ids'][$index % count($lookups['industry_ids'])]];
                $companySizeId = $lookups['company_size_ids'][$index % count($lookups['company_size_ids'])];
                $companySize = DB::table('company_sizes')->where('id', $companySizeId)->first();
                $location = $lookups['locations'][$index % count($lookups['locations'])];

                /** @var User $user */
                $user = User::query()->create([
                    'username' => $username,
                    'first_name' => $companyName,
                    'email' => $email,
                    'phone' => $phone,
                    'password' => Hash::make(self::DEFAULT_PASSWORD),
                    'country_id' => $location['country_id'],
                    'state_id' => $location['state_id'],
                    'city_id' => $location['city_id'],
                    'thana_id' => $location['thana_id'],
                    'region_code' => '880',
                    'is_active' => 1,
                    'is_verified' => 1,
                    'email_verified_at' => $now,
                ]);
                $user->assignRole($role);

                /** @var Company $company */
                $company = Company::query()->create([
                    'user_id' => $user->id,
                    'unique_id' => getUniqueCompanyId(),
                    'company_name' => $companyName,
                    'contact_person_name' => $contactName,
                    'contact_person_designation' => 'HR Manager',
                    'ceo' => 'HR Manager',
                    'industry_id' => $industryIds[0],
                    'industry_ids' => $industryIds,
                    'ownership_type_id' => $lookups['ownership_type_id'],
                    'company_size_id' => $companySizeId,
                    'employee_range' => $companySize?->size,
                    'established_in' => random_int(2000, (int) date('Y')),
                    'no_of_offices' => random_int(1, 4),
                    'details' => '<p>Demo employer profile for '.$companyName.'. This is fake data for testing.</p>',
                    'website' => 'https://'.$slug.'.example.test',
                    'location' => $location['address'],
                    'billing_address' => $location['address'],
                    'billing_phone' => $phone,
                    'billing_region_code' => '880',
                    'billing_email' => $email,
                    'has_disability_facilities' => false,
                    'disability_inclusion_policy' => null,
                    'disability_inclusion_support' => null,
                    'disability_inclusion_training' => null,
                    'disability_facilities' => [],
                    'last_change' => $adminId,
                    'created_by' => Company::CREATED_BY_ADMIN_DEMO,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $user->update([
                    'owner_id' => $company->id,
                    'owner_type' => Company::class,
                ]);

                $this->createLead($company, $user, $companySize, $now);
            }
        });

        $this->command?->info('Demo employers seeded from '.$csvPath);
    }

    private function csvPath(): string
    {
        $path = env('DEMO_EMPLOYER_CSV', self::DEFAULT_CSV);

        return Str::startsWith($path, [DIRECTORY_SEPARATOR, '/', '\\']) || preg_match('/^[A-Z]:\\\\/i', $path)
            ? $path
            : base_path($path);
    }

    private function readNames(string $csvPath): array
    {
        if (! is_file($csvPath)) {
            $this->command?->error('CSV file not found: '.$csvPath);

            return [];
        }

        $handle = fopen($csvPath, 'r');
        if (! $handle) {
            return [];
        }

        $header = fgetcsv($handle);
        $nameIndex = $this->nameColumnIndex($header);

        if ($nameIndex === null && $header) {
            $nameIndex = 0;
        }

        $groupedNames = [];

        if ($header && $nameIndex === 0) {
            $firstVal = trim((string) ($header[0] ?? ''));
            if ($firstVal !== '' && mb_strtolower($firstVal) !== 'company name' && mb_strtolower($firstVal) !== 'name') {
                $this->addNameToGroup($firstVal, $groupedNames);
            }
        }

        while (($row = fgetcsv($handle)) !== false) {
            $name = trim((string) ($row[$nameIndex] ?? ''));
            if ($name !== '') {
                $this->addNameToGroup($name, $groupedNames);
            }
        }

        fclose($handle);

        ksort($groupedNames);

        $result = [];
        foreach ($groupedNames as $letter => $names) {
            foreach ($names as $name) {
                $result[] = $name;
            }
        }

        return array_values(array_unique($result));
    }

    private function addNameToGroup(string $name, array &$groupedNames): void
    {
        if (preg_match('/[a-zA-Z]/', $name, $matches)) {
            $letter = strtoupper($matches[0]);
            if (! isset($groupedNames[$letter])) {
                $groupedNames[$letter] = [];
            }
            if (count($groupedNames[$letter]) < self::PER_LETTER_LIMIT) {
                if (! in_array($name, $groupedNames[$letter], true)) {
                    $groupedNames[$letter][] = $name;
                }
            }
        }
    }

    private function nameColumnIndex(?array $header): ?int
    {
        if (! $header) {
            return null;
        }

        foreach ($header as $index => $column) {
            $normalizedColumn = Str::lower(trim((string) $column));
            $normalizedColumn = str_replace([' ', '-'], '_', $normalizedColumn);

            if (in_array($normalizedColumn, ['name', 'company_name', 'company'], true)) {
                return $index;
            }
        }

        return null;
    }

    private function lookups(Carbon $now): array
    {
        $countryId = DB::table('countries')->where('short_code', 'BD')->value('id')
            ?: DB::table('countries')->where('name', 'Bangladesh')->value('id')
            ?: DB::table('countries')->value('id');
        $stateId = DB::table('states')->where('country_id', $countryId)->value('id')
            ?: DB::table('states')->value('id');
        $cityId = DB::table('cities')->where('state_id', $stateId)->value('id')
            ?: DB::table('cities')->value('id');
        $thanaId = Schema::hasTable('thanas')
            ? DB::table('thanas')->where('city_id', $cityId)->value('id')
            : null;

        $industryIds = DB::table('industries')->pluck('id')->all();
        if ($industryIds === []) {
            $industryTypeId = Schema::hasTable('industry_types')
                ? DB::table('industry_types')->value('id')
                : null;

            if (! $industryTypeId && Schema::hasTable('industry_types')) {
                $industryTypeId = DB::table('industry_types')->insertGetId([
                    'name' => 'General',
                    'sort_order' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $industryIds[] = DB::table('industries')->insertGetId([
                'industry_type_id' => $industryTypeId,
                'name' => 'General Business',
                'description' => 'General Business',
                'is_default' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $ownershipTypeId = DB::table('ownership_types')->value('id')
            ?: DB::table('ownership_types')->insertGetId([
                'name' => 'Private',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

        $companySizeIds = DB::table('company_sizes')->pluck('id')->all();
        if ($companySizeIds === []) {
            $companySizeIds[] = DB::table('company_sizes')->insertGetId([
                'size' => '51-100',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return [
            'industry_ids' => $industryIds,
            'ownership_type_id' => $ownershipTypeId,
            'company_size_ids' => $companySizeIds,
            'locations' => [[
                'country_id' => $countryId,
                'state_id' => $stateId,
                'city_id' => $cityId,
                'thana_id' => $thanaId,
                'address' => 'Dhaka, Bangladesh',
            ]],
        ];
    }

    private function uniqueEmail(string $slug, int $serial): string
    {
        $email = 'demo.employer.'.$slug.'.'.$serial.'@example.test';

        return User::query()->where('email', $email)->exists()
            ? 'demo.employer.'.$slug.'.'.$serial.'.'.time().'@example.test'
            : $email;
    }

    private function uniqueUsername(string $slug, int $serial): string
    {
        $base = Str::limit('demo_'.$slug, 80, '').'_'.$serial;
        $username = $base;
        $suffix = 1;

        while (User::query()->where('username', $username)->exists()) {
            $username = $base.'_'.$suffix;
            $suffix++;
        }

        return $username;
    }

    private function uniquePhone(int $serial): string
    {
        $phone = '17'.str_pad((string) $serial, 9, '0', STR_PAD_LEFT);

        while (User::query()->where('phone', $phone)->exists()) {
            $serial += 10000;
            $phone = '17'.str_pad((string) $serial, 9, '0', STR_PAD_LEFT);
        }

        return $phone;
    }

    private function createLead(Company $company, User $user, ?object $companySize, Carbon $now): void
    {
        if (! Schema::hasTable('consultation_leads') || ! Schema::hasColumn('consultation_leads', 'lead_from')) {
            return;
        }

        ConsultationLead::query()->updateOrCreate(
            [
                'lead_from' => ConsultationLead::LEAD_FROM_EMPLOYER,
                'employer_id' => $company->id,
            ],
            [
                'name' => $company->contact_person_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'company_name' => $company->company_name,
                'designation' => $company->contact_person_designation,
                'company_website' => $company->website,
                'company_size_id' => $company->company_size_id,
                'company_category_id' => $companySize?->company_category_id,
                'consultation_type' => '',
                'source_page' => getSettingValue('application_name') ?: config('app.name'),
                'status' => ConsultationLead::STATUS_NEW,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}
