<?php

namespace App\Repositories\Candidates;

use App\Models\Candidate;
use App\Models\CandidateAccomplishment;
use App\Models\CandidateEducation;
use App\Models\CandidateExperience;
use App\Models\CandidateExtraCurricular;
use App\Models\CandidateLink;
use App\Models\CandidateReference;
use App\Models\CandidateSkill;
use App\Models\CareerLevel;
use App\Models\Country;
use App\Models\FunctionalArea;
use App\Models\Industry;
use App\Models\JobCategory;
use App\Models\JobType;
use App\Models\Language;
use App\Models\MaritalStatus;
use App\Models\OwnerShipType;
use App\Models\ProfileReferenceOption;
use App\Models\SalaryCurrency;
use App\Models\Skill;
use App\Models\State;
use App\Models\User;
use App\ReportedToCandidate;
use App\Repositories\BaseRepository;
use App\Services\ApplicationCvService;
use Arr;
use Auth;
use DB;
use Exception;
use Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use PragmaRX\Countries\Package\Countries;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Models\Role;
use Str;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

/**
 * Class CandidateRepository
 *
 * @version July 20, 2020, 5:48 am UTC
 */
class CandidateRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'father_name',
        'marital_status_id',
        'national_id_card',
        'experience',
        'career_level_id',
        'industry_id',
        'functional_area_id',
        'current_salary',
        'expected_salary',
        'immediate_available',
        'is_active',
    ];

    /**
     * Return searchable fields
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Candidate::class;
    }

    /**
     * @return mixed
     */
    public function prepareData()
    {
        $countries = new Countries();
        $data['countries'] = getCountries();
        $data['maritalStatus'] = MaritalStatus::toBase()->pluck('marital_status', 'id');
        $data['careerLevel'] = CareerLevel::toBase()->pluck('level_name', 'id');
        $data['jobCategory'] = JobCategory::toBase()->orderBy('name', 'ASC')->pluck('name', 'id');
        $data['industry'] = Industry::toBase()->pluck('name', 'id');
        $data['functionalArea'] = FunctionalArea::toBase()->pluck('name', 'id');
        $data['skills'] = Skill::toBase()->orderBy('name', 'ASC')->pluck('name', 'id');
        $data['language'] = Language::toBase()->pluck('language', 'id');
        $data['currency'] = SalaryCurrency::toBase()->pluck('currency_name', 'id');
        $bangladeshId = Country::where('short_code', 'BD')->orWhere('name', 'Bangladesh')->value('id');
        $data['districts'] = State::when($bangladeshId, function ($query) use ($bangladeshId) {
            $query->where('country_id', $bangladeshId);
        })->toBase()->orderBy('name', 'ASC')->pluck('name', 'id');
        $data['outsideCountries'] = Country::when($bangladeshId, function ($query) use ($bangladeshId) {
            $query->where('id', '!=', $bangladeshId);
        })->toBase()->orderBy('name', 'ASC')->pluck('name', 'id');
        $data['organizationTypes'] = OwnerShipType::toBase()->orderBy('name', 'ASC')->pluck('name', 'id');
        $data['profileReferenceOptions'] = [
            'gender' => ProfileReferenceOption::options(ProfileReferenceOption::TYPE_GENDER),
            'religion' => ProfileReferenceOption::options(ProfileReferenceOption::TYPE_RELIGION),
            'blood_group' => ProfileReferenceOption::options(ProfileReferenceOption::TYPE_BLOOD_GROUP),
            'disability_difficulty' => ProfileReferenceOption::options(ProfileReferenceOption::TYPE_DISABILITY_DIFFICULTY),
            'skill_learning_source' => ProfileReferenceOption::options(ProfileReferenceOption::TYPE_SKILL_LEARNING_SOURCE),
            'language_proficiency' => ProfileReferenceOption::options(ProfileReferenceOption::TYPE_LANGUAGE_PROFICIENCY),
            'online_profile_platform' => ProfileReferenceOption::options(ProfileReferenceOption::TYPE_ONLINE_PROFILE_PLATFORM),
            'candidate_reference_relation' => ProfileReferenceOption::options(ProfileReferenceOption::TYPE_REFERENCE_RELATION, [
                ProfileReferenceOption::SCOPE_COMMON,
                ProfileReferenceOption::SCOPE_CANDIDATE,
            ]),
            'education_result' => ProfileReferenceOption::options(ProfileReferenceOption::TYPE_EDUCATION_RESULT),
            'army_ba_no_prefix' => ProfileReferenceOption::options(ProfileReferenceOption::TYPE_ARMY_BA_NO_PREFIX),
            'army_rank' => ProfileReferenceOption::options(ProfileReferenceOption::TYPE_ARMY_RANK),
            'army_employment_type' => ProfileReferenceOption::options(ProfileReferenceOption::TYPE_ARMY_EMPLOYMENT_TYPE),
            'army_arms' => ProfileReferenceOption::options(ProfileReferenceOption::TYPE_ARMY_ARMS),
        ];

        return $data;
    }

    /**
     * @return mixed
     */
    public function getUniqueCandidateId()
    {
        do {
            $candidateUniqueId = Str::random(12);
        } while (Candidate::whereUniqueId($candidateUniqueId)->exists());

        return $candidateUniqueId;
    }

    /**
     * @throws Throwable
     */
    public function store(array $input): bool
    {
        try {
            DB::beginTransaction();
            $input['is_active'] = isset($input['is_active']) ? 1 : 0;
            $input['is_verified'] = isset($input['is_verified']) ? 1 : 0;
            $input['password'] = Hash::make($input['password']);
            $input['dob'] = (! empty($input['dob'])) ? $input['dob'] : null;
            $input['current_salary'] = removeCommaFromNumbers($input['current_salary']);
            $input['expected_salary'] = removeCommaFromNumbers($input['expected_salary']);
            $input['unique_id'] = $this->getUniqueCandidateId();
            $candidateRole = Role::whereName('Candidate')->first();
            /** @var User $user */
            $user = User::create(Arr::only($input, (new User())->getFillable()));

            $candidate = Candidate::create(
                array_merge(array_filter(Arr::only($input, (new Candidate())->getFillable())),
                    ['user_id' => $user->id])
            );
            $candidate->update(['immediate_available' => $input['immediate_available']]);

            $ownerId = $candidate->id;
            $ownerType = Candidate::class;

            $user->update(['owner_id' => $ownerId, 'owner_type' => $ownerType]);
            $user->assignRole($candidateRole);

            //Update Candidate Skills
            if (isset($input['candidateSkills']) && ! empty($input['candidateSkills'])) {
                $user->candidateSkill()->sync($input['candidateSkills']);
            }

            //update Candidate Languages
            if (isset($input['candidateLanguage']) && ! empty($input['candidateLanguage'])) {
                $user->candidateLanguage()->sync($input['candidateLanguage']);
            }

//            if ($user->is_verified) {
//                $user->update(['email_verified_at' => Carbon::now()]);
//            }else{
//                $user->sendEmailVerificationNotification();
//            }
            $user->update(['email_verified_at' => Carbon::now()]);

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return true;
    }

    /**
     * @throws Throwable
     */
    public function updateProfile(array $input): bool
    {
        try {
            DB::beginTransaction();
            $input['dob'] = (! empty($input['dob'])) ? $input['dob'] : null;
            $input['current_salary'] = removeCommaFromNumbers($input['current_salary']);
            $input['expected_salary'] = removeCommaFromNumbers($input['expected_salary']);
            foreach ([
                'preferred_functional_categories',
                'preferred_special_skills',
                'preferred_job_locations_inside',
                'preferred_job_locations_outside',
                'preferred_organization_types',
            ] as $preferredField) {
                $input[$preferredField] = $input[$preferredField] ?? [];
            }
            $input['has_disability_id'] = $input['has_disability_id'] ?? null;

            /** @var User $user */
            $user = Auth::user();

            $userInput = Arr::only($input,
                [
                    'first_name', 'last_name', 'email', 'phone',
                    'country_id', 'state_id', 'city_id', 'gender', 'dob', 'facebook_url', 'twitter_url', 'linkedin_url',
                    'pinterest_url', 'google_plus_url', 'region_code',
                ]);

            $user->update($userInput);

            if ((isset($input['image']))) {
                $user->clearMediaCollection(User::PROFILE);
                $user->addMedia($input['image'])
                    ->toMediaCollection(User::PROFILE, config('app.media_disc'));
            }

            $input['available_at'] = isset($input['immediate_available']) && $input['immediate_available'] == 0
                ? ($input['available_at'] ?? null)
                : null;
            $user->candidate->update($input);

            //Update Candidate Skills
            if (isset($input['candidateSkills']) && ! empty($input['candidateSkills'])) {
                $user->candidateSkill()->sync($input['candidateSkills']);
            }

            //update Candidate Languages
            if (isset($input['candidateLanguage']) && ! empty($input['candidateLanguage'])) {
                $user->candidateLanguage()->sync($input['candidateLanguage']);
            }

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @throws Throwable
     */
    public function updatePersonalDetails(array $input): bool
    {
        try {
            DB::beginTransaction();

            $input['dob'] = ! empty($input['dob']) ? $input['dob'] : null;
            $input['passport_issue_date'] = ! empty($input['passport_issue_date']) ? $input['passport_issue_date'] : null;

            /** @var User $user */
            $user = Auth::user();

            $user->update(Arr::only($input, [
                'first_name',
                'last_name',
                'email',
                'phone',
                'region_code',
                'gender',
                'dob',
            ]));

            if (isset($input['image'])) {
                $user->clearMediaCollection(User::PROFILE);
                $user->addMedia($input['image'])
                    ->toMediaCollection(User::PROFILE, config('app.media_disc'));
            }

            $user->candidate->update(Arr::only($input, [
                'father_name',
                'mother_name',
                'religion',
                'marital_status_id',
                'nationality',
                'national_id_card',
                'passport_number',
                'passport_issue_date',
                'secondary_mobile',
                'alternate_email',
                'emergency_contact',
                'blood_group',
                'height',
                'weight',
            ]));

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @throws Throwable
     */
    public function updateAddressDetails(array $input): bool
    {
        try {
            DB::beginTransaction();

            /** @var User $user */
            $user = Auth::user();

            foreach (['country_id', 'state_id', 'city_id', 'permanent_country_id', 'permanent_state_id', 'permanent_city_id'] as $locationField) {
                if (array_key_exists($locationField, $input) && $input[$locationField] === '') {
                    $input[$locationField] = null;
                }
            }

            if (($input['present_address_type'] ?? null) === 'outside') {
                $input['state_id'] = null;
                $input['present_post_office'] = null;
                $input['city_id'] = null;
            } else {
                $input['country_id'] = Country::where('short_code', 'BD')->orWhere('name', 'Bangladesh')->value('id') ?? $input['country_id'];
                $input['present_state_division'] = null;
            }

            $input['permanent_same_as_present'] = ! empty($input['permanent_same_as_present']);
            $hasPermanentAddressSelection = ! empty($input['permanent_address_selected']);
            if ($input['permanent_same_as_present'] || ! $hasPermanentAddressSelection) {
                $input['permanent_address_type'] = null;
                $input['permanent_country_id'] = null;
                $input['permanent_state_id'] = null;
                $input['permanent_state_division'] = null;
                $input['permanent_city_id'] = null;
                $input['permanent_post_office'] = null;
                $input['permanent_address'] = null;
            } elseif (($input['permanent_address_type'] ?? null) === 'outside') {
                $input['permanent_state_id'] = null;
                $input['permanent_city_id'] = null;
                $input['permanent_post_office'] = null;
            } else {
                $input['permanent_country_id'] = Country::where('short_code', 'BD')->orWhere('name', 'Bangladesh')->value('id') ?? $input['permanent_country_id'];
                $input['permanent_state_division'] = null;
            }

            $user->update(Arr::only($input, [
                'country_id',
                'state_id',
                'city_id',
            ]));

            $user->candidate->update(Arr::only($input, [
                'present_address_type',
                'present_post_office',
                'present_state_division',
                'permanent_same_as_present',
                'permanent_address_type',
                'permanent_country_id',
                'permanent_state_id',
                'permanent_state_division',
                'permanent_city_id',
                'permanent_post_office',
                'permanent_address',
                'address',
            ]));

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @throws Throwable
     */
    public function updateCareerApplication(array $input): bool
    {
        try {
            DB::beginTransaction();

            /** @var User $user */
            $user = Auth::user();

            $user->candidate->update(Arr::only($input, [
                'objective',
                'current_salary',
                'expected_salary',
                'job_level',
                'job_nature',
            ]));

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @throws Throwable
     */
    public function updatePreferredArea(array $input): bool
    {
        try {
            DB::beginTransaction();

            /** @var User $user */
            $user = Auth::user();

            $preferredFields = [
                'preferred_functional_categories',
                'preferred_special_skills',
                'preferred_job_locations_inside',
                'preferred_job_locations_outside',
                'preferred_organization_types',
            ];

            foreach ($preferredFields as $preferredField) {
                $input[$preferredField] = array_values($input[$preferredField] ?? []);
            }

            $user->candidate->update(Arr::only($input, $preferredFields));

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @throws Throwable
     */
    public function updateRelevantInformation(array $input): bool
    {
        try {
            DB::beginTransaction();

            /** @var User $user */
            $user = Auth::user();

            $user->candidate->update(Arr::only($input, [
                'career_summary',
                'special_qualification',
                'keywords',
            ]));

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @throws Throwable
     */
    public function updateDisabilityInformation(array $input): bool
    {
        try {
            DB::beginTransaction();

            /** @var User $user */
            $user = Auth::user();

            if ((string) ($input['has_disability_id'] ?? '') === '0') {
                $input['disability_id_number'] = null;
                $input['disability_id_show_on_profile'] = null;
                $input['disability_difficulty_seeing'] = null;
                $input['disability_difficulty_hearing'] = null;
                $input['disability_difficulty_remembering'] = null;
                $input['disability_difficulty_walking'] = null;
                $input['disability_difficulty_communicating'] = null;
                $input['disability_difficulty_self_care'] = null;
            } else {
                $input['disability_id_show_on_profile'] = (bool) ($input['disability_id_show_on_profile'] ?? true);
            }

            $user->candidate->update(Arr::only($input, [
                'has_disability_id',
                'disability_id_number',
                'disability_id_show_on_profile',
                'disability_difficulty_seeing',
                'disability_difficulty_hearing',
                'disability_difficulty_remembering',
                'disability_difficulty_walking',
                'disability_difficulty_communicating',
                'disability_difficulty_self_care',
            ]));

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @throws Throwable
     */
    public function updateGeneralInformation(array $input)
    {
        try {
            DB::beginTransaction();
            /** @var User $user */
            $user = Auth::user();
            $userInput = Arr::only($input, [
                'first_name', 'last_name', 'country_id', 'state_id', 'city_id', 'phone', 'facebook_url',
                'twitter_url',
                'linkedin_url',
                'google_plus_url',
                'pinterest_url',
            ]);
            $user->update($userInput);
            if (! empty($input['candidateSkillsUpdated'])) {
                $this->syncCandidateSkills($user, $input);
            }
            if (! empty($input['candidateLanguageUpdated'])) {
                $this->syncCandidateLanguages($user, $input);
            }
            DB::commit();

            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function createExtraCurricular(array $input): CandidateExtraCurricular
    {
        try {
            DB::beginTransaction();

            /** @var User $user */
            $user = Auth::user();
            $description = $this->sanitizeRichText($input['description'] ?? null);

            $extraCurricular = CandidateExtraCurricular::create([
                'candidate_id' => $user->owner_id,
                'description' => $description,
            ]);

            DB::commit();

            return $extraCurricular;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateExtraCurricular(CandidateExtraCurricular $extraCurricular, array $input): CandidateExtraCurricular
    {
        try {
            DB::beginTransaction();

            $extraCurricular->update([
                'description' => $this->sanitizeRichText($input['description'] ?? null),
            ]);

            DB::commit();

            return $extraCurricular->fresh();
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function candidateLinkCount(): int
    {
        /** @var User $user */
        $user = Auth::user();

        return CandidateLink::where('candidate_id', $user->owner_id)->count();
    }

    public function candidateLinkPlatformExists(string $platform, ?int $ignoreId = null): bool
    {
        /** @var User $user */
        $user = Auth::user();

        return CandidateLink::where('candidate_id', $user->owner_id)
            ->where('platform', $platform)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists();
    }

    public function createLink(array $input): CandidateLink
    {
        try {
            DB::beginTransaction();

            /** @var User $user */
            $user = Auth::user();
            $sortOrder = CandidateLink::where('candidate_id', $user->owner_id)->max('sort_order');
            $candidateLink = CandidateLink::create([
                'candidate_id' => $user->owner_id,
                'platform' => $input['platform'],
                'url' => trim($input['url']),
                'sort_order' => (int) $sortOrder + 1,
            ]);
            $this->syncUserSocialLink($user, $candidateLink->platform, $candidateLink->url);

            DB::commit();

            return $candidateLink;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateLink(CandidateLink $candidateLink, array $input): CandidateLink
    {
        try {
            DB::beginTransaction();

            /** @var User $user */
            $user = Auth::user();
            $oldPlatform = $candidateLink->platform;
            $candidateLink->update([
                'platform' => $input['platform'],
                'url' => trim($input['url']),
            ]);

            if ($oldPlatform !== $candidateLink->platform) {
                $this->syncUserSocialLink($user, $oldPlatform, null);
            }
            $this->syncUserSocialLink($user, $candidateLink->platform, $candidateLink->url);

            DB::commit();

            return $candidateLink->fresh();
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function deleteLink(CandidateLink $candidateLink): void
    {
        try {
            DB::beginTransaction();

            /** @var User $user */
            $user = Auth::user();
            $platform = $candidateLink->platform;
            $candidateLink->delete();
            $this->syncUserSocialLink($user, $platform, null);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    private function syncUserSocialLink(User $user, string $platform, ?string $url): void
    {
        $columns = [
            'Facebook' => 'facebook_url',
            'Twitter' => 'twitter_url',
            'LinkedIn' => 'linkedin_url',
        ];

        if (! isset($columns[$platform])) {
            return;
        }

        $user->update([
            $columns[$platform] => $url,
        ]);
    }

    public function createReference(array $input): CandidateReference
    {
        try {
            DB::beginTransaction();

            /** @var User $user */
            $user = Auth::user();
            $sortOrder = CandidateReference::where('candidate_id', $user->owner_id)->max('sort_order');
            $candidateReference = CandidateReference::create($this->referencePayload($input) + [
                'candidate_id' => $user->owner_id,
                'sort_order' => (int) $sortOrder + 1,
            ]);

            DB::commit();

            return $candidateReference;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateReference(CandidateReference $candidateReference, array $input): CandidateReference
    {
        try {
            DB::beginTransaction();

            $candidateReference->update($this->referencePayload($input));

            DB::commit();

            return $candidateReference->fresh();
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function deleteReference(CandidateReference $candidateReference): void
    {
        try {
            DB::beginTransaction();

            $candidateReference->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    private function referencePayload(array $input): array
    {
        return [
            'name' => trim($input['name']),
            'designation' => trim($input['designation']),
            'organization' => trim($input['organization']),
            'email' => filled($input['email'] ?? null) ? trim($input['email']) : null,
            'relation' => filled($input['relation'] ?? null) ? $input['relation'] : null,
            'mobile' => filled($input['mobile'] ?? null) ? trim($input['mobile']) : null,
            'office_phone' => filled($input['office_phone'] ?? null) ? trim($input['office_phone']) : null,
            'residential_phone' => filled($input['residential_phone'] ?? null) ? trim($input['residential_phone']) : null,
            'address' => filled($input['address'] ?? null) ? trim($input['address']) : null,
        ];
    }

    public function portfolioCount(): int
    {
        /** @var User $user */
        $user = Auth::user();

        return CandidateAccomplishment::where('candidate_id', $user->owner_id)
            ->where('type', CandidateAccomplishment::TYPE_PORTFOLIO)
            ->count();
    }

    public function createPortfolio(array $input): CandidateAccomplishment
    {
        try {
            DB::beginTransaction();

            /** @var User $user */
            $user = Auth::user();
            $sortOrder = CandidateAccomplishment::where('candidate_id', $user->owner_id)
                ->where('type', CandidateAccomplishment::TYPE_PORTFOLIO)
                ->max('sort_order');
            $portfolio = CandidateAccomplishment::create($this->portfolioPayload($input) + [
                'candidate_id' => $user->owner_id,
                'type' => CandidateAccomplishment::TYPE_PORTFOLIO,
                'sort_order' => (int) $sortOrder + 1,
            ]);

            DB::commit();

            return $portfolio;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updatePortfolio(CandidateAccomplishment $portfolio, array $input): CandidateAccomplishment
    {
        try {
            DB::beginTransaction();

            $portfolio->update($this->portfolioPayload($input));

            DB::commit();

            return $portfolio->fresh();
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function deletePortfolio(CandidateAccomplishment $portfolio): void
    {
        try {
            DB::beginTransaction();

            $portfolio->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    private function portfolioPayload(array $input): array
    {
        return [
            'title' => trim($input['title']),
            'url' => filled($input['url'] ?? null) ? trim($input['url']) : null,
            'description' => $this->sanitizeRichText($input['description'] ?? null),
        ];
    }

    public function publicationCount(): int
    {
        /** @var User $user */
        $user = Auth::user();

        return CandidateAccomplishment::where('candidate_id', $user->owner_id)
            ->where('type', CandidateAccomplishment::TYPE_PUBLICATION)
            ->count();
    }

    public function createPublication(array $input): CandidateAccomplishment
    {
        try {
            DB::beginTransaction();

            /** @var User $user */
            $user = Auth::user();
            $sortOrder = CandidateAccomplishment::where('candidate_id', $user->owner_id)
                ->where('type', CandidateAccomplishment::TYPE_PUBLICATION)
                ->max('sort_order');
            $publication = CandidateAccomplishment::create($this->publicationPayload($input) + [
                'candidate_id' => $user->owner_id,
                'type' => CandidateAccomplishment::TYPE_PUBLICATION,
                'sort_order' => (int) $sortOrder + 1,
            ]);

            DB::commit();

            return $publication;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updatePublication(CandidateAccomplishment $publication, array $input): CandidateAccomplishment
    {
        try {
            DB::beginTransaction();

            $publication->update($this->publicationPayload($input));

            DB::commit();

            return $publication->fresh();
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function deletePublication(CandidateAccomplishment $publication): void
    {
        try {
            DB::beginTransaction();

            $publication->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    private function publicationPayload(array $input): array
    {
        return [
            'title' => trim($input['title']),
            'issued_on' => Carbon::parse($input['issued_on'])->format('Y-m-d'),
            'url' => filled($input['url'] ?? null) ? trim($input['url']) : null,
            'description' => $this->sanitizeRichText($input['description'] ?? null),
        ];
    }

    public function awardCount(): int
    {
        /** @var User $user */
        $user = Auth::user();

        return CandidateAccomplishment::where('candidate_id', $user->owner_id)
            ->where('type', CandidateAccomplishment::TYPE_AWARD)
            ->count();
    }

    public function createAward(array $input): CandidateAccomplishment
    {
        try {
            DB::beginTransaction();

            /** @var User $user */
            $user = Auth::user();
            $sortOrder = CandidateAccomplishment::where('candidate_id', $user->owner_id)
                ->where('type', CandidateAccomplishment::TYPE_AWARD)
                ->max('sort_order');
            $award = CandidateAccomplishment::create($this->awardPayload($input) + [
                'candidate_id' => $user->owner_id,
                'type' => CandidateAccomplishment::TYPE_AWARD,
                'sort_order' => (int) $sortOrder + 1,
            ]);

            DB::commit();

            return $award;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateAward(CandidateAccomplishment $award, array $input): CandidateAccomplishment
    {
        try {
            DB::beginTransaction();

            $award->update($this->awardPayload($input));

            DB::commit();

            return $award->fresh();
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function deleteAward(CandidateAccomplishment $award): void
    {
        try {
            DB::beginTransaction();

            $award->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    private function awardPayload(array $input): array
    {
        return [
            'title' => trim($input['title']),
            'issued_on' => Carbon::parse($input['issued_on'])->format('Y-m-d'),
            'url' => filled($input['url'] ?? null) ? trim($input['url']) : null,
            'description' => $this->sanitizeRichText($input['description'] ?? null),
        ];
    }

    public function projectCount(): int
    {
        /** @var User $user */
        $user = Auth::user();

        return CandidateAccomplishment::where('candidate_id', $user->owner_id)
            ->where('type', CandidateAccomplishment::TYPE_PROJECT)
            ->count();
    }

    public function createProject(array $input): CandidateAccomplishment
    {
        try {
            DB::beginTransaction();

            /** @var User $user */
            $user = Auth::user();
            $sortOrder = CandidateAccomplishment::where('candidate_id', $user->owner_id)
                ->where('type', CandidateAccomplishment::TYPE_PROJECT)
                ->max('sort_order');
            $project = CandidateAccomplishment::create($this->projectPayload($input) + [
                'candidate_id' => $user->owner_id,
                'type' => CandidateAccomplishment::TYPE_PROJECT,
                'sort_order' => (int) $sortOrder + 1,
            ]);

            DB::commit();

            return $project;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateProject(CandidateAccomplishment $project, array $input): CandidateAccomplishment
    {
        try {
            DB::beginTransaction();

            $project->update($this->projectPayload($input));

            DB::commit();

            return $project->fresh();
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function deleteProject(CandidateAccomplishment $project): void
    {
        try {
            DB::beginTransaction();

            $project->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    private function projectPayload(array $input): array
    {
        return [
            'title' => trim($input['title']),
            'issued_on' => Carbon::parse($input['issued_on'])->format('Y-m-d'),
            'url' => filled($input['url'] ?? null) ? trim($input['url']) : null,
            'description' => $this->sanitizeRichText($input['description'] ?? null),
        ];
    }

    public function otherCount(): int
    {
        /** @var User $user */
        $user = Auth::user();

        return CandidateAccomplishment::where('candidate_id', $user->owner_id)
            ->where('type', CandidateAccomplishment::TYPE_OTHER)
            ->count();
    }

    public function createOther(array $input): CandidateAccomplishment
    {
        try {
            DB::beginTransaction();

            /** @var User $user */
            $user = Auth::user();
            $sortOrder = CandidateAccomplishment::where('candidate_id', $user->owner_id)
                ->where('type', CandidateAccomplishment::TYPE_OTHER)
                ->max('sort_order');
            $other = CandidateAccomplishment::create($this->otherPayload($input) + [
                'candidate_id' => $user->owner_id,
                'type' => CandidateAccomplishment::TYPE_OTHER,
                'sort_order' => (int) $sortOrder + 1,
            ]);

            DB::commit();

            return $other;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateOther(CandidateAccomplishment $other, array $input): CandidateAccomplishment
    {
        try {
            DB::beginTransaction();

            $other->update($this->otherPayload($input));

            DB::commit();

            return $other->fresh();
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function deleteOther(CandidateAccomplishment $other): void
    {
        try {
            DB::beginTransaction();

            $other->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    private function otherPayload(array $input): array
    {
        return [
            'title' => trim($input['title']),
            'issued_on' => Carbon::parse($input['issued_on'])->format('Y-m-d'),
            'url' => filled($input['url'] ?? null) ? trim($input['url']) : null,
            'description' => $this->sanitizeRichText($input['description'] ?? null),
        ];
    }

    private function sanitizeRichText(?string $value): ?string
    {
        if (! filled($value)) {
            return null;
        }

        $value = strip_tags($value, '<p><br><strong><b><em><i><ul><ol><li>');

        return trim($value) !== '' ? trim($value) : null;
    }

    private function syncCandidateSkills(User $user, array $input): void
    {
        $skillIds = array_values($input['candidateSkills'] ?? []);
        $skillNames = array_values($input['candidateSkillNames'] ?? []);
        $skillSources = array_values($input['candidateSkillSources'] ?? []);
        $allowedSources = ProfileReferenceOption::values(ProfileReferenceOption::TYPE_SKILL_LEARNING_SOURCE);
        $defaultSource = $allowedSources[0] ?? 'Professional Training';
        $resolvedSkillIds = [];
        $sourcesBySkillId = [];

        foreach ($skillNames as $index => $skillName) {
            $skillName = trim((string) $skillName);
            if ($skillName === '') {
                continue;
            }

            $skillId = (int) ($skillIds[$index] ?? 0);
            $existingSkill = $skillId > 0 ? Skill::find($skillId) : null;

            if (! $existingSkill || strcasecmp($existingSkill->name, $skillName) !== 0) {
                $existingSkill = Skill::whereRaw('LOWER(name) = ?', [mb_strtolower($skillName)])->first();
            }

            $skill = $existingSkill ?: Skill::create([
                'name' => $skillName,
                'description' => null,
                'is_default' => false,
            ]);

            $resolvedSkillIds[] = $skill->id;
            $sources = array_values(array_intersect($skillSources[$index] ?? [], $allowedSources));
            $sourcesBySkillId[$skill->id] = count($sources) ? $sources : [$defaultSource];
        }

        $resolvedSkillIds = array_values(array_unique($resolvedSkillIds));
        $user->candidateSkill()->sync($resolvedSkillIds);

        if (! count($resolvedSkillIds) || ! Schema::hasTable('candidate_skill_sources')) {
            return;
        }

        CandidateSkill::where('user_id', $user->id)
            ->whereIn('skill_id', $resolvedSkillIds)
            ->get()
            ->each(function (CandidateSkill $candidateSkill) use ($sourcesBySkillId) {
                $candidateSkill->sources()->delete();
                foreach ($sourcesBySkillId[$candidateSkill->skill_id] ?? [] as $source) {
                    $candidateSkill->sources()->create(['source' => $source]);
                }
            });
    }

    private function syncCandidateLanguages(User $user, array $input): void
    {
        $languageIds = array_values($input['candidateLanguage'] ?? []);
        $languageNames = array_values($input['candidateLanguageNames'] ?? []);
        $languageLevels = array_values($input['candidateLanguageLevels'] ?? []);
        $readingLevels = array_values($input['candidateLanguageReadingLevels'] ?? []);
        $writingLevels = array_values($input['candidateLanguageWritingLevels'] ?? []);
        $speakingLevels = array_values($input['candidateLanguageSpeakingLevels'] ?? []);
        $allowedLevels = ProfileReferenceOption::values(ProfileReferenceOption::TYPE_LANGUAGE_PROFICIENCY);
        $syncData = [];

        foreach ($languageNames as $index => $languageName) {
            $languageName = trim((string) $languageName);
            $fallbackLevel = (string) ($languageLevels[$index] ?? '');
            $readingLevel = (string) ($readingLevels[$index] ?? $fallbackLevel);
            $writingLevel = (string) ($writingLevels[$index] ?? $fallbackLevel);
            $speakingLevel = (string) ($speakingLevels[$index] ?? $fallbackLevel);

            if (
                $languageName === ''
                || ! in_array($readingLevel, $allowedLevels, true)
                || ! in_array($writingLevel, $allowedLevels, true)
                || ! in_array($speakingLevel, $allowedLevels, true)
            ) {
                continue;
            }

            $languageId = (int) ($languageIds[$index] ?? 0);
            $existingLanguage = $languageId > 0 ? Language::find($languageId) : null;

            if (! $existingLanguage || strcasecmp($existingLanguage->language, $languageName) !== 0) {
                $existingLanguage = Language::whereRaw('LOWER(language) = ?', [mb_strtolower($languageName)])->first();
            }

            $language = $existingLanguage ?: Language::create([
                'language' => $languageName,
                'iso_code' => null,
                'is_default' => false,
            ]);

            $pivotData = [];
            if (Schema::hasColumn('candidate_language', 'proficiency_level')) {
                $pivotData['proficiency_level'] = $speakingLevel;
            }
            foreach ([
                'reading_level' => $readingLevel,
                'writing_level' => $writingLevel,
                'speaking_level' => $speakingLevel,
            ] as $column => $value) {
                if (Schema::hasColumn('candidate_language', $column)) {
                    $pivotData[$column] = $value;
                }
            }

            $syncData[$language->id] = $pivotData;
        }

        $user->candidateLanguage()->sync($syncData);
    }

    public function uploadResume(array $input): bool
    {
        try {
            $user = Auth::user();
            /** @var Candidate $candidate */
            $candidate = Candidate::findOrFail($user->candidate->id);

            $applicationCvService = app(ApplicationCvService::class);
            $applicationCvService->ensure($candidate);

            return DB::transaction(function () use ($candidate, $input) {
                $candidate = Candidate::whereKey($candidate->id)->lockForUpdate()->firstOrFail();
                $candidate->unsetRelation('media');

                $hasUploadedResume = $candidate->getMedia(Candidate::RESUME_PATH)->contains(
                    fn (Media $media) => ! $media->getCustomProperty(ApplicationCvService::APPLICATION_CV_PROPERTY, false)
                );

                if ($hasUploadedResume) {
                    throw new UnprocessableEntityHttpException(__('messages.candidate_profile.resume_upload_limit'));
                }

                $fileExtension = getFileName('download', $input['file']);
                $candidate->addMedia($input['file'])
                    ->withCustomProperties([
                        'is_default' => false,
                        ApplicationCvService::APPLICATION_CV_PROPERTY => false,
                        'title' => $input['title'],
                    ])->usingFileName($fileExtension)->toMediaCollection(Candidate::RESUME_PATH, config('app.resume_disk'));

                return true;
            });
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateCandidate(Candidate $candidate, array $input): bool
    {
        unset($input['password']);

        $input['is_active'] = isset($input['is_active']) ? 1 : 0;
        $input['is_verified'] = isset($input['is_verified']) ? 1 : 0;
        $input['dob'] = (! empty($input['dob'])) ? $input['dob'] : null;
        $input['state'] = (! empty($input['state'])) ? $input['state'] : null;
        $input['city'] = (! empty($input['city'])) ? $input['city'] : null;
        $input['current_salary'] = removeCommaFromNumbers($input['current_salary']);
        $input['expected_salary'] = removeCommaFromNumbers($input['expected_salary']);
        $input['available_at'] = isset($input['immediate_available']) && $input['immediate_available'] == 0
            ? ($input['available_at'] ?? null)
            : null;

        /** @var User $user */
        $user = $candidate->user;

        /* @var Candidate $candidate */
        $user->update($input);
        $candidate->update($input);

        if (! $user->email_verified_at && $input['is_verified'] == 1) {
            $user->update(['email_verified_at' => Carbon::now()]);
        }

        //Update Candidate Skills
        if (isset($input['candidateSkills']) && ! empty($input['candidateSkills'])) {
            $user->candidateSkill()->sync($input['candidateSkills']);
        }

        //update Candidate Languages
        if (isset($input['candidateLanguage']) && ! empty($input['candidateLanguage'])) {
            $user->candidateLanguage()->sync($input['candidateLanguage']);
        }

        return true;
    }

    public function changePassword(array $input): bool
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            if (! Hash::check($input['password_current'], $user->password)) {
                throw new UnprocessableEntityHttpException(__('messages.user.password_invalid'));
            }
            $input['password'] = Hash::make($input['password']);
            $user->update($input);

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function profileUpdate(array $input): bool
    {
        /** @var User $user */
        $user = Auth::user();

        try {
            $user->update(Arr::only($input, [
                'first_name',
                'last_name',
                'email',
                'phone',
            ]));
            if ((isset($input['image']))) {
                $user->clearMediaCollection(User::PROFILE);
                $user->addMedia($input['image'])
                    ->toMediaCollection(User::PROFILE, config('app.media_disc'));
            }

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @return mixed
     */
    public function getCandidateDetail($candidate)
    {
        $candidateDetails = Candidate::with('user', 'functionalArea')->findOrFail($candidate);
        // update profile views count
        if ($candidateDetails->user->id != getLoggedInUserId()) {
            if (! session()->has('user')) {
                $candidateDetails->user->increment('profile_views');
            }
        }
        session()->push('user', getLoggedInUserId());
        $data['isReportedToCandidate'] = $this->isAlreadyReported($candidate);
        $data['candidateDetails'] = $candidateDetails;
        $data['candidateExperiences'] = CandidateExperience::with('expertises')->where('candidate_id', $candidate)->get();
        foreach ($data['candidateExperiences'] as $experience) {
            $experience->country_name = getCountryName($experience->country_id);
        }
        $data['candidateEducations'] = CandidateEducation::with('degreeLevel')->where('candidate_id',
            $candidate)->get();
        foreach ($data['candidateEducations'] as $education) {
            $education->country_name = getCountryName($education->country_id);
        }

        return $data;
    }

    /**
     * @param $companyId
     * @return mixed
     */
    public function isAlreadyReported($candidateId)
    {
        return ReportedToCandidate::where('user_id', Auth::id())
            ->where('candidate_id', $candidateId)
            ->exists();
    }

    public function storeReportCandidate($input)
    {
        $candidateReportedAsAbuse = ReportedToCandidate::where('user_id', $input['userId'])
            ->where('candidate_id', $input['candidateId'])
            ->exists();

        if (! $candidateReportedAsAbuse) {
            $reportedCandidateNote = trim($input['note']);
            if (empty($reportedCandidateNote)) {
                throw ValidationException::withMessages([
                    'note' => 'The Note Field is required',
                ]);
            }
            ReportedToCandidate::create([
                'user_id' => $input['userId'],
                'candidate_id' => $input['candidateId'],
                'note' => $input['note'],
            ]);

            return true;
        }

        return true;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function getReportedToCandidate($reportedToCandidate)
    {
        $query = ReportedToCandidate::with([
            'user', 'candidate.user',
        ])->select('reported_to_candidates.*')->findOrFail($reportedToCandidate);

        return $query;
    }

    /**
     * @return mixed
     */
    public function getJobAlerts()
    {
        $candidate = Candidate::with('jobAlerts')->whereUserId(Auth::id())->first();
        $data['jobTypes'] = JobType::all();
        $data['jobAlerts'] = $candidate->jobAlerts()->pluck('job_type_id')->toArray();
        $data['candidate'] = $candidate;

        return $data;
    }

    public function updateJobAlerts($input): bool
    {
        $candidate = Candidate::with('jobAlerts')->whereUserId(Auth::id())->first();
        try {
            $candidate->job_alert = (isset($input['job_alert'])) ? 1 : 0;
            $candidate->update();

            if (isset($input['job_types']) && ! empty($input['job_types'])) {
                $candidate->jobAlerts()->sync($input['job_types']);
            } else {
                $candidate->jobAlerts()->sync([]);
            }

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
