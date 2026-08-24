<?php

namespace App\Repositories;

use App\Models\Candidate;
use App\Models\Job;
use App\Models\JobApplication;
use App\Services\ApplicationCvService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class JobApplicationRepository
 */
class JobApplicationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'job_id',
        'resume_id',
        'expected_salary',
        'notes',
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
        return JobApplication::class;
    }

    /**
     * @return mixed
     */
    public function checkJobStatus(int $jobId, int $candidateId, int $status)
    {
        return JobApplication::where('job_id', $jobId)
            ->where('candidate_id', $candidateId)
            ->where('status', $status)
            ->exists();
    }

    /**
     * @return mixed
     */
    public function showApplyJobForm($jobId)
    {
        /** @var Candidate $candidate */
        $candidate = Candidate::findOrFail(Auth::user()->owner_id);

        /** @var Job $job */
        $job = Job::whereJobId($jobId)->with('company')->first();
        $data['isActive'] = ($job->status == Job::STATUS_OPEN) ? true : false;

        $jobRepo = app(JobRepository::class);
        $data['isApplied'] = $this->checkJobStatus($job->id, $candidate->id, JobApplication::STATUS_APPLIED);

        $data['resumes'] = [];
        $data['isJobDrafted'] = false;
        if (! $data['isApplied']) {
            // get candidate resumes
            $applicationCv = app(ApplicationCvService::class)->ensure($candidate);
            $candidate->refresh();
            $resumeMedia = $candidate->getMedia(Candidate::RESUME_PATH)
                ->sortByDesc(fn (Media $media) => (bool) $media->getCustomProperty(ApplicationCvService::APPLICATION_CV_PROPERTY, false));
            $data['resumes'] = $resumeMedia->mapWithKeys(fn (Media $media) => [
                $media->id => $media->getCustomProperty(ApplicationCvService::APPLICATION_CV_PROPERTY, false)
                    ? ApplicationCvService::TITLE
                    : $media->getCustomProperty('title', $media->name),
            ]);
            $data['resumeDetails'] = $resumeMedia->mapWithKeys(fn (Media $media) => [
                $media->id => [
                    'title' => $media->getCustomProperty(ApplicationCvService::APPLICATION_CV_PROPERTY, false)
                        ? ApplicationCvService::TITLE
                        : $media->getCustomProperty('title', $media->name),
                    'extension' => strtoupper($media->extension ?: 'FILE'),
                    'is_application_cv' => (bool) $media->getCustomProperty(
                        ApplicationCvService::APPLICATION_CV_PROPERTY,
                        false
                    ),
                    'is_default' => (bool) $media->getCustomProperty('is_default', false),
                ],
            ]);
            $data['default_resume'] = $resumeMedia->first(
                fn (Media $media) => (bool) $media->getCustomProperty('is_default', false)
            ) ?? $applicationCv;
            if (isset($data['default_resume'])) {
                $data['default_resume'] = $data['default_resume']->id;
            }

            // check job is drafted or not
            $data['isJobDrafted'] = $this->checkJobStatus($job->id, $candidate->id, JobApplication::STATUS_DRAFT);

            if ($data['isJobDrafted']) {
                $data['draftJobDetails'] = $job->appliedJobs()->where('candidate_id', $candidate->id)->first();
            }
        }
        $data['job'] = $job;

        return $data;
    }

    public function store(array $input): bool
    {
        try {
            return DB::transaction(function () use ($input): bool {
                /** @var Candidate $candidate */
                $candidate = Candidate::findOrFail(Auth::user()->owner_id);
                $candidate->unsetRelation('media');

                $resumes = $candidate->getMedia(Candidate::RESUME_PATH);
                $selectedResume = $resumes->first(
                    fn (Media $media) => (bool) $media->getCustomProperty('is_default', false)
                ) ?? $resumes->first()
                  ?? app(ApplicationCvService::class)->ensure($candidate);

                $job = Job::findOrFail($input['job_id']);
                if ($job->status !== Job::STATUS_OPEN) {
                    throw new UnprocessableEntityHttpException(__('messages.flash.job_not_active'));
                }

                /** @var JobApplication|null $jobApplication */
                $jobApplication = JobApplication::where('job_id', $job->id)
                    ->where('candidate_id', $candidate->id)
                    ->lockForUpdate()
                    ->first();

                if ($jobApplication?->status === JobApplication::STATUS_APPLIED) {
                    throw new UnprocessableEntityHttpException(__('messages.flash.job_already_applied'));
                }

                $jobApplication ??= new JobApplication();
                $jobApplication->fill([
                    'job_id' => $job->id,
                    'candidate_id' => $candidate->id,
                    'resume_id' => $selectedResume->id,
                    'expected_salary' => filled($input['expected_salary'] ?? null)
                        ? removeCommaFromNumbers($input['expected_salary'])
                        : null,
                    'notes' => $input['notes'] ?? null,
                    'status' => $input['application_type'] === 'apply'
                        ? JobApplication::STATUS_APPLIED
                        : JobApplication::STATUS_DRAFT,
                ]);
                $jobApplication->save();

                return true;
            });
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function downloadMedia(JobApplication $jobApplication): array
    {
        try {
            $candidateResumes = Media::query()
                ->where('model_type', Candidate::class)
                ->where('model_id', $jobApplication->candidate_id)
                ->where('collection_name', Candidate::RESUME_PATH);

            $documentMedia = (clone $candidateResumes)
                ->whereKey($jobApplication->resume_id)
                ->first() ?? $candidateResumes->latest()->firstOrFail();

            $file = Storage::disk($documentMedia->disk)->get($documentMedia->getPathRelativeToRoot());

            $headers = [
                'Content-Type' => $documentMedia->mime_type,
                'Content-Description' => 'File Transfer',
                'Content-Disposition' => "attachment; filename={$documentMedia->file_name}",
                'filename' => $documentMedia->file_name,
            ];

            return [$file, $headers];
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
