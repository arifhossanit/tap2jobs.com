<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class BangladeshJobMarketSeeder extends Seeder
{
    private const COMPANY_COUNT = 520;
    private const JOB_COUNT = 520;

    public function run(): void
    {
        DB::transaction(function () {
            $now = Carbon::now();
            $role = Role::firstOrCreate(['name' => 'Employer', 'guard_name' => 'web']);
            $adminId = User::query()->whereHas('roles', fn ($query) => $query->where('name', 'Admin'))->value('id')
                ?: User::query()->value('id');

            $locations = $this->seedBangladeshLocations($now);
            $lookups = $this->seedLookups($now);
            $companies = $this->seedCompanies($locations, $lookups, $role->id, $adminId, $now);
            $this->seedJobs($companies, $locations, $lookups, $adminId, $now);
        });
    }

    private function seedBangladeshLocations(Carbon $now): array
    {
        $countryId = DB::table('countries')->where('short_code', 'BD')->value('id')
            ?: DB::table('countries')->where('name', 'Bangladesh')->value('id');

        if (! $countryId) {
            $countryId = DB::table('countries')->insertGetId([
                'name' => 'Bangladesh',
                'short_code' => 'BD',
                'phone_code' => '880',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $divisions = [
            'Dhaka' => ['Dhaka', 'Gazipur', 'Narayanganj', 'Savar', 'Tangail', 'Narsingdi'],
            'Chattogram' => ['Chattogram', 'Coxs Bazar', 'Cumilla', 'Feni', 'Noakhali'],
            'Sylhet' => ['Sylhet', 'Moulvibazar', 'Habiganj', 'Sunamganj'],
            'Rajshahi' => ['Rajshahi', 'Bogura', 'Pabna', 'Natore'],
            'Khulna' => ['Khulna', 'Jashore', 'Kushtia', 'Satkhira'],
            'Barishal' => ['Barishal', 'Patuakhali', 'Bhola'],
            'Rangpur' => ['Rangpur', 'Dinajpur', 'Nilphamari'],
            'Mymensingh' => ['Mymensingh', 'Jamalpur', 'Netrokona'],
        ];

        $locations = [];
        foreach ($divisions as $division => $cities) {
            $stateId = DB::table('states')
                ->where('country_id', $countryId)
                ->where('name', $division)
                ->value('id');

            if (! $stateId) {
                $stateId = DB::table('states')->insertGetId([
                    'country_id' => $countryId,
                    'name' => $division,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            foreach ($cities as $city) {
                $cityId = DB::table('cities')
                    ->where('state_id', $stateId)
                    ->where('name', $city)
                    ->value('id');

                if (! $cityId) {
                    $cityId = DB::table('cities')->insertGetId([
                        'state_id' => $stateId,
                        'name' => $city,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                $locations[] = [
                    'country_id' => $countryId,
                    'state_id' => $stateId,
                    'city_id' => $cityId,
                    'division' => $division,
                    'city' => $city,
                ];
            }
        }

        return $locations;
    }

    private function seedLookups(Carbon $now): array
    {
        $industryTypeIds = $this->tableExists('industry_types') ? [
            'Information Technology' => $this->firstId('industry_types', 'name', 'Information Technology', ['sort_order' => 4], $now),
            'Manufacturing' => $this->firstId('industry_types', 'name', 'Manufacturing', ['sort_order' => 5], $now),
            'Banking/ Financial Services' => $this->firstId('industry_types', 'name', 'Banking/ Financial Services', ['sort_order' => 6], $now),
            'Healthcare/ Pharmaceutical' => $this->firstId('industry_types', 'name', 'Healthcare/ Pharmaceutical', ['sort_order' => 7], $now),
            'Education/ Training' => $this->firstId('industry_types', 'name', 'Education/ Training', ['sort_order' => 8], $now),
            'Hospitality/ Travel' => $this->firstId('industry_types', 'name', 'Hospitality/ Travel', ['sort_order' => 9], $now),
        ] : [];

        $industries = [
            'Software and IT Services' => 'Information Technology',
            'E-commerce' => 'Information Technology',
            'Telecommunication' => 'Information Technology',
            'Garments and Textiles' => 'Manufacturing',
            'Pharmaceuticals' => 'Healthcare/ Pharmaceutical',
            'Banking and Fintech' => 'Banking/ Financial Services',
            'NGO and Development' => 'Education/ Training',
            'Education and Training' => 'Education/ Training',
            'Hospital and Diagnostics' => 'Healthcare/ Pharmaceutical',
            'Logistics and Supply Chain' => 'Manufacturing',
            'FMCG and Retail' => 'Manufacturing',
            'Hospitality and Tourism' => 'Hospitality/ Travel',
        ];

        $industryIds = [];
        $industryMap = [];
        foreach ($industries as $industry => $type) {
            $industryMap[$industry] = $this->firstId('industries', 'name', $industry, [
                'description' => $industry,
                'industry_type_id' => $industryTypeIds[$type] ?? null,
                'is_default' => true,
            ], $now);
            $industryIds[] = $industryMap[$industry];
        }

        $ownershipTypeMap = [
            'Private Limited' => $this->firstId('ownership_types', 'name', 'Private Limited', ['description' => 'Private Limited', 'is_default' => true], $now),
            'Public Limited' => $this->firstId('ownership_types', 'name', 'Public Limited', ['description' => 'Public Limited', 'is_default' => true], $now),
            'NGO' => $this->firstId('ownership_types', 'name', 'NGO', ['description' => 'NGO', 'is_default' => true], $now),
            'Partnership' => $this->firstId('ownership_types', 'name', 'Partnership', ['description' => 'Partnership', 'is_default' => true], $now),
        ];
        $companySizeMap = [
            '1-50' => $this->firstId('company_sizes', 'size', '1-50', ['is_default' => true], $now),
            '51-200' => $this->firstId('company_sizes', 'size', '51-200', ['is_default' => true], $now),
            '201-500' => $this->firstId('company_sizes', 'size', '201-500', ['is_default' => true], $now),
            '501-1000' => $this->firstId('company_sizes', 'size', '501-1000', ['is_default' => true], $now),
            '1000+' => $this->firstId('company_sizes', 'size', '1000+', ['is_default' => true], $now),
        ];
        $jobTypeMap = [
            'Full Time' => $this->firstId('job_types', 'name', 'Full Time', ['description' => 'Full Time', 'is_default' => true], $now),
            'Contractual' => $this->firstId('job_types', 'name', 'Contractual', ['description' => 'Contractual', 'is_default' => true], $now),
            'Internship' => $this->firstId('job_types', 'name', 'Internship', ['description' => 'Internship', 'is_default' => true], $now),
        ];
        $careerLevelMap = [
            'Entry Level' => $this->firstId('career_levels', 'level_name', 'Entry Level', ['is_default' => true], $now),
            'Mid Level' => $this->firstId('career_levels', 'level_name', 'Mid Level', ['is_default' => true], $now),
            'Senior Level' => $this->firstId('career_levels', 'level_name', 'Senior Level', ['is_default' => true], $now),
        ];
        $functionalAreaMap = [
            'Software Development' => $this->firstId('functional_areas', 'name', 'Software Development', ['is_default' => true], $now),
            'Sales and Marketing' => $this->firstId('functional_areas', 'name', 'Sales and Marketing', ['is_default' => true], $now),
            'Accounts and Finance' => $this->firstId('functional_areas', 'name', 'Accounts and Finance', ['is_default' => true], $now),
            'Human Resources' => $this->firstId('functional_areas', 'name', 'Human Resources', ['is_default' => true], $now),
            'Operations' => $this->firstId('functional_areas', 'name', 'Operations', ['is_default' => true], $now),
            'Customer Support' => $this->firstId('functional_areas', 'name', 'Customer Support', ['is_default' => true], $now),
            'Merchandising' => $this->firstId('functional_areas', 'name', 'Merchandising', ['is_default' => true], $now),
            'Quality Assurance' => $this->firstId('functional_areas', 'name', 'Quality Assurance', ['is_default' => true], $now),
            'Healthcare Services' => $this->firstId('functional_areas', 'name', 'Healthcare Services', ['is_default' => true], $now),
            'Education and Training' => $this->firstId('functional_areas', 'name', 'Education and Training', ['is_default' => true], $now),
        ];
        $jobShiftMap = [
            'Day Shift' => $this->firstId('job_shifts', 'shift', 'Day Shift', ['description' => 'Day Shift', 'is_default' => true], $now),
            'Night Shift' => $this->firstId('job_shifts', 'shift', 'Night Shift', ['description' => 'Night Shift', 'is_default' => true], $now),
            'Flexible Shift' => $this->firstId('job_shifts', 'shift', 'Flexible Shift', ['description' => 'Flexible Shift', 'is_default' => true], $now),
        ];
        $degreeLevelMap = [
            'HSC' => $this->firstId('required_degree_levels', 'name', 'HSC', ['is_default' => true], $now),
            'Diploma' => $this->firstId('required_degree_levels', 'name', 'Diploma', ['is_default' => true], $now),
            'Bachelor' => $this->firstId('required_degree_levels', 'name', 'Bachelor', ['is_default' => true], $now),
            'Masters' => $this->firstId('required_degree_levels', 'name', 'Masters', ['is_default' => true], $now),
        ];
        $jobCategoryMap = [
            'IT and Software' => $this->firstId('job_categories', 'name', 'IT and Software', ['description' => 'IT and Software', 'is_featured' => true, 'is_default' => true], $now),
            'Garments and Textile' => $this->firstId('job_categories', 'name', 'Garments and Textile', ['description' => 'Garments and Textile', 'is_featured' => true, 'is_default' => true], $now),
            'Sales and Marketing' => $this->firstId('job_categories', 'name', 'Sales and Marketing', ['description' => 'Sales and Marketing', 'is_featured' => true, 'is_default' => true], $now),
            'Bank and Finance' => $this->firstId('job_categories', 'name', 'Bank and Finance', ['description' => 'Bank and Finance', 'is_featured' => true, 'is_default' => true], $now),
            'Healthcare' => $this->firstId('job_categories', 'name', 'Healthcare', ['description' => 'Healthcare', 'is_featured' => true, 'is_default' => true], $now),
            'Education' => $this->firstId('job_categories', 'name', 'Education', ['description' => 'Education', 'is_featured' => true, 'is_default' => true], $now),
            'NGO Development' => $this->firstId('job_categories', 'name', 'NGO Development', ['description' => 'NGO Development', 'is_featured' => true, 'is_default' => true], $now),
            'Operations and Supply Chain' => $this->firstId('job_categories', 'name', 'Operations and Supply Chain', ['description' => 'Operations and Supply Chain', 'is_featured' => true, 'is_default' => true], $now),
        ];
        $skillMap = $this->seedSkills($now);
        $tagMap = $this->seedTags($now);

        return [
            'industries' => $industryIds,
            'industry_map' => $industryMap,
            'ownership_types' => array_values($ownershipTypeMap),
            'company_sizes' => array_values($companySizeMap),
            'currency_id' => $this->firstCurrencyId($now),
            'salary_period_id' => $this->firstId('salary_periods', 'period', 'Monthly Pay Period', ['description' => 'Monthly Pay Period', 'is_default' => true], $now),
            'job_types' => array_values($jobTypeMap),
            'job_type_map' => $jobTypeMap,
            'career_levels' => array_values($careerLevelMap),
            'career_level_map' => $careerLevelMap,
            'functional_areas' => array_values($functionalAreaMap),
            'functional_area_map' => $functionalAreaMap,
            'job_shifts' => array_values($jobShiftMap),
            'job_shift_map' => $jobShiftMap,
            'degree_levels' => array_values($degreeLevelMap),
            'degree_level_map' => $degreeLevelMap,
            'job_categories' => array_values($jobCategoryMap),
            'job_category_map' => $jobCategoryMap,
            'skills' => array_values($skillMap),
            'skill_map' => $skillMap,
            'tags' => array_values($tagMap),
            'tag_map' => $tagMap,
        ];
    }

    private function seedCompanies(array $locations, array $lookups, int $roleId, ?int $adminId, Carbon $now): array
    {
        $prefixes = ['Padma', 'Meghna', 'Jamuna', 'Karnafuli', 'Rupsha', 'Surma', 'Bengal', 'Dhaka', 'Chattogram', 'Sylhet', 'Narayanganj', 'Gazipur'];
        $businesses = ['Tech', 'Textiles', 'Pharma', 'Foods', 'Logistics', 'Finance', 'Retail', 'Health', 'Education', 'Digital', 'Agro', 'Solutions'];
        $suffixes = ['Limited', 'Group', 'Holdings', 'Services', 'Industries', 'Ventures'];
        $contacts = ['Ahmed Rahman', 'Nusrat Jahan', 'Tanvir Hasan', 'Farhana Islam', 'Mahmudul Karim', 'Sadia Akter', 'Imran Chowdhury', 'Nabila Hossain'];
        $companies = [];

        for ($i = 1; $i <= self::COMPANY_COUNT; $i++) {
            $location = $locations[($i - 1) % count($locations)];
            $name = $prefixes[$i % count($prefixes)].' '.$businesses[$i % count($businesses)].' '.$suffixes[$i % count($suffixes)].' '.str_pad((string) $i, 3, '0', STR_PAD_LEFT);
            $email = 'bd-employer-'.str_pad((string) $i, 3, '0', STR_PAD_LEFT).'@tap2jobs.test';

            $userId = DB::table('users')->where('email', $email)->value('id');
            $userData = $this->filterColumns('users', [
                'first_name' => $name,
                'last_name' => 'HR',
                'username' => 'bd_employer_'.str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                'email' => $email,
                'phone' => '17'.str_pad((string) (($i * 7919) % 100000000), 8, '0', STR_PAD_LEFT),
                'email_verified_at' => $now,
                'password' => Hash::make('123456'),
                'country_id' => $location['country_id'],
                'state_id' => $location['state_id'],
                'city_id' => $location['city_id'],
                'is_active' => true,
                'is_verified' => true,
                'language' => 'bn',
                'is_default' => true,
                'region_code' => 'bd',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            if ($userId) {
                unset($userData['created_at'], $userData['password']);
                DB::table('users')->where('id', $userId)->update($userData);
            } else {
                $userId = DB::table('users')->insertGetId($userData);
            }

            $this->assignRole($userId, $roleId);

            $companyId = DB::table('companies')->where('user_id', $userId)->value('id');
            $industryId = $lookups['industries'][$i % count($lookups['industries'])];
            $companyData = $this->filterColumns('companies', [
                'ceo' => $contacts[$i % count($contacts)],
                'company_name' => $name,
                'company_name_bn' => null,
                'contact_person_name' => $contacts[($i + 3) % count($contacts)],
                'contact_person_designation' => 'HR Manager',
                'company_summary' => $name.' is a Bangladesh based employer hiring across '.$location['city'].' and nearby districts.',
                'company_summary_bn' => null,
                'trade_license_no' => 'TRD-BD-'.str_pad((string) $i, 6, '0', STR_PAD_LEFT),
                'rl_no' => null,
                'employee_range' => ['1-50', '51-200', '201-500', '501-1000', '1000+'][$i % 5],
                'industry_id' => $industryId,
                'industry_ids' => json_encode([$industryId]),
                'ownership_type_id' => $lookups['ownership_types'][$i % count($lookups['ownership_types'])],
                'company_size_id' => $lookups['company_sizes'][$i % count($lookups['company_sizes'])],
                'established_in' => 1995 + ($i % 29),
                'details' => $name.' focuses on steady hiring, inclusive workplaces, and practical career growth for local talent.',
                'website' => 'https://company'.str_pad((string) $i, 3, '0', STR_PAD_LEFT).'.example.com',
                'location' => $location['city'].', '.$location['division'].', Bangladesh',
                'location2' => 'House '.(($i % 99) + 1).', Road '.(($i % 20) + 1).', '.$location['city'],
                'company_address_bn' => null,
                'billing_address' => 'Accounts Office, '.$location['city'].', Bangladesh',
                'billing_phone' => '+8801'.str_pad((string) (($i * 3571) % 100000000), 9, '0', STR_PAD_LEFT),
                'billing_region_code' => 'bd',
                'billing_email' => 'accounts+'.str_pad((string) $i, 3, '0', STR_PAD_LEFT).'@tap2jobs.test',
                'has_disability_facilities' => $i % 3 === 0,
                'disability_inclusion_policy' => $i % 4 === 0,
                'disability_inclusion_support' => $i % 3 === 0,
                'disability_inclusion_training' => $i % 5 === 0,
                'disability_facilities' => json_encode($i % 3 === 0 ? ['accessible_entrance', 'flexible_interview'] : []),
                'no_of_offices' => ($i % 12) + 1,
                'fax' => null,
                'user_id' => $userId,
                'unique_id' => 'BDCO'.str_pad((string) $i, 6, '0', STR_PAD_LEFT),
                'last_change' => $adminId ?: $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            if ($companyId) {
                unset($companyData['created_at']);
                DB::table('companies')->where('id', $companyId)->update($companyData);
            } else {
                $companyId = DB::table('companies')->insertGetId($companyData);
            }

            DB::table('users')->where('id', $userId)->update($this->filterColumns('users', [
                'owner_id' => $companyId,
                'owner_type' => Company::class,
                'updated_at' => $now,
            ]));

            $companies[] = ['id' => $companyId, 'user_id' => $userId, 'location' => $location];
        }

        return $companies;
    }

    private function seedJobs(array $companies, array $locations, array $lookups, ?int $adminId, Carbon $now): void
    {
        $catalog = $this->jobCatalog();
        $jobTypeByStatus = [
            Job::EMPLOYMENT_STATUS_FULL_TIME => $lookups['job_type_map']['Full Time'],
            Job::EMPLOYMENT_STATUS_CONTRACTUAL => $lookups['job_type_map']['Contractual'],
            Job::EMPLOYMENT_STATUS_INTERNSHIP => $lookups['job_type_map']['Internship'],
            Job::EMPLOYMENT_STATUS_PART_TIME => $lookups['job_type_map']['Contractual'],
            Job::EMPLOYMENT_STATUS_FREELANCE => $lookups['job_type_map']['Contractual'],
        ];

        for ($i = 1; $i <= self::JOB_COUNT; $i++) {
            $company = $companies[($i - 1) % count($companies)];
            $location = $locations[($i + 7) % count($locations)];
            $jobProfile = $catalog[($i - 1) % count($catalog)];
            $experience = $jobProfile['experience'][0] + ($i % (($jobProfile['experience'][1] - $jobProfile['experience'][0]) + 1));
            $salaryFrom = $jobProfile['salary'][0] + (($i % 5) * 2000);
            $salaryTo = $jobProfile['salary'][1] + (($i % 7) * 3000);
            $status = $i % 17 === 0 ? Job::STATUS_PAUSED : Job::STATUS_OPEN;
            $workplaceMode = $jobProfile['workplace'] ?? ($i % 5);
            $jobId = 'BDJOB'.str_pad((string) $i, 6, '0', STR_PAD_LEFT);
            $existingJobId = DB::table('jobs')->where('job_id', $jobId)->value('id');
            $employmentStatus = $jobProfile['employment_status'];
            $skillIds = $this->idsFromNames($lookups['skill_map'], $jobProfile['skills']);
            $tagIds = $this->idsFromNames($lookups['tag_map'], $jobProfile['tags']);

            $jobData = $this->filterColumns('jobs', [
                'job_id' => $jobId,
                'job_title' => $jobProfile['title'].' - '.$location['city'],
                'description' => $this->paragraphsToHtml([
                    $jobProfile['context'],
                    'This Bangladesh market role is suitable for candidates who can work with local teams, vendors, customers, and reporting lines while keeping delivery standards high.',
                    'Applicants should be comfortable with practical field realities, deadline pressure, and clear written communication.',
                ]),
                'key_responsibilities' => $this->listToHtml($jobProfile['responsibilities']),
                'salary_from' => $salaryFrom,
                'salary_to' => $salaryTo,
                'company_id' => $company['id'],
                'job_category_id' => $lookups['job_category_map'][$jobProfile['category']],
                'currency_id' => $lookups['currency_id'],
                'salary_period_id' => $lookups['salary_period_id'],
                'job_type_id' => $jobTypeByStatus[$employmentStatus],
                'employment_status' => $employmentStatus,
                'work_from_office' => in_array($workplaceMode, [0, 3], true),
                'work_from_home' => in_array($workplaceMode, [1, 3], true),
                'hybrid' => $workplaceMode === 3,
                'career_level_id' => $lookups['career_level_map'][$jobProfile['career_level']],
                'functional_area_id' => $lookups['functional_area_map'][$jobProfile['functional_area']],
                'job_shift_id' => $lookups['job_shift_map'][$jobProfile['shift']],
                'degree_level_id' => $lookups['degree_level_map'][$jobProfile['degree']],
                'experience' => $experience,
                'experience_unit' => Job::EXPERIENCE_UNIT_YEAR,
                'experience_requirement' => $experience === 0 ? '0' : $experience.'-'.($experience + 2),
                'freshers_encouraged' => $jobProfile['freshers'],
                'vacancy' => $jobProfile['vacancy'][0] + ($i % (($jobProfile['vacancy'][1] - $jobProfile['vacancy'][0]) + 1)),
                'position' => $jobProfile['vacancy'][0] + ($i % (($jobProfile['vacancy'][1] - $jobProfile['vacancy'][0]) + 1)),
                'job_expiry_date' => $now->copy()->addDays(30 + ($i % 90))->toDateString(),
                'no_preference' => 2,
                'hide_salary' => $jobProfile['hide_salary'] ?? false,
                'is_freelance' => $employmentStatus === Job::EMPLOYMENT_STATUS_FREELANCE,
                'is_suspended' => Job::NOT_SUSPENDED,
                'country_id' => $location['country_id'],
                'state_id' => $location['state_id'],
                'city_id' => $location['city_id'],
                'status' => $status,
                'is_created_by_admin' => false,
                'last_change' => $adminId ?: $company['user_id'],
                'is_default' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            if ($existingJobId) {
                unset($jobData['created_at']);
                DB::table('jobs')->where('id', $existingJobId)->update($jobData);
                $databaseJobId = $existingJobId;
            } else {
                $databaseJobId = DB::table('jobs')->insertGetId($jobData);
            }

            $this->syncPivot('jobs_skill', 'job_id', $databaseJobId, 'skill_id', $skillIds);

            if ($this->tableExists('jobs_tag')) {
                $this->syncPivot('jobs_tag', 'job_id', $databaseJobId, 'tag_id', $tagIds);
            }
        }
    }

    private function jobCatalog(): array
    {
        return [
            [
                'title' => 'Laravel Developer',
                'category' => 'IT and Software',
                'functional_area' => 'Software Development',
                'career_level' => 'Mid Level',
                'degree' => 'Bachelor',
                'shift' => 'Day Shift',
                'employment_status' => Job::EMPLOYMENT_STATUS_FULL_TIME,
                'experience' => [2, 5],
                'salary' => [50000, 95000],
                'vacancy' => [1, 4],
                'freshers' => false,
                'workplace' => 3,
                'skills' => ['PHP', 'Laravel', 'REST API', 'MySQL', 'Git'],
                'tags' => ['Bangladesh Jobs', 'IT Jobs', 'Hybrid'],
                'context' => 'The engineering team is expanding its Laravel product stack for employer, candidate, and back-office workflows.',
                'responsibilities' => [
                    'Build and maintain Laravel modules, REST APIs, queues, jobs, and scheduled tasks.',
                    'Design database tables, migrations, seeders, and Eloquent relationships for new features.',
                    'Review existing Blade, Livewire, and JavaScript flows before changing user-facing behavior.',
                    'Write clean validation, authorization, and ownership checks for candidate and employer data.',
                    'Optimize slow queries and troubleshoot production issues from logs and user reports.',
                    'Coordinate with frontend, QA, and product teams to release tested features on time.',
                ],
            ],
            [
                'title' => 'Frontend Developer',
                'category' => 'IT and Software',
                'functional_area' => 'Software Development',
                'career_level' => 'Mid Level',
                'degree' => 'Bachelor',
                'shift' => 'Day Shift',
                'employment_status' => Job::EMPLOYMENT_STATUS_FULL_TIME,
                'experience' => [1, 4],
                'salary' => [40000, 80000],
                'vacancy' => [1, 5],
                'freshers' => false,
                'workplace' => 3,
                'skills' => ['JavaScript', 'React', 'Vue.js', 'Git', 'Communication'],
                'tags' => ['Bangladesh Jobs', 'IT Jobs', 'Hybrid'],
                'context' => 'The product team needs a frontend developer to improve responsive dashboards and job search experiences.',
                'responsibilities' => [
                    'Develop responsive pages and reusable UI components for candidate and employer dashboards.',
                    'Convert product requirements into accessible forms, tables, filters, and modal interactions.',
                    'Integrate frontend components with Laravel routes, Livewire events, and JSON APIs.',
                    'Fix layout issues across mobile, tablet, and desktop browsers.',
                    'Maintain JavaScript modules and keep compiled assets aligned with source files.',
                    'Work with QA to resolve visual regressions before release.',
                ],
            ],
            [
                'title' => 'SQA Engineer',
                'category' => 'IT and Software',
                'functional_area' => 'Quality Assurance',
                'career_level' => 'Entry Level',
                'degree' => 'Bachelor',
                'shift' => 'Day Shift',
                'employment_status' => Job::EMPLOYMENT_STATUS_FULL_TIME,
                'experience' => [1, 3],
                'salary' => [30000, 60000],
                'vacancy' => [1, 4],
                'freshers' => true,
                'workplace' => 0,
                'skills' => ['Manual Testing', 'Test Case Writing', 'Selenium', 'Communication', 'Report Writing'],
                'tags' => ['Bangladesh Jobs', 'IT Jobs', 'Freshers Encouraged'],
                'context' => 'The QA team is looking for a detail-oriented tester for web application releases.',
                'responsibilities' => [
                    'Prepare test cases, checklists, and regression plans from feature requirements.',
                    'Execute functional, UI, smoke, and regression testing for candidate and employer journeys.',
                    'Log bugs with clear reproduction steps, screenshots, expected results, and severity.',
                    'Verify fixes and coordinate with developers before deployment.',
                    'Test forms, filters, pagination, file uploads, notifications, and permission-sensitive actions.',
                    'Support automation coverage for stable high-value workflows.',
                ],
            ],
            [
                'title' => 'Merchandiser - Knit Garments',
                'category' => 'Garments and Textile',
                'functional_area' => 'Merchandising',
                'career_level' => 'Mid Level',
                'degree' => 'Bachelor',
                'shift' => 'Day Shift',
                'employment_status' => Job::EMPLOYMENT_STATUS_FULL_TIME,
                'experience' => [3, 6],
                'salary' => [45000, 85000],
                'vacancy' => [2, 8],
                'freshers' => false,
                'workplace' => 0,
                'skills' => ['Merchandising', 'TNA Planning', 'Costing', 'Buyer Communication', 'Fabric Sourcing'],
                'tags' => ['Bangladesh Jobs', 'RMG', 'Factory'],
                'context' => 'A knit garments exporter needs merchandisers to manage orders from development to shipment.',
                'responsibilities' => [
                    'Handle complete merchandising process from order confirmation to shipment handover.',
                    'Prepare TNA plans, costing sheets, order sheets, and buyer approval trackers.',
                    'Coordinate fabric, trims, accessories, lab dips, strike-offs, and sample submissions.',
                    'Follow up with cutting, sewing, finishing, quality, commercial, and shipment teams.',
                    'Communicate with buyers on specifications, approvals, delivery schedules, and claims.',
                    'Maintain order files, production status reports, and shipment documentation.',
                    'Monitor delivery risks and escalate material or quality issues early.',
                ],
            ],
            [
                'title' => 'Quality Inspector - Garments',
                'category' => 'Garments and Textile',
                'functional_area' => 'Quality Assurance',
                'career_level' => 'Entry Level',
                'degree' => 'Diploma',
                'shift' => 'Day Shift',
                'employment_status' => Job::EMPLOYMENT_STATUS_FULL_TIME,
                'experience' => [2, 5],
                'salary' => [28000, 52000],
                'vacancy' => [3, 10],
                'freshers' => false,
                'workplace' => 0,
                'skills' => ['Quality Inspection', 'AQL', 'Garments Production', 'MS Excel', 'Report Writing'],
                'tags' => ['Bangladesh Jobs', 'RMG', 'Factory'],
                'context' => 'The factory quality team requires inspectors for inline, final, and buyer-standard inspections.',
                'responsibilities' => [
                    'Conduct inline, pre-final, and final inspections according to buyer quality standards.',
                    'Check measurements, workmanship, shade, trims, packing, labeling, and finishing quality.',
                    'Prepare inspection reports with defect classification and corrective action notes.',
                    'Coordinate with production supervisors to reduce recurring defects on the sewing floor.',
                    'Follow AQL requirements and maintain inspection documentation for audit readiness.',
                    'Escalate critical quality issues before shipment or buyer inspection.',
                ],
            ],
            [
                'title' => 'Sales Executive',
                'category' => 'Sales and Marketing',
                'functional_area' => 'Sales and Marketing',
                'career_level' => 'Entry Level',
                'degree' => 'Bachelor',
                'shift' => 'Day Shift',
                'employment_status' => Job::EMPLOYMENT_STATUS_FULL_TIME,
                'experience' => [1, 4],
                'salary' => [25000, 55000],
                'vacancy' => [5, 20],
                'freshers' => true,
                'workplace' => 0,
                'skills' => ['Sales', 'Route Planning', 'Key Account Management', 'Communication', 'MS Excel'],
                'tags' => ['Bangladesh Jobs', 'Field Job', 'Freshers Encouraged'],
                'context' => 'The sales team is hiring field executives for retail, pharmacy, restaurant, and showroom coverage.',
                'responsibilities' => [
                    'Visit assigned outlets daily and generate new business from local market routes.',
                    'Maintain retailer relationships, collect orders, and follow up on payment commitments.',
                    'Prepare daily visit plans, sales reports, and competitor activity updates.',
                    'Support key account management, promotional campaigns, fairs, and activation events.',
                    'Coordinate with distribution and customer service teams for timely delivery.',
                    'Achieve monthly sales targets while maintaining customer satisfaction.',
                ],
            ],
            [
                'title' => 'Digital Marketing Executive',
                'category' => 'Sales and Marketing',
                'functional_area' => 'Sales and Marketing',
                'career_level' => 'Entry Level',
                'degree' => 'Bachelor',
                'shift' => 'Day Shift',
                'employment_status' => Job::EMPLOYMENT_STATUS_FULL_TIME,
                'experience' => [1, 3],
                'salary' => [28000, 60000],
                'vacancy' => [1, 4],
                'freshers' => true,
                'workplace' => 3,
                'skills' => ['Digital Marketing', 'SEO', 'Facebook Ads', 'MS Excel', 'Communication'],
                'tags' => ['Bangladesh Jobs', 'Hybrid', 'Freshers Encouraged'],
                'context' => 'The marketing team needs a digital executive to grow employer branding and candidate acquisition campaigns.',
                'responsibilities' => [
                    'Plan and execute Facebook, Google, SEO, and content campaigns for assigned products.',
                    'Prepare keyword research, campaign calendars, creatives briefs, and performance reports.',
                    'Track CPL, CTR, conversion, and lead quality using analytics dashboards.',
                    'Coordinate with design and sales teams for campaign assets and landing page updates.',
                    'Run A/B tests and optimize copy, targeting, and budget allocation.',
                    'Monitor competitor campaigns and suggest improvements for local audiences.',
                ],
            ],
            [
                'title' => 'Accounts Officer',
                'category' => 'Bank and Finance',
                'functional_area' => 'Accounts and Finance',
                'career_level' => 'Entry Level',
                'degree' => 'Bachelor',
                'shift' => 'Day Shift',
                'employment_status' => Job::EMPLOYMENT_STATUS_FULL_TIME,
                'experience' => [2, 5],
                'salary' => [32000, 65000],
                'vacancy' => [1, 5],
                'freshers' => false,
                'workplace' => 0,
                'skills' => ['Accounting', 'Bank Reconciliation', 'Tax and VAT', 'Financial Reporting', 'MS Excel'],
                'tags' => ['Bangladesh Jobs', 'Finance Jobs'],
                'context' => 'The finance department needs an accounts officer for receivable, payable, reporting, and compliance work.',
                'responsibilities' => [
                    'Maintain accounts receivable, payable schedules, vouchers, and supporting documents.',
                    'Prepare daily collection reports and ensure timely bank deposits.',
                    'Perform monthly bank reconciliation and resolve unreconciled transactions.',
                    'Assist in journal entries, general ledger maintenance, and month-end closing.',
                    'Support VAT, tax deduction, filing, challan, and compliance documentation.',
                    'Prepare periodic financial reports for management review.',
                ],
            ],
            [
                'title' => 'Credit Officer',
                'category' => 'Bank and Finance',
                'functional_area' => 'Accounts and Finance',
                'career_level' => 'Mid Level',
                'degree' => 'Bachelor',
                'shift' => 'Day Shift',
                'employment_status' => Job::EMPLOYMENT_STATUS_FULL_TIME,
                'experience' => [2, 6],
                'salary' => [40000, 75000],
                'vacancy' => [1, 6],
                'freshers' => false,
                'workplace' => 0,
                'skills' => ['Credit Analysis', 'Financial Reporting', 'MS Excel', 'Communication', 'Report Writing'],
                'tags' => ['Bangladesh Jobs', 'Finance Jobs', 'Field Job'],
                'context' => 'A financial services employer needs credit officers for SME and retail borrower assessment.',
                'responsibilities' => [
                    'Review loan applications, KYC documents, income sources, and business information.',
                    'Conduct field verification and prepare credit assessment notes.',
                    'Analyze repayment capacity, risk indicators, and collateral documentation.',
                    'Coordinate with branch, operations, and recovery teams for application processing.',
                    'Maintain credit files and update MIS reports accurately.',
                    'Follow internal policy, Bangladesh Bank guidance, and compliance requirements.',
                ],
            ],
            [
                'title' => 'Nurse',
                'category' => 'Healthcare',
                'functional_area' => 'Healthcare Services',
                'career_level' => 'Entry Level',
                'degree' => 'Diploma',
                'shift' => 'Flexible Shift',
                'employment_status' => Job::EMPLOYMENT_STATUS_FULL_TIME,
                'experience' => [1, 5],
                'salary' => [25000, 55000],
                'vacancy' => [3, 12],
                'freshers' => true,
                'workplace' => 0,
                'skills' => ['Nursing', 'Customer Service', 'Communication', 'Report Writing'],
                'tags' => ['Bangladesh Jobs', 'Healthcare Jobs', 'Freshers Encouraged'],
                'context' => 'A hospital and diagnostics employer requires nurses for ward, emergency, and patient support duties.',
                'responsibilities' => [
                    'Provide patient care according to physician instructions and hospital protocols.',
                    'Monitor vital signs, medication schedules, and patient condition changes.',
                    'Maintain patient records, nursing notes, and handover documentation.',
                    'Support emergency response, admission, discharge, and patient counselling.',
                    'Coordinate with doctors, lab, pharmacy, and front desk teams.',
                    'Follow infection control, hygiene, and patient safety standards.',
                ],
            ],
            [
                'title' => 'Teacher',
                'category' => 'Education',
                'functional_area' => 'Education and Training',
                'career_level' => 'Entry Level',
                'degree' => 'Bachelor',
                'shift' => 'Day Shift',
                'employment_status' => Job::EMPLOYMENT_STATUS_FULL_TIME,
                'experience' => [1, 4],
                'salary' => [22000, 50000],
                'vacancy' => [2, 8],
                'freshers' => true,
                'workplace' => 0,
                'skills' => ['Teaching', 'Training Facilitation', 'Communication', 'Report Writing'],
                'tags' => ['Bangladesh Jobs', 'Freshers Encouraged'],
                'context' => 'An education provider is hiring teachers for classroom delivery and learner progress tracking.',
                'responsibilities' => [
                    'Prepare lesson plans, class materials, assignments, and assessment activities.',
                    'Conduct classes with clear instruction and active student participation.',
                    'Evaluate scripts, maintain attendance, and report learner progress to guardians.',
                    'Support academic events, exam invigilation, and curriculum improvement work.',
                    'Maintain classroom discipline and inclusive learning practices.',
                    'Coordinate with academic coordinators for routine and syllabus completion.',
                ],
            ],
            [
                'title' => 'Project Officer',
                'category' => 'NGO Development',
                'functional_area' => 'Operations',
                'career_level' => 'Mid Level',
                'degree' => 'Bachelor',
                'shift' => 'Day Shift',
                'employment_status' => Job::EMPLOYMENT_STATUS_CONTRACTUAL,
                'experience' => [2, 6],
                'salary' => [35000, 70000],
                'vacancy' => [1, 6],
                'freshers' => false,
                'workplace' => 0,
                'skills' => ['Project Management', 'Monitoring and Evaluation', 'Report Writing', 'Communication', 'MS Excel'],
                'tags' => ['Bangladesh Jobs', 'NGO Jobs', 'Field Job'],
                'context' => 'A development organization is hiring project officers for field implementation and donor reporting.',
                'responsibilities' => [
                    'Implement project activities according to work plan, budget, and donor requirements.',
                    'Coordinate with community stakeholders, local administration, and partner organizations.',
                    'Collect field data, update beneficiary records, and prepare monthly progress reports.',
                    'Monitor activity quality and document lessons, challenges, and corrective actions.',
                    'Support training, workshops, awareness sessions, and field visits.',
                    'Maintain project files, procurement requests, and compliance documentation.',
                ],
            ],
            [
                'title' => 'Supply Chain Executive',
                'category' => 'Operations and Supply Chain',
                'functional_area' => 'Operations',
                'career_level' => 'Mid Level',
                'degree' => 'Bachelor',
                'shift' => 'Day Shift',
                'employment_status' => Job::EMPLOYMENT_STATUS_FULL_TIME,
                'experience' => [2, 5],
                'salary' => [35000, 70000],
                'vacancy' => [1, 6],
                'freshers' => false,
                'workplace' => 0,
                'skills' => ['Supply Chain', 'Inventory Management', 'Procurement', 'MS Excel', 'Communication'],
                'tags' => ['Bangladesh Jobs', 'Factory'],
                'context' => 'The operations team needs supply chain executives for procurement, inventory, and vendor coordination.',
                'responsibilities' => [
                    'Prepare purchase requisitions, quotation comparisons, and supplier follow-up reports.',
                    'Maintain inventory records, stock movement reports, and reorder alerts.',
                    'Coordinate local procurement, delivery schedules, and vendor payment documents.',
                    'Support warehouse, production, and finance teams with timely material availability.',
                    'Track purchase orders, GRN, invoices, and pending delivery issues.',
                    'Identify cost-saving opportunities without compromising quality or timeline.',
                ],
            ],
            [
                'title' => 'Customer Support Executive',
                'category' => 'Operations and Supply Chain',
                'functional_area' => 'Customer Support',
                'career_level' => 'Entry Level',
                'degree' => 'HSC',
                'shift' => 'Flexible Shift',
                'employment_status' => Job::EMPLOYMENT_STATUS_FULL_TIME,
                'experience' => [0, 3],
                'salary' => [18000, 35000],
                'vacancy' => [5, 20],
                'freshers' => true,
                'workplace' => 0,
                'skills' => ['Customer Service', 'Communication', 'MS Excel', 'Report Writing'],
                'tags' => ['Bangladesh Jobs', 'Freshers Encouraged'],
                'context' => 'The service team is hiring support executives for phone, chat, and ticket-based customer assistance.',
                'responsibilities' => [
                    'Respond to customer queries through phone, chat, email, and support tickets.',
                    'Understand customer issues, provide accurate information, and escalate complex cases.',
                    'Maintain call notes, complaint logs, and daily service reports.',
                    'Coordinate with operations, delivery, billing, and technical teams for resolution.',
                    'Follow service scripts while keeping communication polite and practical.',
                    'Meet response time, resolution, and customer satisfaction targets.',
                ],
            ],
            [
                'title' => 'HR Officer',
                'category' => 'Operations and Supply Chain',
                'functional_area' => 'Human Resources',
                'career_level' => 'Entry Level',
                'degree' => 'Bachelor',
                'shift' => 'Day Shift',
                'employment_status' => Job::EMPLOYMENT_STATUS_FULL_TIME,
                'experience' => [1, 4],
                'salary' => [28000, 55000],
                'vacancy' => [1, 4],
                'freshers' => true,
                'workplace' => 0,
                'skills' => ['HR Operations', 'Recruitment', 'Payroll', 'MS Excel', 'Communication'],
                'tags' => ['Bangladesh Jobs', 'Freshers Encouraged'],
                'context' => 'The HR team requires an officer for recruitment, attendance, documentation, and employee support.',
                'responsibilities' => [
                    'Publish job posts, shortlist CVs, schedule interviews, and maintain recruitment trackers.',
                    'Prepare appointment letters, employee files, joining documents, and separation records.',
                    'Support attendance, leave, overtime, payroll inputs, and HRIS updates.',
                    'Coordinate onboarding, orientation, training logistics, and employee communication.',
                    'Assist in policy implementation, grievance documentation, and compliance reporting.',
                    'Prepare monthly HR reports for management review.',
                ],
            ],
        ];
    }

    private function paragraphsToHtml(array $paragraphs): string
    {
        return collect($paragraphs)
            ->map(fn (string $paragraph): string => '<p>'.e($paragraph).'</p>')
            ->implode('');
    }

    private function listToHtml(array $items): string
    {
        return '<ul>'.collect($items)
            ->map(fn (string $item): string => '<li>'.e($item).'</li>')
            ->implode('').'</ul>';
    }

    private function idsFromNames(array $map, array $names): array
    {
        return array_values(array_filter(array_map(fn (string $name): ?int => $map[$name] ?? null, $names)));
    }

    private function seedSkills(Carbon $now): array
    {
        $skills = [
            'PHP', 'Laravel', 'JavaScript', 'Vue.js', 'React', 'MySQL', 'REST API', 'Git', 'Linux',
            'Manual Testing', 'Test Case Writing', 'Selenium', 'MS Excel', 'Costing', 'TNA Planning',
            'Buyer Communication', 'Fabric Sourcing', 'Garments Production', 'Quality Inspection',
            'AQL', 'Pattern Making', 'SMV', 'Sales', 'Route Planning', 'Key Account Management',
            'Digital Marketing', 'SEO', 'Facebook Ads', 'Accounting', 'Bank Reconciliation',
            'Tax and VAT', 'Financial Reporting', 'Credit Analysis', 'Customer Service',
            'HR Operations', 'Recruitment', 'Payroll', 'Supply Chain', 'Inventory Management',
            'Warehouse Operations', 'Procurement', 'Nursing', 'Pharmacy', 'Laboratory Testing',
            'Teaching', 'Training Facilitation', 'Project Management', 'Monitoring and Evaluation',
            'Report Writing', 'Communication',
        ];

        $skillMap = [];
        foreach ($skills as $skill) {
            $skillMap[$skill] = $this->firstId('skills', 'name', $skill, [
                'description' => $skill,
                'is_default' => true,
            ], $now);
        }

        return $skillMap;
    }

    private function seedTags(Carbon $now): array
    {
        if (! $this->tableExists('tags')) {
            return [];
        }

        $tags = [
            'Bangladesh Jobs', 'Dhaka Jobs', 'Freshers Encouraged', 'Inclusive Workplace',
            'Urgent Hiring', 'Hybrid', 'Factory', 'Field Job', 'RMG', 'IT Jobs',
            'Finance Jobs', 'Healthcare Jobs', 'NGO Jobs',
        ];

        $tagMap = [];
        foreach ($tags as $tag) {
            $tagMap[$tag] = $this->firstId('tags', 'name', $tag, [
                'description' => $tag,
                'is_default' => true,
            ], $now);
        }

        return $tagMap;
    }

    private function firstCurrencyId(Carbon $now): int
    {
        $query = DB::table('salary_currencies');
        if ($this->hasColumn('salary_currencies', 'currency_code')) {
            $id = $query->where('currency_code', 'BDT')->value('id');
            if ($id) {
                return (int) $id;
            }
        }

        return $this->firstId('salary_currencies', 'currency_name', 'BDT Bangladesh Taka', [
            'currency_icon' => 'BDT',
            'currency_code' => 'BDT',
            'is_default' => true,
        ], $now);
    }

    private function firstId(string $table, string $column, string $value, array $extra, Carbon $now): int
    {
        $id = DB::table($table)->where($column, $value)->value('id');
        $data = $this->filterColumns($table, array_merge($extra, [
            $column => $value,
            'updated_at' => $now,
        ]));

        if ($id) {
            unset($data[$column]);
            DB::table($table)->where('id', $id)->update($data);

            return (int) $id;
        }

        $data = $this->filterColumns($table, array_merge($data, ['created_at' => $now]));

        return (int) DB::table($table)->insertGetId($data);
    }

    private function assignRole(int $userId, int $roleId): void
    {
        if (! $this->tableExists('model_has_roles')) {
            return;
        }

        $exists = DB::table('model_has_roles')
            ->where('role_id', $roleId)
            ->where('model_type', User::class)
            ->where('model_id', $userId)
            ->exists();

        if (! $exists) {
            DB::table('model_has_roles')->insert([
                'role_id' => $roleId,
                'model_type' => User::class,
                'model_id' => $userId,
            ]);
        }
    }

    private function syncPivot(string $table, string $leftKey, int $leftId, string $rightKey, array $rightIds): void
    {
        if (! $this->tableExists($table) || empty($rightIds)) {
            return;
        }

        DB::table($table)->where($leftKey, $leftId)->delete();
        foreach ($rightIds as $rightId) {
            DB::table($table)->insert([$leftKey => $leftId, $rightKey => $rightId]);
        }
    }

    private function pickIds(array $ids, int $offset, int $count): array
    {
        if (empty($ids)) {
            return [];
        }

        $picked = [];
        for ($i = 0; $i < $count; $i++) {
            $picked[] = $ids[($offset + $i) % count($ids)];
        }

        return array_values(array_unique($picked));
    }

    private function filterColumns(string $table, array $data): array
    {
        return array_filter(
            $data,
            fn (string $column): bool => $this->hasColumn($table, $column),
            ARRAY_FILTER_USE_KEY
        );
    }

    private function hasColumn(string $table, string $column): bool
    {
        return $this->tableExists($table) && Schema::hasColumn($table, $column);
    }

    private function tableExists(string $table): bool
    {
        return Schema::hasTable($table);
    }
}
