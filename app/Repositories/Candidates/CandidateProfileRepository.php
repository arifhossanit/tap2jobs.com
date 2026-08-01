<?php

namespace App\Repositories\Candidates;

use App\Models\Candidate;
use App\Models\CandidateEducation;
use App\Models\CandidateExperience;
use App\Models\CandidateRetiredArmyEmployment;
use App\Models\CandidateTraining;
use App\Models\RequiredDegreeLevel;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

/**
 * Class CandidateProfileRepository
 */
class CandidateProfileRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'experience',
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
    public function createExperience(array $input)
    {
        $input['candidate_id'] = Auth::user()->owner_id;
        $input = $this->normalizeExperienceInput($input);
        $input['sort_order'] = CandidateExperience::where('candidate_id', $input['candidate_id'])->max('sort_order') + 1;

        $candidateExperience = CandidateExperience::create(Arr::only($input, $this->experienceFields()));
        $this->syncExperienceExpertises($candidateExperience, $input);
        $candidateExperience->country = getCountryName($candidateExperience->country_id);

        return $candidateExperience->load('expertises');
    }

    public function updateExperience(CandidateExperience $candidateExperience, array $input): CandidateExperience
    {
        $input['candidate_id'] = $candidateExperience->candidate_id;
        $input = $this->normalizeExperienceInput($input);

        $candidateExperience->update(Arr::only($input, $this->experienceFields()));
        $this->syncExperienceExpertises($candidateExperience, $input);
        $candidateExperience->country = getCountryName($candidateExperience->country_id);

        return $candidateExperience->fresh()->load('expertises');
    }

    /**
     * @return Builder|Model|object
     */
    public function createEducation(array $input)
    {
        $input['candidate_id'] = Auth::user()->owner_id;
        $input = $this->normalizeEducationInput($input);
        $input['show_summary'] = ! empty($input['show_summary']);
        $input['foreign_institute'] = ! empty($input['foreign_institute']);
        $input['sort_order'] = CandidateEducation::where('candidate_id', $input['candidate_id'])->max('sort_order') + 1;

        /** @var CandidateEducation $education */
        $education = CandidateEducation::create($input);

        return $this->getEducation($education);
    }

    /**
     * @return Builder|Model|object
     */
    public function updateEducation(CandidateEducation $candidateEducation, array $input)
    {
        $input = $this->normalizeEducationInput($input);
        $input['show_summary'] = ! empty($input['show_summary']);
        $input['foreign_institute'] = ! empty($input['foreign_institute']);

        $candidateEducation->update(Arr::only($input, [
            'degree_level_id',
            'degree_title',
            'major',
            'board',
            'country_id',
            'state_id',
            'city_id',
            'institute',
            'foreign_institute',
            'show_summary',
            'result',
            'cgpa',
            'scale',
            'year',
            'duration',
            'achievement',
        ]));

        return $this->getEducation($candidateEducation);
    }

    /**
     * @return Builder|Model|object
     */
    public function getEducation(CandidateEducation $candidateEducation)
    {
        return CandidateEducation::with('degreeLevel')
            ->where('id', $candidateEducation->id)->first();
    }

    public function createTraining(array $input): CandidateTraining
    {
        $input['candidate_id'] = Auth::user()->owner_id;
        $input['sort_order'] = CandidateTraining::where('candidate_id', $input['candidate_id'])->max('sort_order') + 1;

        return CandidateTraining::create($input);
    }

    public function updateTraining(CandidateTraining $candidateTraining, array $input): CandidateTraining
    {
        $candidateTraining->update(Arr::only($input, [
            'title',
            'country',
            'topics',
            'year',
            'institute',
            'duration',
            'location',
        ]));

        return $candidateTraining->fresh();
    }

    public function updateRetiredArmyEmployment(array $input): CandidateRetiredArmyEmployment
    {
        $candidateId = Auth::user()->owner_id;
        $input['candidate_id'] = $candidateId;

        return CandidateRetiredArmyEmployment::updateOrCreate(
            ['candidate_id' => $candidateId],
            Arr::only($input, [
                'candidate_id',
                'ba_no_prefix',
                'ba_no',
                'rank',
                'type',
                'arms',
                'trade',
                'course',
                'date_of_commission',
                'date_of_retirement',
            ])
        );
    }

    private function normalizeEducationInput(array $input): array
    {
        $levelType = $this->educationLevelType($input['degree_level_id'] ?? null);

        if (in_array($levelType, ['psc', 'jsc'], true)) {
            $input['major'] = null;
            $input['show_summary'] = false;
        }

        if (in_array($levelType, ['secondary', 'higher_secondary', 'advanced'], true)) {
            $input['board'] = null;
        }

        return $input;
    }

    private function educationLevelType($degreeLevelId): string
    {
        $levelName = RequiredDegreeLevel::whereKey($degreeLevelId)->value('name') ?? '';
        $levelName = strtolower($levelName);

        if (str_contains($levelName, 'psc') || str_contains($levelName, '5 pass')) {
            return 'psc';
        }

        if (str_contains($levelName, 'jsc') || str_contains($levelName, 'jdc') || str_contains($levelName, '8 pass')) {
            return 'jsc';
        }

        if (str_contains($levelName, 'higher secondary') || str_contains($levelName, 'hsc')) {
            return 'higher_secondary';
        }

        if (str_contains($levelName, 'secondary') || str_contains($levelName, 'ssc')) {
            return 'secondary';
        }

        return 'advanced';
    }

    private function normalizeExperienceInput(array $input): array
    {
        $input['currently_working'] = ! empty($input['currently_working']);
        $input['end_date'] = $input['currently_working'] ? null : ($input['end_date'] ?? null);

        return $input;
    }

    private function experienceFields(): array
    {
        return [
            'candidate_id',
            'experience_title',
            'department',
            'company',
            'company_business',
            'country_id',
            'state_id',
            'city_id',
            'start_date',
            'end_date',
            'currently_working',
            'description',
            'company_location',
            'sort_order',
        ];
    }

    private function syncExperienceExpertises(CandidateExperience $candidateExperience, array $input): void
    {
        $candidateExperience->expertises()->delete();

        $names = $input['area_of_expertise'] ?? [];
        $durations = $input['expertise_duration'] ?? [];
        foreach ($names as $index => $name) {
            if (! filled($name)) {
                continue;
            }

            $candidateExperience->expertises()->create([
                'name' => $name,
                'duration_months' => filled($durations[$index] ?? null) ? (int) $durations[$index] : null,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
