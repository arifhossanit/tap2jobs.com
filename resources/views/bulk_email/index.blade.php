@extends('layouts.app')

@section('title', 'Bulk Email')

@section('content')
<link rel="stylesheet" href="{{ asset('css/tagify.css') }}">
<style>#recipientGroup .tagify { width: 100%; min-height: 43px; }</style>
<div class="container-fluid">
    <div class="card">
        <div class="card-header border-0 pt-5"><h2 class="card-title fw-bold">Send Bulk Email</h2></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            <form id="bulkEmailForm" action="{{ route('bulk-email.send') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <label class="form-label required" for="target_type">Target Type</label>
                        <select class="form-select" id="target_type" name="target_type" required>
                            <option value="candidate" @selected(old('target_type') === 'candidate')>All candidates</option>
                            <option value="employer" @selected(old('target_type') === 'employer')>All employers</option>
                            <option value="existing" @selected(old('target_type') === 'existing')>Select existing user emails</option>
                            <option value="custom" @selected(old('target_type') === 'custom')>Custom emails</option>
                            <option value="csv" @selected(old('target_type') === 'csv')>Import emails from CSV</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="candidateFilterGroup">
                        <label class="form-label required" for="candidate_profile_filter">Profile Completion</label>
                        <select class="form-select" id="candidate_profile_filter" name="candidate_profile_filter">
                            <option value="all" @selected(old('candidate_profile_filter', 'all') === 'all')>All</option>
                            <option value="below_30" @selected(old('candidate_profile_filter') === 'below_30')>Below 30%</option>
                            <option value="30_to_below_80" @selected(old('candidate_profile_filter') === '30_to_below_80')>30% to 80%</option>
                            <option value="80_plus" @selected(old('candidate_profile_filter') === '80_plus')>Above 80% </option>
                        </select>
                        <div class="form-text">Only verified candidates matching this profile completion range will receive the email.</div>
                    </div>
                    <div class="col-md-6" id="recipientGroup" hidden>
                        <label class="form-label required" for="recipientInput">Email Addresses</label>
                        <input class="form-control" id="recipientInput" placeholder="Search name or email" autocomplete="off">
                        <div class="form-text" id="recipientHint"></div>
                        <div id="recipientHidden"></div>
                    </div>
                    <div class="col-md-6" id="csvGroup" hidden>
                        <label class="form-label required" for="csv_file">CSV File</label>
                        <input class="form-control" type="file" id="csv_file" name="csv_file" accept=".csv,text/csv,text/plain">
                        <div class="form-text">Maximum 50 MB. The first row must contain an <code>email</code> column. Invalid and duplicate addresses are skipped automatically.</div>
                    </div>
                </div>
                <div class="mb-5">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="setSchedule" @checked(old('schedule_at'))>
                        <label class="form-check-label" for="setSchedule">Schedule for later (optional)</label>
                    </div>
                    <div class="mt-3" id="scheduleGroup" hidden>
                        <label class="form-label" for="schedule_at">Send date and time</label>
                        <input type="datetime-local" class="form-control" id="schedule_at" name="schedule_at"
                               value="{{ old('schedule_at') }}" style="max-width: 350px;" disabled>
                        <div class="form-text">Time zone: {{ config('app.timezone') }}. Queue worker must be running.</div>
                    </div>
                </div>
                <div class="mb-5">
                    <label class="form-label required" for="subject">Subject</label>
                    <input class="form-control" type="text" id="subject" name="subject" value="{{ old('subject') }}" maxlength="255" required>
                    <div class="form-text">Personalize with: <code>@{{Name}}</code>, <code>@{{first_name}}</code>, <code>@{{last_name}}</code>, <code>@{{email}}</code></div>
                </div>
                <div class="mb-5">
                    <label class="form-label required" for="body">Message Body</label>
                    <textarea class="form-control" id="body" name="body" rows="15">{{ old('body') }}</textarea>
                    <div class="form-text mb-1">Personalize with: <code>@{{Name}}</code>, <code>@{{first_name}}</code>, <code>@{{last_name}}</code>, <code>@{{email}}</code></div>
                    <div class="form-text">Use Insert file in the editor to add a file link; that file is also attached to the email. Images can be inserted in the body.</div>
                </div>
                <button type="submit" class="btn btn-primary" id="sendButton">Send Email</button>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('vendor/tagify/tagify.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const successMessage = @json(session('bulk_email_success'));
    if (successMessage && typeof window.displaySuccessMessage === 'function') {
        window.displaySuccessMessage(successMessage);
    }

    const form = document.getElementById('bulkEmailForm');
    const type = document.getElementById('target_type');
    const recipientGroup = document.getElementById('recipientGroup');
    const csvGroup = document.getElementById('csvGroup');
    const csvInput = document.getElementById('csv_file');
    const candidateFilterGroup = document.getElementById('candidateFilterGroup');
    const candidateProfileFilter = document.getElementById('candidate_profile_filter');
    const recipientInput = document.getElementById('recipientInput');
    const recipientHint = document.getElementById('recipientHint');
    const hidden = document.getElementById('recipientHidden');
    const scheduleToggle = document.getElementById('setSchedule');
    const scheduleGroup = document.getElementById('scheduleGroup');
    const scheduleInput = document.getElementById('schedule_at');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let searchController;
    let searchTimer;

    const tags = new Tagify(recipientInput, {
        enforceWhitelist: false,
        maxTags: 1000,
        tagTextProp: 'email',
        dropdown: { enabled: 0, maxItems: 20, searchKeys: ['name', 'email'] },
        templates: {
            dropdownItem: function (item) {
                const name = document.createElement('span');
                name.textContent = item.name || '';
                const email = document.createElement('small');
                email.className = 'text-muted ms-2';
                email.textContent = item.email || '';
                const wrapper = document.createElement('div');
                wrapper.innerHTML = '<div ' + this.getAttributes(item) + ' class="tagify__dropdown__item" tabindex="0" role="option"></div>';
                const row = wrapper.firstElementChild;
                row.append(name, email);
                return row.outerHTML;
            }
        }
    });

    function refreshRecipients() {
        const selected = type.value;
        const visible = selected === 'existing' || selected === 'custom';
        recipientGroup.hidden = !visible;
        csvGroup.hidden = selected !== 'csv';
        csvInput.disabled = selected !== 'csv';
        csvInput.required = selected === 'csv';
        candidateFilterGroup.hidden = selected !== 'candidate';
        candidateProfileFilter.disabled = selected !== 'candidate';
        tags.removeAllTags();
        tags.settings.enforceWhitelist = selected === 'existing';
        tags.settings.tagTextProp = selected === 'existing' ? 'email' : 'value';
        tags.settings.validate = selected === 'custom'
            ? (tag) => emailPattern.test(tag.value)
            : () => true;
        tags.settings.whitelist = [];
        recipientInput.placeholder = selected === 'existing' ? 'Search verified users by name or email' : 'Type an email and press Enter';
        recipientHint.textContent = selected === 'existing'
            ? 'Search results load from the server; only verified candidates and employers appear.'
            : 'Add multiple email addresses. Custom addresses are not checked against registered users.';
    }

    tags.on('input', function (event) {
        if (type.value !== 'existing') return;
        clearTimeout(searchTimer);
        if (searchController) searchController.abort();
        const term = event.detail.value.trim();
        if (term.length < 2) { tags.dropdown.hide(); return; }
        searchTimer = setTimeout(async function () {
            searchController = new AbortController();
            try {
                const response = await fetch(@json(route('bulk-email.users')) + '?q=' + encodeURIComponent(term), {
                    signal: searchController.signal, headers: { 'Accept': 'application/json' }
                });
                if (!response.ok) throw new Error('Search failed');
                tags.settings.whitelist = await response.json();
                tags.dropdown.show(term);
            } catch (error) {
                if (error.name !== 'AbortError') recipientHint.textContent = 'Could not load users. Please try again.';
            }
        }, 300);
    });

    function refreshSchedule() {
        scheduleGroup.hidden = !scheduleToggle.checked;
        scheduleInput.disabled = !scheduleToggle.checked;
        scheduleInput.required = scheduleToggle.checked;
    }

    type.addEventListener('change', refreshRecipients);
    scheduleToggle.addEventListener('change', refreshSchedule);
    refreshRecipients();
    refreshSchedule();

    form.addEventListener('submit', function (event) {
        tinymce.triggerSave();
        hidden.replaceChildren();
        const candidateRange = candidateProfileFilter.options[candidateProfileFilter.selectedIndex]?.text || 'All candidates';
        const audience = type.value === 'candidate'
            ? 'verified candidates matching “' + candidateRange + '”'
            : 'all verified employers';
        if ((type.value === 'candidate' || type.value === 'employer') &&
            !confirm('Queue this email for ' + audience + '?')) {
            event.preventDefault();
            return;
        }
        if (type.value === 'existing' || type.value === 'custom') {
            if (!tags.value.length) {
                event.preventDefault();
                alert('Please add at least one email recipient.');
                return;
            }
            tags.value.forEach(function (item) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'recipients[]';
                input.value = item.value;
                hidden.appendChild(input);
            });
        }
        document.getElementById('sendButton').disabled = true;
    });

    tinymce.init({
        base_url: '/tinymce', suffix: '.min', license_key: 'gpl', target: document.getElementById('body'),
        // Email links and images need full URLs outside the admin page.
        relative_urls: false,
        remove_script_host: false,
        height: 380, menubar: false, branding: false, promotion: false,
        plugins: 'link image lists table code',
        toolbar: 'undo redo | blocks | bold italic underline strikethrough | bullist numlist | link image insertfile table | removeformat code',
        file_picker_types: 'image',
        file_picker_callback: function (callback, value, meta) {
            if (meta.filetype !== 'image') return;
            const picker = document.createElement('input');
            picker.type = 'file';
            picker.accept = 'image/jpeg,image/png,image/gif,image/webp';
            picker.addEventListener('change', async function () {
                if (!picker.files.length) return;
                const data = new FormData();
                data.append('file', picker.files[0]);
                try {
                    const response = await fetch(@json(route('bulk-email.upload')), {
                        method: 'POST', headers: { 'X-CSRF-TOKEN': @json(csrf_token()), 'Accept': 'application/json' }, body: data
                    });
                    if (!response.ok) throw new Error('Image upload failed. Check file type and 10 MB limit.');
                    const image = await response.json();
                    callback(image.url, { alt: image.name, title: image.name });
                } catch (error) { alert(error.message); }
            });
            picker.click();
        },
        setup: function (editor) {
            editor.ui.registry.addButton('insertfile', {
                text: 'Insert file', onAction: function () {
                    const picker = document.createElement('input');
                    picker.type = 'file';
                    picker.accept = '.jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx';
                    picker.addEventListener('change', async function () {
                        if (!picker.files.length) return;
                        const data = new FormData();
                        data.append('file', picker.files[0]);
                        try {
                            const response = await fetch(@json(route('bulk-email.upload')), {
                                method: 'POST', headers: { 'X-CSRF-TOKEN': @json(csrf_token()), 'Accept': 'application/json' }, body: data
                            });
                            if (!response.ok) throw new Error('Upload failed. Check file type and 10 MB limit.');
                            const file = await response.json();
                            editor.insertContent('<a href="' + editor.dom.encode(file.url) + '">' + editor.dom.encode(file.name) + '</a>');
                        } catch (error) { alert(error.message); }
                    });
                    picker.click();
                }
            });
        },
        images_upload_handler: async function (blobInfo) {
            const data = new FormData();
            data.append('file', blobInfo.blob(), blobInfo.filename());
            const response = await fetch(@json(route('bulk-email.upload')), {
                method: 'POST', headers: { 'X-CSRF-TOKEN': @json(csrf_token()), 'Accept': 'application/json' }, body: data
            });
            if (!response.ok) throw new Error('Image upload failed.');
            return (await response.json()).url;
        }
    });
});
</script>
@endsection
