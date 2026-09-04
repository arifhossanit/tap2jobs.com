@forelse($jobs as $key => $job)
    <li class="media mt-4 notification rounded shadow p-4">
        <div class="form-group col-md-4 col-sm-12 mb-0 pt-1">
            <div class="form-check custom-checkbox">
                <input type="checkbox" name="job_id[]" id="job_{{$job->id}}"
                       class="form-check-input notification__checkbox jobCheck {{ checkLanguageSession() == 'ar' ? 'float-end ms-5' : '' }}"
                       value="{{$job->id}}">
                <label class="form-check-label d-inline-block {{ checkLanguageSession() == 'ar' ? 'me-5' : 'ms-5' }}" for="job_{{$job->id}}">
                    <a href="{{ route('admin.jobs.show',$job->id) }}" target="_blank"
                       class="media-title mb-1 notification__title text-decoration-none">{{ \Illuminate\Support\Str::limit($job->job_title,65) }}</a>
                </label>
            </div>
            <div class="text-time form-check-label {{ checkLanguageSession() == 'ar' ? 'me-15' : 'ms-15' }}">{{ $job->created_at->diffForHumans() }}</div>
        </div>
    </li>
@empty
    <h4 class="text-center mt-9">{{__('messages.job_notification.no_jobs_available')}}.</h4>
@endforelse

@if($jobs->hasPages())
    <div class="mt-5 d-flex justify-content-end">
        {{ $jobs->links('job_notification.custom-pagination-jobs') }}
    </div>
@endif
