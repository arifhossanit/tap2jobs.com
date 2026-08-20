<?php

namespace App\Repositories;

use App;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\CompanySize;
use App\Models\Industry;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\OwnerShipType;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\Candidates\CandidateRepository;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

/**
 * Class WebRegisterRepository
 *
 * @version July 7, 2020, 5:07 am UTC
 */
class WebRegisterRepository
{
    /**
     * @return mixed
     */
    public function getSettingForReCaptcha()
    {
        return Setting::where('key', 'enable_google_recaptcha')->first()->value;
    }

    /**
     * @throws Throwable
     */
    public function store(array $input): User
    {
        try {
            DB::beginTransaction();

            $userInput = Arr::only($input, [
                'first_name', 'last_name', 'username', 'email', 'phone', 'region_code',
                'country_id', 'state_id', 'city_id',
            ]);
            if ((int) $input['type'] === 2) {
                $userInput['first_name'] = $input['company_name'];
            }
            $userInput['password'] = Hash::make($input['password']);
            $userInput['email_verified_at'] = now();
            /** @var User $user */
            $user = User::create($userInput);
            $userRole = Role::where('name', ($input['type'] == 1) ? 'Candidate' : 'Employer')->first();
            $user->assignRole($userRole);
            $adminId = User::role('Admin')->first()->id;
            if ($input['type'] == 1) {
                /** @var CandidateRepository $candidateRepo */
                $candidateRepo = App::make(CandidateRepository::class);
                $candidate = Candidate::create([
                    'user_id' => $user->id,
                    'unique_id' => $candidateRepo->getUniqueCandidateId(),
                ]);
                $user->update(['owner_id' => $candidate->id, 'owner_type' => Candidate::class]);
                NotificationSetting::where('key', 'NEW_CANDIDATE_REGISTERED')->first()->value == 1 ?
                    addNotification([
                        Notification::NEW_CANDIDATE_REGISTERED,
                        $adminId,
                        Notification::ADMIN,
                        'New Candidate Registered',
                    ]) : false;
            } else {
                $companySizeId = CompanySize::where('size', $input['employee_range'])->value('id')
                    ?: CompanySize::value('id');
                $industryIds = array_values(array_unique($input['industry_ids'] ?? []));
                foreach ($input['custom_industries'] ?? [] as $customIndustry) {
                    $industry = Industry::create([
                        'industry_type_id' => $customIndustry['industry_type_id'],
                        'name' => trim($customIndustry['name']),
                        'description' => trim($customIndustry['name']),
                        'created_by' => $user->id,
                        'is_default' => false,
                    ]);
                    $industryIds[] = $industry->id;
                }
                $industryIds = array_values(array_unique($industryIds));

                $employer = Company::create([
                    'user_id' => $user->id,
                    'unique_id' => getUniqueCompanyId(),
                    'company_name' => $input['company_name'],
                    'company_name_bn' => $input['company_name_bn'] ?? null,
                    'contact_person_name' => $input['contact_person_name'],
                    'contact_person_designation' => $input['contact_person_designation'],
                    'ceo' => $input['contact_person_designation'],
                    'established_in' => $input['established_in'],
                    'employee_range' => $input['employee_range'],
                    'company_size_id' => $companySizeId,
                    'ownership_type_id' => OwnerShipType::value('id'),
                    'no_of_offices' => 1,
                    'industry_id' => $industryIds[0],
                    'industry_ids' => $industryIds,
                    'details' => $input['details'] ?? null,
                    'trade_license_no' => $input['trade_license_no'] ?? null,
                    'rl_no' => $input['rl_no'] ?? null,
                    'website' => $input['website'] ?? null,
                    'location' => $input['company_address'],
                    'company_address_bn' => $input['company_address_bn'] ?? null,
                    'billing_address' => $input['company_address'],
                    'billing_phone' => $input['phone'],
                    'billing_region_code' => $input['region_code'],
                    'billing_email' => $input['email'],
                    'has_disability_facilities' => (bool) ($input['has_disability_facilities'] ?? false),
                    'disability_inclusion_policy' => ($input['has_disability_facilities'] ?? false)
                        ? (bool) $input['disability_inclusion_policy']
                        : null,
                    'disability_inclusion_support' => ($input['has_disability_facilities'] ?? false)
                        && ! (bool) $input['disability_inclusion_policy']
                            ? (bool) $input['disability_inclusion_support']
                            : null,
                    'disability_inclusion_training' => ($input['has_disability_facilities'] ?? false)
                        ? (bool) $input['disability_inclusion_training']
                        : null,
                    'disability_facilities' => ($input['has_disability_facilities'] ?? false)
                        ? array_values(array_unique($input['disability_facilities'] ?? []))
                        : [],
                ]);
                $user->update(['owner_id' => $employer->id, 'owner_type' => Company::class]);
                NotificationSetting::where('key', 'NEW_EMPLOYER_REGISTERED')->first()->value == 1 ?
                    addNotification([
                        Notification::NEW_EMPLOYER_REGISTERED,
                        $adminId,
                        Notification::ADMIN,
                        'New Employer Registered',
                    ]) : false;

                /** @var SubscriptionRepository $subscriptionRepo */
                $subscriptionRepo = app(SubscriptionRepository::class);
                $subscriptionRepo->createStripeCustomer($user);
            }

            if ($input['type'] != 1) {
                $user->sendEmailVerificationNotification();
            }

            DB::commit();

            return $user;
        } catch (Exception $e) {
            DB::rollBack();

            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
