@extends('candidate.profile.index')
@section('section')
    @php
        $candidateSkillItems = $data['candidateSkillRows'] ?? collect();
        $candidateSkillNames = $candidateSkillItems->pluck('skill.name')->filter()->toArray();
        $candidateLanguageItems = $data['candidateLanguageItems'] ?? $user->candidateLanguage;
        $candidateLanguageNames = $candidateLanguageItems->pluck('language')->toArray();
        $candidateExtraCurricularItems = $data['candidateExtraCurriculars'] ?? collect();
        $candidateReferenceItems = $data['candidateReferences'] ?? collect();
        $skillLearnOptions = ['Self', 'Job', 'Educational', 'Professional Training', 'NTVQF'];
        $candidateLinkAccounts = $data['candidateLinks'] ?? collect();
        $candidateLinkIcons = [
            'Facebook' => 'fa-brands fa-facebook',
            'GitHub' => 'fa-brands fa-github',
            'LinkedIn' => 'fa-brands fa-linkedin',
            'Twitter' => 'fa-brands fa-twitter',
            'Website' => 'fa-solid fa-globe',
        ];
    @endphp

    <div class="mb-xl-8 candidate-other-info-page">
        <div class="candidate-education-panel" id="candidateSkillInformation">
            <div class="candidate-education-panel__header">
                <h1>{{ __('messages.candidate_profile.skill') }}</h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-add" data-skill-add-action>
                        <i class="fa-solid fa-plus"></i> {{ __('messages.candidate_profile.add_skill') }}
                    </button>
                    <button type="button" class="candidate-education-collapse" data-bs-toggle="collapse"
                            data-bs-target="#candidateSkillInformationPanelBody" aria-expanded="true"
                            aria-controls="candidateSkillInformationPanelBody"
                            data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                            data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.collapse') }}</span>
                        <i class="fa-solid fa-chevron-up"></i>
                    </button>
                </div>
            </div>
            <div id="candidateSkillInformationPanelBody" class="collapse show candidate-profile-section__collapse">
                <div class="candidate-profile-section__body candidate-education-panel__body">
                    <div class="candidate-skill-manager"
                         data-update-url="{{ route('candidate.general.profile.update') }}"
                         data-first-name="{{ $user->first_name }}"
                         data-last-name="{{ $user->last_name }}">
                        <div class="candidate-skill-list" data-skill-list>
                            @forelse($candidateSkillItems as $candidateSkill)
                                @php
                                    $skill = $candidateSkill->skill;
                                    $sources = $candidateSkill->relationLoaded('sources')
                                        ? $candidateSkill->sources->pluck('source')->filter()->values()
                                        : collect();
                                    $sourceText = $sources->count() ? $sources->implode(', ') : 'Professional Training';
                                @endphp
                                @continue(! $skill)
                                <div class="candidate-skill-item" data-skill-item data-skill-id="{{ $skill->id }}"
                                     data-skill-name="{{ $skill->name }}" data-skill-sources="{{ $sourceText }}">
                                    <div>
                                        <strong>{{ $skill->name }}</strong>
                                        <span>{{ $sourceText }}</span>
                                    </div>
                                    <div class="candidate-skill-item__actions">
                                        <button type="button" data-skill-edit>
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <button type="button" data-skill-delete>
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <p class="candidate-skill-empty" data-skill-empty>---</p>
                            @endforelse
                        </div>

                        <form class="candidate-skill-form d-none" data-skill-form>
                            @csrf
                            <input type="hidden" name="first_name" value="{{ $user->first_name }}">
                            <input type="hidden" name="last_name" value="{{ $user->last_name }}">
                            <input type="hidden" data-skill-editing-id>

                            <div class="candidate-skill-form__field">
                                <label for="candidateSkillName">Skill <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="candidateSkillName" data-skill-name-input
                                       placeholder="Enter your skill">
                            </div>

                            <div class="candidate-skill-form__field">
                                <label>How did you learn the skill?</label>
                                <div class="candidate-skill-source-list">
                                    @foreach($skillLearnOptions as $source)
                                        <label class="candidate-skill-source">
                                            <input type="checkbox" value="{{ $source }}" data-skill-source
                                                   {{ $source === 'Professional Training' ? 'checked' : '' }}>
                                            <span>{{ $source }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="candidate-skill-form__actions">
                                <button type="submit" class="candidate-skill-save">Save Changes</button>
                                <button type="button" class="candidate-skill-close" data-skill-close>Close</button>
                            </div>
                        </form>

                        @if(count($data['skills'] ?? []))
                            <div class="d-none" data-skill-options>
                                @foreach($data['skills'] as $skillId => $skillName)
                                    <span data-skill-option data-id="{{ $skillId }}" data-name="{{ $skillName }}"></span>
                                @endforeach
                            </div>
                        @else
                            <div class="d-none" data-skill-options></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel" id="candidateExtracurricularActivities">
            <div class="candidate-education-panel__header collapsed">
                <h1>{{ __('messages.candidate_profile.extracurricular_activities') }}</h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-add d-none" data-activity-add-action>
                        <i class="fa-solid fa-plus"></i> {{ __('messages.candidate_profile.add_extracurricular_activities') }}
                    </button>
                    <button type="button" class="candidate-education-collapse" data-bs-toggle="collapse"
                            data-bs-target="#candidateExtracurricularActivitiesPanelBody" aria-expanded="false"
                            aria-controls="candidateExtracurricularActivitiesPanelBody"
                            data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                            data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.expand') }}</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
            </div>
            <div id="candidateExtracurricularActivitiesPanelBody" class="collapse candidate-profile-section__collapse">
                <div class="candidate-profile-section__body candidate-education-panel__body">
                    <div class="candidate-activity-summary" data-activity-summary>
                        <div class="candidate-activity-list {{ $candidateExtraCurricularItems->count() ? '' : 'd-none' }}" data-activity-list>
                            @foreach($candidateExtraCurricularItems as $extraCurricular)
                                @php
                                    $activityDescription = strip_tags((string) $extraCurricular->description, '<p><br><strong><b><em><i><ul><ol><li>');
                                @endphp
                                <div class="candidate-activity-item" data-activity-item
                                     data-activity-id="{{ $extraCurricular->id }}"
                                     data-update-url="{{ route('candidate-profile.extracurricular-activities.update', $extraCurricular) }}"
                                     data-delete-url="{{ route('candidate-profile.extracurricular-activities.destroy', $extraCurricular) }}">
                                    <div class="candidate-activity-item__header">
                                        <h2>Extracurricular Activities {{ $loop->iteration }}</h2>
                                        <div class="candidate-reference-actions">
                                            <button type="button" data-activity-edit>
                                                <i class="fa-regular fa-pen-to-square"></i> Edit
                                            </button>
                                            <button type="button" data-activity-delete>
                                                <i class="fa-regular fa-trash-can"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                    <div class="candidate-activity-summary__content" data-activity-summary-content>{!! $activityDescription ?: '---' !!}</div>
                                </div>
                            @endforeach
                        </div>
                        <p class="candidate-skill-empty candidate-activity-empty {{ $candidateExtraCurricularItems->count() ? 'd-none' : '' }}" data-activity-empty>---</p>
                    </div>
                    <form class="candidate-activity-form d-none" data-activity-form
                          data-store-url="{{ route('candidate-profile.extracurricular-activities.store') }}">
                        @csrf
                        <input type="hidden" data-activity-editing-id>
                        <h2 class="candidate-language-form__title" data-activity-form-title>Extracurricular Activities {{ max($candidateExtraCurricularItems->count() + 1, 1) }}</h2>
                        <div class="candidate-activity-editor">
                            <textarea class="d-none" name="description" data-activity-quill-input></textarea>
                            <div class="candidate-activity-quill" data-activity-quill-editor
                                 data-placeholder="Enter your writing texts..."></div>
                        </div>
                        <p class="candidate-activity-count">
                            You wrote <strong data-activity-character-count>0/500</strong> characters
                        </p>
                        <div class="candidate-skill-form__actions candidate-activity-form__actions mt-5">
                            <button type="submit" class="candidate-skill-save">Save</button>
                            <button type="button" class="candidate-skill-close" data-activity-close>Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel" id="candidateLanguageProficiency">
            <div class="candidate-education-panel__header collapsed">
                <h1>{{ __('messages.candidate_profile.language_proficiency') }}</h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-add d-none" data-language-edit-action>
                        <i class="fa-solid fa-plus"></i>
                        {{ __('messages.candidate_profile.add_language') }}
                    </button>
                    <button type="button" class="candidate-education-collapse" data-bs-toggle="collapse"
                            data-bs-target="#candidateLanguageProficiencyPanelBody" aria-expanded="false"
                            aria-controls="candidateLanguageProficiencyPanelBody"
                            data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                            data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.expand') }}</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
            </div>
            <div id="candidateLanguageProficiencyPanelBody" class="collapse candidate-profile-section__collapse">
                <div class="candidate-profile-section__body candidate-education-panel__body">
                    <div class="candidate-other-summary" data-language-summary>
                        <div class="candidate-language-list {{ count($candidateLanguageNames) ? '' : 'd-none' }}" data-language-chip-list>
                            @foreach($candidateLanguageItems as $language)
                                @php
                                    $baseLanguageLevel = data_get($language, 'proficiency_level', data_get($language, 'pivot.proficiency_level', ''));
                                    $readingLevel = data_get($language, 'reading_level') ?: $baseLanguageLevel;
                                    $writingLevel = data_get($language, 'writing_level') ?: $baseLanguageLevel;
                                    $speakingLevel = data_get($language, 'speaking_level') ?: $baseLanguageLevel;
                                    $languageName = data_get($language, 'language');
                                @endphp
                                <div class="candidate-language-item" data-language-chip
                                     data-language-id="{{ $language->id }}"
                                     data-language-name="{{ $languageName }}"
                                     data-language-reading="{{ $readingLevel }}"
                                     data-language-writing="{{ $writingLevel }}"
                                     data-language-speaking="{{ $speakingLevel }}">
                                    <div class="candidate-language-item__header">
                                        <h2>Language {{ $loop->iteration }}</h2>
                                        <div class="candidate-reference-actions">
                                            <button type="button" data-language-item-edit>
                                                <i class="fa-regular fa-pen-to-square"></i> Edit
                                            </button>
                                            <button type="button" data-language-item-delete>
                                                <i class="fa-regular fa-trash-can"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                    <div class="candidate-language-detail-grid">
                                        <div class="candidate-reference-field">
                                            <span>Language</span>
                                            <strong data-language-detail="name">{{ $languageName }}</strong>
                                        </div>
                                        <div class="candidate-reference-field">
                                            <span>Reading</span>
                                            <strong data-language-detail="reading">{{ $readingLevel ?: '---' }}</strong>
                                        </div>
                                        <div class="candidate-reference-field">
                                            <span>Writing</span>
                                            <strong data-language-detail="writing">{{ $writingLevel ?: '---' }}</strong>
                                        </div>
                                        <div class="candidate-reference-field">
                                            <span>Speaking</span>
                                            <strong data-language-detail="speaking">{{ $speakingLevel ?: '---' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{-- <button type="button" class="candidate-language-inline-add {{ count($candidateLanguageNames) ? '' : 'd-none' }}" data-language-inline-add>
                            <i class="fa-solid fa-plus"></i> Add Language
                        </button> --}}
                        <p class="candidate-skill-empty candidate-language-empty {{ count($candidateLanguageNames) ? 'd-none' : '' }}" data-language-empty>---</p>
                    </div>

                    <form class="candidate-language-form d-none" data-language-form
                          data-update-url="{{ route('candidate.general.profile.update') }}"
                          data-first-name="{{ $user->first_name }}"
                          data-last-name="{{ $user->last_name }}">
                        <h2 class="candidate-language-form__title" data-language-form-title>Language {{ max(count($candidateLanguageNames) + 1, 1) }}</h2>
                        <div class="candidate-language-form-grid">
                            <div class="candidate-skill-form__field">
                                <label for="candidateLanguageName">Language <span class="text-danger">*</span></label>
                                <select class="form-control" id="candidateLanguageName" data-language-name-input>
                                    <option value="">Select your Language</option>
                                    @foreach($data['language'] ?? [] as $languageId => $languageName)
                                        <option value="{{ $languageName }}" data-language-id="{{ $languageId }}">{{ $languageName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidateLanguageReading">Reading <span class="text-danger">*</span></label>
                                <select class="form-control" id="candidateLanguageReading" data-language-reading-input>
                                    <option value="">Select your Reading</option>
                                    <option value="High">High</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Low">Low</option>
                                </select>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidateLanguageWriting">Writing <span class="text-danger">*</span></label>
                                <select class="form-control" id="candidateLanguageWriting" data-language-writing-input>
                                    <option value="">Select your Writing</option>
                                    <option value="High">High</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Low">Low</option>
                                </select>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidateLanguageSpeaking">Speaking <span class="text-danger">*</span></label>
                                <select class="form-control" id="candidateLanguageSpeaking" data-language-speaking-input>
                                    <option value="">Select your Speaking</option>
                                    <option value="High">High</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Low">Low</option>
                                </select>
                            </div>
                        </div>

                        <div class="candidate-language-current d-none" data-language-current-list>
                            @foreach($candidateLanguageItems as $language)
                                @php
                                    $baseLanguageLevel = data_get($language, 'proficiency_level', data_get($language, 'pivot.proficiency_level', ''));
                                    $readingLevel = data_get($language, 'reading_level') ?: $baseLanguageLevel;
                                    $writingLevel = data_get($language, 'writing_level') ?: $baseLanguageLevel;
                                    $speakingLevel = data_get($language, 'speaking_level') ?: $baseLanguageLevel;
                                    $languageName = data_get($language, 'language');
                                @endphp
                                <span class="candidate-preferred-chip" data-language-current
                                      data-language-id="{{ $language->id }}"
                                      data-language-name="{{ $languageName }}"
                                      data-language-reading="{{ $readingLevel }}"
                                      data-language-writing="{{ $writingLevel }}"
                                      data-language-speaking="{{ $speakingLevel }}">
                                    <span>{{ $languageName }}</span>
                                    <button type="button" data-language-remove aria-label="Remove language">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </span>
                            @endforeach
                        </div>

                        <div class="candidate-skill-form__actions">
                            <button type="submit" class="candidate-skill-save">Save Changes</button>
                            <button type="button" class="candidate-skill-close" data-language-close>Close</button>
                        </div>

                        @if(count($data['language'] ?? []))
                            <div class="d-none" data-language-options>
                                @foreach($data['language'] as $languageId => $languageName)
                                    <span data-language-option data-id="{{ $languageId }}" data-name="{{ $languageName }}"></span>
                                @endforeach
                            </div>
                        @else
                            <div class="d-none" data-language-options></div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel" id="candidateLinkAccount">
            <div class="candidate-education-panel__header collapsed">
                <h1>{{ __('messages.candidate_profile.link_account') }}</h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-add d-none" data-link-add-action>
                        <i class="fa-solid fa-plus"></i>
                        {{ __('messages.candidate_profile.add_link_account') }}
                    </button>
                    <button type="button" class="candidate-education-collapse" data-bs-toggle="collapse"
                            data-bs-target="#candidateLinkAccountPanelBody" aria-expanded="false"
                            aria-controls="candidateLinkAccountPanelBody"
                            data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                            data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.expand') }}</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
            </div>
            <div id="candidateLinkAccountPanelBody" class="collapse candidate-profile-section__collapse">
                <div class="candidate-profile-section__body candidate-education-panel__body">
                    <div class="candidate-link-manager">
                        <div class="candidate-link-list" data-link-list>
                            @forelse($candidateLinkAccounts as $linkAccount)
                                <div class="candidate-link-item" data-link-item
                                     data-link-id="{{ $linkAccount->id }}"
                                     data-link-platform="{{ $linkAccount->platform }}"
                                     data-link-url="{{ $linkAccount->url }}"
                                     data-update-url="{{ route('candidate-profile.links.update', $linkAccount) }}"
                                     data-delete-url="{{ route('candidate-profile.links.destroy', $linkAccount) }}">
                                    <span class="candidate-link-platform">
                                        <i class="{{ $candidateLinkIcons[$linkAccount->platform] ?? $candidateLinkIcons['Website'] }}"></i>
                                        {{ $linkAccount->platform }}
                                    </span>
                                    <a href="{{ addLinkHttpUrl($linkAccount->url) }}" target="_blank" data-link-url-text>
                                        {{ $linkAccount->url }}
                                    </a>
                                    <span class="candidate-link-actions">
                                        <button type="button" data-link-edit>
                                            <i class="fa-regular fa-pen-to-square"></i> Edit
                                        </button>
                                        <button type="button" data-link-delete>
                                            <i class="fa-regular fa-trash-can"></i> Delete
                                        </button>
                                    </span>
                                </div>
                            @empty
                                <p class="candidate-skill-empty" data-link-empty>---</p>
                            @endforelse
                        </div>

                        <form class="candidate-link-form d-none" data-link-form
                              data-store-url="{{ route('candidate-profile.links.store') }}">
                            @csrf
                            <input type="hidden" data-link-editing-id>
                            <div class="candidate-link-form-grid">
                                <div class="candidate-skill-form__field">
                                    <label for="candidateLinkPlatform">Account Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="candidateLinkPlatform" name="platform" data-link-platform-input>
                                        <option value="">Select account type</option>
                                        <option value="Facebook">Facebook</option>
                                        <option value="GitHub">GitHub</option>
                                        <option value="LinkedIn">LinkedIn</option>
                                        <option value="Twitter">Twitter</option>
                                        <option value="Website">Website</option>
                                    </select>
                                </div>
                                <div class="candidate-skill-form__field">
                                    <label for="candidateLinkUrl">URL <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control" id="candidateLinkUrl" name="url"
                                           data-link-url-input placeholder="https://example.com/profile">
                                </div>
                            </div>
                            <div class="candidate-skill-form__actions">
                                <button type="submit" class="candidate-skill-save" data-link-submit>Save</button>
                                <button type="button" class="candidate-skill-close" data-link-close>Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel" id="candidateReference">
            <div class="candidate-education-panel__header collapsed">
                <h1>{{ __('messages.candidate_profile.reference') }}</h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-add d-none" data-reference-add-action>
                        <i class="fa-solid fa-plus"></i>
                        {{ __('messages.candidate_profile.add_reference') }}
                    </button>
                    <button type="button" class="candidate-education-collapse" data-bs-toggle="collapse"
                            data-bs-target="#candidateReferencePanelBody" aria-expanded="false"
                            aria-controls="candidateReferencePanelBody"
                            data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                            data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.expand') }}</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
            </div>
            <div id="candidateReferencePanelBody" class="collapse candidate-profile-section__collapse">
                <div class="candidate-profile-section__body candidate-education-panel__body">
                    <div class="candidate-reference-list" data-reference-list>
                        <form class="candidate-reference-form d-none" data-reference-form
                              data-store-url="{{ route('candidate-profile.references.store') }}">
                            @csrf
                            <input type="hidden" data-reference-editing-id>
                            <div class="candidate-reference-item__header">
                                <h2 data-reference-form-title>Reference</h2>
                            </div>
                            <div class="candidate-reference-form-grid">
                                <div class="candidate-skill-form__field">
                                    <label for="candidateReferenceName">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="candidateReferenceName"
                                           name="name" data-reference-field-input="name" required>
                                </div>
                                <div class="candidate-skill-form__field">
                                    <label for="candidateReferenceDesignation">Designation <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="candidateReferenceDesignation"
                                           name="designation" data-reference-field-input="designation" required>
                                </div>
                                <div class="candidate-skill-form__field">
                                    <label for="candidateReferenceOrganization">Organization <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="candidateReferenceOrganization"
                                           name="organization" data-reference-field-input="organization" required>
                                </div>
                                <div class="candidate-skill-form__field">
                                    <label for="candidateReferenceEmail">Email</label>
                                    <input type="email" class="form-control" id="candidateReferenceEmail"
                                           name="email" data-reference-field-input="email">
                                </div>
                                <div class="candidate-skill-form__field">
                                    <label for="candidateReferenceRelation">Relation</label>
                                    <select class="form-control" id="candidateReferenceRelation" name="relation" data-reference-field-input="relation">
                                        <option value="">Select relation</option>
                                        <option value="Relative">Relative</option>
                                        <option value="Academic">Academic</option>
                                        <option value="Professional">Professional</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="candidate-skill-form__field">
                                    <label for="candidateReferenceMobile">Mobile</label>
                                    <input type="text" class="form-control" id="candidateReferenceMobile"
                                           name="mobile" data-reference-field-input="mobile">
                                </div>
                                <div class="candidate-skill-form__field">
                                    <label for="candidateReferenceOfficePhone">Phone (Office)</label>
                                    <input type="text" class="form-control" id="candidateReferenceOfficePhone"
                                           name="office_phone" data-reference-field-input="officePhone" placeholder="Enter your Phone (Office)">
                                </div>
                                <div class="candidate-skill-form__field">
                                    <label for="candidateReferenceResidentialPhone">Phone (Residential)</label>
                                    <input type="text" class="form-control" id="candidateReferenceResidentialPhone"
                                           name="residential_phone" data-reference-field-input="residentialPhone" placeholder="Enter your Phone (Residential)">
                                </div>
                                <div class="candidate-skill-form__field candidate-reference-form-field--full">
                                    <label for="candidateReferenceAddress">Address</label>
                                    <textarea class="form-control" id="candidateReferenceAddress" rows="4"
                                              name="address" data-reference-field-input="address"></textarea>
                                </div>
                            </div>
                            <div class="candidate-skill-form__actions">
                                <button type="submit" class="candidate-skill-save" data-reference-submit>Save</button>
                                <button type="button" class="candidate-skill-close" data-reference-close>Close</button>
                            </div>
                        </form>

                        @forelse($candidateReferenceItems as $reference)
                            <div class="candidate-reference-item" data-reference-item
                                 data-reference-id="{{ $reference->id }}"
                                 data-reference-name="{{ $reference->name }}"
                                 data-reference-designation="{{ $reference->designation }}"
                                 data-reference-organization="{{ $reference->organization }}"
                                 data-reference-email="{{ $reference->email ?: '---' }}"
                                 data-reference-relation="{{ $reference->relation ?: '---' }}"
                                 data-reference-mobile="{{ $reference->mobile ?: '---' }}"
                                 data-reference-office-phone="{{ $reference->office_phone ?: '---' }}"
                                 data-reference-residential-phone="{{ $reference->residential_phone ?: '---' }}"
                                 data-reference-address="{{ $reference->address ?: '---' }}"
                                 data-update-url="{{ route('candidate-profile.references.update', $reference) }}"
                                 data-delete-url="{{ route('candidate-profile.references.destroy', $reference) }}">
                                <div class="candidate-reference-item__header">
                                    <h2>Reference {{ $loop->iteration }}</h2>
                                    <div class="candidate-reference-actions">
                                        <button type="button" data-reference-edit>
                                            <i class="fa-regular fa-pen-to-square"></i>
                                            Edit
                                        </button>
                                        <button type="button" data-reference-delete>
                                            <i class="fa-regular fa-trash-can"></i>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                                <div class="candidate-reference-detail-grid">
                                    <div class="candidate-reference-field">
                                        <span>Name</span>
                                        <strong data-reference-value="name">{{ $reference->name }}</strong>
                                    </div>
                                    <div class="candidate-reference-field">
                                        <span>Designation</span>
                                        <strong data-reference-value="designation">{{ $reference->designation }}</strong>
                                    </div>
                                    <div class="candidate-reference-field">
                                        <span>Organization</span>
                                        <strong data-reference-value="organization">{{ $reference->organization }}</strong>
                                    </div>
                                    <div class="candidate-reference-field">
                                        <span>Email</span>
                                        <strong data-reference-value="email">{{ $reference->email ?: '---' }}</strong>
                                    </div>
                                    <div class="candidate-reference-field">
                                        <span>Relation</span>
                                        <strong data-reference-value="relation">{{ $reference->relation ?: '---' }}</strong>
                                    </div>
                                    <div class="candidate-reference-field">
                                        <span>Mobile</span>
                                        <strong data-reference-value="mobile">{{ $reference->mobile ?: '---' }}</strong>
                                    </div>
                                    <div class="candidate-reference-field">
                                        <span>Phone (Office)</span>
                                        <strong data-reference-value="officePhone">{{ $reference->office_phone ?: '---' }}</strong>
                                    </div>
                                    <div class="candidate-reference-field">
                                        <span>Phone (Residential)</span>
                                        <strong data-reference-value="residentialPhone">{{ $reference->residential_phone ?: '---' }}</strong>
                                    </div>
                                    <div class="candidate-reference-field candidate-reference-field--full">
                                        <span>Address</span>
                                        <strong data-reference-value="address">{{ $reference->address ?: '---' }}</strong>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="candidate-skill-empty candidate-reference-empty" data-reference-empty>---</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const otherSectionLinks = document.querySelectorAll('[data-other-section-link]');
            const otherSectionBodies = document.querySelectorAll('.candidate-other-info-page .candidate-profile-section__collapse');
            const skillManager = document.querySelector('.candidate-skill-manager');
            const activityForm = document.querySelector('[data-activity-form]');
            const languageForm = document.querySelector('[data-language-form]');
            const linkManager = document.querySelector('.candidate-link-manager');

            if (linkManager) {
                const linkList = linkManager.querySelector('[data-link-list]');
                const linkForm = linkManager.querySelector('[data-link-form]');
                const linkPlatformInput = linkManager.querySelector('[data-link-platform-input]');
                const linkUrlInput = linkManager.querySelector('[data-link-url-input]');
                const linkEditingId = linkManager.querySelector('[data-link-editing-id]');
                const linkSubmit = linkManager.querySelector('[data-link-submit]');
                const linkClose = linkManager.querySelector('[data-link-close]');
                const linkAddAction = document.querySelector('[data-link-add-action]');
                const linkToken = linkForm.querySelector('input[name="_token"]')?.value || '';
                const maxLinks = 5;
                let activeLinkItem = null;
                const linkFormHome = document.createElement('div');
                linkForm.after(linkFormHome);
                const platformIcons = {
                    Facebook: 'fa-brands fa-facebook',
                    GitHub: 'fa-brands fa-github',
                    LinkedIn: 'fa-brands fa-linkedin',
                    Twitter: 'fa-brands fa-twitter',
                    Website: 'fa-solid fa-globe',
                };

                const linkItems = function () {
                    return Array.from(linkList.querySelectorAll('[data-link-item]'));
                };

                const refreshLinkAddAction = function () {
                    if (!linkAddAction) {
                        return;
                    }
                    linkAddAction.classList.toggle('d-none', linkItems().length >= maxLinks || linkAddAction.dataset.sectionOpen !== 'true');
                };

                const setLinkFormMode = function (isEditing) {
                    linkForm.classList.toggle('d-none', !isEditing);
                    if (isEditing) {
                        linkPlatformInput.focus();
                    }
                };

                const linkMessage = function (error) {
                    return error && error.message
                        ? error.message
                        : (error && error.errors ? Object.values(error.errors).flat().shift() : null);
                };

                const makeLinkItem = function (link) {
                    const platform = link.platform || '';
                    const url = link.url || '';
                    const item = document.createElement('div');
                    item.className = 'candidate-link-item';
                    item.dataset.linkItem = '';
                    item.dataset.linkId = link.id || '';
                    item.dataset.linkPlatform = platform;
                    item.dataset.linkUrl = url;
                    item.dataset.updateUrl = link.update_url || '';
                    item.dataset.deleteUrl = link.delete_url || '';
                    item.innerHTML = [
                        '<span class="candidate-link-platform"><i></i><span></span></span>',
                        '<a target="_blank" data-link-url-text></a>',
                        '<span class="candidate-link-actions">',
                        '<button type="button" data-link-edit><i class="fa-regular fa-pen-to-square"></i> Edit</button>',
                        '<button type="button" data-link-delete><i class="fa-regular fa-trash-can"></i> Delete</button>',
                        '</span>',
                    ].join('');
                    item.querySelector('.candidate-link-platform i').className = platformIcons[platform] || platformIcons.Website;
                    item.querySelector('.candidate-link-platform span').textContent = platform;
                    item.querySelector('[data-link-url-text]').textContent = url;
                    item.querySelector('[data-link-url-text]').href = /^https?:\/\//i.test(url) ? url : 'https://' + url;
                    return item;
                };

                const updateLinkItem = function (item, link) {
                    const platform = link.platform || '';
                    const url = link.url || '';
                    item.dataset.linkId = link.id || item.dataset.linkId || '';
                    item.dataset.linkPlatform = platform;
                    item.dataset.linkUrl = url;
                    item.dataset.updateUrl = link.update_url || item.dataset.updateUrl || '';
                    item.dataset.deleteUrl = link.delete_url || item.dataset.deleteUrl || '';
                    item.querySelector('.candidate-link-platform i').className = platformIcons[platform] || platformIcons.Website;
                    item.querySelector('.candidate-link-platform span').textContent = platform;
                    item.querySelector('[data-link-url-text]').textContent = url;
                    item.querySelector('[data-link-url-text]').href = /^https?:\/\//i.test(url) ? url : 'https://' + url;
                };

                const resetLinkForm = function () {
                    linkPlatformInput.value = '';
                    linkUrlInput.value = '';
                    if (linkEditingId) {
                        linkEditingId.value = '';
                    }
                    if (linkSubmit) {
                        linkSubmit.textContent = 'Save';
                    }
                    if (linkClose) {
                        linkClose.textContent = 'Close';
                    }
                };

                const closeLinkForm = function () {
                    activeLinkItem?.classList.remove('d-none');
                    activeLinkItem = null;
                    linkForm.classList.remove('candidate-link-form--inline');
                    resetLinkForm();
                    setLinkFormMode(false);
                    linkFormHome.appendChild(linkForm);
                    refreshLinkAddAction();
                };

                linkAddAction?.addEventListener('click', function () {
                    if (linkItems().length >= maxLinks) {
                        refreshLinkAddAction();
                        return;
                    }
                    const section = document.getElementById('candidateLinkAccountPanelBody');
                    if (section && typeof bootstrap !== 'undefined') {
                        bootstrap.Collapse.getOrCreateInstance(section, { toggle: false }).show();
                    }
                    resetLinkForm();
                    activeLinkItem?.classList.remove('d-none');
                    activeLinkItem = null;
                    linkForm.classList.remove('candidate-link-form--inline');
                    linkList.appendChild(linkForm);
                    setLinkFormMode(true);
                });

                linkList.addEventListener('click', function (event) {
                    const editButton = event.target.closest('[data-link-edit]');
                    const deleteButton = event.target.closest('[data-link-delete]');
                    const item = event.target.closest('[data-link-item]');

                    if (editButton && item) {
                        activeLinkItem?.classList.remove('d-none');
                        activeLinkItem = item;
                        if (linkEditingId) {
                            linkEditingId.value = item.dataset.linkId || '';
                        }
                        linkPlatformInput.value = item.dataset.linkPlatform || '';
                        linkUrlInput.value = item.dataset.linkUrl || '';
                        if (linkSubmit) {
                            linkSubmit.textContent = 'Update';
                        }
                        if (linkClose) {
                            linkClose.textContent = 'Cancel';
                        }
                        linkForm.classList.add('candidate-link-form--inline');
                        item.before(linkForm);
                        item.classList.add('d-none');
                        setLinkFormMode(true);
                    }

                    if (deleteButton && item) {
                        if (!window.confirm('Are you sure you want to delete this link account?')) {
                            return;
                        }

                        const formData = new FormData();
                        formData.append('_method', 'DELETE');
                        if (linkToken) {
                            formData.append('_token', linkToken);
                        }

                        fetch(item.dataset.deleteUrl, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        }).then(function (response) {
                            return response.json().then(function (body) {
                                if (!response.ok) {
                                    throw body;
                                }

                                return body;
                            });
                        }).then(function (response) {
                            if (activeLinkItem === item) {
                                closeLinkForm();
                            }
                            item.remove();
                            if (!linkItems().length) {
                                closeLinkForm();
                                linkList.innerHTML = '<p class="candidate-skill-empty" data-link-empty>---</p>';
                            }
                            refreshLinkAddAction();
                            if (response && response.message && typeof displaySuccessMessage === 'function') {
                                displaySuccessMessage(response.message);
                            }
                        }).catch(function (error) {
                            const message = linkMessage(error);
                            if (message && typeof displayErrorMessage === 'function') {
                                displayErrorMessage(message);
                            }
                        });
                    }
                });

                linkClose?.addEventListener('click', closeLinkForm);

                linkForm.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const platform = linkPlatformInput.value;
                    const url = linkUrlInput.value.trim();

                    if (!platform) {
                        linkPlatformInput.focus();
                        return;
                    }

                    if (!url) {
                        linkUrlInput.focus();
                        return;
                    }

                    const formData = new FormData(linkForm);
                    const requestUrl = activeLinkItem ? activeLinkItem.dataset.updateUrl : linkForm.dataset.storeUrl;
                    if (activeLinkItem) {
                        formData.append('_method', 'PUT');
                    }

                    fetch(requestUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    }).then(function (response) {
                        return response.json().then(function (body) {
                            if (!response.ok) {
                                throw body;
                            }

                            return body;
                        });
                    }).then(function (response) {
                        const link = response && response.data ? response.data : {
                            platform: platform,
                            url: url,
                        };

                        if (activeLinkItem) {
                            updateLinkItem(activeLinkItem, link);
                        } else {
                            linkManager.querySelector('[data-link-empty]')?.remove();
                            linkList.appendChild(makeLinkItem(link));
                        }

                        closeLinkForm();
                        if (response && response.message && typeof displaySuccessMessage === 'function') {
                            displaySuccessMessage(response.message);
                        }
                    }).catch(function (error) {
                        const message = linkMessage(error);
                        if (message && typeof displayErrorMessage === 'function') {
                            displayErrorMessage(message);
                        }
                    });
                });

                refreshLinkAddAction();
            }

            const referenceList = document.querySelector('[data-reference-list]');
            const referenceForm = document.querySelector('[data-reference-form]');
            const referenceAddAction = document.querySelector('[data-reference-add-action]');

            if (referenceList && referenceForm) {
                const referenceEditingId = referenceForm.querySelector('[data-reference-editing-id]');
                const referenceInputs = referenceForm.querySelectorAll('[data-reference-field-input]');
                const referenceFormTitle = referenceForm.querySelector('[data-reference-form-title]');
                const referenceSubmit = referenceForm.querySelector('[data-reference-submit]');
                const referenceClose = referenceForm.querySelector('[data-reference-close]');
                const referenceToken = referenceForm.querySelector('input[name="_token"]')?.value || '';
                let activeReferenceItem = null;
                const referenceFormHome = document.createElement('div');
                referenceForm.after(referenceFormHome);

                const referenceItems = function () {
                    return Array.from(referenceList.querySelectorAll('[data-reference-item]'));
                };

                const referenceEmpty = function () {
                    return referenceList.querySelector('[data-reference-empty]');
                };

                const referenceFields = [
                    'name',
                    'designation',
                    'organization',
                    'email',
                    'relation',
                    'mobile',
                    'officePhone',
                    'residentialPhone',
                    'address',
                ];

                const displayReferenceValue = function (value) {
                    return value && value.trim() ? value.trim() : '---';
                };

                const referenceMessage = function (error) {
                    return error && error.message
                        ? error.message
                        : (error && error.errors ? Object.values(error.errors).flat().shift() : null);
                };

                const setReferenceInputValues = function (item) {
                    referenceInputs.forEach(function (input) {
                        const field = input.dataset.referenceFieldInput;
                        const value = item ? (item.dataset['reference' + field.charAt(0).toUpperCase() + field.slice(1)] || '') : '';
                        input.value = value === '---' ? '' : value;
                    });
                };

                const collectReferenceValues = function () {
                    const values = {};
                    referenceInputs.forEach(function (input) {
                        values[input.dataset.referenceFieldInput] = input.value.trim();
                    });
                    return values;
                };

                const renderReferenceNumbers = function () {
                    const items = referenceItems();
                    items.forEach(function (item, index) {
                        const title = item.querySelector('.candidate-reference-item__header h2');
                        if (title) {
                            title.textContent = 'Reference ' + (index + 1);
                        }
                    });
                    referenceEmpty()?.classList.toggle('d-none', items.length > 0);
                };

                const syncReferenceItem = function (item, values) {
                    item.dataset.referenceId = values.id || item.dataset.referenceId || '';
                    item.dataset.updateUrl = values.update_url || item.dataset.updateUrl || '';
                    item.dataset.deleteUrl = values.delete_url || item.dataset.deleteUrl || '';
                    referenceFields.forEach(function (field) {
                        item.dataset['reference' + field.charAt(0).toUpperCase() + field.slice(1)] = displayReferenceValue(values[field]);
                        const valueNode = item.querySelector('[data-reference-value="' + field + '"]');
                        if (valueNode) {
                            valueNode.textContent = displayReferenceValue(values[field]);
                        }
                    });
                };

                const makeReferenceItem = function (values) {
                    const item = document.createElement('div');
                    item.className = 'candidate-reference-item';
                    item.dataset.referenceItem = '';
                    item.innerHTML = [
                        '<div class="candidate-reference-item__header">',
                        '<h2></h2>',
                        '<div class="candidate-reference-actions">',
                        '<button type="button" data-reference-edit><i class="fa-regular fa-pen-to-square"></i> Edit</button>',
                        '<button type="button" data-reference-delete><i class="fa-regular fa-trash-can"></i> Delete</button>',
                        '</div>',
                        '</div>',
                        '<div class="candidate-reference-detail-grid">',
                        '<div class="candidate-reference-field"><span>Name</span><strong data-reference-value="name"></strong></div>',
                        '<div class="candidate-reference-field"><span>Designation</span><strong data-reference-value="designation"></strong></div>',
                        '<div class="candidate-reference-field"><span>Organization</span><strong data-reference-value="organization"></strong></div>',
                        '<div class="candidate-reference-field"><span>Email</span><strong data-reference-value="email"></strong></div>',
                        '<div class="candidate-reference-field"><span>Relation</span><strong data-reference-value="relation"></strong></div>',
                        '<div class="candidate-reference-field"><span>Mobile</span><strong data-reference-value="mobile"></strong></div>',
                        '<div class="candidate-reference-field"><span>Phone (Office)</span><strong data-reference-value="officePhone"></strong></div>',
                        '<div class="candidate-reference-field"><span>Phone (Residential)</span><strong data-reference-value="residentialPhone"></strong></div>',
                        '<div class="candidate-reference-field candidate-reference-field--full"><span>Address</span><strong data-reference-value="address"></strong></div>',
                        '</div>',
                    ].join('');
                    syncReferenceItem(item, values);
                    return item;
                };

                const closeReferenceForm = function () {
                    referenceForm.classList.add('d-none');
                    if (referenceEditingId) {
                        referenceEditingId.value = '';
                    }
                    setReferenceInputValues(null);
                    if (referenceSubmit) {
                        referenceSubmit.textContent = 'Save';
                    }
                    if (referenceClose) {
                        referenceClose.textContent = 'Close';
                    }
                    if (referenceFormTitle) {
                        referenceFormTitle.textContent = 'Reference';
                    }
                    referenceForm.classList.remove('candidate-reference-form--inline');
                    if (activeReferenceItem) {
                        activeReferenceItem.classList.remove('d-none');
                        activeReferenceItem = null;
                    }
                    referenceFormHome.appendChild(referenceForm);
                    renderReferenceNumbers();
                };

                const openReferenceForm = function (item) {
                    const section = document.getElementById('candidateReferencePanelBody');
                    if (section && typeof bootstrap !== 'undefined') {
                        bootstrap.Collapse.getOrCreateInstance(section, { toggle: false }).show();
                    }

                    closeReferenceForm();
                    activeReferenceItem = item || null;
                    if (referenceEditingId) {
                        referenceEditingId.value = item ? (item.dataset.referenceId || '') : '';
                    }
                    setReferenceInputValues(item);
                    referenceForm.classList.remove('d-none');
                    if (referenceFormTitle) {
                        const formIndex = item ? referenceItems().indexOf(item) + 1 : referenceItems().length + 1;
                        referenceFormTitle.textContent = 'Reference ' + formIndex;
                    }
                    if (referenceSubmit) {
                        referenceSubmit.textContent = item ? 'Update' : 'Save';
                    }
                    if (referenceClose) {
                        referenceClose.textContent = item ? 'Cancel' : 'Close';
                    }
                    if (item) {
                        referenceForm.classList.add('candidate-reference-form--inline');
                        item.insertAdjacentElement('beforebegin', referenceForm);
                        item.classList.add('d-none');
                    } else {
                        referenceForm.classList.remove('candidate-reference-form--inline');
                        const items = referenceItems();
                        const lastItem = items.length ? items[items.length - 1] : null;
                        if (lastItem) {
                            lastItem.after(referenceForm);
                        } else {
                            referenceEmpty()?.after(referenceForm);
                        }
                    }
                    referenceForm.querySelector('[data-reference-field-input="name"]')?.focus();
                };

                referenceAddAction?.addEventListener('click', function () {
                    openReferenceForm(null);
                });

                referenceList.addEventListener('click', function (event) {
                    const editButton = event.target.closest('[data-reference-edit]');
                    const deleteButton = event.target.closest('[data-reference-delete]');
                    const item = event.target.closest('[data-reference-item]');

                    if (editButton && item) {
                        openReferenceForm(item);
                    }

                    if (deleteButton && item) {
                        if (!window.confirm('Are you sure you want to delete this reference?')) {
                            return;
                        }

                        const formData = new FormData();
                        formData.append('_method', 'DELETE');
                        if (referenceToken) {
                            formData.append('_token', referenceToken);
                        }

                        fetch(item.dataset.deleteUrl, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        }).then(function (response) {
                            return response.json().then(function (body) {
                                if (!response.ok) {
                                    throw body;
                                }

                                return body;
                            });
                        }).then(function (response) {
                            if (activeReferenceItem === item) {
                                closeReferenceForm();
                            }
                            item.remove();
                            if (!referenceItems().length) {
                                closeReferenceForm();
                                if (!referenceEmpty()) {
                                    const empty = document.createElement('p');
                                    empty.className = 'candidate-skill-empty candidate-reference-empty';
                                    empty.dataset.referenceEmpty = '';
                                    empty.textContent = '---';
                                    referenceList.appendChild(empty);
                                }
                            }
                            renderReferenceNumbers();
                            if (response && response.message && typeof displaySuccessMessage === 'function') {
                                displaySuccessMessage(response.message);
                            }
                        }).catch(function (error) {
                            const message = referenceMessage(error);
                            if (message && typeof displayErrorMessage === 'function') {
                                displayErrorMessage(message);
                            }
                        });
                    }
                });

                referenceClose?.addEventListener('click', function () {
                    closeReferenceForm();
                });

                referenceForm.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const values = collectReferenceValues();

                    if (!values.name) {
                        referenceForm.querySelector('[data-reference-field-input="name"]')?.focus();
                        return;
                    }

                    if (!values.designation) {
                        referenceForm.querySelector('[data-reference-field-input="designation"]')?.focus();
                        return;
                    }

                    if (!values.organization) {
                        referenceForm.querySelector('[data-reference-field-input="organization"]')?.focus();
                        return;
                    }

                    const formData = new FormData(referenceForm);
                    const requestUrl = activeReferenceItem ? activeReferenceItem.dataset.updateUrl : referenceForm.dataset.storeUrl;
                    if (activeReferenceItem) {
                        formData.append('_method', 'PUT');
                    }

                    fetch(requestUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    }).then(function (response) {
                        return response.json().then(function (body) {
                            if (!response.ok) {
                                throw body;
                            }

                            return body;
                        });
                    }).then(function (response) {
                        const reference = response && response.data ? response.data : values;

                        if (activeReferenceItem) {
                            syncReferenceItem(activeReferenceItem, reference);
                            activeReferenceItem.classList.remove('d-none');
                        } else {
                            referenceEmpty()?.remove();
                            referenceList.appendChild(makeReferenceItem(reference));
                        }

                        closeReferenceForm();
                        renderReferenceNumbers();
                        if (response && response.message && typeof displaySuccessMessage === 'function') {
                            displaySuccessMessage(response.message);
                        }
                    }).catch(function (error) {
                        const message = referenceMessage(error);
                        if (message && typeof displayErrorMessage === 'function') {
                            displayErrorMessage(message);
                        }
                    });
                });

                closeReferenceForm();
                renderReferenceNumbers();
            }

            if (languageForm) {
                const languageSummary = document.querySelector('[data-language-summary]');
                const languageChipList = document.querySelector('[data-language-chip-list]');
                const languageEmpty = document.querySelector('[data-language-empty]');
                const languageInput = languageForm.querySelector('[data-language-name-input]');
                const languageReadingInput = languageForm.querySelector('[data-language-reading-input]');
                const languageWritingInput = languageForm.querySelector('[data-language-writing-input]');
                const languageSpeakingInput = languageForm.querySelector('[data-language-speaking-input]');
                const languageCurrentList = languageForm.querySelector('[data-language-current-list]');
                const languageEditAction = document.querySelector('[data-language-edit-action]');
                const languageInlineAddAction = document.querySelector('[data-language-inline-add]');
                const languageFormTitle = languageForm.querySelector('[data-language-form-title]');
                const languageFormHome = languageForm.parentElement;
                const languageOptions = {};
                let editingLanguageName = '';

                languageForm.querySelectorAll('[data-language-option]').forEach(function (option) {
                    languageOptions[String(option.dataset.name || '').toLowerCase()] = {
                        id: option.dataset.id,
                        name: option.dataset.name,
                    };
                });

                const setLanguageFormMode = function (isEditing) {
                    languageForm.classList.toggle('d-none', !isEditing);
                    if (isEditing) {
                        languageInput.focus();
                    }
                };

                const languageItems = function () {
                    return Array.from(languageCurrentList.querySelectorAll('[data-language-current]'));
                };

                const renderLanguageSummary = function () {
                    const items = languageItems();
                    if (languageChipList) {
                        languageChipList.innerHTML = '';
                        items.forEach(function (item, index) {
                            const chip = document.createElement('div');
                            chip.className = 'candidate-language-item';
                            chip.dataset.languageChip = '';
                            chip.dataset.languageId = item.dataset.languageId || '';
                            chip.dataset.languageName = item.dataset.languageName || '';
                            chip.dataset.languageReading = item.dataset.languageReading || '';
                            chip.dataset.languageWriting = item.dataset.languageWriting || '';
                            chip.dataset.languageSpeaking = item.dataset.languageSpeaking || '';
                            chip.innerHTML = [
                                '<div class="candidate-language-item__header">',
                                '<h2></h2>',
                                '<div class="candidate-reference-actions">',
                                '<button type="button" data-language-item-edit><i class="fa-regular fa-pen-to-square"></i> Edit</button>',
                                '<button type="button" data-language-item-delete><i class="fa-regular fa-trash-can"></i> Delete</button>',
                                '</div>',
                                '</div>',
                                '<div class="candidate-language-detail-grid">',
                                '<div class="candidate-reference-field"><span>Language</span><strong data-language-detail="name"></strong></div>',
                                '<div class="candidate-reference-field"><span>Reading</span><strong data-language-detail="reading"></strong></div>',
                                '<div class="candidate-reference-field"><span>Writing</span><strong data-language-detail="writing"></strong></div>',
                                '<div class="candidate-reference-field"><span>Speaking</span><strong data-language-detail="speaking"></strong></div>',
                                '</div>',
                            ].join('');
                            chip.querySelector('h2').textContent = 'Language ' + (index + 1);
                            chip.querySelector('[data-language-detail="name"]').textContent = item.dataset.languageName || '---';
                            chip.querySelector('[data-language-detail="reading"]').textContent = item.dataset.languageReading || '---';
                            chip.querySelector('[data-language-detail="writing"]').textContent = item.dataset.languageWriting || '---';
                            chip.querySelector('[data-language-detail="speaking"]').textContent = item.dataset.languageSpeaking || '---';
                            languageChipList.appendChild(chip);
                        });
                        languageChipList.classList.toggle('d-none', !items.length);
                    }
                    languageEmpty?.classList.toggle('d-none', Boolean(items.length));
                    languageInlineAddAction?.classList.toggle('d-none', !items.length);
                    if (languageEditAction) {
                        languageEditAction.innerHTML = '<i class="fa-solid fa-plus"></i> Add Language';
                    }
                };

                const moveLanguageFormAfter = function (element) {
                    if (element && element.parentElement) {
                        element.insertAdjacentElement('afterend', languageForm);
                        return;
                    }

                    languageFormHome.appendChild(languageForm);
                };

                const makeLanguageCurrent = function (id, name, reading, writing, speaking) {
                    const chip = document.createElement('span');
                    chip.className = 'candidate-preferred-chip';
                    chip.dataset.languageCurrent = '';
                    chip.dataset.languageId = id || '';
                    chip.dataset.languageName = name;
                    chip.dataset.languageReading = reading || '';
                    chip.dataset.languageWriting = writing || '';
                    chip.dataset.languageSpeaking = speaking || '';
                    chip.innerHTML = '<span></span><button type="button" data-language-remove aria-label="Remove language"><i class="fa-solid fa-xmark"></i></button>';
                    chip.querySelector('span').textContent = name;
                    return chip;
                };

                const setLanguageFormTitle = function (item) {
                    if (!languageFormTitle) {
                        return;
                    }

                    const items = languageItems();
                    const index = item ? items.indexOf(item) + 1 : items.length + 1;
                    languageFormTitle.textContent = 'Language ' + Math.max(index, 1);
                };

                const syncLanguages = function () {
                    const currentLanguageItems = languageItems();
                    const skillItems = Array.from(document.querySelectorAll('[data-skill-item]'));

                    if (!languageForm.dataset.updateUrl) {
                        return;
                    }

                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('first_name', languageForm.dataset.firstName || '');
                    formData.append('last_name', languageForm.dataset.lastName || '');
                    if (skillItems.length) {
                        formData.append('candidateSkillsUpdated', '1');
                    }
                    skillItems.forEach(function (item, index) {
                        formData.append('candidateSkills[]', item.dataset.skillId || '');
                        formData.append('candidateSkillNames[]', item.dataset.skillName || '');
                        String(item.dataset.skillSources || '')
                            .split(', ')
                            .filter(Boolean)
                            .forEach(function (source) {
                                formData.append('candidateSkillSources[' + index + '][]', source);
                            });
                    });
                    formData.append('candidateLanguageUpdated', '1');
                    currentLanguageItems.forEach(function (item) {
                        formData.append('candidateLanguage[]', item.dataset.languageId || '');
                        formData.append('candidateLanguageNames[]', item.dataset.languageName || '');
                        formData.append('candidateLanguageReadingLevels[]', item.dataset.languageReading || '');
                        formData.append('candidateLanguageWritingLevels[]', item.dataset.languageWriting || '');
                        formData.append('candidateLanguageSpeakingLevels[]', item.dataset.languageSpeaking || '');
                    });

                    fetch(languageForm.dataset.updateUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    }).then(function (response) {
                        return response.json().then(function (body) {
                            if (!response.ok) {
                                throw body;
                            }

                            return body;
                        });
                    }).then(function (response) {
                        const savedItems = response && response.data && response.data.candidateLanguageItems
                            ? response.data.candidateLanguageItems
                            : [];

                        savedItems.forEach(function (savedItem) {
                            const item = languageItems().find(function (languageItem) {
                                return String(languageItem.dataset.languageName || '').toLowerCase() === String(savedItem.name || '').toLowerCase();
                            });

                            if (item) {
                                item.dataset.languageId = savedItem.id || item.dataset.languageId || '';
                                item.dataset.languageReading = savedItem.reading || item.dataset.languageReading || '';
                                item.dataset.languageWriting = savedItem.writing || item.dataset.languageWriting || '';
                                item.dataset.languageSpeaking = savedItem.speaking || item.dataset.languageSpeaking || '';
                            }
                            if (savedItem.name) {
                                languageOptions[String(savedItem.name || '').toLowerCase()] = {
                                    id: savedItem.id,
                                    name: savedItem.name,
                                };
                            }
                        });
                        renderLanguageSummary();
                        if (response && response.message && typeof displaySuccessMessage === 'function') {
                            displaySuccessMessage(response.message);
                        }
                    }).catch(function (error) {
                        if (error && error.message && typeof displayErrorMessage === 'function') {
                            displayErrorMessage(error.message);
                        }
                    });
                };

                const openLanguageCreateForm = function () {
                    const section = document.getElementById('candidateLanguageProficiencyPanelBody');
                    if (section && typeof bootstrap !== 'undefined') {
                        bootstrap.Collapse.getOrCreateInstance(section, { toggle: false }).show();
                    }
                    editingLanguageName = '';
                    setLanguageFormTitle(null);
                    const hasLanguages = languageItems().length > 0;
                    moveLanguageFormAfter(hasLanguages && languageInlineAddAction && !languageInlineAddAction.classList.contains('d-none') ? languageInlineAddAction : languageEmpty);
                    languageInput.value = '';
                    languageReadingInput.value = '';
                    languageWritingInput.value = '';
                    languageSpeakingInput.value = '';
                    setLanguageFormMode(true);
                };

                languageEditAction?.addEventListener('click', openLanguageCreateForm);
                languageInlineAddAction?.addEventListener('click', openLanguageCreateForm);

                languageForm.querySelector('[data-language-close]')?.addEventListener('click', function () {
                    languageInput.value = '';
                    languageReadingInput.value = '';
                    languageWritingInput.value = '';
                    languageSpeakingInput.value = '';
                    editingLanguageName = '';
                    setLanguageFormMode(false);
                    languageFormHome.appendChild(languageForm);
                });

                languageChipList?.addEventListener('click', function (event) {
                    const editButton = event.target.closest('[data-language-item-edit]');
                    const deleteButton = event.target.closest('[data-language-item-delete]');
                    const item = event.target.closest('[data-language-chip]');

                    if (editButton && item) {
                        editingLanguageName = item.dataset.languageName || '';
                        const current = languageItems().find(function (languageItem) {
                            return String(languageItem.dataset.languageName || '').toLowerCase() === editingLanguageName.toLowerCase();
                        });
                        setLanguageFormTitle(current);
                        moveLanguageFormAfter(item);
                        languageInput.value = item.dataset.languageName || '';
                        languageReadingInput.value = item.dataset.languageReading || '';
                        languageWritingInput.value = item.dataset.languageWriting || '';
                        languageSpeakingInput.value = item.dataset.languageSpeaking || '';
                        setLanguageFormMode(true);
                    }

                    if (deleteButton && item) {
                        const current = languageItems().find(function (languageItem) {
                            return String(languageItem.dataset.languageName || '').toLowerCase() === String(item.dataset.languageName || '').toLowerCase();
                        });
                        current?.remove();
                        renderLanguageSummary();
                        syncLanguages();
                    }
                });

                languageCurrentList.addEventListener('click', function (event) {
                    const removeButton = event.target.closest('[data-language-remove]');
                    if (removeButton) {
                        removeButton.closest('[data-language-current]')?.remove();
                    }
                });

                languageForm.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const selectedOption = languageInput.options ? languageInput.options[languageInput.selectedIndex] : null;
                    const enteredName = languageInput.value.trim();
                    const matchedLanguage = languageOptions[enteredName.toLowerCase()] || {};
                    const languageId = (selectedOption && selectedOption.dataset.languageId) || matchedLanguage.id || '';
                    const languageName = matchedLanguage.name || enteredName;
                    const readingLevel = languageReadingInput.value;
                    const writingLevel = languageWritingInput.value;
                    const speakingLevel = languageSpeakingInput.value;

                    if (!languageName) {
                        languageInput.focus();
                        return;
                    }

                    if (!readingLevel) {
                        languageReadingInput.focus();
                        return;
                    }
                    if (!writingLevel) {
                        languageWritingInput.focus();
                        return;
                    }
                    if (!speakingLevel) {
                        languageSpeakingInput.focus();
                        return;
                    }

                    const existingLanguage = languageItems().find(function (item) {
                        return String(item.dataset.languageName || '').toLowerCase() === (editingLanguageName || languageName).toLowerCase();
                    });

                    if (existingLanguage) {
                        existingLanguage.dataset.languageId = languageId || existingLanguage.dataset.languageId || '';
                        existingLanguage.dataset.languageName = languageName;
                        existingLanguage.dataset.languageReading = readingLevel;
                        existingLanguage.dataset.languageWriting = writingLevel;
                        existingLanguage.dataset.languageSpeaking = speakingLevel;
                        existingLanguage.querySelector('span').textContent = languageName;
                    } else {
                        languageCurrentList.appendChild(makeLanguageCurrent(languageId, languageName, readingLevel, writingLevel, speakingLevel));
                    }

                    languageInput.value = '';
                    languageReadingInput.value = '';
                    languageWritingInput.value = '';
                    languageSpeakingInput.value = '';
                    editingLanguageName = '';
                    renderLanguageSummary();
                    setLanguageFormMode(false);
                    languageFormHome.appendChild(languageForm);
                    syncLanguages();
                });

                renderLanguageSummary();
                setLanguageFormMode(false);
            }

            if (activityForm) {
                const activitySummary = document.querySelector('[data-activity-summary]');
                const activityList = document.querySelector('[data-activity-list]');
                const activityEmpty = document.querySelector('[data-activity-empty]');
                const activityAddAction = document.querySelector('[data-activity-add-action]');
                const activityInput = activityForm.querySelector('[data-activity-quill-input]');
                const activityEditor = activityForm.querySelector('[data-activity-quill-editor]');
                const activityCounter = activityForm.querySelector('[data-activity-character-count]');
                const activityEditingId = activityForm.querySelector('[data-activity-editing-id]');
                const activityFormTitle = activityForm.querySelector('[data-activity-form-title]');
                const activityToken = activityForm.querySelector('input[name="_token"]')?.value || '';
                let activityQuill = null;
                let activeActivityItem = null;
                const activityFormHome = document.createElement('div');
                activityForm.after(activityFormHome);

                const setActivityFormMode = function (isEditing) {
                    activityForm.classList.toggle('d-none', !isEditing);
                    if (isEditing && activityQuill) {
                        setTimeout(function () {
                            activityQuill.focus();
                        }, 0);
                    }
                };

                const openActivitySection = function () {
                    const section = document.getElementById('candidateExtracurricularActivitiesPanelBody');
                    if (section && typeof bootstrap !== 'undefined') {
                        bootstrap.Collapse.getOrCreateInstance(section, { toggle: false }).show();
                    }
                };

                const activityItems = function () {
                    return activityList ? Array.from(activityList.querySelectorAll('[data-activity-item]')) : [];
                };

                const renderActivityNumbers = function () {
                    const items = activityItems();
                    items.forEach(function (item, index) {
                        const title = item.querySelector('h2');
                        if (title) {
                            title.textContent = 'Extracurricular Activities ' + (index + 1);
                        }
                    });

                    activityList?.classList.toggle('d-none', !items.length);
                    activityEmpty?.classList.toggle('d-none', items.length > 0);
                    if (activityFormTitle && !activeActivityItem) {
                        activityFormTitle.textContent = 'Extracurricular Activities ' + (items.length + 1);
                    }
                };

                const createActivityItem = function (activity) {
                    const description = activity.description || '';
                    const item = document.createElement('div');
                    item.className = 'candidate-activity-item';
                    item.dataset.activityItem = '';
                    item.dataset.activityId = activity.id || '';
                    item.dataset.updateUrl = activity.update_url || '';
                    item.dataset.deleteUrl = activity.delete_url || '';
                    item.innerHTML = `
                        <div class="candidate-activity-item__header">
                            <h2>Extracurricular Activities</h2>
                            <div class="candidate-reference-actions">
                                <button type="button" data-activity-edit>
                                    <i class="fa-regular fa-pen-to-square"></i> Edit
                                </button>
                                <button type="button" data-activity-delete>
                                    <i class="fa-regular fa-trash-can"></i> Delete
                                </button>
                            </div>
                        </div>
                        <div class="candidate-activity-summary__content" data-activity-summary-content>${description || '---'}</div>
                    `;

                    return item;
                };

                const showActivityError = function (error) {
                    const message = error && error.message
                        ? error.message
                        : (error && error.errors ? Object.values(error.errors).flat().shift() : null);

                    if (message && typeof displayErrorMessage === 'function') {
                        displayErrorMessage(message);
                    }
                };

                const closeActivityForm = function () {
                    const editingItem = activeActivityItem;
                    activeActivityItem = null;
                    editingItem?.classList.remove('d-none');
                    if (activityEditingId) {
                        activityEditingId.value = '';
                    }
                    if (activityQuill) {
                        activityQuill.root.innerHTML = '';
                    }
                    activityInput.value = '';
                    updateActivityCounter();
                    setActivityFormMode(false);
                    activityFormHome.appendChild(activityForm);
                    renderActivityNumbers();
                };

                const placeActivityFormForAdd = function () {
                    const items = activityItems();
                    const lastItem = items.length ? items[items.length - 1] : null;

                    if (lastItem) {
                        lastItem.after(activityForm);
                        return;
                    }

                    if (activityEmpty) {
                        activityEmpty.after(activityForm);
                        return;
                    }

                    activitySummary?.after(activityForm);
                };

                const placeActivityFormForEdit = function (item) {
                    if (! item) {
                        placeActivityFormForAdd();
                        return;
                    }

                    item.before(activityForm);
                    item.classList.add('d-none');
                };

                const updateActivityCounter = function () {
                    if (!activityCounter) {
                        return;
                    }

                    const length = activityQuill
                        ? Math.max(activityQuill.getText().replace(/\n$/, '').length, 0)
                        : (activityInput.value || '').replace(/<[^>]*>/g, '').length;
                    activityCounter.textContent = Math.min(length, 500) + '/500';
                };

                if (typeof Quill !== 'undefined' && activityEditor) {
                    activityQuill = new Quill(activityEditor, {
                        theme: 'snow',
                        placeholder: activityEditor.dataset.placeholder || '',
                        modules: {
                            toolbar: [['bold', 'italic'], [{ list: 'bullet' }]],
                        },
                    });

                    if (activityInput.value) {
                        activityQuill.root.innerHTML = activityInput.value;
                    }

                    activityQuill.on('text-change', function () {
                        const text = activityQuill.getText();
                        if (text.length > 501) {
                            activityQuill.deleteText(500, text.length);
                        }
                        activityInput.value = activityQuill.getText().trim().length ? activityQuill.root.innerHTML : '';
                        updateActivityCounter();
                    });
                }

                const openActivityForm = function (item) {
                    openActivitySection();
                    const previousItem = activeActivityItem;
                    previousItem?.classList.remove('d-none');
                    activeActivityItem = item || null;
                    const items = activityItems();
                    const description = activeActivityItem
                        ? (activeActivityItem.querySelector('[data-activity-summary-content]')?.innerHTML || '')
                        : '';
                    const number = activeActivityItem ? (items.indexOf(activeActivityItem) + 1) : (items.length + 1);

                    if (activityEditingId) {
                        activityEditingId.value = activeActivityItem ? (activeActivityItem.dataset.activityId || '') : '';
                    }
                    if (activityFormTitle) {
                        activityFormTitle.textContent = 'Extracurricular Activities ' + Math.max(number, 1);
                    }
                    if (activityQuill) {
                        activityQuill.root.innerHTML = description;
                    }
                    activityInput.value = description;
                    if (activeActivityItem) {
                        placeActivityFormForEdit(activeActivityItem);
                    } else {
                        placeActivityFormForAdd();
                    }
                    setActivityFormMode(true);
                    updateActivityCounter();
                };

                activityForm.addEventListener('submit', function (event) {
                    event.preventDefault();
                    if (activityQuill) {
                        activityInput.value = activityQuill.getText().trim().length ? activityQuill.root.innerHTML : '';
                    }

                    const formData = new FormData(activityForm);
                    const url = activeActivityItem ? activeActivityItem.dataset.updateUrl : activityForm.dataset.storeUrl;
                    if (activeActivityItem) {
                        formData.append('_method', 'PUT');
                    }

                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    }).then(function (response) {
                        return response.json().then(function (body) {
                            if (!response.ok) {
                                throw body;
                            }

                            return body;
                        });
                    }).then(function (response) {
                        const activity = response && response.data ? response.data : {
                            description: activityInput.value || '',
                        };

                        if (activeActivityItem) {
                            activeActivityItem.dataset.updateUrl = activity.update_url || activeActivityItem.dataset.updateUrl || '';
                            activeActivityItem.dataset.deleteUrl = activity.delete_url || activeActivityItem.dataset.deleteUrl || '';
                            const summaryContent = activeActivityItem.querySelector('[data-activity-summary-content]');
                            if (summaryContent) {
                                summaryContent.innerHTML = activity.description || '---';
                            }
                        } else if (activityList) {
                            const item = createActivityItem(activity);
                            activityList.appendChild(item);
                        }

                        closeActivityForm();
                        if (response && response.message && typeof displaySuccessMessage === 'function') {
                            displaySuccessMessage(response.message);
                        }
                    }).catch(function (error) {
                        showActivityError(error);
                    });
                });

                activityForm.querySelector('[data-activity-close]')?.addEventListener('click', closeActivityForm);

                activityAddAction?.addEventListener('click', function () {
                    openActivityForm(null);
                });

                activitySummary?.addEventListener('click', function (event) {
                    const editButton = event.target.closest('[data-activity-edit]');
                    const deleteButton = event.target.closest('[data-activity-delete]');
                    const item = event.target.closest('[data-activity-item]');

                    if (editButton && item) {
                        openActivityForm(item);
                        return;
                    }

                    if (!deleteButton || !item || !item.dataset.deleteUrl) {
                        return;
                    }

                    if (!window.confirm('Are you sure you want to delete this extracurricular activity?')) {
                        return;
                    }

                    const formData = new FormData();
                    formData.append('_method', 'DELETE');
                    if (activityToken) {
                        formData.append('_token', activityToken);
                    }

                    fetch(item.dataset.deleteUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    }).then(function (response) {
                        return response.json().then(function (body) {
                            if (!response.ok) {
                                throw body;
                            }

                            return body;
                        });
                    }).then(function (response) {
                        if (activeActivityItem === item) {
                            closeActivityForm();
                        }
                        item.remove();
                        renderActivityNumbers();
                        if (response && response.message && typeof displaySuccessMessage === 'function') {
                            displaySuccessMessage(response.message);
                        }
                    }).catch(showActivityError);
                });

                renderActivityNumbers();
                setActivityFormMode(false);
                updateActivityCounter();
            }

            if (skillManager) {
                const skillList = skillManager.querySelector('[data-skill-list]');
                const skillForm = skillManager.querySelector('[data-skill-form]');
                const skillNameInput = skillManager.querySelector('[data-skill-name-input]');
                const skillEditingId = skillManager.querySelector('[data-skill-editing-id]');
                const skillSources = skillManager.querySelectorAll('[data-skill-source]');
                const skillEmpty = function () {
                    return skillManager.querySelector('[data-skill-empty]');
                };
                const skillOptions = {};

                skillManager.querySelectorAll('[data-skill-option]').forEach(function (option) {
                    skillOptions[String(option.dataset.name || '').toLowerCase()] = {
                        id: option.dataset.id,
                        name: option.dataset.name,
                    };
                });

                const selectedSources = function () {
                    return Array.from(skillSources)
                        .filter(function (source) {
                            return source.checked;
                        })
                        .map(function (source) {
                            return source.value;
                        });
                };

                const setSelectedSources = function (sources) {
                    const selected = sources.length ? sources : ['Professional Training'];
                    skillSources.forEach(function (source) {
                        source.checked = selected.includes(source.value);
                    });
                };

                const makeSkillItem = function (id, name, sources) {
                    const item = document.createElement('div');
                    item.className = 'candidate-skill-item';
                    item.dataset.skillItem = '';
                    item.dataset.skillId = id || '';
                    item.dataset.skillName = name;
                    item.dataset.skillSources = sources.join(', ');
                    item.innerHTML = [
                        '<div>',
                        '<strong></strong>',
                        '<span></span>',
                        '</div>',
                        '<div class="candidate-skill-item__actions">',
                        '<button type="button" data-skill-edit><i class="fa-regular fa-pen-to-square"></i></button>',
                        '<button type="button" data-skill-delete><i class="fa-regular fa-trash-can"></i></button>',
                        '</div>',
                    ].join('');
                    item.querySelector('strong').textContent = name;
                    item.querySelector('span').textContent = sources.join(', ') || 'Professional Training';
                    return item;
                };

                const openSkillForm = function (item) {
                    skillForm.classList.remove('d-none');
                    skillNameInput.value = item ? item.dataset.skillName : '';
                    skillEditingId.value = item ? item.dataset.skillId : '';
                    setSelectedSources(item ? String(item.dataset.skillSources || '').split(', ').filter(Boolean) : []);
                    skillNameInput.focus();
                };

                const closeSkillForm = function () {
                    skillForm.classList.add('d-none');
                    skillForm.reset();
                    skillEditingId.value = '';
                    setSelectedSources([]);
                };

                const syncSkills = function () {
                    const skillItems = Array.from(skillList.querySelectorAll('[data-skill-item]'));

                    if (!skillManager.dataset.updateUrl) {
                        return;
                    }

                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('first_name', skillManager.dataset.firstName || '');
                    formData.append('last_name', skillManager.dataset.lastName || '');
                    formData.append('candidateSkillsUpdated', '1');
                    skillItems.forEach(function (item, index) {
                        formData.append('candidateSkills[]', item.dataset.skillId || '');
                        formData.append('candidateSkillNames[]', item.dataset.skillName || '');
                        String(item.dataset.skillSources || '')
                            .split(', ')
                            .filter(Boolean)
                            .forEach(function (source) {
                                formData.append('candidateSkillSources[' + index + '][]', source);
                            });
                    });

                    fetch(skillManager.dataset.updateUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    }).then(function (response) {
                        return response.json().then(function (body) {
                            if (!response.ok) {
                                throw body;
                            }

                            return body;
                        });
                    }).then(function (response) {
                        const savedItems = response && response.data && response.data.candidateSkillItems
                            ? response.data.candidateSkillItems
                            : [];
                        savedItems.forEach(function (savedItem) {
                            const item = Array.from(skillList.querySelectorAll('[data-skill-item]')).find(function (skillItem) {
                                return String(skillItem.dataset.skillName || '').toLowerCase() === String(savedItem.name || '').toLowerCase();
                            });

                            if (item) {
                                item.dataset.skillId = savedItem.id || item.dataset.skillId || '';
                                item.dataset.skillSources = (savedItem.sources || []).join(', ');
                            }
                            if (savedItem.name) {
                                skillOptions[String(savedItem.name || '').toLowerCase()] = {
                                    id: savedItem.id,
                                    name: savedItem.name,
                                };
                            }
                        });
                        if (response && response.message && typeof displaySuccessMessage === 'function') {
                            displaySuccessMessage(response.message);
                        }
                    }).catch(function (error) {
                        if (error && error.message && typeof displayErrorMessage === 'function') {
                            displayErrorMessage(error.message);
                        }
                    });
                };

                document.querySelector('[data-skill-add-action]')?.addEventListener('click', function () {
                    const skillPanelBody = document.getElementById('candidateSkillInformationPanelBody');
                    if (skillPanelBody && typeof bootstrap !== 'undefined') {
                        bootstrap.Collapse.getOrCreateInstance(skillPanelBody, { toggle: false }).show();
                    }
                    openSkillForm(null);
                });

                skillManager.addEventListener('click', function (event) {
                    const editButton = event.target.closest('[data-skill-edit]');
                    const deleteButton = event.target.closest('[data-skill-delete]');

                    if (editButton) {
                        openSkillForm(editButton.closest('[data-skill-item]'));
                    }

                    if (deleteButton) {
                        deleteButton.closest('[data-skill-item]')?.remove();
                        if (!skillList.querySelector('[data-skill-item]')) {
                            skillList.innerHTML = '<p class="candidate-skill-empty" data-skill-empty>---</p>';
                        }
                        syncSkills();
                    }
                });

                skillManager.querySelector('[data-skill-close]')?.addEventListener('click', closeSkillForm);

                skillForm.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const enteredName = skillNameInput.value.trim();
                    if (!enteredName) {
                        skillNameInput.focus();
                        return;
                    }

                    const matchedSkill = skillOptions[enteredName.toLowerCase()] || {};
                    const skillId = matchedSkill.id || skillEditingId.value || '';
                    const skillName = matchedSkill.name || enteredName;
                    const sources = selectedSources();
                    const existingItem = skillEditingId.value
                        ? skillList.querySelector('[data-skill-id="' + skillEditingId.value + '"]')
                        : null;

                    skillEmpty()?.remove();

                    if (existingItem) {
                        existingItem.dataset.skillId = skillId;
                        existingItem.dataset.skillName = skillName;
                        existingItem.dataset.skillSources = sources.join(', ');
                        existingItem.querySelector('strong').textContent = skillName;
                        existingItem.querySelector('span').textContent = sources.join(', ') || 'Professional Training';
                    } else {
                        skillList.appendChild(makeSkillItem(skillId, skillName, sources));
                    }

                    closeSkillForm();
                    syncSkills();
                });
            }

            const setActiveOtherSection = function (panelId) {
                otherSectionLinks.forEach(function (link) {
                    link.classList.toggle('active', link.dataset.otherSectionLink === panelId);
                });
            };

            const closeOtherSections = function (activeSection) {
                if (typeof bootstrap === 'undefined') {
                    return;
                }

                otherSectionBodies.forEach(function (section) {
                    if (section !== activeSection) {
                        bootstrap.Collapse.getOrCreateInstance(section, { toggle: false }).hide();
                    }
                });
            };

            otherSectionBodies.forEach(function (section) {
                const toggle = document.querySelector('[data-bs-target="#' + section.id + '"]');
                if (!toggle) {
                    return;
                }

                const label = toggle.querySelector('span');
                const icon = toggle.querySelector('i');
                const header = toggle.closest('.candidate-education-panel__header');
                const panel = section.closest('.candidate-education-panel');

                const setPanelToggleState = function (isOpen) {
                    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    if (label) {
                        label.textContent = isOpen
                            ? (toggle.dataset.collapseLabel || '{{ __('messages.candidate_profile.collapse') }}')
                            : (toggle.dataset.expandLabel || '{{ __('messages.candidate_profile.expand') }}');
                    }
                    if (icon) {
                        icon.classList.toggle('fa-chevron-up', isOpen);
                        icon.classList.toggle('fa-chevron-down', !isOpen);
                    }
                    if (header) {
                        header.classList.toggle('collapsed', !isOpen);
                    }
                    const skillAddAction = header ? header.querySelector('[data-skill-add-action]') : null;
                    if (skillAddAction) {
                        skillAddAction.classList.toggle('d-none', !isOpen);
                    }
                    const activityAddAction = header ? header.querySelector('[data-activity-add-action]') : null;
                    if (activityAddAction) {
                        activityAddAction.classList.toggle('d-none', !isOpen);
                    }
                    const languageEditAction = header ? header.querySelector('[data-language-edit-action]') : null;
                    if (languageEditAction) {
                        languageEditAction.classList.toggle('d-none', !isOpen);
                    }
                    const linkAddAction = header ? header.querySelector('[data-link-add-action]') : null;
                    if (linkAddAction) {
                        linkAddAction.dataset.sectionOpen = isOpen ? 'true' : 'false';
                        linkAddAction.classList.toggle('d-none', !isOpen || document.querySelectorAll('[data-link-item]').length >= 5);
                    }
                    const referenceAddAction = header ? header.querySelector('[data-reference-add-action]') : null;
                    if (referenceAddAction) {
                        referenceAddAction.classList.toggle('d-none', !isOpen);
                    }
                };

                section.addEventListener('shown.bs.collapse', function () {
                    closeOtherSections(section);
                    setPanelToggleState(true);
                    if (panel) {
                        setActiveOtherSection(panel.id);
                    }
                });
                section.addEventListener('hidden.bs.collapse', function () {
                    setPanelToggleState(false);
                });

                if (header) {
                    header.addEventListener('click', function (event) {
                        if (event.target.closest('button, a, input, select, textarea, label')) {
                            return;
                        }
                        toggle.click();
                    });
                }

                setPanelToggleState(section.classList.contains('show'));
            });

            otherSectionLinks.forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    const panel = document.getElementById(link.dataset.otherSectionLink);
                    const section = panel ? panel.querySelector('.candidate-profile-section__collapse') : null;

                    if (!panel || !section || typeof bootstrap === 'undefined') {
                        return;
                    }

                    closeOtherSections(section);
                    bootstrap.Collapse.getOrCreateInstance(section, { toggle: false }).show();
                    setActiveOtherSection(panel.id);
                    panel.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                    });
                });
            });
        });
    </script>
@endpush
