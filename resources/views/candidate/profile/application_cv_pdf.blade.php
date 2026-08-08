<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; }
        * { box-sizing: border-box; }
        body { color: #263548; font-family: DejaVu Sans, sans-serif; font-size: 9.5px; line-height: 1.55; margin: 0; }
        h1, h2, h3, p { margin: 0; }
        .header { background: #103a5d; color: #fff; padding: 32px 42px 25px; }
        .header-table, .content-table, .entry-table, .reference-table { border-collapse: collapse; width: 100%; }
        .header-main { vertical-align: top; width: 63%; }
        .header-contact { border-left: 1px solid rgba(255, 255, 255, .3); padding-left: 22px; vertical-align: top; width: 37%; }
        .name { font-size: 27px; font-weight: 700; letter-spacing: .2px; line-height: 1.15; }
        .headline { color: #bfe5f5; font-size: 11px; margin-top: 7px; }
        .contact-line { margin-bottom: 5px; word-break: break-word; }
        .contact-label { color: #8fcce5; display: inline-block; font-size: 8px; font-weight: 700; text-transform: uppercase; width: 48px; }
        .body { padding: 25px 42px 34px; }
        .summary { background: #eef7fb; border-left: 4px solid #1688b8; border-radius: 3px; color: #34495e; margin-bottom: 20px; padding: 12px 15px; white-space: pre-line; }
        .section { margin-top: 18px; }
        .section-title { color: #103a5d; font-size: 12px; font-weight: 700; letter-spacing: .8px; margin-bottom: 9px; padding-bottom: 5px; position: relative; text-transform: uppercase; }
        .section-title-line { border-top: 1px solid #b9d6e3; display: block; margin-top: 4px; }
        .entry { margin-bottom: 12px; page-break-inside: avoid; }
        .entry-period { color: #1688b8; font-size: 8.5px; font-weight: 700; padding-right: 14px; text-transform: uppercase; vertical-align: top; width: 105px; }
        .entry-content { border-left: 2px solid #d7e8ef; padding-left: 14px; vertical-align: top; }
        .entry-title { color: #172b3f; font-size: 11px; font-weight: 700; line-height: 1.35; }
        .entry-subtitle { color: #5d7184; font-size: 9px; margin-top: 2px; }
        .entry-description { color: #425466; margin-top: 5px; white-space: pre-line; }
        .detail-grid { border-collapse: separate; border-spacing: 8px 7px; margin: -7px -8px 0; width: calc(100% + 16px); }
        .detail-card { background: #f7fafc; border: 1px solid #dce8ee; border-radius: 4px; padding: 9px 11px; vertical-align: top; width: 50%; }
        .detail-card-title { color: #183b56; font-size: 10px; font-weight: 700; }
        .detail-card-meta { color: #60758a; font-size: 8.5px; margin-top: 3px; }
        .skill-pill { background: #eaf4f8; border: 1px solid #c5e0eb; border-radius: 10px; color: #126f98; display: inline-block; margin: 0 5px 6px 0; padding: 3px 9px; }
        .link-row { margin-bottom: 4px; }
        .link-label { color: #183b56; display: inline-block; font-weight: 700; width: 90px; }
        .link-value { color: #1688b8; text-decoration: none; }
        .reference-table { border-spacing: 8px 0; margin: 0 -8px; width: calc(100% + 16px); }
        .reference-card { background: #f7fafc; border-top: 2px solid #1688b8; padding: 9px 11px; vertical-align: top; width: 50%; }
        .footer { bottom: 12px; color: #8a9baa; font-size: 7.5px; left: 42px; position: fixed; right: 42px; text-align: right; }
        .footer-page:after { content: counter(page); }
    </style>
</head>
<body>
    @php
        $summary = $plainText($candidate->career_summary ?: $candidate->objective);
        $headline = $plainText($candidate->special_qualification ?: $candidate->career_summary);
    @endphp

    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-main">
                    <h1 class="name">{{ $user->full_name }}</h1>
                    @if($headline)<p class="headline">{{ $headline }}</p>@endif
                </td>
                <td class="header-contact">
                    <p class="contact-line"><span class="contact-label">Email</span>{{ $user->email }}</p>
                    @if($user->phone)<p class="contact-line"><span class="contact-label">Phone</span>{{ $user->phone }}</p>@endif
                    @if($candidate->full_location)<p class="contact-line"><span class="contact-label">Address</span>{{ $candidate->full_location }}</p>@endif
                </td>
            </tr>
        </table>
    </div>

    <div class="body">
        @if($summary)
            <div class="summary">{{ $summary }}</div>
        @endif

        @if($skills->isNotEmpty())
            <div class="section">
                <h2 class="section-title">Core Skills<span class="section-title-line"></span></h2>
                <div>
                    @foreach($skills as $candidateSkill)
                        @if($candidateSkill->skill)<span class="skill-pill">{{ $candidateSkill->skill->name }}</span>@endif
                    @endforeach
                </div>
            </div>
        @endif

        @if($experiences->isNotEmpty())
            <div class="section">
                <h2 class="section-title">Employment Experience<span class="section-title-line"></span></h2>
                @foreach($experiences as $experience)
                    <div class="entry">
                        <table class="entry-table">
                            <tr>
                                <td class="entry-period">
                                    {{ optional($experience->start_date)->format('M Y') }}<br>
                                    {{ $experience->currently_working ? 'Present' : optional($experience->end_date)->format('M Y') }}
                                </td>
                                <td class="entry-content">
                                    <h3 class="entry-title">{{ $experience->experience_title }}</h3>
                                    <p class="entry-subtitle">{{ $experience->company }}@if($experience->company_location) · {{ $experience->company_location }}@endif</p>
                                    @if($plainText($experience->description))
                                        <p class="entry-description">{{ $plainText($experience->description) }}</p>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                @endforeach
            </div>
        @endif

        @if($educations->isNotEmpty())
            <div class="section">
                <h2 class="section-title">Education<span class="section-title-line"></span></h2>
                <table class="detail-grid">
                    @foreach($educations->chunk(2) as $educationRow)
                        <tr>
                            @foreach($educationRow as $education)
                                <td class="detail-card">
                                    <h3 class="detail-card-title">{{ $education->degree_title ?: optional($education->degreeLevel)->name }}</h3>
                                    <p class="detail-card-meta">{{ $education->institute }}</p>
                                    <p class="detail-card-meta">
                                        @if($education->year){{ $education->year }}@endif
                                        @if($education->major) · {{ $education->major }}@endif
                                        @if($education->result) · {{ $education->result }}@endif
                                    </p>
                                    @if($plainText($education->achievement))<p class="entry-description">{{ $plainText($education->achievement) }}</p>@endif
                                </td>
                            @endforeach
                            @if($educationRow->count() === 1)<td style="width: 50%"></td>@endif
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif

        @if($trainings->isNotEmpty())
            <div class="section">
                <h2 class="section-title">Training &amp; Certifications<span class="section-title-line"></span></h2>
                @foreach($trainings as $training)
                    <div class="entry">
                        <table class="entry-table">
                            <tr>
                                <td class="entry-period">{{ $training->year }}<br>{{ $training->duration }}</td>
                                <td class="entry-content">
                                    <h3 class="entry-title">{{ $training->title }}</h3>
                                    <p class="entry-subtitle">{{ $training->institute }}@if($training->location) · {{ $training->location }}@endif</p>
                                    @if($plainText($training->topics))<p class="entry-description">{{ $plainText($training->topics) }}</p>@endif
                                </td>
                            </tr>
                        </table>
                    </div>
                @endforeach
            </div>
        @endif

        @if($accomplishments->isNotEmpty())
            <div class="section">
                <h2 class="section-title">Accomplishments<span class="section-title-line"></span></h2>
                <table class="detail-grid">
                    @foreach($accomplishments->chunk(2) as $accomplishmentRow)
                        <tr>
                            @foreach($accomplishmentRow as $accomplishment)
                                <td class="detail-card">
                                    <h3 class="detail-card-title">{{ $accomplishment->title }}</h3>
                                    <p class="detail-card-meta">{{ ucfirst($accomplishment->type) }}@if($accomplishment->issued_on) · {{ $accomplishment->issued_on->format('M Y') }}@endif</p>
                                    @if($plainText($accomplishment->description))<p class="entry-description">{{ $plainText($accomplishment->description) }}</p>@endif
                                    @if($accomplishment->url)<p><a class="link-value" href="{{ $accomplishment->url }}">{{ $accomplishment->url }}</a></p>@endif
                                </td>
                            @endforeach
                            @if($accomplishmentRow->count() === 1)<td style="width: 50%"></td>@endif
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif

        @if($links->isNotEmpty())
            <div class="section">
                <h2 class="section-title">Online Profiles<span class="section-title-line"></span></h2>
                @foreach($links as $link)
                    <p class="link-row"><span class="link-label">{{ $link->platform }}</span><a class="link-value" href="{{ $link->url }}">{{ $link->url }}</a></p>
                @endforeach
            </div>
        @endif

        @if($extraCurriculars->isNotEmpty())
            <div class="section">
                <h2 class="section-title">Extracurricular Activities<span class="section-title-line"></span></h2>
                @foreach($extraCurriculars as $activity)
                    @if($plainText($activity->description))<p class="entry-description">{{ $plainText($activity->description) }}</p>@endif
                @endforeach
            </div>
        @endif

        @if($references->isNotEmpty())
            <div class="section">
                <h2 class="section-title">References<span class="section-title-line"></span></h2>
                <table class="reference-table">
                    @foreach($references->chunk(2) as $referenceRow)
                        <tr>
                            @foreach($referenceRow as $reference)
                                <td class="reference-card">
                                    <h3 class="detail-card-title">{{ $reference->name }}</h3>
                                    <p>{{ $reference->designation }}@if($reference->organization), {{ $reference->organization }}@endif</p>
                                    @if($reference->email)<p>{{ $reference->email }}</p>@endif
                                    @if($reference->mobile)<p>{{ $reference->mobile }}</p>@endif
                                </td>
                            @endforeach
                            @if($referenceRow->count() === 1)<td style="width: 50%"></td>@endif
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif
    </div>

    <div class="footer">Generated from the candidate's latest Tap2Jobs profile · Page <span class="footer-page"></span></div>
</body>
</html>
