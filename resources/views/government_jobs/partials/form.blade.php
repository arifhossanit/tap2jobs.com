@csrf
<div class="row">
    <div class="col-md-6 mb-5">
        <label class="form-label">Circular Title <span class="required"></span></label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $governmentJob->title ?? '') }}" required maxlength="255">
    </div>
    <div class="col-md-6 mb-5">
        <label class="form-label">Ministry / Organization <span class="required"></span></label>
        <input type="text" name="organization_name" class="form-control" value="{{ old('organization_name', $governmentJob->organization_name ?? '') }}" required maxlength="255">
    </div>
    <div class="col-md-4 mb-5">
        <label class="form-label">Source</label>
        <input type="text" name="source_name" class="form-control" value="{{ old('source_name', $governmentJob->source_name ?? '') }}" placeholder="e.g. The Daily Newspaper">
    </div>
    <div class="col-md-4 mb-5">
        <label class="form-label">Publication Date</label>
        <input type="date" name="published_at" class="form-control" value="{{ old('published_at', isset($governmentJob) && $governmentJob->published_at ? $governmentJob->published_at->format('Y-m-d') : '') }}">
    </div>
    <div class="col-md-4 mb-5">
        <label class="form-label">Application Deadline</label>
        <input type="date" name="application_deadline" class="form-control" value="{{ old('application_deadline', isset($governmentJob) && $governmentJob->application_deadline ? $governmentJob->application_deadline->format('Y-m-d') : '') }}">
    </div>
    <div class="col-md-6 mb-5">
        <label class="form-label">Circular Image / PDF @if(!isset($governmentJob))<span class="required"></span>@endif</label>
        <input type="file" name="circular_file" class="form-control" accept="image/jpeg,image/png,image/webp,application/pdf" @required(!isset($governmentJob))>
        <div class="form-text">JPG, PNG, WEBP or PDF; maximum 20 MB.</div>
        @isset($governmentJob)
            <a href="{{ $governmentJob->circular_url }}" target="_blank" class="d-inline-block mt-2">View current circular</a>
        @endisset
    </div>
    <div class="col-md-6 mb-5">
        <label class="form-label">Online Application URL</label>
        <input type="url" name="application_url" class="form-control" value="{{ old('application_url', $governmentJob->application_url ?? '') }}" placeholder="https://">
    </div>
    <div class="col-12 mb-5">
        <div class="form-check form-switch">
            <input type="hidden" name="is_published" value="0">
            <input class="form-check-input" type="checkbox" name="is_published" id="governmentJobPublished" value="1" @checked(old('is_published', $governmentJob->is_published ?? true))>
            <label class="form-check-label" for="governmentJobPublished">Published</label>
        </div>
    </div>
</div>
<div class="d-flex justify-content-end gap-3">
    <a href="{{ route('admin.government-jobs.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Save</button>
</div>
