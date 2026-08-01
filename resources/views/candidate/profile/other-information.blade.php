@extends('candidate.profile.index')
@section('section')
    @php
        $candidateSkillItems = $user->candidateSkill;
        $candidateSkillNames = $candidateSkillItems->pluck('name')->toArray();
        $candidateLanguageItems = $user->candidateLanguage;
        $candidateLanguageNames = $candidateLanguageItems->pluck('language')->toArray();
        $skillLearnOptions = ['Self', 'Job', 'Educational', 'Professional Training', 'NTVQF'];
        $candidateLinkAccounts = collect([
            ['platform' => 'Facebook', 'url' => $user->facebook_url, 'icon' => 'fa-brands fa-facebook'],
            ['platform' => 'Twitter', 'url' => $user->twitter_url, 'icon' => 'fa-brands fa-twitter'],
            ['platform' => 'LinkedIn', 'url' => $user->linkedin_url, 'icon' => 'fa-brands fa-linkedin'],
        ])->filter(fn ($link) => filled($link['url']))->values();
    @endphp

    <div class="mb-xl-8 candidate-other-info-page">
        <div class="candidate-education-panel" id="candidateSkillInformation">
            <div class="candidate-education-panel__header">
                <h1>Skill</h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-add" data-skill-add-action>
                        <i class="fa-solid fa-plus"></i> Add Skill
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
                            @forelse($candidateSkillItems as $skill)
                                <div class="candidate-skill-item" data-skill-item data-skill-id="{{ $skill->id }}"
                                     data-skill-name="{{ $skill->name }}" data-skill-sources="Educational, Professional Training">
                                    <div>
                                        <strong>{{ $skill->name }}</strong>
                                        <span>Educational, Professional Training</span>
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
                <h1>Extracurricular Activities</h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-add d-none" data-activity-add-action>
                        <i class="fa-solid fa-plus"></i> Add Extracurricular Activities
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
                        <p data-activity-empty>---</p>
                        <div class="candidate-activity-summary__content d-none" data-activity-summary-content></div>
                        <button type="button" class="candidate-skill-inline-edit d-none" data-activity-edit>
                            <i class="fa-regular fa-pen-to-square"></i> Edit
                        </button>
                    </div>
                    <form class="candidate-activity-form d-none" data-activity-form>
                        <div class="candidate-activity-editor">
                            <textarea class="d-none" data-activity-quill-input></textarea>
                            <div class="candidate-activity-quill" data-activity-quill-editor
                                 data-placeholder="Enter your writing texts..."></div>
                        </div>
                        <p class="candidate-activity-count">
                            You wrote <strong data-activity-character-count>0/500</strong> characters
                        </p>
                        <div class="candidate-skill-form__actions candidate-activity-form__actions">
                            <button type="submit" class="candidate-skill-save">Save</button>
                            <button type="button" class="candidate-skill-close" data-activity-close>Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel" id="candidateLanguageProficiency">
            <div class="candidate-education-panel__header collapsed">
                <h1>Language Proficiency</h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-add d-none" data-language-edit-action>
                        <i class="fa-solid fa-plus"></i>
                        Add Language
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
                        <div class="candidate-skill-list {{ count($candidateLanguageNames) ? '' : 'd-none' }}" data-language-chip-list>
                            @foreach($candidateLanguageItems as $language)
                                <div class="candidate-skill-item" data-language-chip
                                     data-language-id="{{ $language->id }}"
                                     data-language-name="{{ $language->language }}"
                                     data-language-level="">
                                    <div>
                                        <strong>{{ $language->language }}</strong>
                                        <span>---</span>
                                    </div>
                                    <div class="candidate-skill-item__actions">
                                        <button type="button" data-language-item-edit>
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <button type="button" data-language-item-delete>
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="{{ count($candidateLanguageNames) ? 'd-none' : '' }}" data-language-empty>---</p>
                    </div>

                    <form class="candidate-language-form d-none" data-language-form
                          data-update-url="{{ route('candidate.general.profile.update') }}"
                          data-first-name="{{ $user->first_name }}"
                          data-last-name="{{ $user->last_name }}">
                        <div class="candidate-language-form-grid">
                            <div class="candidate-skill-form__field">
                                <label for="candidateLanguageName">Language <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="candidateLanguageName"
                                       data-language-name-input placeholder="Enter your language">
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidateLanguageLevel">Proficiency Level <span class="text-danger">*</span></label>
                                <select class="form-control" id="candidateLanguageLevel" data-language-level-input>
                                    <option value="">Select proficiency level</option>
                                    <option value="Basic">Basic</option>
                                    <option value="Conversational">Conversational</option>
                                    <option value="Fluent">Fluent</option>
                                    <option value="Native">Native</option>
                                </select>
                            </div>
                        </div>

                        <div class="candidate-language-current" data-language-current-list>
                            @foreach($candidateLanguageItems as $language)
                                <span class="candidate-preferred-chip" data-language-current
                                      data-language-id="{{ $language->id }}"
                                      data-language-name="{{ $language->language }}"
                                      data-language-level="">
                                    <span>{{ $language->language }}</span>
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
                <h1>Link Account</h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-add d-none" data-link-add-action>
                        <i class="fa-solid fa-plus"></i>
                        Add Link Account
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
                                     data-link-platform="{{ $linkAccount['platform'] }}"
                                     data-link-url="{{ $linkAccount['url'] }}">
                                    <span class="candidate-link-platform">
                                        <i class="{{ $linkAccount['icon'] }}"></i>
                                        {{ $linkAccount['platform'] }}
                                    </span>
                                    <a href="{{ addLinkHttpUrl($linkAccount['url']) }}" target="_blank" data-link-url-text>
                                        {{ $linkAccount['url'] }}
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

                        <form class="candidate-link-form d-none" data-link-form>
                            <input type="hidden" data-link-editing-index>
                            <div class="candidate-link-form-grid">
                                <div class="candidate-skill-form__field">
                                    <label for="candidateLinkPlatform">Account Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="candidateLinkPlatform" data-link-platform-input>
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
                                    <input type="url" class="form-control" id="candidateLinkUrl"
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
                <h1>Reference</h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-add d-none" data-reference-add-action>
                        <i class="fa-solid fa-plus"></i>
                        Add Reference
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
                        <form class="candidate-reference-form d-none" data-reference-form>
                            <input type="hidden" data-reference-editing-index>
                            <div class="candidate-reference-item__header">
                                <h2 data-reference-form-title>Reference</h2>
                            </div>
                            <div class="candidate-reference-form-grid">
                                <div class="candidate-skill-form__field">
                                    <label for="candidateReferenceName">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="candidateReferenceName"
                                           data-reference-field-input="name" required>
                                </div>
                                <div class="candidate-skill-form__field">
                                    <label for="candidateReferenceDesignation">Designation <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="candidateReferenceDesignation"
                                           data-reference-field-input="designation" required>
                                </div>
                                <div class="candidate-skill-form__field">
                                    <label for="candidateReferenceOrganization">Organization <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="candidateReferenceOrganization"
                                           data-reference-field-input="organization" required>
                                </div>
                                <div class="candidate-skill-form__field">
                                    <label for="candidateReferenceEmail">Email</label>
                                    <input type="email" class="form-control" id="candidateReferenceEmail"
                                           data-reference-field-input="email">
                                </div>
                                <div class="candidate-skill-form__field">
                                    <label for="candidateReferenceRelation">Relation</label>
                                    <select class="form-control" id="candidateReferenceRelation" data-reference-field-input="relation">
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
                                           data-reference-field-input="mobile">
                                </div>
                                <div class="candidate-skill-form__field">
                                    <label for="candidateReferenceOfficePhone">Phone (Office)</label>
                                    <input type="text" class="form-control" id="candidateReferenceOfficePhone"
                                           data-reference-field-input="officePhone" placeholder="Enter your Phone (Office)">
                                </div>
                                <div class="candidate-skill-form__field">
                                    <label for="candidateReferenceResidentialPhone">Phone (Residential)</label>
                                    <input type="text" class="form-control" id="candidateReferenceResidentialPhone"
                                           data-reference-field-input="residentialPhone" placeholder="Enter your Phone (Residential)">
                                </div>
                                <div class="candidate-skill-form__field candidate-reference-form-field--full">
                                    <label for="candidateReferenceAddress">Address</label>
                                    <textarea class="form-control" id="candidateReferenceAddress" rows="4"
                                              data-reference-field-input="address"></textarea>
                                </div>
                            </div>
                            <div class="candidate-skill-form__actions">
                                <button type="submit" class="candidate-skill-save" data-reference-submit>Save</button>
                                <button type="button" class="candidate-skill-close" data-reference-close>Close</button>
                            </div>
                        </form>

                        <div class="candidate-reference-item" data-reference-item
                             data-reference-name="Dr. Nadim Chowdhury"
                             data-reference-designation="Associate Professor"
                             data-reference-organization="BUET"
                             data-reference-email="nadim@eee.buet.ac.bd"
                             data-reference-relation="Relative"
                             data-reference-mobile="01730725252"
                             data-reference-office-phone="---"
                             data-reference-residential-phone="---"
                             data-reference-address="ECE Building, West Palashi Campus, Dhaka">
                            <div class="candidate-reference-item__header">
                                <h2>Reference 1</h2>
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
                                    <strong data-reference-value="name">Dr. Nadim Chowdhury</strong>
                                </div>
                                <div class="candidate-reference-field">
                                    <span>Designation</span>
                                    <strong data-reference-value="designation">Associate Professor</strong>
                                </div>
                                <div class="candidate-reference-field">
                                    <span>Organization</span>
                                    <strong data-reference-value="organization">BUET</strong>
                                </div>
                                <div class="candidate-reference-field">
                                    <span>Email</span>
                                    <strong data-reference-value="email">nadim@eee.buet.ac.bd</strong>
                                </div>
                                <div class="candidate-reference-field">
                                    <span>Relation</span>
                                    <strong data-reference-value="relation">Relative</strong>
                                </div>
                                <div class="candidate-reference-field">
                                    <span>Mobile</span>
                                    <strong data-reference-value="mobile">01730725252</strong>
                                </div>
                                <div class="candidate-reference-field">
                                    <span>Phone (Office)</span>
                                    <strong data-reference-value="officePhone">---</strong>
                                </div>
                                <div class="candidate-reference-field">
                                    <span>Phone (Residential)</span>
                                    <strong data-reference-value="residentialPhone">---</strong>
                                </div>
                                <div class="candidate-reference-field candidate-reference-field--full">
                                    <span>Address</span>
                                    <strong data-reference-value="address">ECE Building, West Palashi Campus, Dhaka</strong>
                                </div>
                            </div>
                        </div>

                        <div class="candidate-reference-item" data-reference-item
                             data-reference-name="Mohammed Saizuddin"
                             data-reference-designation="Superintendent"
                             data-reference-organization="Department of Immigration &amp; passports"
                             data-reference-email="araman666@gmail.com"
                             data-reference-relation="Relative"
                             data-reference-mobile="01992588494"
                             data-reference-office-phone="---"
                             data-reference-residential-phone="---"
                             data-reference-address="Joy Nogor, Mirpur-13, Dhaka">
                            <div class="candidate-reference-item__header">
                                <h2>Reference 2</h2>
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
                                    <strong data-reference-value="name">Mohammed Saizuddin</strong>
                                </div>
                                <div class="candidate-reference-field">
                                    <span>Designation</span>
                                    <strong data-reference-value="designation">Superintendent</strong>
                                </div>
                                <div class="candidate-reference-field">
                                    <span>Organization</span>
                                    <strong data-reference-value="organization">Department of Immigration &amp; passports</strong>
                                </div>
                                <div class="candidate-reference-field">
                                    <span>Email</span>
                                    <strong data-reference-value="email">araman666@gmail.com</strong>
                                </div>
                                <div class="candidate-reference-field">
                                    <span>Relation</span>
                                    <strong data-reference-value="relation">Relative</strong>
                                </div>
                                <div class="candidate-reference-field">
                                    <span>Mobile</span>
                                    <strong data-reference-value="mobile">01992588494</strong>
                                </div>
                                <div class="candidate-reference-field">
                                    <span>Phone (Office)</span>
                                    <strong data-reference-value="officePhone">---</strong>
                                </div>
                                <div class="candidate-reference-field">
                                    <span>Phone (Residential)</span>
                                    <strong data-reference-value="residentialPhone">---</strong>
                                </div>
                                <div class="candidate-reference-field candidate-reference-field--full">
                                    <span>Address</span>
                                    <strong data-reference-value="address">Joy Nogor, Mirpur-13, Dhaka</strong>
                                </div>
                            </div>
                        </div>
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
                const linkEditingIndex = linkManager.querySelector('[data-link-editing-index]');
                const linkSubmit = linkManager.querySelector('[data-link-submit]');
                const linkClose = linkManager.querySelector('[data-link-close]');
                const linkAddAction = document.querySelector('[data-link-add-action]');
                const maxLinks = 5;
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

                const makeLinkItem = function (platform, url) {
                    const item = document.createElement('div');
                    item.className = 'candidate-link-item';
                    item.dataset.linkItem = '';
                    item.dataset.linkPlatform = platform;
                    item.dataset.linkUrl = url;
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

                const resetLinkForm = function () {
                    linkPlatformInput.value = '';
                    linkUrlInput.value = '';
                    linkEditingIndex.value = '';
                    if (linkSubmit) {
                        linkSubmit.textContent = 'Save';
                    }
                    if (linkClose) {
                        linkClose.textContent = 'Close';
                    }
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
                    linkList.appendChild(linkForm);
                    setLinkFormMode(true);
                });

                linkList.addEventListener('click', function (event) {
                    const editButton = event.target.closest('[data-link-edit]');
                    const deleteButton = event.target.closest('[data-link-delete]');
                    const item = event.target.closest('[data-link-item]');

                    if (editButton && item) {
                        linkEditingIndex.value = String(linkItems().indexOf(item));
                        linkPlatformInput.value = item.dataset.linkPlatform || '';
                        linkUrlInput.value = item.dataset.linkUrl || '';
                        if (linkSubmit) {
                            linkSubmit.textContent = 'Update';
                        }
                        if (linkClose) {
                            linkClose.textContent = 'Cancel';
                        }
                        item.insertAdjacentElement('afterend', linkForm);
                        setLinkFormMode(true);
                    }

                    if (deleteButton && item) {
                        item.remove();
                        if (!linkItems().length) {
                            linkList.innerHTML = '<p class="candidate-skill-empty" data-link-empty>---</p>';
                        }
                        refreshLinkAddAction();
                    }
                });

                linkClose?.addEventListener('click', function () {
                    resetLinkForm();
                    setLinkFormMode(false);
                    linkManager.appendChild(linkForm);
                });

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

                    const editingItem = linkEditingIndex.value !== ''
                        ? linkItems()[Number(linkEditingIndex.value)]
                        : null;

                    if (editingItem) {
                        const newItem = makeLinkItem(platform, url);
                        editingItem.replaceWith(newItem);
                    } else if (linkItems().length < maxLinks) {
                        linkManager.querySelector('[data-link-empty]')?.remove();
                        linkList.appendChild(makeLinkItem(platform, url));
                    }

                    resetLinkForm();
                    setLinkFormMode(false);
                    linkManager.appendChild(linkForm);
                    refreshLinkAddAction();
                });

                refreshLinkAddAction();
            }

            const referenceList = document.querySelector('[data-reference-list]');
            const referenceForm = document.querySelector('[data-reference-form]');
            const referenceAddAction = document.querySelector('[data-reference-add-action]');

            if (referenceList && referenceForm) {
                const referenceEditingIndex = referenceForm.querySelector('[data-reference-editing-index]');
                const referenceInputs = referenceForm.querySelectorAll('[data-reference-field-input]');
                const referenceFormTitle = referenceForm.querySelector('[data-reference-form-title]');
                const referenceSubmit = referenceForm.querySelector('[data-reference-submit]');
                const referenceClose = referenceForm.querySelector('[data-reference-close]');
                let activeReferenceItem = null;

                const referenceItems = function () {
                    return Array.from(referenceList.querySelectorAll('[data-reference-item]'));
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
                    referenceItems().forEach(function (item, index) {
                        const title = item.querySelector('.candidate-reference-item__header h2');
                        if (title) {
                            title.textContent = 'Reference ' + (index + 1);
                        }
                    });
                };

                const syncReferenceItem = function (item, values) {
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
                    referenceEditingIndex.value = '';
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
                    if (activeReferenceItem) {
                        activeReferenceItem.classList.remove('d-none');
                        activeReferenceItem = null;
                    }
                    referenceList.insertBefore(referenceForm, referenceList.firstElementChild);
                };

                const openReferenceForm = function (item) {
                    const section = document.getElementById('candidateReferencePanelBody');
                    if (section && typeof bootstrap !== 'undefined') {
                        bootstrap.Collapse.getOrCreateInstance(section, { toggle: false }).show();
                    }

                    closeReferenceForm();
                    activeReferenceItem = item || null;
                    referenceEditingIndex.value = item ? String(referenceItems().indexOf(item)) : '';
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
                        item.insertAdjacentElement('beforebegin', referenceForm);
                        item.classList.add('d-none');
                    } else {
                        referenceList.insertBefore(referenceForm, referenceList.firstElementChild);
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
                        item.remove();
                        closeReferenceForm();
                        renderReferenceNumbers();
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

                    if (activeReferenceItem) {
                        syncReferenceItem(activeReferenceItem, values);
                        activeReferenceItem.classList.remove('d-none');
                    } else {
                        referenceList.appendChild(makeReferenceItem(values));
                    }

                    closeReferenceForm();
                    renderReferenceNumbers();
                });

                closeReferenceForm();
                renderReferenceNumbers();
            }

            if (languageForm) {
                const languageSummary = document.querySelector('[data-language-summary]');
                const languageChipList = document.querySelector('[data-language-chip-list]');
                const languageEmpty = document.querySelector('[data-language-empty]');
                const languageInput = languageForm.querySelector('[data-language-name-input]');
                const languageLevelInput = languageForm.querySelector('[data-language-level-input]');
                const languageCurrentList = languageForm.querySelector('[data-language-current-list]');
                const languageEditAction = document.querySelector('[data-language-edit-action]');
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
                    languageSummary?.classList.toggle('d-none', isEditing);
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
                        items.forEach(function (item) {
                            const chip = document.createElement('div');
                            chip.className = 'candidate-skill-item';
                            chip.dataset.languageChip = '';
                            chip.dataset.languageId = item.dataset.languageId || '';
                            chip.dataset.languageName = item.dataset.languageName || '';
                            chip.dataset.languageLevel = item.dataset.languageLevel || '';
                            chip.innerHTML = [
                                '<div>',
                                '<strong></strong>',
                                '<span></span>',
                                '</div>',
                                '<div class="candidate-skill-item__actions">',
                                '<button type="button" data-language-item-edit><i class="fa-regular fa-pen-to-square"></i></button>',
                                '<button type="button" data-language-item-delete><i class="fa-regular fa-trash-can"></i></button>',
                                '</div>',
                            ].join('');
                            chip.querySelector('strong').textContent = item.dataset.languageName || '';
                            chip.querySelector('span').textContent = item.dataset.languageLevel || '---';
                            languageChipList.appendChild(chip);
                        });
                        languageChipList.classList.toggle('d-none', !items.length);
                    }
                    languageEmpty?.classList.toggle('d-none', Boolean(items.length));
                    if (languageEditAction) {
                        languageEditAction.innerHTML = '<i class="fa-solid fa-plus"></i> Add Language';
                    }
                };

                const makeLanguageCurrent = function (id, name, level) {
                    const chip = document.createElement('span');
                    chip.className = 'candidate-preferred-chip';
                    chip.dataset.languageCurrent = '';
                    chip.dataset.languageId = id || '';
                    chip.dataset.languageName = name;
                    chip.dataset.languageLevel = level || '';
                    chip.innerHTML = '<span></span><button type="button" data-language-remove aria-label="Remove language"><i class="fa-solid fa-xmark"></i></button>';
                    chip.querySelector('span').textContent = level ? name + ' - ' + level : name;
                    return chip;
                };

                const syncLanguages = function () {
                    const languageIds = languageItems()
                        .map(function (item) {
                            return item.dataset.languageId;
                        })
                        .filter(Boolean);
                    const skillIds = Array.from(document.querySelectorAll('[data-skill-item]'))
                        .map(function (item) {
                            return item.dataset.skillId;
                        })
                        .filter(Boolean);

                    if (!languageIds.length || !skillIds.length || !languageForm.dataset.updateUrl) {
                        return;
                    }

                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('first_name', languageForm.dataset.firstName || '');
                    formData.append('last_name', languageForm.dataset.lastName || '');
                    skillIds.forEach(function (skillId) {
                        formData.append('candidateSkills[]', skillId);
                    });
                    languageIds.forEach(function (languageId) {
                        formData.append('candidateLanguage[]', languageId);
                    });

                    fetch(languageForm.dataset.updateUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    }).catch(function () {});
                };

                languageEditAction?.addEventListener('click', function () {
                    const section = document.getElementById('candidateLanguageProficiencyPanelBody');
                    if (section && typeof bootstrap !== 'undefined') {
                        bootstrap.Collapse.getOrCreateInstance(section, { toggle: false }).show();
                    }
                    editingLanguageName = '';
                    languageInput.value = '';
                    languageLevelInput.value = '';
                    setLanguageFormMode(true);
                });

                languageForm.querySelector('[data-language-close]')?.addEventListener('click', function () {
                    languageInput.value = '';
                    languageLevelInput.value = '';
                    editingLanguageName = '';
                    setLanguageFormMode(false);
                });

                languageChipList?.addEventListener('click', function (event) {
                    const editButton = event.target.closest('[data-language-item-edit]');
                    const deleteButton = event.target.closest('[data-language-item-delete]');
                    const item = event.target.closest('[data-language-chip]');

                    if (editButton && item) {
                        editingLanguageName = item.dataset.languageName || '';
                        languageInput.value = item.dataset.languageName || '';
                        languageLevelInput.value = item.dataset.languageLevel || '';
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
                    const enteredName = languageInput.value.trim();
                    const matchedLanguage = languageOptions[enteredName.toLowerCase()] || {};
                    const languageId = matchedLanguage.id || '';
                    const languageName = matchedLanguage.name || enteredName;
                    const languageLevel = languageLevelInput.value;

                    if (!languageName) {
                        languageInput.focus();
                        return;
                    }

                    if (!languageLevel) {
                        languageLevelInput.focus();
                        return;
                    }

                    const existingLanguage = languageItems().find(function (item) {
                        return String(item.dataset.languageName || '').toLowerCase() === (editingLanguageName || languageName).toLowerCase();
                    });

                    if (existingLanguage) {
                        existingLanguage.dataset.languageId = languageId || existingLanguage.dataset.languageId || '';
                        existingLanguage.dataset.languageName = languageName;
                        existingLanguage.dataset.languageLevel = languageLevel;
                        existingLanguage.querySelector('span').textContent = languageName + ' - ' + languageLevel;
                    } else {
                        languageCurrentList.appendChild(makeLanguageCurrent(languageId, languageName, languageLevel));
                    }

                    languageInput.value = '';
                    languageLevelInput.value = '';
                    editingLanguageName = '';
                    renderLanguageSummary();
                    setLanguageFormMode(false);
                    syncLanguages();
                });

                renderLanguageSummary();
                setLanguageFormMode(false);
            }

            if (activityForm) {
                const activitySummary = document.querySelector('[data-activity-summary]');
                const activitySummaryContent = document.querySelector('[data-activity-summary-content]');
                const activityEmpty = document.querySelector('[data-activity-empty]');
                const activityEdit = document.querySelector('[data-activity-edit]');
                const activityAddAction = document.querySelector('[data-activity-add-action]');
                const activityInput = activityForm.querySelector('[data-activity-quill-input]');
                const activityEditor = activityForm.querySelector('[data-activity-quill-editor]');
                const activityCounter = activityForm.querySelector('[data-activity-character-count]');
                let activityQuill = null;
                let savedActivityContent = '';

                const setActivityFormMode = function (isEditing) {
                    activityForm.classList.toggle('d-none', !isEditing);
                    activitySummary?.classList.toggle('d-none', isEditing);
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

                const renderActivitySummary = function () {
                    const hasContent = savedActivityContent.trim().length > 0;
                    if (activitySummaryContent) {
                        activitySummaryContent.innerHTML = savedActivityContent;
                        activitySummaryContent.classList.toggle('d-none', !hasContent);
                    }
                    activityEmpty?.classList.toggle('d-none', hasContent);
                    activityEdit?.classList.toggle('d-none', !hasContent);
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

                activityForm.addEventListener('submit', function (event) {
                    event.preventDefault();
                    if (activityQuill) {
                        activityInput.value = activityQuill.getText().trim().length ? activityQuill.root.innerHTML : '';
                    }
                    savedActivityContent = activityInput.value || '';
                    renderActivitySummary();
                    setActivityFormMode(false);
                });

                activityForm.querySelector('[data-activity-close]')?.addEventListener('click', function () {
                    if (activityQuill) {
                        activityQuill.root.innerHTML = savedActivityContent || '';
                        activityInput.value = savedActivityContent || '';
                    }
                    updateActivityCounter();
                    setActivityFormMode(false);
                });

                activityAddAction?.addEventListener('click', function () {
                    openActivitySection();
                    setActivityFormMode(true);
                });

                activityEdit?.addEventListener('click', function () {
                    openActivitySection();
                    if (activityQuill) {
                        activityQuill.root.innerHTML = savedActivityContent || '';
                    }
                    setActivityFormMode(true);
                    updateActivityCounter();
                });

                renderActivitySummary();
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
                    const skillIds = Array.from(skillList.querySelectorAll('[data-skill-item]'))
                        .map(function (item) {
                            return item.dataset.skillId;
                        })
                        .filter(Boolean);

                    if (!skillIds.length || !skillManager.dataset.updateUrl) {
                        return;
                    }

                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('first_name', skillManager.dataset.firstName || '');
                    formData.append('last_name', skillManager.dataset.lastName || '');
                    skillIds.forEach(function (skillId) {
                        formData.append('candidateSkills[]', skillId);
                    });

                    fetch(skillManager.dataset.updateUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    }).catch(function () {});
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
