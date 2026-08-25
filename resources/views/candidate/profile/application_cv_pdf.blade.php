<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 34px 46px 38px; }
        * { box-sizing: border-box; }
        body { color: #30343b; font-family: DejaVu Sans, sans-serif; font-size: 9.2px; line-height: 1.42; margin: 0; }
        h1, h2, h3, p { margin: 0; }
        table { border-collapse: collapse; width: 100%; }
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        .header { border-bottom: 1.4px solid #363636; margin-bottom: 13px; padding: 3px 7px 13px; }
        .header-info { padding-right: 18px; vertical-align: top; }
        .header-photo-cell { text-align: right; vertical-align: top; width: 116px; }
        .candidate-name { color: #3c3198; font-size: 16px; font-weight: 700; line-height: 1.2; margin: 4px 0 7px; }
        .contact-row { margin: 0 0 5px; word-break: break-word; }
        .contact-icon { height: 11px; margin-right: 7px; vertical-align: -1px; width: 11px; }
        .profile-photo { border: 2px solid #dedede; border-radius: 5px; height: 116px; object-fit: cover; padding: 2px; width: 96px; }
        .section { margin: 0 0 12px; }
        .section-title { background: #e5e5e5; color: #5b5b5b; font-size: 11.5px; font-weight: 700; margin-bottom: 9px; padding: 4px 5px; page-break-after: avoid; }
        .section-copy { padding: 0 5px; white-space: pre-line; }
        .experience-summary { font-weight: 700; margin: 0 5px 8px; }
        .experience-item { margin: 0 5px 11px; page-break-inside: avoid; }
        .experience-number { padding-right: 8px; vertical-align: top; width: 22px; }
        .experience-content { vertical-align: top; }
        .experience-title { font-weight: 700; }
        .experience-date { margin-bottom: 3px; }
        .label-line { margin-top: 3px; }
        .label-line strong { display: block; }
        .pre-line { white-space: pre-line; }
        .data-table { table-layout: fixed; }
        .data-table th, .data-table td { border: 1px solid #c9ccd1; padding: 5px 5px; text-align: center; vertical-align: middle; word-break: break-word; }
        .data-table th { color: #30343b; font-size: 8.2px; font-weight: 700; }
        .data-table td { font-size: 8.4px; }
        .detail-table { margin: 0 6px; width: calc(100% - 12px); }
        .detail-table td { padding: 2px 0; vertical-align: top; }
        .detail-label { width: 175px; }
        .detail-colon { text-align: center; width: 18px; }
        .skill-table td:first-child { text-align: left; width: 36%; }
        .skill-list { margin: 0; padding-left: 17px; }
        .skill-list li { margin-bottom: 6px; }
        .accomplishment-group { margin: 0 6px 10px; page-break-inside: avoid; }
        .accomplishment-group-title { font-weight: 400; margin-bottom: 3px; text-transform: capitalize; }
        .accomplishment-item { margin-bottom: 7px; }
        .accomplishment-number { padding-right: 8px; vertical-align: top; width: 24px; }
        .accomplishment-content { vertical-align: top; }
        .link { color: #30343b; text-decoration: none; word-break: break-all; }
        .language-table { margin: 0 auto; width: 74%; }
        .personal-table { width: 66%; }
        .reference-table { margin: 0 6px; width: calc(100% - 12px); }
        .reference-card { padding: 0 13px 0 0; vertical-align: top; width: 50%; }
        .reference-card + .reference-card { border-left: 1px solid #d5d5d5; padding-left: 16px; }
        .reference-card table td { padding: 1px 0; vertical-align: top; }
        .reference-label { width: 86px; }
        .reference-colon { width: 12px; }
        .muted { color: #636a73; }
        .footer { bottom: 14px; color: #9a9a9a; font-size: 7px; left: 46px; position: fixed; right: 46px; text-align: right; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
@php
    $objective = $plainText($candidate->objective ?: $candidate->career_summary);
    $specialQualification = $plainText($candidate->special_qualification);
    $formatDuration = function ($startDate, $endDate = null, $current = false) {
        if (!$startDate) return '';
        $end = $current ? now() : ($endDate ?: now());
        $months = max(1, $startDate->diffInMonths($end));
        $years = intdiv($months, 12);
        $remainingMonths = $months % 12;
        return collect([
            $years ? $years.' yr'.($years > 1 ? 's' : '') : null,
            $remainingMonths ? $remainingMonths.' month'.($remainingMonths > 1 ? 's' : '') : null,
        ])->filter()->implode(' ');
    };
    $formatExpertiseDuration = function ($months) {
        if (! filled($months)) {
            return '';
        }

        $months = (int) $months;

        return ' ('.$months.' '.\Illuminate\Support\Str::plural('month', $months).')';
    };
    $totalExperienceMonths = $experiences->sum(function ($experience) {
        if (!$experience->start_date) return 0;
        return max(1, $experience->start_date->diffInMonths(
            $experience->currently_working ? now() : ($experience->end_date ?: now())
        ));
    });
    $totalExperience = collect([
        intdiv($totalExperienceMonths, 12) ? intdiv($totalExperienceMonths, 12).' yrs' : null,
        ($totalExperienceMonths % 12) ? ($totalExperienceMonths % 12).' months' : null,
    ])->filter()->implode(' ');
    $jobLevelLabels = ['entry' => 'Entry Level Job', 'mid' => 'Mid Level Job', 'top' => 'Top Level Job'];
    $jobNatureLabels = ['full_time' => 'Full Time', 'part_time' => 'Part Time', 'contract' => 'Contract', 'internship' => 'Internship', 'freelance' => 'Freelance'];
    $contactAddress = collect([
        $candidate->address,
        $candidate->present_post_office,
        $candidate->thana_name,
        $candidate->city_name,
        $candidate->state_name,
        $candidate->country_name,
    ])->filter()->unique()->implode(', ');
    $phoneNumbers = collect([$user->phone, $candidate->secondary_mobile])->filter()->unique()->implode(', ');
    $emails = collect([$user->email, $candidate->alternate_email])->filter()->unique()->implode(', ');
    $formatSalary = fn ($amount) => filled($amount) ? 'BDT '.number_format((float) $amount, 0) : null;
    $careerRows = collect([
        'Preferred Job Category' => $preferredFunctionalAreas->merge($preferredSkills)->unique()->implode(', '),
        'Looking For' => $jobLevelLabels[$candidate->job_level] ?? null,
        'Available For' => $jobNatureLabels[$candidate->job_nature] ?? null,
        'Present Salary' => $formatSalary($candidate->current_salary),
        'Expected Salary' => $formatSalary($candidate->expected_salary),
        'Preferred District' => $preferredLocations->implode(', '),
        'Preferred Country' => $preferredCountries->implode(', '),
        'Preferred Organization' => $preferredOrganizations->implode(', '),
    ])->filter();
    $personalRows = $candidate->include_sensitive_personal_data_in_cv ? collect([
        "Father's Name" => $candidate->father_name,
        "Mother's Name" => $candidate->mother_name,
        'Date of Birth' => $user->dob?->format('d M, Y'),
        'Gender' => is_null($user->gender) ? null : ($user->gender == 0 ? 'Male' : 'Female'),
        'Marital Status' => optional($candidate->maritalStatus)->marital_status,
        'Nationality' => $candidate->nationality,
        'National ID No.' => $candidate->national_id_card,
        'Passport No.' => $candidate->passport_number,
        'Religion' => $candidate->religion,
        'Blood Group' => $candidate->blood_group,
        'Height (Meter)' => $candidate->height,
        'Weight (Kg)' => $candidate->weight,
    ])->filter(fn ($value) => filled($value)) : collect();
    $svgIcon = fn (string $svg): string => 'data:image/svg+xml;base64,'.base64_encode($svg);
    $contactIcons = [
        'location' => $svgIcon('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#454b54" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>'),
        'phone' => $svgIcon('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#454b54" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.78.62 2.63a2 2 0 0 1-.45 2.11L8 9.73a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.85.29 1.73.5 2.63.62A2 2 0 0 1 22 16.92Z"/></svg>'),
        'email' => $svgIcon('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#454b54" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2.5" y="4.5" width="19" height="15" rx="2"/><path d="m3.5 6 8.5 6 8.5-6"/></svg>'),
        'link' => $svgIcon('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#454b54" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>'),
    ];
@endphp

<div class="header">
    <table>

<tr>
    <td class="header-info">
        <h1 class="candidate-name">{{ $user->full_name }}</h1>

        @if($contactAddress)
            <p class="contact-row">
                <img class="contact-icon" src="{{ $contactIcons['location'] }}" alt="">
                {{ $contactAddress }}
            </p>
        @endif

        @if($phoneNumbers)
            <p class="contact-row">
                <img class="contact-icon" src="{{ $contactIcons['phone'] }}" alt="">
                {{ $phoneNumbers }}
            </p>
        @endif

        @if($emails)
            <p class="contact-row">
                <img class="contact-icon" src="{{ $contactIcons['email'] }}" alt="">
                {{ $emails }}
            </p>
        @endif

        @foreach($links as $link)
            <p class="contact-row">
                <img class="contact-icon" src="{{ $contactIcons['link'] }}" alt="">
                <a class="link" href="{{ $link->url }}">
                    {{ $link->url }}
                </a>
            </p>
        @endforeach
    </td>

    <td class="header-photo-cell">
        @if($profilePhoto)
            <img class="profile-photo" src="{{ $profilePhoto }}" alt="">
        @endif
    </td>
</tr>


    </table>
</div>

@if($objective)
    <section class="section">
        <h2 class="section-title">Career Objective</h2>
        <div class="section-copy">{{ $objective }}</div>
    </section>
@endif

@if($specialQualification)
    <section class="section">
        <h2 class="section-title">Special Qualification</h2>
        <div class="section-copy">{{ $specialQualification }}</div>
    </section>
@endif

@if($experiences->isNotEmpty())
    <section class="section">
        <h2 class="section-title">Experience</h2>
        @if($totalExperience)<p class="experience-summary">Total Year of Experience: {{ $totalExperience }}</p>@endif
        @foreach($experiences as $experience)
            <div class="experience-item">
                <table>
                    <tr>
                        <td class="experience-number">{{ $loop->iteration }}.</td>
                        <td class="experience-content">
                            <p class="experience-title">{{ $experience->experience_title }} ({{ $formatDuration($experience->start_date, $experience->end_date, $experience->currently_working) }})</p>
                            <p class="experience-date">({{ optional($experience->start_date)->format('d M, Y') }} - {{ $experience->currently_working ? 'Continuing' : optional($experience->end_date)->format('d M, Y') }})</p>
                            <p><strong>{{ $experience->company }}</strong></p>
                            @if($experience->company_location)<p>{{ $experience->company_location }}</p>@endif
                            @if($experience->expertises->isNotEmpty())
                                <p class="label-line"><strong>Area of Expertise</strong>{{ $experience->expertises->map(fn ($expertise) => $expertise->name.$formatExpertiseDuration($expertise->duration_months))->filter()->implode(', ') }}</p>
                            @endif
                            @if($plainText($experience->description))
                                <p class="label-line"><strong>Duties/Responsibilities</strong><span class="pre-line">{{ $plainText($experience->description) }}</span></p>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        @endforeach
    </section>
@endif

@if($educations->isNotEmpty())
    <section class="section">
        <h2 class="section-title">Academic / Education</h2>
        <table class="data-table">
            <thead><tr><th>Exam Title</th><th>Concentration/<br>Major</th><th>Institute</th><th>Result</th><th>Pas. Year</th><th>Duration</th><th>Achievement</th></tr></thead>
            <tbody>
            @foreach($educations as $education)
                <tr>
                    <td>{{ $education->degree_title ?: optional($education->degreeLevel)->name }}</td>
                    <td>{{ $education->major }}</td>
                    <td>{{ $education->institute }}</td>
                    <td>@if($education->cgpa){{ $education->cgpa }} out of {{ $education->scale }}@elseif($education->marks_percentage){{ $education->marks_percentage }}%@else{{ $education->result }}@endif</td>
                    <td>{{ $education->year }}</td>
                    <td>{{ $education->duration }}</td>
                    <td>{{ $plainText($education->achievement) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>
@endif

@if($trainings->isNotEmpty())
    <section class="section">
        <h2 class="section-title">Training</h2>
        <table class="data-table">
            <thead><tr><th>Training Title</th><th>Topic</th><th>Institute</th><th>Country</th><th>Location</th><th>Year</th><th>Duration</th></tr></thead>
            <tbody>
            @foreach($trainings as $training)
                <tr><td>{{ $training->title }}</td><td>{{ $plainText($training->topics) }}</td><td>{{ $training->institute }}</td><td>{{ $training->country }}</td><td>{{ $training->location }}</td><td>{{ $training->year }}</td><td>{{ $training->duration }}</td></tr>
            @endforeach
            </tbody>
        </table>
    </section>
@endif

@if($certifications->isNotEmpty())
    <section class="section">
        <h2 class="section-title">Professional Qualification</h2>
        <table class="data-table">
            <thead><tr><th>Certification</th><th>Institute</th><th>Location</th><th>Duration</th></tr></thead>
            <tbody>@foreach($certifications as $certification)<tr><td>{{ $certification->name }}</td><td>{{ $certification->institute }}</td><td>{{ $certification->location }}</td><td>{{ $certification->duration }}</td></tr>@endforeach</tbody>
        </table>
    </section>
@endif

@if($careerRows->isNotEmpty())
    <section class="section">
        <h2 class="section-title">Career and Application Information</h2>
        <table class="detail-table">
            @foreach($careerRows as $label => $value)<tr><td class="detail-label">{{ $label }}</td><td class="detail-colon">:</td><td>{{ $value }}</td></tr>@endforeach
        </table>
    </section>
@endif

@if($skills->isNotEmpty())
    <section class="section">
        <h2 class="section-title">Skill</h2>
        <table class="data-table skill-table">
            <thead><tr><th>Fields of Skill</th><th>Description</th></tr></thead>
            <tbody><tr><td><ul class="skill-list">@foreach($skills as $candidateSkill)@if($candidateSkill->skill)<li>{{ $candidateSkill->skill->name }}</li>@endif @endforeach</ul></td><td></td></tr></tbody>
        </table>
    </section>
@endif

@if($accomplishments->isNotEmpty())
    <section class="section">
        <h2 class="section-title">Accomplishment</h2>
        @foreach($accomplishments->groupBy('type') as $type => $items)
            <div class="accomplishment-group">
                <h3 class="accomplishment-group-title">{{ str($type)->replace('_', ' ')->title() }}</h3>
                @foreach($items as $item)
                    <table class="accomplishment-item"><tr><td class="accomplishment-number">{{ $loop->iteration }}.</td><td class="accomplishment-content"><strong>{{ $item->title }}</strong>@if($item->url)<br>URL: <a class="link" href="{{ $item->url }}">{{ $item->url }}</a>@endif @if($plainText($item->description))<br><span class="pre-line">{{ $plainText($item->description) }}</span>@endif</td></tr></table>
                @endforeach
            </div>
        @endforeach
    </section>
@endif

@if($extraCurriculars->isNotEmpty())
    <section class="section"><h2 class="section-title">Extracurricular Activities</h2><div class="section-copy">@foreach($extraCurriculars as $activity)<p class="pre-line">{{ $plainText($activity->description) }}</p>@endforeach</div></section>
@endif

@if($languages->isNotEmpty())
    <section class="section">
        <h2 class="section-title">Language Proficiency</h2>
        <table class="data-table language-table"><thead><tr><th>Language</th><th>Reading</th><th>Writing</th><th>Speaking</th></tr></thead><tbody>
            @foreach($languages as $language)<tr><td>{{ $language->language }}</td><td>{{ $language->reading_level ?: $language->proficiency_level }}</td><td>{{ $language->writing_level ?: $language->proficiency_level }}</td><td>{{ $language->speaking_level ?: $language->proficiency_level }}</td></tr>@endforeach
        </tbody></table>
    </section>
@endif

@if($personalRows->isNotEmpty())
    <section class="section">
        <h2 class="section-title">Personal Details</h2>
        <table class="detail-table personal-table">@foreach($personalRows as $label => $value)<tr><td class="detail-label">{{ $label }}</td><td class="detail-colon">:</td><td>{{ $value }}</td></tr>@endforeach</table>
    </section>
@endif

@if($references->isNotEmpty())
    <section class="section">
        <h2 class="section-title">Reference</h2>
        <table class="reference-table"><tr>
            @foreach($references->take(2) as $reference)
                <td class="reference-card"><table>
                    @foreach(collect(['Name' => $reference->name, 'Organization' => $reference->organization, 'Designation' => $reference->designation, 'Address' => $reference->address, 'Mobile' => $reference->mobile, 'Email' => $reference->email, 'Relation' => $reference->relation])->filter() as $label => $value)
                        <tr><td class="reference-label">{{ $label }}</td><td class="reference-colon">:</td><td>{{ $value }}</td></tr>
                    @endforeach
                </table></td>
            @endforeach
            @if($references->count() === 1)<td class="reference-card"></td>@endif
        </tr></table>
    </section>
@endif

</body>
</html>
