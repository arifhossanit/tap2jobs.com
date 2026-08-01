<?php

namespace App\Http\Controllers\Candidates;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateCandidateEducationRequest;
use App\Http\Requests\CreateCandidateExperienceRequest;
use App\Http\Requests\CreateCandidateTrainingRequest;
use App\Models\CandidateEducation;
use App\Models\CandidateExperience;
use App\Models\CandidateTraining;
use App\Repositories\Candidates\CandidateProfileRepository;

class CandidateProfileController extends AppBaseController
{
    /** @var CandidateProfileRepository */
    private $candidateProfileRepository;

    public function __construct(CandidateProfileRepository $candidateProfileRepo)
    {
        $this->candidateProfileRepository = $candidateProfileRepo;
    }

    /**
     * @return mixed
     */
    public function createExperience(CreateCandidateExperienceRequest $request)
    {
        $input = $request->all();
        if (! isset($input['currently_working'])) {
            $request->validate([
                'end_date' => 'required|date',
            ]);
        }
        $input['end_date'] = empty($input['end_date']) ? date('Y-m-d') : $input['end_date'];
        $candidateExperience = $this->candidateProfileRepository->createExperience($input);

        return $this->sendResponse($candidateExperience, __('messages.flash.candidate_experience_save'));
    }

    /**
     * @return mixed
     */
    public function editExperience(CandidateExperience $candidateExperience)
    {
        $candidateId = getLoggedInUser()->candidate->id;
        $candidateExperienceId = CandidateExperience::whereCandidateId($candidateId)->pluck('id')->toArray();

        if (! in_array($candidateExperience->id, $candidateExperienceId)) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        return $this->sendResponse($candidateExperience, __('messages.flash.candidate_experience_retrieved'));
    }

    /**
     * @return mixed
     *
     * @throws \Exception
     */
    public function updateExperience(
        CandidateExperience $candidateExperience,
        CreateCandidateExperienceRequest $request
    ) {
        $input = $request->all();
        $input['end_date'] = empty($input['end_date']) ? date('Y-m-d') : $input['end_date'];
        $data['id'] = $candidateExperience->id;
        $candidateExperience->delete();

        $data['candidateExperience'] = $this->candidateProfileRepository->createExperience($input);

        return $this->sendResponse($data, __('messages.flash.candidate_experience_update'));
    }

    /**
     * @return mixed
     *
     * @throws \Exception
     */
    public function destroyExperience(CandidateExperience $candidateExperience)
    {
        $id = $candidateExperience->id;

        $candidateId = getLoggedInUser()->candidate->id;
        $candidateExperienceId = CandidateExperience::whereCandidateId($candidateId)->pluck('id')->toArray();

        if (! in_array($id, $candidateExperienceId)) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $candidateExperience->delete();

        return $this->sendResponse($id, __('messages.flash.candidate_experience_delete'));
    }

    /**
     * @return mixed
     */
    public function createEducation(CreateCandidateEducationRequest $request)
    {
        $input = $request->validated();

        $candidateEducation = $this->candidateProfileRepository->createEducation($input);
        $candidateEducation->country = getCountryName($candidateEducation->country_id);

        return $this->sendResponse($candidateEducation, __('messages.flash.candidate_education_save'));
    }

    /**
     * @return mixed
     */
    public function editEducation(CandidateEducation $candidateEducation)
    {
        $candidateId = getLoggedInUser()->candidate->id;
        $candidateExperienceId = CandidateEducation::whereCandidateId($candidateId)->pluck('id')->toArray();

        if (! in_array($candidateEducation->id, $candidateExperienceId)) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $education = $this->candidateProfileRepository->getEducation($candidateEducation);

        return $this->sendResponse($education, __('messages.flash.candidate_education_retrieved'));
    }

    /**
     * @return mixed
     *
     * @throws \Exception
     */
    public function updateEducation(CandidateEducation $candidateEducation, CreateCandidateEducationRequest $request)
    {
        $candidateId = getLoggedInUser()->candidate->id;

        if ($candidateEducation->candidate_id !== $candidateId) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $data['id'] = $candidateEducation->id;
        $data['candidateEducation'] = $this->candidateProfileRepository->updateEducation($candidateEducation, $request->validated());
        $data['candidateEducation']->country = getCountryName($data['candidateEducation']->country_id);

        return $this->sendResponse($data, __('messages.flash.candidate_education_update'));
    }

    /**
     * @return mixed
     *
     * @throws \Exception
     */
    public function destroyEducation(CandidateEducation $candidateEducation)
    {
        $id = $candidateEducation->id;

        $candidateId = getLoggedInUser()->candidate->id;
        $candidateExperienceId = CandidateEducation::whereCandidateId($candidateId)->pluck('id')->toArray();

        if (! in_array($id, $candidateExperienceId)) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $candidateEducation->delete();

        return $this->sendResponse($id, __('messages.flash.candidate_education_delete'));
    }

    public function createTraining(CreateCandidateTrainingRequest $request)
    {
        $candidateTraining = $this->candidateProfileRepository->createTraining($request->validated());

        return $this->sendResponse($candidateTraining, __('messages.flash.candidate_training_save'));
    }

    public function editTraining(CandidateTraining $candidateTraining)
    {
        if ($candidateTraining->candidate_id !== getLoggedInUser()->candidate->id) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        return $this->sendResponse($candidateTraining, __('messages.flash.candidate_training_retrieved'));
    }

    public function updateTraining(CandidateTraining $candidateTraining, CreateCandidateTrainingRequest $request)
    {
        if ($candidateTraining->candidate_id !== getLoggedInUser()->candidate->id) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $data['id'] = $candidateTraining->id;
        $data['candidateTraining'] = $this->candidateProfileRepository->updateTraining($candidateTraining, $request->validated());

        return $this->sendResponse($data, __('messages.flash.candidate_training_update'));
    }

    public function destroyTraining(CandidateTraining $candidateTraining)
    {
        $id = $candidateTraining->id;

        if ($candidateTraining->candidate_id !== getLoggedInUser()->candidate->id) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $candidateTraining->delete();

        return $this->sendResponse($id, __('messages.flash.candidate_training_delete'));
    }
}
