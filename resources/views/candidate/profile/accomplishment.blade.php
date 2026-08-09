@extends('candidate.profile.index')
@section('section')
    @php
        $candidatePortfolios = $data['candidatePortfolios'] ?? collect();
        $candidatePublications = $data['candidatePublications'] ?? collect();
        $candidateAwards = $data['candidateAwards'] ?? collect();
        $candidateProjects = $data['candidateProjects'] ?? collect();
        $candidateOthers = $data['candidateOthers'] ?? collect();
    @endphp
    <div class="mb-xl-8 candidate-accomplishment-page">
        <div class="candidate-education-panel" id="candidatePortfolioInformation">
            <div class="candidate-education-panel__header">
                <h1>{{ __('messages.candidate_profile.portfolio') }} <span class="candidate-portfolio-limit">{{ __('messages.candidate_profile.max_2') }}</span></h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-add" data-portfolio-add-action>
                        <i class="fa-solid fa-plus"></i>
                        {{ __('messages.candidate_profile.add_portfolio') }}
                    </button>
                    <button type="button" class="candidate-education-collapse" data-bs-toggle="collapse"
                            data-bs-target="#candidatePortfolioInformationPanelBody" aria-expanded="true"
                            aria-controls="candidatePortfolioInformationPanelBody"
                            data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                            data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.collapse') }}</span>
                        <i class="fa-solid fa-chevron-up"></i>
                    </button>
                </div>
            </div>
            <div id="candidatePortfolioInformationPanelBody" class="collapse show candidate-profile-section__collapse">
                <div class="candidate-profile-section__body candidate-education-panel__body">
                    <div class="candidate-portfolio-list" data-portfolio-list>
                        <form class="candidate-portfolio-form d-none" data-portfolio-form
                              data-store-url="{{ route('candidate-profile.portfolios.store') }}">
                            @csrf
                            <h2 data-portfolio-form-title>{{ __('messages.candidate_profile.portfolio') }}</h2>
                            <input type="hidden" data-portfolio-editing-id>
                            <div class="candidate-skill-form__field">
                                <label for="candidatePortfolioTitle">{{ __('messages.candidate_profile.title') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="candidatePortfolioTitle" name="title"
                                       data-portfolio-title-input placeholder="{{ __('messages.candidate_profile.enter_title') }}" required>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidatePortfolioUrl">{{ __('messages.candidate_profile.url') }}</label>
                                <input type="url" class="form-control" id="candidatePortfolioUrl" name="url"
                                       data-portfolio-url-input placeholder="{{ __('messages.candidate_profile.enter_url') }}">
                            </div>
                            <div class="candidate-skill-form__field">
                                <label>{{ __('messages.candidate_profile.description') }} <span class="text-danger">*</span></label>
                                <input type="hidden" name="description" data-portfolio-description-input>
                                <div class="candidate-portfolio-editor">
                                    <div class="candidate-portfolio-quill" data-portfolio-description-editor
                                         data-placeholder="{{ __('messages.candidate_profile.enter_writing_texts') }}"></div>
                                </div>
                                <p class="candidate-portfolio-counter">
                                    {{ __('messages.candidate_profile.you_wrote') }} <strong data-portfolio-character-count>0/300</strong> {{ __('messages.candidate_profile.characters') }}
                                </p>
                            </div>
                            <div class="candidate-skill-form__actions">
                                <button type="submit" class="candidate-skill-save" data-portfolio-submit>{{ __('messages.candidate_profile.save') }}</button>
                                <button type="button" class="candidate-skill-close" data-portfolio-close>{{ __('messages.candidate_profile.close') }}</button>
                            </div>
                        </form>

                        @forelse($candidatePortfolios as $portfolio)
                            @php
                                $portfolioDescription = strip_tags((string) $portfolio->description, '<p><br><strong><b><em><i><ul><ol><li>');
                            @endphp
                            <div class="candidate-portfolio-item" data-portfolio-item
                                 data-portfolio-id="{{ $portfolio->id }}"
                                 data-portfolio-title="{{ $portfolio->title }}"
                                 data-portfolio-url="{{ $portfolio->url }}"
                                 data-update-url="{{ route('candidate-profile.portfolios.update', $portfolio) }}"
                                 data-delete-url="{{ route('candidate-profile.portfolios.destroy', $portfolio) }}">
                                <div class="candidate-portfolio-item__header">
                                    <h2>{{ $loop->iteration }}. {{ $portfolio->title }}</h2>
                                    <div class="candidate-portfolio-actions">
                                        <button type="button" data-portfolio-edit>
                                            <i class="fa-regular fa-pen-to-square"></i>
                                            {{ __('messages.candidate_profile.edit') }}
                                        </button>
                                        <button type="button" data-portfolio-delete>
                                            <i class="fa-regular fa-trash-can"></i>
                                            {{ __('messages.candidate_profile.delete') }}
                                        </button>
                                    </div>
                                </div>

                                <div class="candidate-portfolio-field">
                                    <span>{{ __('messages.candidate_profile.url') }}</span>
                                    @if(filled($portfolio->url))
                                        <a href="{{ addLinkHttpUrl($portfolio->url) }}" target="_blank" rel="noopener" data-portfolio-url-text>{{ $portfolio->url }}</a>
                                        <strong class="d-none" data-portfolio-url-empty>---</strong>
                                    @else
                                        <a href="#" target="_blank" rel="noopener" class="d-none" data-portfolio-url-text></a>
                                        <strong data-portfolio-url-empty>---</strong>
                                    @endif
                                </div>

                                <div class="candidate-portfolio-field">
                                    <span>{{ __('messages.candidate_profile.description') }}</span>
                                    <div data-portfolio-description-text>{!! $portfolioDescription ?: '---' !!}</div>
                                </div>
                            </div>
                        @empty
                            <p class="candidate-skill-empty candidate-portfolio-empty" data-portfolio-empty>---</p>
                        @endforelse

                        {{-- <button type="button" class="candidate-portfolio-add-outline" data-portfolio-add-action>
                            <i class="fa-solid fa-plus"></i>
                            {{ __('messages.candidate_profile.add_portfolio') }}
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel" id="candidatePublicationInformation">
            <div class="candidate-education-panel__header collapsed">
                <h1>{{ __('messages.candidate_profile.publication') }} <span class="candidate-portfolio-limit">{{ __('messages.candidate_profile.max_5') }}</span></h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-add d-none" data-publication-add-action>
                        <i class="fa-solid fa-plus"></i>
                        {{ __('messages.candidate_profile.add_publication') }}
                    </button>
                    <button type="button" class="candidate-education-collapse" data-bs-toggle="collapse"
                            data-bs-target="#candidatePublicationInformationPanelBody" aria-expanded="false"
                            aria-controls="candidatePublicationInformationPanelBody"
                            data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                            data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.expand') }}</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
            </div>
            <div id="candidatePublicationInformationPanelBody" class="collapse candidate-profile-section__collapse">
                <div class="candidate-profile-section__body candidate-education-panel__body">
                    <div class="candidate-publication-list" data-publication-list>
                        <form class="candidate-publication-form d-none" data-publication-form
                              data-store-url="{{ route('candidate-profile.publications.store') }}">
                            @csrf
                            <h2 data-publication-form-title>{{ __('messages.candidate_profile.publication') }}</h2>
                            <input type="hidden" data-publication-editing-id>
                            <div class="candidate-skill-form__field">
                                <label for="candidatePublicationTitle">{{ __('messages.candidate_profile.title') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="candidatePublicationTitle" name="title"
                                       data-publication-title-input placeholder="{{ __('messages.candidate_profile.enter_title') }}" required>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidatePublicationIssuedOn">{{ __('messages.candidate_profile.issued_on') }} <span class="text-danger">*</span></label>
                                <div class="candidate-publication-date-field" style="position: relative; width: 100%; height: 38px;">
                                    <i class="fa-regular fa-calendar" style="align-items: center; bottom: 0; color: #52637a; display: flex; font-size: 16px; height: 38px; justify-content: center; left: 14px; line-height: 1; pointer-events: none; position: absolute; top: 0; width: 16px; z-index: 2;"></i>
                                    <input type="text" class="form-control" id="candidatePublicationIssuedOn" name="issued_on"
                                           data-publication-issued-input placeholder="MM/DD/YY" required
                                           style="height: 38px; padding-left: 38px; width: 100%;">
                                </div>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidatePublicationUrl">{{ __('messages.candidate_profile.url') }}</label>
                                <input type="url" class="form-control" id="candidatePublicationUrl" name="url"
                                       data-publication-url-input placeholder="{{ __('messages.candidate_profile.enter_url') }}">
                            </div>
                            <div class="candidate-skill-form__field">
                                <label>{{ __('messages.candidate_profile.description') }} <span class="text-danger">*</span></label>
                                <input type="hidden" name="description" data-publication-description-input>
                                <div class="candidate-publication-editor">
                                    <div class="candidate-publication-quill" data-publication-description-editor
                                         data-placeholder="{{ __('messages.candidate_profile.enter_writing_texts') }}"></div>
                                </div>
                                <p class="candidate-publication-counter">
                                    {{ __('messages.candidate_profile.you_wrote') }} <strong data-publication-character-count>0/300</strong> {{ __('messages.candidate_profile.characters') }}
                                </p>
                            </div>
                            <div class="candidate-skill-form__actions">
                                <button type="submit" class="candidate-skill-save" data-publication-submit>{{ __('messages.candidate_profile.save') }}</button>
                                <button type="button" class="candidate-skill-close" data-publication-close>{{ __('messages.candidate_profile.close') }}</button>
                            </div>
                        </form>

                        @forelse($candidatePublications as $publication)
                            @php
                                $publicationDescription = strip_tags((string) $publication->description, '<p><br><strong><b><em><i><ul><ol><li>');
                            @endphp
                            <div class="candidate-publication-item" data-publication-item
                                 data-publication-id="{{ $publication->id }}"
                                 data-publication-title="{{ $publication->title }}"
                                 data-publication-issued="{{ optional($publication->issued_on)->format('d M Y') }}"
                                 data-publication-issued-value="{{ optional($publication->issued_on)->format('Y-m-d') }}"
                                 data-publication-url="{{ $publication->url }}"
                                 data-update-url="{{ route('candidate-profile.publications.update', $publication) }}"
                                 data-delete-url="{{ route('candidate-profile.publications.destroy', $publication) }}">
                                <div class="candidate-publication-item__header">
                                    <h2>{{ $loop->iteration }}. {{ $publication->title }}</h2>
                                    <div class="candidate-publication-actions">
                                        <button type="button" data-publication-edit>
                                            <i class="fa-regular fa-pen-to-square"></i>
                                            {{ __('messages.candidate_profile.edit') }}
                                        </button>
                                        <button type="button" data-publication-delete>
                                            <i class="fa-regular fa-trash-can"></i>
                                            {{ __('messages.candidate_profile.delete') }}
                                        </button>
                                    </div>
                                </div>

                                <div class="candidate-publication-field">
                                    <span>{{ __('messages.candidate_profile.issued_on') }}</span>
                                    <strong data-publication-issued-text>{{ optional($publication->issued_on)->format('d M Y') ?: '---' }}</strong>
                                </div>

                                <div class="candidate-publication-field">
                                    <span>{{ __('messages.candidate_profile.url') }}</span>
                                    @if(filled($publication->url))
                                        <a href="{{ addLinkHttpUrl($publication->url) }}" target="_blank" rel="noopener" data-publication-url-text>{{ $publication->url }}</a>
                                        <strong class="d-none" data-publication-url-empty>---</strong>
                                    @else
                                        <a href="#" target="_blank" rel="noopener" class="d-none" data-publication-url-text></a>
                                        <strong data-publication-url-empty>---</strong>
                                    @endif
                                </div>

                                <div class="candidate-publication-field">
                                    <span>{{ __('messages.candidate_profile.description') }}</span>
                                    <div data-publication-description-text>{!! $publicationDescription ?: '---' !!}</div>
                                </div>
                            </div>
                        @empty
                            <p class="candidate-skill-empty candidate-publication-empty" data-publication-empty>---</p>
                        @endforelse

                        {{-- <button type="button" class="candidate-publication-add-outline" data-publication-add-action>
                            <i class="fa-solid fa-plus"></i>
                            {{ __('messages.candidate_profile.add_publication') }}
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel" id="candidateAwardHonorInformation">
            <div class="candidate-education-panel__header collapsed">
                <h1>{{ __('messages.candidate_profile.award') }} <span class="candidate-portfolio-limit">{{ __('messages.candidate_profile.max_5') }}</span></h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-add d-none" data-award-add-action>
                        <i class="fa-solid fa-plus"></i>
                        {{ __('messages.candidate_profile.add_award') }}
                    </button>
                    <button type="button" class="candidate-education-collapse" data-bs-toggle="collapse"
                            data-bs-target="#candidateAwardHonorInformationPanelBody" aria-expanded="false"
                            aria-controls="candidateAwardHonorInformationPanelBody"
                            data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                            data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.expand') }}</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
            </div>
            <div id="candidateAwardHonorInformationPanelBody" class="collapse candidate-profile-section__collapse">
                <div class="candidate-profile-section__body candidate-education-panel__body">
                    <div class="candidate-publication-list" data-award-list>
                        <form class="candidate-publication-form d-none" data-award-form
                              data-store-url="{{ route('candidate-profile.awards.store') }}">
                            @csrf
                            <h2 data-award-form-title>{{ __('messages.candidate_profile.award') }}</h2>
                            <input type="hidden" data-award-editing-id>
                            <div class="candidate-skill-form__field">
                                <label for="candidateAwardTitle">{{ __('messages.candidate_profile.title') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="candidateAwardTitle" name="title"
                                       data-award-title-input placeholder="{{ __('messages.candidate_profile.enter_title') }}" required>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidateAwardIssuedOn">{{ __('messages.candidate_profile.issued_on') }} <span class="text-danger">*</span></label>
                                <div class="candidate-publication-date-field" style="position: relative; width: 100%; height: 38px;">
                                    <i class="fa-regular fa-calendar" style="align-items: center; bottom: 0; color: #52637a; display: flex; font-size: 16px; height: 38px; justify-content: center; left: 14px; line-height: 1; pointer-events: none; position: absolute; top: 0; width: 16px; z-index: 2;"></i>
                                    <input type="text" class="form-control" id="candidateAwardIssuedOn" name="issued_on"
                                           data-award-issued-input placeholder="MM/DD/YY" required
                                           style="height: 38px; padding-left: 38px; width: 100%;">
                                </div>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidateAwardUrl">{{ __('messages.candidate_profile.url') }}</label>
                                <input type="url" class="form-control" id="candidateAwardUrl" name="url"
                                       data-award-url-input placeholder="{{ __('messages.candidate_profile.enter_url') }}">
                            </div>
                            <div class="candidate-skill-form__field">
                                <label>{{ __('messages.candidate_profile.description') }} <span class="text-danger">*</span></label>
                                <input type="hidden" name="description" data-award-description-input>
                                <div class="candidate-publication-editor">
                                    <div class="candidate-award-quill" data-award-description-editor
                                         data-placeholder="{{ __('messages.candidate_profile.enter_writing_texts') }}"></div>
                                </div>
                                <p class="candidate-publication-counter">
                                    {{ __('messages.candidate_profile.you_wrote') }} <strong data-award-character-count>0/300</strong> {{ __('messages.candidate_profile.characters') }}
                                </p>
                            </div>
                            <div class="candidate-skill-form__actions">
                                <button type="submit" class="candidate-skill-save" data-award-submit>{{ __('messages.candidate_profile.save') }}</button>
                                <button type="button" class="candidate-skill-close" data-award-close>{{ __('messages.candidate_profile.close') }}</button>
                            </div>
                        </form>

                        @forelse($candidateAwards as $award)
                            @php
                                $awardDescription = strip_tags((string) $award->description, '<p><br><strong><b><em><i><ul><ol><li>');
                            @endphp
                            <div class="candidate-publication-item" data-award-item
                                 data-award-id="{{ $award->id }}"
                                 data-award-title="{{ $award->title }}"
                                 data-award-issued="{{ optional($award->issued_on)->format('d M Y') }}"
                                 data-award-issued-value="{{ optional($award->issued_on)->format('Y-m-d') }}"
                                 data-award-url="{{ $award->url }}"
                                 data-update-url="{{ route('candidate-profile.awards.update', $award) }}"
                                 data-delete-url="{{ route('candidate-profile.awards.destroy', $award) }}">
                                <div class="candidate-publication-item__header">
                                    <h2>{{ $loop->iteration }}. {{ $award->title }}</h2>
                                    <div class="candidate-publication-actions">
                                        <button type="button" data-award-edit>
                                            <i class="fa-regular fa-pen-to-square"></i>
                                            {{ __('messages.candidate_profile.edit') }}
                                        </button>
                                        <button type="button" data-award-delete>
                                            <i class="fa-regular fa-trash-can"></i>
                                            {{ __('messages.candidate_profile.delete') }}
                                        </button>
                                    </div>
                                </div>

                                <div class="candidate-publication-field">
                                    <span>{{ __('messages.candidate_profile.issued_on') }}</span>
                                    <strong data-award-issued-text>{{ optional($award->issued_on)->format('d M Y') ?: '---' }}</strong>
                                </div>

                                <div class="candidate-publication-field">
                                    <span>{{ __('messages.candidate_profile.url') }}</span>
                                    @if(filled($award->url))
                                        <a href="{{ addLinkHttpUrl($award->url) }}" target="_blank" rel="noopener" data-award-url-text>{{ $award->url }}</a>
                                        <strong class="d-none" data-award-url-empty>---</strong>
                                    @else
                                        <a href="#" target="_blank" rel="noopener" class="d-none" data-award-url-text></a>
                                        <strong data-award-url-empty>---</strong>
                                    @endif
                                </div>

                                <div class="candidate-publication-field">
                                    <span>{{ __('messages.candidate_profile.description') }}</span>
                                    <div data-award-description-text>{!! $awardDescription ?: '---' !!}</div>
                                </div>
                            </div>
                        @empty
                            <p class="candidate-skill-empty candidate-publication-empty" data-award-empty>---</p>
                        @endforelse

                        {{-- <button type="button" class="candidate-publication-add-outline" data-award-add-action>
                            <i class="fa-solid fa-plus"></i>
                            {{ __('messages.candidate_profile.add_award') }}
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel" id="candidateProjectInformation">
            <div class="candidate-education-panel__header collapsed">
                <h1>{{ __('messages.candidate_profile.project') }} <span class="candidate-portfolio-limit">{{ __('messages.candidate_profile.max_5') }}</span></h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-add d-none" data-project-add-action>
                        <i class="fa-solid fa-plus"></i>
                        {{ __('messages.candidate_profile.add_project') }}
                    </button>
                    <button type="button" class="candidate-education-collapse" data-bs-toggle="collapse"
                            data-bs-target="#candidateProjectInformationPanelBody" aria-expanded="false"
                            aria-controls="candidateProjectInformationPanelBody"
                            data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                            data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.expand') }}</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
            </div>
            <div id="candidateProjectInformationPanelBody" class="collapse candidate-profile-section__collapse">
                <div class="candidate-profile-section__body candidate-education-panel__body">
                    <div class="candidate-project-list" data-project-list>
                        <form class="candidate-project-form d-none" data-project-form
                              data-store-url="{{ route('candidate-profile.projects.store') }}">
                            @csrf
                            <input type="hidden" data-project-editing-id>
                            <div class="candidate-skill-form__field">
                                <label for="candidateProjectTitle">{{ __('messages.candidate_profile.title') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="candidateProjectTitle" name="title"
                                       data-project-title-input placeholder="{{ __('messages.candidate_profile.enter_title') }}" required>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidateProjectIssuedOn">{{ __('messages.candidate_profile.issued_on') }} <span class="text-danger">*</span></label>
                                <div class="candidate-publication-date-field" style="position: relative; width: 100%; height: 38px;">
                                    <i class="fa-regular fa-calendar" style="align-items: center; bottom: 0; color: #52637a; display: flex; font-size: 16px; height: 38px; justify-content: center; left: 14px; line-height: 1; pointer-events: none; position: absolute; top: 0; width: 16px; z-index: 2;"></i>
                                    <input type="text" class="form-control" id="candidateProjectIssuedOn" name="issued_on"
                                           data-project-issued-input placeholder="MM/DD/YY" required
                                           style="height: 38px; padding-left: 38px; width: 100%;">
                                </div>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidateProjectUrl">{{ __('messages.candidate_profile.url') }}</label>
                                <input type="url" class="form-control" id="candidateProjectUrl" name="url"
                                       data-project-url-input placeholder="{{ __('messages.candidate_profile.enter_url') }}">
                            </div>
                            <div class="candidate-skill-form__field">
                                <label>{{ __('messages.candidate_profile.description') }} <span class="text-danger">*</span></label>
                                <input type="hidden" name="description" data-project-description-input>
                                <div class="candidate-project-editor">
                                    <div class="candidate-project-quill" data-project-description-editor
                                         data-placeholder="{{ __('messages.candidate_profile.enter_writing_texts') }}"></div>
                                </div>
                                <p class="candidate-project-counter">
                                    {{ __('messages.candidate_profile.you_wrote') }} <strong data-project-character-count>0/300</strong> {{ __('messages.candidate_profile.characters') }}
                                </p>
                            </div>
                            <div class="candidate-skill-form__actions">
                                <button type="submit" class="candidate-skill-save" data-project-submit>{{ __('messages.candidate_profile.save') }}</button>
                                <button type="button" class="candidate-skill-close" data-project-close>{{ __('messages.candidate_profile.close') }}</button>
                            </div>
                        </form>

                        @forelse($candidateProjects as $project)
                            @php
                                $projectDescription = strip_tags((string) $project->description, '<p><br><strong><b><em><i><ul><ol><li>');
                            @endphp
                            <div class="candidate-project-item" data-project-item
                                 data-project-id="{{ $project->id }}"
                                 data-project-title="{{ $project->title }}"
                                 data-project-issued="{{ optional($project->issued_on)->format('d M Y') }}"
                                 data-project-issued-value="{{ optional($project->issued_on)->format('Y-m-d') }}"
                                 data-project-url="{{ $project->url }}"
                                 data-update-url="{{ route('candidate-profile.projects.update', $project) }}"
                                 data-delete-url="{{ route('candidate-profile.projects.destroy', $project) }}">
                                <div class="candidate-project-item__header">
                                    <h2>{{ $loop->iteration }}. {{ $project->title }}</h2>
                                    <div class="candidate-project-actions">
                                        <button type="button" data-project-edit>
                                            <i class="fa-regular fa-pen-to-square"></i>
                                            {{ __('messages.candidate_profile.edit') }}
                                        </button>
                                        <button type="button" data-project-delete>
                                            <i class="fa-regular fa-trash-can"></i>
                                            {{ __('messages.candidate_profile.delete') }}
                                        </button>
                                    </div>
                                </div>
                                <div class="candidate-project-field">
                                    <span>{{ __('messages.candidate_profile.issued_on') }}</span>
                                    <strong data-project-issued-text>{{ optional($project->issued_on)->format('d M Y') ?: '---' }}</strong>
                                </div>
                                <div class="candidate-project-field">
                                    <span>{{ __('messages.candidate_profile.url') }}</span>
                                    @if(filled($project->url))
                                        <a href="{{ addLinkHttpUrl($project->url) }}" target="_blank" rel="noopener" data-project-url-text>{{ $project->url }}</a>
                                        <strong class="d-none" data-project-url-empty>---</strong>
                                    @else
                                        <a href="#" target="_blank" rel="noopener" class="d-none" data-project-url-text></a>
                                        <strong data-project-url-empty>---</strong>
                                    @endif
                                </div>
                                <div class="candidate-project-field">
                                    <span>{{ __('messages.candidate_profile.description') }}</span>
                                    <div data-project-description-text>{!! $projectDescription ?: '---' !!}</div>
                                </div>
                            </div>
                        @empty
                            <div class="candidate-project-empty" data-project-empty>---</div>
                        @endforelse

                        {{-- <button type="button" class="candidate-project-add-outline" data-project-add-action>
                            <i class="fa-solid fa-plus"></i>
                            {{ __('messages.candidate_profile.add_project') }}
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel" id="candidateOtherAccomplishmentInformation">
            <div class="candidate-education-panel__header collapsed">
                <h1>{{ __('messages.candidate_profile.other') }} <span class="candidate-portfolio-limit">{{ __('messages.candidate_profile.max_5') }}</span></h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-add d-none" data-other-add-action>
                        <i class="fa-solid fa-plus"></i>
                        {{ __('messages.candidate_profile.add_other') }}
                    </button>
                    <button type="button" class="candidate-education-collapse" data-bs-toggle="collapse"
                            data-bs-target="#candidateOtherAccomplishmentInformationPanelBody" aria-expanded="false"
                            aria-controls="candidateOtherAccomplishmentInformationPanelBody"
                            data-collapse-label="{{ __('messages.candidate_profile.collapse') }}"
                            data-expand-label="{{ __('messages.candidate_profile.expand') }}">
                        <span>{{ __('messages.candidate_profile.expand') }}</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
            </div>
            <div id="candidateOtherAccomplishmentInformationPanelBody" class="collapse candidate-profile-section__collapse">
                <div class="candidate-profile-section__body candidate-education-panel__body">
                    <div class="candidate-other-list" data-other-list>
                        <form class="candidate-other-form d-none" data-other-form
                              data-store-url="{{ route('candidate-profile.others.store') }}">
                            @csrf
                            <h2 data-other-form-title>{{ __('messages.candidate_profile.other_accomplishment') }}</h2>
                            <input type="hidden" data-other-editing-id>
                            <div class="candidate-skill-form__field">
                                <label for="candidateOtherTitle">{{ __('messages.candidate_profile.title') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="candidateOtherTitle" name="title"
                                       data-other-title-input placeholder="{{ __('messages.candidate_profile.enter_title') }}" required>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidateOtherIssuedOn">{{ __('messages.candidate_profile.issued_on') }} <span class="text-danger">*</span></label>
                                <div class="candidate-publication-date-field" style="position: relative; width: 100%; height: 38px;">
                                    <i class="fa-regular fa-calendar" style="align-items: center; bottom: 0; color: #52637a; display: flex; font-size: 16px; height: 38px; justify-content: center; left: 14px; line-height: 1; pointer-events: none; position: absolute; top: 0; width: 16px; z-index: 2;"></i>
                                    <input type="text" class="form-control" id="candidateOtherIssuedOn" name="issued_on"
                                           data-other-issued-input placeholder="MM/DD/YY" required
                                           style="height: 38px; padding-left: 38px; width: 100%;">
                                </div>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidateOtherUrl">{{ __('messages.candidate_profile.url') }}</label>
                                <input type="url" class="form-control" id="candidateOtherUrl" name="url"
                                       data-other-url-input placeholder="{{ __('messages.candidate_profile.enter_url') }}">
                            </div>
                            <div class="candidate-skill-form__field">
                                <label>{{ __('messages.candidate_profile.description') }} <span class="text-danger">*</span></label>
                                <input type="hidden" name="description" data-other-description-input>
                                <div class="candidate-other-editor">
                                    <div class="candidate-other-quill" data-other-description-editor
                                         data-placeholder="{{ __('messages.candidate_profile.enter_writing_texts') }}"></div>
                                </div>
                                <p class="candidate-other-counter">
                                    {{ __('messages.candidate_profile.you_wrote') }} <strong data-other-character-count>0/300</strong> {{ __('messages.candidate_profile.characters') }}
                                </p>
                            </div>
                            <div class="candidate-skill-form__actions">
                                <button type="submit" class="candidate-skill-save" data-other-submit>{{ __('messages.candidate_profile.save') }}</button>
                                <button type="button" class="candidate-skill-close" data-other-close>{{ __('messages.candidate_profile.close') }}</button>
                            </div>
                        </form>

                        @forelse($candidateOthers as $other)
                            @php
                                $otherDescription = strip_tags((string) $other->description, '<p><br><strong><b><em><i><ul><ol><li>');
                            @endphp
                            <div class="candidate-other-item" data-other-item
                                 data-other-id="{{ $other->id }}"
                                 data-other-title="{{ $other->title }}"
                                 data-other-issued="{{ optional($other->issued_on)->format('d M Y') }}"
                                 data-other-issued-value="{{ optional($other->issued_on)->format('Y-m-d') }}"
                                 data-other-url="{{ $other->url }}"
                                 data-update-url="{{ route('candidate-profile.others.update', $other) }}"
                                 data-delete-url="{{ route('candidate-profile.others.destroy', $other) }}">
                                <div class="candidate-other-item__header">
                                    <h2>{{ $loop->iteration }}. {{ $other->title }}</h2>
                                    <div class="candidate-other-actions">
                                        <button type="button" data-other-edit>
                                            <i class="fa-regular fa-pen-to-square"></i>
                                            {{ __('messages.candidate_profile.edit') }}
                                        </button>
                                        <button type="button" data-other-delete>
                                            <i class="fa-regular fa-trash-can"></i>
                                            {{ __('messages.candidate_profile.delete') }}
                                        </button>
                                    </div>
                                </div>
                                <div class="candidate-other-field">
                                    <span>{{ __('messages.candidate_profile.issued_on') }}</span>
                                    <strong data-other-issued-text>{{ optional($other->issued_on)->format('d M Y') ?: '---' }}</strong>
                                </div>
                                <div class="candidate-other-field">
                                    <span>{{ __('messages.candidate_profile.url') }}</span>
                                    @if(filled($other->url))
                                        <a href="{{ addLinkHttpUrl($other->url) }}" target="_blank" rel="noopener" data-other-url-text>{{ $other->url }}</a>
                                        <strong class="d-none" data-other-url-empty>---</strong>
                                    @else
                                        <a href="#" target="_blank" rel="noopener" class="d-none" data-other-url-text></a>
                                        <strong data-other-url-empty>---</strong>
                                    @endif
                                </div>
                                <div class="candidate-other-field">
                                    <span>{{ __('messages.candidate_profile.description') }}</span>
                                    <div data-other-description-text>{!! $otherDescription ?: '---' !!}</div>
                                </div>
                            </div>
                        @empty
                            <div class="candidate-other-empty" data-other-empty>---</div>
                        @endforelse

                        {{-- <button type="button" class="candidate-other-add-outline" data-other-add-action>
                            <i class="fa-solid fa-plus"></i>
                            {{ __('messages.candidate_profile.add_other') }}
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const accomplishmentSectionLinks = document.querySelectorAll('[data-accomplishment-section-link]');
            const accomplishmentSectionBodies = document.querySelectorAll('.candidate-accomplishment-page .candidate-profile-section__collapse');
            const portfolioList = document.querySelector('[data-portfolio-list]');
            const portfolioForm = document.querySelector('[data-portfolio-form]');
            const portfolioAddActions = document.querySelectorAll('[data-portfolio-add-action]');
            const maxPortfolioItems = 2;
            const publicationList = document.querySelector('[data-publication-list]');
            const publicationForm = document.querySelector('[data-publication-form]');
            const publicationAddActions = document.querySelectorAll('[data-publication-add-action]');
            const maxPublicationItems = 5;
            const awardList = document.querySelector('[data-award-list]');
            const awardForm = document.querySelector('[data-award-form]');
            const awardAddActions = document.querySelectorAll('[data-award-add-action]');
            const maxAwardItems = 5;
            const projectList = document.querySelector('[data-project-list]');
            const projectAddActions = document.querySelectorAll('[data-project-add-action]');
            const maxProjectItems = 5;
            const otherList = document.querySelector('[data-other-list]');
            const otherForm = document.querySelector('[data-other-form]');
            const otherAddActions = document.querySelectorAll('[data-other-add-action]');
            const maxOtherItems = 5;

            const initAccomplishmentDatePicker = function (input) {
                if (!input || input.dataset.flatpickrReady === 'true') {
                    return;
                }

                if (typeof flatpickr !== 'undefined') {
                    const resizeCalendar = function (calendar) {
                        const field = input.closest('.candidate-skill-form__field') || input.closest('.candidate-publication-date-field');
                        const width = field ? Math.round(field.getBoundingClientRect().width) : 0;

                        calendar.classList.add('candidate-accomplishment-calendar');
                        if (width > 0) {
                            calendar.style.setProperty('width', width + 'px', 'important');
                        }
                    };

                    const applyHeaderDropdowns = function (instance) {
                        const calendar = instance.calendarContainer;
                        const monthSelect = calendar.querySelector('.flatpickr-current-month .flatpickr-monthDropdown-months');
                        const yearWrapper = calendar.querySelector('.flatpickr-current-month .numInputWrapper');
                        const monthNames = instance.l10n.months.longhand;

                        const closeMenus = function (exceptMenu) {
                            calendar.querySelectorAll('.candidate-accomplishment-picker-menu').forEach(function (menu) {
                                if (menu !== exceptMenu) {
                                    menu.classList.add('d-none');
                                    menu.previousElementSibling?.setAttribute('aria-expanded', 'false');
                                }
                            });
                        };

                        const bindMenu = function (button, menu) {
                            button.addEventListener('click', function (event) {
                                event.preventDefault();
                                event.stopPropagation();

                                if (menu.classList.contains('d-none')) {
                                    closeMenus(menu);
                                    menu.classList.remove('d-none');
                                    button.setAttribute('aria-expanded', 'true');
                                    menu.querySelector('[aria-selected="true"]')?.scrollIntoView({ block: 'center' });
                                } else {
                                    closeMenus();
                                }
                            });
                        };

                        if (monthSelect && monthSelect.dataset.monthDropdownReady !== 'true') {
                            const monthWrapper = document.createElement('span');
                            const monthButton = document.createElement('button');
                            const monthMenu = document.createElement('div');

                            monthWrapper.className = 'candidate-accomplishment-month-wrapper';
                            monthButton.type = 'button';
                            monthButton.className = 'candidate-accomplishment-month-select';
                            monthButton.textContent = monthNames[instance.currentMonth];
                            monthButton.setAttribute('aria-haspopup', 'listbox');
                            monthButton.setAttribute('aria-expanded', 'false');
                            monthMenu.className = 'candidate-accomplishment-picker-menu candidate-accomplishment-month-menu d-none';
                            monthMenu.setAttribute('role', 'listbox');

                            monthNames.forEach(function (month, index) {
                                const option = document.createElement('button');
                                option.type = 'button';
                                option.className = 'candidate-accomplishment-picker-option';
                                option.dataset.month = String(index);
                                option.textContent = month;
                                option.setAttribute('role', 'option');
                                option.setAttribute('aria-selected', index === instance.currentMonth ? 'true' : 'false');
                                monthMenu.appendChild(option);
                            });

                            bindMenu(monthButton, monthMenu);
                            monthMenu.addEventListener('click', function (event) {
                                const option = event.target.closest('[data-month]');
                                if (!option) {
                                    return;
                                }

                                event.preventDefault();
                                event.stopPropagation();
                                instance.changeMonth(Number(option.dataset.month), false);
                                monthButton.textContent = option.textContent;
                                monthMenu.querySelectorAll('[aria-selected="true"]').forEach(function (selected) {
                                    selected.setAttribute('aria-selected', 'false');
                                });
                                option.setAttribute('aria-selected', 'true');
                                closeMenus();
                            });

                            monthWrapper.appendChild(monthButton);
                            monthWrapper.appendChild(monthMenu);
                            monthSelect.classList.add('d-none');
                            monthSelect.dataset.monthDropdownReady = 'true';
                            monthSelect.insertAdjacentElement('afterend', monthWrapper);
                        }

                        if (yearWrapper && yearWrapper.dataset.yearDropdownReady !== 'true') {
                            const yearInput = yearWrapper.querySelector('.cur-year');
                            const yearButton = document.createElement('button');
                            const yearMenu = document.createElement('div');
                            const selectedYear = instance.currentYear || new Date().getFullYear();
                            const currentYear = new Date().getFullYear();

                            yearButton.type = 'button';
                            yearButton.className = 'candidate-accomplishment-year-select';
                            yearButton.textContent = String(selectedYear);
                            yearButton.setAttribute('aria-haspopup', 'listbox');
                            yearButton.setAttribute('aria-expanded', 'false');
                            yearMenu.className = 'candidate-accomplishment-picker-menu candidate-accomplishment-year-menu d-none';
                            yearMenu.setAttribute('role', 'listbox');

                            for (let year = currentYear; year >= 1970; year--) {
                                const option = document.createElement('button');
                                option.type = 'button';
                                option.className = 'candidate-accomplishment-picker-option';
                                option.dataset.year = String(year);
                                option.textContent = String(year);
                                option.setAttribute('role', 'option');
                                option.setAttribute('aria-selected', year === selectedYear ? 'true' : 'false');
                                yearMenu.appendChild(option);
                            }

                            bindMenu(yearButton, yearMenu);
                            yearMenu.addEventListener('click', function (event) {
                                const option = event.target.closest('[data-year]');
                                if (!option) {
                                    return;
                                }

                                event.preventDefault();
                                event.stopPropagation();
                                instance.changeYear(Number(option.dataset.year));
                                yearButton.textContent = option.dataset.year;
                                yearMenu.querySelectorAll('[aria-selected="true"]').forEach(function (selected) {
                                    selected.setAttribute('aria-selected', 'false');
                                });
                                option.setAttribute('aria-selected', 'true');
                                closeMenus();
                            });

                            yearWrapper.innerHTML = '';
                            yearWrapper.appendChild(yearButton);
                            yearWrapper.appendChild(yearMenu);
                            yearWrapper.dataset.yearDropdownReady = 'true';
                            yearWrapper.classList.add('candidate-accomplishment-year-wrapper');

                            if (yearInput) {
                                yearInput.setAttribute('aria-hidden', 'true');
                            }
                        }

                        if (calendar.dataset.headerDropdownCloseReady !== 'true') {
                            calendar.addEventListener('click', function (event) {
                                if (!event.target.closest('.candidate-accomplishment-month-wrapper, .candidate-accomplishment-year-wrapper')) {
                                    closeMenus();
                                }
                            });
                            calendar.dataset.headerDropdownCloseReady = 'true';
                        }
                    };

                    flatpickr(input, {
                        allowInput: true,
                        clickOpens: true,
                        dateFormat: 'm/d/y',
                        locale: typeof getLoggedInUserLang !== 'undefined' ? getLoggedInUserLang : 'default',
                        onOpen: function (selectedDates, dateStr, instance) {
                            resizeCalendar(instance.calendarContainer);
                            applyHeaderDropdowns(instance);
                        },
                        onReady: function (selectedDates, dateStr, instance) {
                            resizeCalendar(instance.calendarContainer);
                            applyHeaderDropdowns(instance);
                        },
                        onMonthChange: function (selectedDates, dateStr, instance) {
                            applyHeaderDropdowns(instance);
                            const monthButton = instance.calendarContainer.querySelector('.candidate-accomplishment-month-select');
                            const monthMenu = instance.calendarContainer.querySelector('.candidate-accomplishment-month-menu');
                            if (monthButton) {
                                monthButton.textContent = instance.l10n.months.longhand[instance.currentMonth];
                            }
                            if (monthMenu) {
                                monthMenu.querySelectorAll('[aria-selected="true"]').forEach(function (selected) {
                                    selected.setAttribute('aria-selected', 'false');
                                });
                                monthMenu.querySelector('[data-month="' + instance.currentMonth + '"]')?.setAttribute('aria-selected', 'true');
                            }
                        },
                        onYearChange: function (selectedDates, dateStr, instance) {
                            const yearButton = instance.calendarContainer.querySelector('.candidate-accomplishment-year-select');
                            const yearMenu = instance.calendarContainer.querySelector('.candidate-accomplishment-year-menu');
                            if (yearButton) {
                                yearButton.textContent = String(instance.currentYear);
                            }
                            if (yearMenu) {
                                yearMenu.querySelectorAll('[aria-selected="true"]').forEach(function (selected) {
                                    selected.setAttribute('aria-selected', 'false');
                                });
                                yearMenu.querySelector('[data-year="' + instance.currentYear + '"]')?.setAttribute('aria-selected', 'true');
                            }
                        },
                    });

                    input.dataset.flatpickrReady = 'true';
                }
            };

            const setAccomplishmentDateValue = function (input, value) {
                if (!input) {
                    return;
                }

                if (input._flatpickr) {
                    if (value) {
                        const parsedDate = value instanceof Date ? value : new Date(value);
                        input._flatpickr.setDate(isNaN(parsedDate.getTime()) ? value : parsedDate, true);
                    } else {
                        input._flatpickr.clear();
                    }

                    return;
                }

                input.value = value || '';
            };

            document.querySelectorAll('[data-publication-issued-input], [data-award-issued-input], [data-project-issued-input], [data-other-issued-input]').forEach(function (input) {
                initAccomplishmentDatePicker(input);
                input.closest('.candidate-publication-date-field')?.addEventListener('click', function () {
                    input.focus();
                    input._flatpickr?.open();
                });
            });

            const setActiveAccomplishmentSection = function (panelId) {
                accomplishmentSectionLinks.forEach(function (link) {
                    link.classList.toggle('active', link.dataset.accomplishmentSectionLink === panelId);
                });
            };

            const closeAccomplishmentSections = function (activeSection) {
                if (typeof bootstrap === 'undefined') {
                    return;
                }

                accomplishmentSectionBodies.forEach(function (section) {
                    if (section !== activeSection) {
                        bootstrap.Collapse.getOrCreateInstance(section, { toggle: false }).hide();
                    }
                });
            };

            if (portfolioList && portfolioForm) {
                const portfolioTitleInput = portfolioForm.querySelector('[data-portfolio-title-input]');
                const portfolioUrlInput = portfolioForm.querySelector('[data-portfolio-url-input]');
                const portfolioDescriptionInput = portfolioForm.querySelector('[data-portfolio-description-input]');
                const portfolioDescriptionEditor = portfolioForm.querySelector('[data-portfolio-description-editor]');
                const portfolioEditingId = portfolioForm.querySelector('[data-portfolio-editing-id]');
                const portfolioFormTitle = portfolioForm.querySelector('[data-portfolio-form-title]');
                const portfolioCounter = portfolioForm.querySelector('[data-portfolio-character-count]');
                const portfolioSubmit = portfolioForm.querySelector('[data-portfolio-submit]');
                const portfolioClose = portfolioForm.querySelector('[data-portfolio-close]');
                const portfolioToken = portfolioForm.querySelector('input[name="_token"]')?.value || '';
                let activePortfolioItem = null;
                let portfolioQuill = null;
                const portfolioFormHome = document.createElement('div');
                portfolioForm.after(portfolioFormHome);
                const portfolioLabels = {
                    portfolio: @json(__('messages.candidate_profile.portfolio')),
                    save: @json(__('messages.candidate_profile.save')),
                    close: @json(__('messages.candidate_profile.close')),
                    update: @json(__('messages.candidate_profile.update')),
                    cancel: @json(__('messages.candidate_profile.cancel')),
                    edit: @json(__('messages.candidate_profile.edit')),
                    delete: @json(__('messages.candidate_profile.delete')),
                    url: @json(__('messages.candidate_profile.url')),
                    description: @json(__('messages.candidate_profile.description')),
                    confirmDelete: @json(__('messages.candidate_profile.confirm_delete_portfolio')),
                };

                const portfolioItems = function () {
                    return Array.from(portfolioList.querySelectorAll('[data-portfolio-item]'));
                };

                const portfolioEmpty = function () {
                    return portfolioList.querySelector('[data-portfolio-empty]');
                };

                const portfolioMessage = function (error) {
                    return error && error.message
                        ? error.message
                        : (error && error.errors ? Object.values(error.errors).flat().shift() : null);
                };

                const portfolioDescriptionText = function () {
                    return portfolioQuill
                        ? portfolioQuill.getText().replace(/\n$/, '').trim()
                        : (portfolioDescriptionEditor?.innerText || '').replace(/\n$/, '').trim();
                };

                const refreshPortfolioCounter = function () {
                    const length = portfolioDescriptionText().length;
                    if (portfolioCounter) {
                        portfolioCounter.textContent = Math.min(length, 300) + '/300';
                    }
                    if (portfolioDescriptionInput) {
                        portfolioDescriptionInput.value = portfolioQuill && portfolioDescriptionText()
                            ? portfolioQuill.root.innerHTML
                            : '';
                    }
                };

                const renderPortfolioNumbers = function () {
                    const items = portfolioItems();
                    items.forEach(function (item, index) {
                        const title = item.querySelector('.candidate-portfolio-item__header h2');
                        if (title) {
                            title.textContent = (index + 1) + '. ' + (item.dataset.portfolioTitle || '---');
                        }
                    });
                    portfolioEmpty()?.classList.toggle('d-none', items.length > 0);
                };

                const refreshPortfolioAddActions = function () {
                    const isMax = portfolioItems().length >= maxPortfolioItems;
                    portfolioAddActions.forEach(function (action) {
                        const header = action.closest('.candidate-education-panel__header');
                        const sectionOpen = !header || !header.classList.contains('collapsed');
                        action.classList.toggle('d-none', isMax || !sectionOpen);
                    });
                };

                const setPortfolioFormValues = function (item) {
                    portfolioTitleInput.value = item ? (item.dataset.portfolioTitle || '') : '';
                    portfolioUrlInput.value = item ? (item.dataset.portfolioUrl || '') : '';
                    const description = item ? (item.querySelector('[data-portfolio-description-text]')?.innerHTML || '') : '';
                    if (portfolioQuill) {
                        portfolioQuill.root.innerHTML = description;
                    } else if (portfolioDescriptionEditor) {
                        portfolioDescriptionEditor.innerHTML = description;
                    }
                    refreshPortfolioCounter();
                };

                const syncPortfolioItem = function (item, values) {
                    item.dataset.portfolioId = values.id || item.dataset.portfolioId || '';
                    item.dataset.portfolioTitle = values.title || '---';
                    item.dataset.portfolioUrl = values.url || '';
                    item.dataset.updateUrl = values.update_url || item.dataset.updateUrl || '';
                    item.dataset.deleteUrl = values.delete_url || item.dataset.deleteUrl || '';
                    const urlNode = item.querySelector('[data-portfolio-url-text]');
                    const urlEmptyNode = item.querySelector('[data-portfolio-url-empty]');
                    const descriptionNode = item.querySelector('[data-portfolio-description-text]');
                    if (urlNode) {
                        urlNode.textContent = values.url || '';
                        urlNode.href = values.url ? (/^https?:\/\//i.test(values.url) ? values.url : 'https://' + values.url) : '#';
                        urlNode.classList.toggle('d-none', !values.url);
                    }
                    if (urlEmptyNode) {
                        urlEmptyNode.classList.toggle('d-none', Boolean(values.url));
                    }
                    if (descriptionNode) {
                        descriptionNode.innerHTML = values.description || values.descriptionHtml || '---';
                    }
                };

                const makePortfolioItem = function (values) {
                    const item = document.createElement('div');
                    item.className = 'candidate-portfolio-item';
                    item.dataset.portfolioItem = '';
                    item.innerHTML = [
                        '<div class="candidate-portfolio-item__header">',
                        '<h2></h2>',
                        '<div class="candidate-portfolio-actions">',
                        '<button type="button" data-portfolio-edit><i class="fa-regular fa-pen-to-square"></i> ' + portfolioLabels.edit + '</button>',
                        '<button type="button" data-portfolio-delete><i class="fa-regular fa-trash-can"></i> ' + portfolioLabels.delete + '</button>',
                        '</div>',
                        '</div>',
                        '<div class="candidate-portfolio-field"><span>' + portfolioLabels.url + '</span><a target="_blank" rel="noopener" data-portfolio-url-text></a><strong data-portfolio-url-empty>---</strong></div>',
                        '<div class="candidate-portfolio-field"><span>' + portfolioLabels.description + '</span><div data-portfolio-description-text></div></div>',
                    ].join('');
                    syncPortfolioItem(item, values);
                    return item;
                };

                const closePortfolioForm = function () {
                    portfolioForm.classList.add('d-none');
                    if (portfolioEditingId) {
                        portfolioEditingId.value = '';
                    }
                    setPortfolioFormValues(null);
                    if (portfolioFormTitle) {
                        portfolioFormTitle.textContent = portfolioLabels.portfolio;
                    }
                    if (portfolioSubmit) {
                        portfolioSubmit.textContent = portfolioLabels.save;
                    }
                    if (portfolioClose) {
                        portfolioClose.textContent = portfolioLabels.close;
                    }
                    if (activePortfolioItem) {
                        activePortfolioItem.classList.remove('d-none');
                        activePortfolioItem = null;
                    }
                    portfolioForm.classList.remove('candidate-portfolio-form--inline');
                    portfolioFormHome.appendChild(portfolioForm);
                    refreshPortfolioAddActions();
                    renderPortfolioNumbers();
                };

                const openPortfolioForm = function (item) {
                    const section = document.getElementById('candidatePortfolioInformationPanelBody');
                    if (section && typeof bootstrap !== 'undefined') {
                        bootstrap.Collapse.getOrCreateInstance(section, { toggle: false }).show();
                    }

                    if (!item && portfolioItems().length >= maxPortfolioItems) {
                        refreshPortfolioAddActions();
                        return;
                    }

                    closePortfolioForm();
                    activePortfolioItem = item || null;
                    if (portfolioEditingId) {
                        portfolioEditingId.value = item ? (item.dataset.portfolioId || '') : '';
                    }
                    setPortfolioFormValues(item);
                    portfolioForm.classList.remove('d-none');
                    if (portfolioFormTitle) {
                        portfolioFormTitle.textContent = portfolioLabels.portfolio;
                    }
                    if (portfolioSubmit) {
                        portfolioSubmit.textContent = item ? portfolioLabels.update : portfolioLabels.save;
                    }
                    if (portfolioClose) {
                        portfolioClose.textContent = item ? portfolioLabels.cancel : portfolioLabels.close;
                    }
                    if (item) {
                        portfolioForm.classList.add('candidate-portfolio-form--inline');
                        item.insertAdjacentElement('beforebegin', portfolioForm);
                        item.classList.add('d-none');
                    } else {
                        portfolioForm.classList.remove('candidate-portfolio-form--inline');
                        const footerAdd = portfolioList.querySelector('.candidate-portfolio-add-outline');
                        portfolioList.insertBefore(portfolioForm, footerAdd || null);
                    }
                    portfolioTitleInput.focus();
                    if (portfolioQuill) {
                        setTimeout(function () {
                            portfolioQuill.focus();
                        }, 0);
                    }
                    refreshPortfolioAddActions();
                };

                portfolioAddActions.forEach(function (action) {
                    action.addEventListener('click', function () {
                        openPortfolioForm(null);
                    });
                });

                portfolioList.addEventListener('click', function (event) {
                    const editButton = event.target.closest('[data-portfolio-edit]');
                    const deleteButton = event.target.closest('[data-portfolio-delete]');
                    const item = event.target.closest('[data-portfolio-item]');

                    if (editButton && item) {
                        openPortfolioForm(item);
                    }

                    if (deleteButton && item) {
                        if (!window.confirm(portfolioLabels.confirmDelete)) {
                            return;
                        }

                        const formData = new FormData();
                        formData.append('_method', 'DELETE');
                        if (portfolioToken) {
                            formData.append('_token', portfolioToken);
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
                            if (activePortfolioItem === item) {
                                closePortfolioForm();
                            }
                            item.remove();
                            if (!portfolioItems().length && !portfolioEmpty()) {
                                const empty = document.createElement('p');
                                empty.className = 'candidate-skill-empty candidate-portfolio-empty';
                                empty.dataset.portfolioEmpty = '';
                                empty.textContent = '---';
                                portfolioList.insertBefore(empty, portfolioList.querySelector('.candidate-portfolio-add-outline') || null);
                            }
                            renderPortfolioNumbers();
                            refreshPortfolioAddActions();
                            if (response && response.message && typeof displaySuccessMessage === 'function') {
                                displaySuccessMessage(response.message);
                            }
                        }).catch(function (error) {
                            const message = portfolioMessage(error);
                            if (message && typeof displayErrorMessage === 'function') {
                                displayErrorMessage(message);
                            }
                        });
                    }
                });

                if (typeof Quill !== 'undefined' && portfolioDescriptionEditor) {
                    portfolioQuill = new Quill(portfolioDescriptionEditor, {
                        theme: 'snow',
                        placeholder: portfolioDescriptionEditor.dataset.placeholder || '',
                        modules: {
                            toolbar: [['bold', 'italic'], [{ list: 'bullet' }]],
                        },
                    });

                    portfolioQuill.on('text-change', function () {
                        const text = portfolioQuill.getText();
                        if (text.length > 301) {
                            portfolioQuill.deleteText(300, text.length);
                        }
                        refreshPortfolioCounter();
                    });
                }

                portfolioDescriptionEditor?.addEventListener('input', refreshPortfolioCounter);

                portfolioClose?.addEventListener('click', function () {
                    closePortfolioForm();
                });

                portfolioForm.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const values = {
                        title: portfolioTitleInput.value.trim(),
                        url: portfolioUrlInput.value.trim(),
                        description: portfolioDescriptionText(),
                    };

                    if (!values.title) {
                        portfolioTitleInput.focus();
                        return;
                    }

                    if (!values.description) {
                        portfolioDescriptionEditor?.focus();
                        return;
                    }

                    if (portfolioQuill && portfolioDescriptionText()) {
                        portfolioDescriptionInput.value = portfolioQuill.root.innerHTML;
                    }

                    const formData = new FormData(portfolioForm);
                    const requestUrl = activePortfolioItem ? activePortfolioItem.dataset.updateUrl : portfolioForm.dataset.storeUrl;
                    if (activePortfolioItem) {
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
                        const portfolio = response && response.data ? response.data : values;

                        if (activePortfolioItem) {
                            syncPortfolioItem(activePortfolioItem, portfolio);
                            activePortfolioItem.classList.remove('d-none');
                        } else {
                            portfolioEmpty()?.remove();
                            const footerAdd = portfolioList.querySelector('.candidate-portfolio-add-outline');
                            portfolioList.insertBefore(makePortfolioItem(portfolio), footerAdd || null);
                        }

                        closePortfolioForm();
                        renderPortfolioNumbers();
                        refreshPortfolioAddActions();
                        if (response && response.message && typeof displaySuccessMessage === 'function') {
                            displaySuccessMessage(response.message);
                        }
                    }).catch(function (error) {
                        const message = portfolioMessage(error);
                        if (message && typeof displayErrorMessage === 'function') {
                            displayErrorMessage(message);
                        }
                    });
                });

                closePortfolioForm();
                renderPortfolioNumbers();
            }

            if (publicationList && publicationForm) {
                const publicationTitleInput = publicationForm.querySelector('[data-publication-title-input]');
                const publicationIssuedInput = publicationForm.querySelector('[data-publication-issued-input]');
                const publicationUrlInput = publicationForm.querySelector('[data-publication-url-input]');
                const publicationDescriptionInput = publicationForm.querySelector('[data-publication-description-input]');
                const publicationDescriptionEditor = publicationForm.querySelector('[data-publication-description-editor]');
                const publicationEditingId = publicationForm.querySelector('[data-publication-editing-id]');
                const publicationFormTitle = publicationForm.querySelector('[data-publication-form-title]');
                const publicationCounter = publicationForm.querySelector('[data-publication-character-count]');
                const publicationSubmit = publicationForm.querySelector('[data-publication-submit]');
                const publicationClose = publicationForm.querySelector('[data-publication-close]');
                const publicationToken = publicationForm.querySelector('input[name="_token"]')?.value || '';
                let activePublicationItem = null;
                let publicationQuill = null;
                const publicationFormHome = document.createElement('div');
                publicationForm.after(publicationFormHome);
                const publicationLabels = {
                    publication: @json(__('messages.candidate_profile.publication')),
                    save: @json(__('messages.candidate_profile.save')),
                    close: @json(__('messages.candidate_profile.close')),
                    update: @json(__('messages.candidate_profile.update')),
                    cancel: @json(__('messages.candidate_profile.cancel')),
                    edit: @json(__('messages.candidate_profile.edit')),
                    delete: @json(__('messages.candidate_profile.delete')),
                    issuedOn: @json(__('messages.candidate_profile.issued_on')),
                    url: @json(__('messages.candidate_profile.url')),
                    description: @json(__('messages.candidate_profile.description')),
                    confirmDelete: @json(__('messages.candidate_profile.confirm_delete_publication')),
                };

                const publicationItems = function () {
                    return Array.from(publicationList.querySelectorAll('[data-publication-item]'));
                };

                const publicationEmpty = function () {
                    return publicationList.querySelector('[data-publication-empty]');
                };

                const publicationMessage = function (error) {
                    return error && error.message
                        ? error.message
                        : (error && error.errors ? Object.values(error.errors).flat().shift() : null);
                };

                const publicationDescriptionText = function () {
                    return publicationQuill
                        ? publicationQuill.getText().replace(/\n$/, '').trim()
                        : (publicationDescriptionEditor?.innerText || '').replace(/\n$/, '').trim();
                };

                const refreshPublicationCounter = function () {
                    const length = publicationDescriptionText().length;
                    if (publicationCounter) {
                        publicationCounter.textContent = Math.min(length, 300) + '/300';
                    }
                    if (publicationDescriptionInput) {
                        publicationDescriptionInput.value = publicationQuill && publicationDescriptionText()
                            ? publicationQuill.root.innerHTML
                            : '';
                    }
                };

                const renderPublicationNumbers = function () {
                    const items = publicationItems();
                    items.forEach(function (item, index) {
                        const title = item.querySelector('.candidate-publication-item__header h2');
                        if (title) {
                            title.textContent = (index + 1) + '. ' + (item.dataset.publicationTitle || '---');
                        }
                    });
                    publicationEmpty()?.classList.toggle('d-none', items.length > 0);
                };

                const refreshPublicationAddActions = function () {
                    const isMax = publicationItems().length >= maxPublicationItems;
                    publicationAddActions.forEach(function (action) {
                        const header = action.closest('.candidate-education-panel__header');
                        const sectionOpen = !header || !header.classList.contains('collapsed');
                        action.classList.toggle('d-none', isMax || !sectionOpen);
                    });
                };

                const setPublicationFormValues = function (item) {
                    publicationTitleInput.value = item ? (item.dataset.publicationTitle || '') : '';
                    setAccomplishmentDateValue(publicationIssuedInput, item ? (item.dataset.publicationIssuedValue || item.dataset.publicationIssued || '') : '');
                    publicationUrlInput.value = item ? (item.dataset.publicationUrl || '') : '';
                    const description = item ? (item.querySelector('[data-publication-description-text]')?.innerHTML || '') : '';
                    if (publicationQuill) {
                        publicationQuill.root.innerHTML = description;
                    } else if (publicationDescriptionEditor) {
                        publicationDescriptionEditor.innerHTML = description;
                    }
                    refreshPublicationCounter();
                };

                const syncPublicationItem = function (item, values) {
                    item.dataset.publicationId = values.id || item.dataset.publicationId || '';
                    item.dataset.publicationTitle = values.title || '---';
                    item.dataset.publicationIssued = values.issued_on || values.issued || '---';
                    item.dataset.publicationIssuedValue = values.issued_on_value || values.issued_on || values.issued || '';
                    item.dataset.publicationUrl = values.url || '';
                    item.dataset.updateUrl = values.update_url || item.dataset.updateUrl || '';
                    item.dataset.deleteUrl = values.delete_url || item.dataset.deleteUrl || '';
                    const issuedNode = item.querySelector('[data-publication-issued-text]');
                    const urlNode = item.querySelector('[data-publication-url-text]');
                    const urlEmptyNode = item.querySelector('[data-publication-url-empty]');
                    const descriptionNode = item.querySelector('[data-publication-description-text]');
                    if (issuedNode) {
                        issuedNode.textContent = values.issued_on || values.issued || '---';
                    }
                    if (urlNode) {
                        urlNode.textContent = values.url || '';
                        urlNode.href = values.url ? (/^https?:\/\//i.test(values.url) ? values.url : 'https://' + values.url) : '#';
                        urlNode.classList.toggle('d-none', !values.url);
                    }
                    if (urlEmptyNode) {
                        urlEmptyNode.classList.toggle('d-none', Boolean(values.url));
                    }
                    if (descriptionNode) {
                        descriptionNode.innerHTML = values.description || values.descriptionHtml || '---';
                    }
                };

                const makePublicationItem = function (values) {
                    const item = document.createElement('div');
                    item.className = 'candidate-publication-item';
                    item.dataset.publicationItem = '';
                    item.innerHTML = [
                        '<div class="candidate-publication-item__header">',
                        '<h2></h2>',
                        '<div class="candidate-publication-actions">',
                        '<button type="button" data-publication-edit><i class="fa-regular fa-pen-to-square"></i> ' + publicationLabels.edit + '</button>',
                        '<button type="button" data-publication-delete><i class="fa-regular fa-trash-can"></i> ' + publicationLabels.delete + '</button>',
                        '</div>',
                        '</div>',
                        '<div class="candidate-publication-field"><span>' + publicationLabels.issuedOn + '</span><strong data-publication-issued-text></strong></div>',
                        '<div class="candidate-publication-field"><span>' + publicationLabels.url + '</span><a target="_blank" rel="noopener" data-publication-url-text></a><strong data-publication-url-empty>---</strong></div>',
                        '<div class="candidate-publication-field"><span>' + publicationLabels.description + '</span><div data-publication-description-text></div></div>',
                    ].join('');
                    syncPublicationItem(item, values);
                    return item;
                };

                const closePublicationForm = function () {
                    publicationForm.classList.add('d-none');
                    if (publicationEditingId) {
                        publicationEditingId.value = '';
                    }
                    setPublicationFormValues(null);
                    if (publicationFormTitle) {
                        publicationFormTitle.textContent = publicationLabels.publication;
                    }
                    if (publicationSubmit) {
                        publicationSubmit.textContent = publicationLabels.save;
                    }
                    if (publicationClose) {
                        publicationClose.textContent = publicationLabels.close;
                    }
                    if (activePublicationItem) {
                        activePublicationItem.classList.remove('d-none');
                        activePublicationItem = null;
                    }
                    publicationForm.classList.remove('candidate-publication-form--inline');
                    publicationFormHome.appendChild(publicationForm);
                    refreshPublicationAddActions();
                    renderPublicationNumbers();
                };

                const openPublicationForm = function (item) {
                    const section = document.getElementById('candidatePublicationInformationPanelBody');
                    if (section && typeof bootstrap !== 'undefined') {
                        bootstrap.Collapse.getOrCreateInstance(section, { toggle: false }).show();
                    }

                    if (!item && publicationItems().length >= maxPublicationItems) {
                        refreshPublicationAddActions();
                        return;
                    }

                    closePublicationForm();
                    activePublicationItem = item || null;
                    if (publicationEditingId) {
                        publicationEditingId.value = item ? (item.dataset.publicationId || '') : '';
                    }
                    setPublicationFormValues(item);
                    publicationForm.classList.remove('d-none');
                    if (publicationFormTitle) {
                        publicationFormTitle.textContent = publicationLabels.publication;
                    }
                    if (publicationSubmit) {
                        publicationSubmit.textContent = item ? publicationLabels.update : publicationLabels.save;
                    }
                    if (publicationClose) {
                        publicationClose.textContent = item ? publicationLabels.cancel : publicationLabels.close;
                    }
                    if (item) {
                        publicationForm.classList.add('candidate-publication-form--inline');
                        item.insertAdjacentElement('beforebegin', publicationForm);
                        item.classList.add('d-none');
                    } else {
                        publicationForm.classList.remove('candidate-publication-form--inline');
                        const footerAdd = publicationList.querySelector('.candidate-publication-add-outline');
                        publicationList.insertBefore(publicationForm, footerAdd || null);
                    }
                    publicationTitleInput.focus();
                    refreshPublicationAddActions();
                };

                publicationAddActions.forEach(function (action) {
                    action.addEventListener('click', function () {
                        openPublicationForm(null);
                    });
                });

                publicationList.addEventListener('click', function (event) {
                    const editButton = event.target.closest('[data-publication-edit]');
                    const deleteButton = event.target.closest('[data-publication-delete]');
                    const item = event.target.closest('[data-publication-item]');

                    if (editButton && item) {
                        openPublicationForm(item);
                    }

                    if (deleteButton && item) {
                        if (!window.confirm(publicationLabels.confirmDelete)) {
                            return;
                        }

                        const formData = new FormData();
                        formData.append('_method', 'DELETE');
                        if (publicationToken) {
                            formData.append('_token', publicationToken);
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
                            if (activePublicationItem === item) {
                                closePublicationForm();
                            }
                            item.remove();
                            if (!publicationItems().length && !publicationEmpty()) {
                                const empty = document.createElement('p');
                                empty.className = 'candidate-skill-empty candidate-publication-empty';
                                empty.dataset.publicationEmpty = '';
                                empty.textContent = '---';
                                publicationList.insertBefore(empty, publicationList.querySelector('.candidate-publication-add-outline') || null);
                            }
                            renderPublicationNumbers();
                            refreshPublicationAddActions();
                            if (response && response.message && typeof displaySuccessMessage === 'function') {
                                displaySuccessMessage(response.message);
                            }
                        }).catch(function (error) {
                            const message = publicationMessage(error);
                            if (message && typeof displayErrorMessage === 'function') {
                                displayErrorMessage(message);
                            }
                        });
                    }
                });

                if (typeof Quill !== 'undefined' && publicationDescriptionEditor) {
                    publicationQuill = new Quill(publicationDescriptionEditor, {
                        theme: 'snow',
                        placeholder: publicationDescriptionEditor.dataset.placeholder || '',
                        modules: {
                            toolbar: [['bold', 'italic'], [{ list: 'bullet' }]],
                        },
                    });

                    publicationQuill.on('text-change', function () {
                        const text = publicationQuill.getText();
                        if (text.length > 301) {
                            publicationQuill.deleteText(300, text.length);
                        }
                        refreshPublicationCounter();
                    });
                }

                publicationClose?.addEventListener('click', function () {
                    closePublicationForm();
                });

                publicationForm.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const values = {
                        title: publicationTitleInput.value.trim(),
                        issued: publicationIssuedInput.value.trim(),
                        url: publicationUrlInput.value.trim(),
                        description: publicationDescriptionText(),
                        descriptionHtml: publicationQuill && publicationDescriptionText() ? publicationQuill.root.innerHTML : '',
                    };

                    if (!values.title) {
                        publicationTitleInput.focus();
                        return;
                    }

                    if (!values.issued) {
                        publicationIssuedInput.focus();
                        return;
                    }

                    if (!values.description) {
                        publicationDescriptionEditor?.focus();
                        return;
                    }

                    if (values.description.length > 300) {
                        values.description = values.description.slice(0, 300);
                    }

                    if (publicationQuill && publicationDescriptionText()) {
                        publicationDescriptionInput.value = publicationQuill.root.innerHTML;
                    }
                    refreshPublicationCounter();

                    const formData = new FormData(publicationForm);
                    const requestUrl = activePublicationItem ? activePublicationItem.dataset.updateUrl : publicationForm.dataset.storeUrl;
                    if (activePublicationItem) {
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
                        const publication = response && response.data ? response.data : values;

                        if (activePublicationItem) {
                            syncPublicationItem(activePublicationItem, publication);
                            activePublicationItem.classList.remove('d-none');
                        } else {
                            publicationEmpty()?.remove();
                            const footerAdd = publicationList.querySelector('.candidate-publication-add-outline');
                            publicationList.insertBefore(makePublicationItem(publication), footerAdd || null);
                        }

                        closePublicationForm();
                        renderPublicationNumbers();
                        refreshPublicationAddActions();
                        if (response && response.message && typeof displaySuccessMessage === 'function') {
                            displaySuccessMessage(response.message);
                        }
                    }).catch(function (error) {
                        const message = publicationMessage(error);
                        if (message && typeof displayErrorMessage === 'function') {
                            displayErrorMessage(message);
                        }
                    });
                });

                closePublicationForm();
                renderPublicationNumbers();
            }

            if (awardList && awardForm) {
                const awardTitleInput = awardForm.querySelector('[data-award-title-input]');
                const awardIssuedInput = awardForm.querySelector('[data-award-issued-input]');
                const awardUrlInput = awardForm.querySelector('[data-award-url-input]');
                const awardDescriptionInput = awardForm.querySelector('[data-award-description-input]');
                const awardDescriptionEditor = awardForm.querySelector('[data-award-description-editor]');
                const awardEditingId = awardForm.querySelector('[data-award-editing-id]');
                const awardCounter = awardForm.querySelector('[data-award-character-count]');
                const awardSubmit = awardForm.querySelector('[data-award-submit]');
                const awardClose = awardForm.querySelector('[data-award-close]');
                const awardToken = awardForm.querySelector('input[name="_token"]')?.value || '';
                let activeAwardItem = null;
                let awardQuill = null;
                const awardFormHome = document.createElement('div');
                awardForm.after(awardFormHome);
                const awardLabels = {
                    award: @json(__('messages.candidate_profile.award')),
                    save: @json(__('messages.candidate_profile.save')),
                    close: @json(__('messages.candidate_profile.close')),
                    update: @json(__('messages.candidate_profile.update')),
                    cancel: @json(__('messages.candidate_profile.cancel')),
                    edit: @json(__('messages.candidate_profile.edit')),
                    delete: @json(__('messages.candidate_profile.delete')),
                    issuedOn: @json(__('messages.candidate_profile.issued_on')),
                    url: @json(__('messages.candidate_profile.url')),
                    description: @json(__('messages.candidate_profile.description')),
                    confirmDelete: @json(__('messages.candidate_profile.confirm_delete_award')),
                };

                const awardItems = function () {
                    return Array.from(awardList.querySelectorAll('[data-award-item]'));
                };

                const awardEmpty = function () {
                    return awardList.querySelector('[data-award-empty]');
                };

                const awardMessage = function (error) {
                    return error && error.message
                        ? error.message
                        : (error && error.errors ? Object.values(error.errors).flat().shift() : null);
                };

                const awardDescriptionText = function () {
                    return awardQuill
                        ? awardQuill.getText().replace(/\n$/, '').trim()
                        : (awardDescriptionEditor?.innerText || '').replace(/\n$/, '').trim();
                };

                const refreshAwardCounter = function () {
                    const length = awardDescriptionText().length;
                    if (awardCounter) {
                        awardCounter.textContent = Math.min(length, 300) + '/300';
                    }
                    if (awardDescriptionInput) {
                        awardDescriptionInput.value = awardQuill && awardDescriptionText() ? awardQuill.root.innerHTML : '';
                    }
                };

                const renderAwardNumbers = function () {
                    const items = awardItems();
                    items.forEach(function (item, index) {
                        const title = item.querySelector('.candidate-publication-item__header h2');
                        if (title) {
                            title.textContent = (index + 1) + '. ' + (item.dataset.awardTitle || '---');
                        }
                    });
                    awardEmpty()?.classList.toggle('d-none', items.length > 0);
                };

                const refreshAwardEmpty = function () {
                    awardEmpty()?.classList.toggle('d-none', Boolean(awardItems().length));
                };

                const refreshAwardAddActions = function () {
                    const isMax = awardItems().length >= maxAwardItems;
                    awardAddActions.forEach(function (action) {
                        const header = action.closest('.candidate-education-panel__header');
                        const sectionOpen = !header || !header.classList.contains('collapsed');
                        action.classList.toggle('d-none', isMax || !sectionOpen);
                    });
                };

                const setAwardFormValues = function (item) {
                    awardTitleInput.value = item ? (item.dataset.awardTitle || '') : '';
                    setAccomplishmentDateValue(awardIssuedInput, item ? (item.dataset.awardIssuedValue || item.dataset.awardIssued || '') : '');
                    awardUrlInput.value = item ? (item.dataset.awardUrl || '') : '';
                    const description = item ? (item.querySelector('[data-award-description-text]')?.innerHTML || '') : '';
                    if (awardQuill) {
                        awardQuill.root.innerHTML = description;
                    } else if (awardDescriptionEditor) {
                        awardDescriptionEditor.innerHTML = description;
                    }
                    refreshAwardCounter();
                };

                const syncAwardItem = function (item, values) {
                    item.dataset.awardId = values.id || item.dataset.awardId || '';
                    item.dataset.awardTitle = values.title || '---';
                    item.dataset.awardIssued = values.issued_on || values.issued || '---';
                    item.dataset.awardIssuedValue = values.issued_on_value || values.issued_on || values.issued || '';
                    item.dataset.awardUrl = values.url || '';
                    item.dataset.updateUrl = values.update_url || item.dataset.updateUrl || '';
                    item.dataset.deleteUrl = values.delete_url || item.dataset.deleteUrl || '';
                    const issuedNode = item.querySelector('[data-award-issued-text]');
                    const urlNode = item.querySelector('[data-award-url-text]');
                    const urlEmptyNode = item.querySelector('[data-award-url-empty]');
                    const descriptionNode = item.querySelector('[data-award-description-text]');
                    if (issuedNode) {
                        issuedNode.textContent = values.issued_on || values.issued || '---';
                    }
                    if (urlNode) {
                        urlNode.textContent = values.url || '';
                        urlNode.href = values.url ? (/^https?:\/\//i.test(values.url) ? values.url : 'https://' + values.url) : '#';
                        urlNode.classList.toggle('d-none', !values.url);
                    }
                    if (urlEmptyNode) {
                        urlEmptyNode.classList.toggle('d-none', Boolean(values.url));
                    }
                    if (descriptionNode) {
                        descriptionNode.innerHTML = values.description || values.descriptionHtml || '---';
                    }
                };

                const makeAwardItem = function (values) {
                    const item = document.createElement('div');
                    item.className = 'candidate-publication-item';
                    item.dataset.awardItem = '';
                    item.innerHTML = [
                        '<div class="candidate-publication-item__header">',
                        '<h2></h2>',
                        '<div class="candidate-publication-actions">',
                        '<button type="button" data-award-edit><i class="fa-regular fa-pen-to-square"></i> ' + awardLabels.edit + '</button>',
                        '<button type="button" data-award-delete><i class="fa-regular fa-trash-can"></i> ' + awardLabels.delete + '</button>',
                        '</div>',
                        '</div>',
                        '<div class="candidate-publication-field"><span>' + awardLabels.issuedOn + '</span><strong data-award-issued-text></strong></div>',
                        '<div class="candidate-publication-field"><span>' + awardLabels.url + '</span><a target="_blank" rel="noopener" data-award-url-text></a><strong data-award-url-empty>---</strong></div>',
                        '<div class="candidate-publication-field"><span>' + awardLabels.description + '</span><div data-award-description-text></div></div>',
                    ].join('');
                    syncAwardItem(item, values);
                    return item;
                };

                const closeAwardForm = function () {
                    awardForm.classList.add('d-none');
                    if (awardEditingId) {
                        awardEditingId.value = '';
                    }
                    setAwardFormValues(null);
                    if (awardSubmit) {
                        awardSubmit.textContent = awardLabels.save;
                    }
                    if (awardClose) {
                        awardClose.textContent = awardLabels.close;
                    }
                    if (activeAwardItem) {
                        activeAwardItem.classList.remove('d-none');
                        activeAwardItem = null;
                    }
                    awardForm.classList.remove('candidate-publication-form--inline');
                    awardFormHome.appendChild(awardForm);
                    refreshAwardAddActions();
                    renderAwardNumbers();
                };

                const openAwardForm = function (item) {
                    const section = document.getElementById('candidateAwardHonorInformationPanelBody');
                    if (section && typeof bootstrap !== 'undefined') {
                        bootstrap.Collapse.getOrCreateInstance(section, { toggle: false }).show();
                    }
                    if (!item && awardItems().length >= maxAwardItems) {
                        refreshAwardAddActions();
                        return;
                    }
                    closeAwardForm();
                    activeAwardItem = item || null;
                    if (awardEditingId) {
                        awardEditingId.value = item ? (item.dataset.awardId || '') : '';
                    }
                    setAwardFormValues(item);
                    awardForm.classList.remove('d-none');
                    if (awardSubmit) {
                        awardSubmit.textContent = item ? awardLabels.update : awardLabels.save;
                    }
                    if (awardClose) {
                        awardClose.textContent = item ? awardLabels.cancel : awardLabels.close;
                    }
                    if (item) {
                        awardForm.classList.add('candidate-publication-form--inline');
                        item.insertAdjacentElement('beforebegin', awardForm);
                        item.classList.add('d-none');
                    } else {
                        awardForm.classList.remove('candidate-publication-form--inline');
                        const footerAdd = awardList.querySelector('.candidate-publication-add-outline');
                        awardList.insertBefore(awardForm, footerAdd || null);
                    }
                    awardTitleInput.focus();
                    refreshAwardEmpty();
                    refreshAwardAddActions();
                };

                awardAddActions.forEach(function (action) {
                    action.addEventListener('click', function () {
                        openAwardForm(null);
                    });
                });

                awardList.addEventListener('click', function (event) {
                    const editButton = event.target.closest('[data-award-edit]');
                    const deleteButton = event.target.closest('[data-award-delete]');
                    const item = event.target.closest('[data-award-item]');
                    if (editButton && item) {
                        openAwardForm(item);
                    }
                    if (deleteButton && item) {
                        if (!window.confirm(awardLabels.confirmDelete)) {
                            return;
                        }

                        const formData = new FormData();
                        formData.append('_method', 'DELETE');
                        if (awardToken) {
                            formData.append('_token', awardToken);
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
                            if (activeAwardItem === item) {
                                closeAwardForm();
                            }
                            item.remove();
                            if (!awardItems().length && !awardEmpty()) {
                                const empty = document.createElement('p');
                                empty.className = 'candidate-skill-empty candidate-publication-empty';
                                empty.dataset.awardEmpty = '';
                                empty.textContent = '---';
                                awardList.insertBefore(empty, awardList.querySelector('.candidate-publication-add-outline') || null);
                            }
                            renderAwardNumbers();
                            refreshAwardAddActions();
                            if (response && response.message && typeof displaySuccessMessage === 'function') {
                                displaySuccessMessage(response.message);
                            }
                        }).catch(function (error) {
                            const message = awardMessage(error);
                            if (message && typeof displayErrorMessage === 'function') {
                                displayErrorMessage(message);
                            }
                        });
                    }
                });

                if (typeof Quill !== 'undefined' && awardDescriptionEditor) {
                    awardQuill = new Quill(awardDescriptionEditor, {
                        theme: 'snow',
                        placeholder: awardDescriptionEditor.dataset.placeholder || '',
                        modules: {
                            toolbar: [['bold', 'italic'], [{ list: 'bullet' }]],
                        },
                    });
                    awardQuill.on('text-change', function () {
                        const text = awardQuill.getText();
                        if (text.length > 301) {
                            awardQuill.deleteText(300, text.length);
                        }
                        refreshAwardCounter();
                    });
                }

                awardClose?.addEventListener('click', function () {
                    closeAwardForm();
                });

                awardForm.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const values = {
                        title: awardTitleInput.value.trim(),
                        issued: awardIssuedInput.value.trim(),
                        url: awardUrlInput.value.trim(),
                        description: awardDescriptionText(),
                        descriptionHtml: awardQuill && awardDescriptionText() ? awardQuill.root.innerHTML : '',
                    };
                    if (!values.title) {
                        awardTitleInput.focus();
                        return;
                    }
                    if (!values.issued) {
                        awardIssuedInput.focus();
                        return;
                    }
                    if (!values.description) {
                        awardDescriptionEditor?.focus();
                        return;
                    }
                    if (values.description.length > 300) {
                        values.description = values.description.slice(0, 300);
                    }
                    if (awardQuill && awardDescriptionText()) {
                        awardDescriptionInput.value = awardQuill.root.innerHTML;
                    }

                    const formData = new FormData(awardForm);
                    const requestUrl = activeAwardItem ? activeAwardItem.dataset.updateUrl : awardForm.dataset.storeUrl;
                    if (activeAwardItem) {
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
                        const award = response && response.data ? response.data : values;

                        if (activeAwardItem) {
                            syncAwardItem(activeAwardItem, award);
                            activeAwardItem.classList.remove('d-none');
                        } else {
                            awardEmpty()?.remove();
                            const footerAdd = awardList.querySelector('.candidate-publication-add-outline');
                            awardList.insertBefore(makeAwardItem(award), footerAdd || null);
                        }

                        closeAwardForm();
                        renderAwardNumbers();
                        refreshAwardAddActions();
                        if (response && response.message && typeof displaySuccessMessage === 'function') {
                            displaySuccessMessage(response.message);
                        }
                    }).catch(function (error) {
                        const message = awardMessage(error);
                        if (message && typeof displayErrorMessage === 'function') {
                            displayErrorMessage(message);
                        }
                    });
                });

                closeAwardForm();
                renderAwardNumbers();
            }

            if (projectList) {
                const projectForm = projectList.querySelector('[data-project-form]');
                const projectTitleInput = projectForm ? projectForm.querySelector('[data-project-title-input]') : null;
                const projectIssuedInput = projectForm ? projectForm.querySelector('[data-project-issued-input]') : null;
                const projectUrlInput = projectForm ? projectForm.querySelector('[data-project-url-input]') : null;
                const projectDescriptionInput = projectForm ? projectForm.querySelector('[data-project-description-input]') : null;
                const projectDescriptionEditor = projectForm ? projectForm.querySelector('[data-project-description-editor]') : null;
                const projectEditingId = projectForm ? projectForm.querySelector('[data-project-editing-id]') : null;
                const projectCounter = projectForm ? projectForm.querySelector('[data-project-character-count]') : null;
                const projectSubmit = projectForm ? projectForm.querySelector('[data-project-submit]') : null;
                const projectClose = projectForm ? projectForm.querySelector('[data-project-close]') : null;
                const projectToken = projectForm ? (projectForm.querySelector('input[name="_token"]')?.value || '') : '';
                let activeProjectItem = null;
                let projectQuill = null;
                const projectFormHome = document.createElement('div');
                if (projectForm) {
                    projectForm.after(projectFormHome);
                }
                const projectLabels = {
                    save: @json(__('messages.candidate_profile.save')),
                    close: @json(__('messages.candidate_profile.close')),
                    update: @json(__('messages.candidate_profile.update')),
                    cancel: @json(__('messages.candidate_profile.cancel')),
                    edit: @json(__('messages.candidate_profile.edit')),
                    delete: @json(__('messages.candidate_profile.delete')),
                    issuedOn: @json(__('messages.candidate_profile.issued_on')),
                    url: @json(__('messages.candidate_profile.url')),
                    description: @json(__('messages.candidate_profile.description')),
                    confirmDelete: @json(__('messages.candidate_profile.confirm_delete_project')),
                };

                const projectItems = function () {
                    return Array.from(projectList.querySelectorAll('[data-project-item]'));
                };

                const projectEmpty = function () {
                    return projectList.querySelector('[data-project-empty]');
                };

                const projectMessage = function (error) {
                    return error && error.message
                        ? error.message
                        : (error && error.errors ? Object.values(error.errors).flat().shift() : null);
                };

                const projectDescriptionText = function () {
                    return projectQuill
                        ? projectQuill.getText().replace(/\n$/, '').trim()
                        : (projectDescriptionEditor?.innerText || '').replace(/\n$/, '').trim();
                };

                const refreshProjectCounter = function () {
                    const length = projectDescriptionText().length;
                    if (projectCounter) {
                        projectCounter.textContent = Math.min(length, 300) + '/300';
                    }
                    if (projectDescriptionInput) {
                        projectDescriptionInput.value = projectQuill && projectDescriptionText() ? projectQuill.root.innerHTML : '';
                    }
                };

                const renderProjectNumbers = function () {
                    const items = projectItems();
                    items.forEach(function (item, index) {
                        const title = item.querySelector('.candidate-project-item__header h2');
                        if (title) {
                            title.textContent = (index + 1) + '. ' + (item.dataset.projectTitle || '---');
                        }
                    });
                    projectEmpty()?.classList.toggle('d-none', items.length > 0);
                };

                const refreshProjectEmpty = function () {
                    projectEmpty()?.classList.toggle('d-none', Boolean(projectItems().length));
                };

                const refreshProjectAddActions = function () {
                    const isMax = projectItems().length >= maxProjectItems;
                    projectAddActions.forEach(function (action) {
                        const header = action.closest('.candidate-education-panel__header');
                        const sectionOpen = !header || !header.classList.contains('collapsed');
                        action.classList.toggle('d-none', isMax || !sectionOpen);
                    });
                };

                const setProjectFormValues = function (item) {
                    if (!projectForm) {
                        return;
                    }
                    projectTitleInput.value = item ? (item.dataset.projectTitle || '') : '';
                    setAccomplishmentDateValue(projectIssuedInput, item ? (item.dataset.projectIssuedValue || item.dataset.projectIssued || '') : '');
                    projectUrlInput.value = item ? (item.dataset.projectUrl || '') : '';
                    const description = item ? (item.querySelector('[data-project-description-text]')?.innerHTML || '') : '';
                    if (projectQuill) {
                        projectQuill.root.innerHTML = description;
                    } else if (projectDescriptionEditor) {
                        projectDescriptionEditor.innerHTML = description;
                    }
                    refreshProjectCounter();
                };

                const syncProjectItem = function (item, values) {
                    item.dataset.projectId = values.id || item.dataset.projectId || '';
                    item.dataset.projectTitle = values.title || '---';
                    item.dataset.projectIssued = values.issued_on || values.issued || '---';
                    item.dataset.projectIssuedValue = values.issued_on_value || values.issued_on || values.issued || '';
                    item.dataset.projectUrl = values.url || '';
                    item.dataset.updateUrl = values.update_url || item.dataset.updateUrl || '';
                    item.dataset.deleteUrl = values.delete_url || item.dataset.deleteUrl || '';
                    const issuedNode = item.querySelector('[data-project-issued-text]');
                    const urlNode = item.querySelector('[data-project-url-text]');
                    const urlEmptyNode = item.querySelector('[data-project-url-empty]');
                    const descriptionNode = item.querySelector('[data-project-description-text]');
                    if (issuedNode) {
                        issuedNode.textContent = values.issued_on || values.issued || '---';
                    }
                    if (urlNode) {
                        urlNode.textContent = values.url || '';
                        urlNode.href = values.url ? (/^https?:\/\//i.test(values.url) ? values.url : 'https://' + values.url) : '#';
                        urlNode.classList.toggle('d-none', !values.url);
                    }
                    if (urlEmptyNode) {
                        urlEmptyNode.classList.toggle('d-none', Boolean(values.url));
                    }
                    if (descriptionNode) {
                        descriptionNode.innerHTML = values.description || values.descriptionHtml || '---';
                    }
                };

                const makeProjectItem = function (values) {
                    const item = document.createElement('div');
                    item.className = 'candidate-project-item';
                    item.dataset.projectItem = '';
                    item.innerHTML = [
                        '<div class="candidate-project-item__header">',
                        '<h2></h2>',
                        '<div class="candidate-project-actions">',
                        '<button type="button" data-project-edit><i class="fa-regular fa-pen-to-square"></i> ' + projectLabels.edit + '</button>',
                        '<button type="button" data-project-delete><i class="fa-regular fa-trash-can"></i> ' + projectLabels.delete + '</button>',
                        '</div>',
                        '</div>',
                        '<div class="candidate-project-field"><span>' + projectLabels.issuedOn + '</span><strong data-project-issued-text></strong></div>',
                        '<div class="candidate-project-field"><span>' + projectLabels.url + '</span><a target="_blank" rel="noopener" data-project-url-text></a><strong data-project-url-empty>---</strong></div>',
                        '<div class="candidate-project-field"><span>' + projectLabels.description + '</span><div data-project-description-text></div></div>',
                    ].join('');
                    syncProjectItem(item, values);
                    return item;
                };

                const closeProjectForm = function () {
                    if (!projectForm) {
                        return;
                    }
                    projectForm.classList.add('d-none');
                    if (projectEditingId) {
                        projectEditingId.value = '';
                    }
                    setProjectFormValues(null);
                    if (projectSubmit) {
                        projectSubmit.textContent = projectLabels.save;
                    }
                    if (projectClose) {
                        projectClose.textContent = projectLabels.close;
                    }
                    if (activeProjectItem) {
                        activeProjectItem.classList.remove('d-none');
                        activeProjectItem = null;
                    }
                    projectForm.classList.remove('candidate-project-form--inline');
                    projectFormHome.appendChild(projectForm);
                    refreshProjectAddActions();
                    renderProjectNumbers();
                };

                const openProjectForm = function (item) {
                    if (!projectForm) {
                        return;
                    }
                    const section = document.getElementById('candidateProjectInformationPanelBody');
                    if (section && typeof bootstrap !== 'undefined') {
                        bootstrap.Collapse.getOrCreateInstance(section, { toggle: false }).show();
                    }
                    if (!item && projectItems().length >= maxProjectItems) {
                        refreshProjectAddActions();
                        return;
                    }
                    closeProjectForm();
                    activeProjectItem = item || null;
                    if (projectEditingId) {
                        projectEditingId.value = item ? (item.dataset.projectId || '') : '';
                    }
                    setProjectFormValues(item);
                    projectForm.classList.remove('d-none');
                    if (projectSubmit) {
                        projectSubmit.textContent = item ? projectLabels.update : projectLabels.save;
                    }
                    if (projectClose) {
                        projectClose.textContent = item ? projectLabels.cancel : projectLabels.close;
                    }
                    if (item) {
                        projectForm.classList.add('candidate-project-form--inline');
                        item.insertAdjacentElement('beforebegin', projectForm);
                        item.classList.add('d-none');
                    } else {
                        projectForm.classList.remove('candidate-project-form--inline');
                        const footerAdd = projectList.querySelector('.candidate-project-add-outline');
                        projectList.insertBefore(projectForm, footerAdd || null);
                    }
                    projectTitleInput.focus();
                    refreshProjectEmpty();
                    refreshProjectAddActions();
                };

                projectAddActions.forEach(function (action) {
                    action.addEventListener('click', function () {
                        openProjectForm(null);
                    });
                });

                projectList.addEventListener('click', function (event) {
                    const editButton = event.target.closest('[data-project-edit]');
                    const deleteButton = event.target.closest('[data-project-delete]');
                    const item = event.target.closest('[data-project-item]');

                    if (editButton && item) {
                        openProjectForm(item);
                    }

                    if (deleteButton && item) {
                        if (!window.confirm(projectLabels.confirmDelete)) {
                            return;
                        }

                        const formData = new FormData();
                        formData.append('_method', 'DELETE');
                        if (projectToken) {
                            formData.append('_token', projectToken);
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
                            if (activeProjectItem === item) {
                                closeProjectForm();
                            }
                            item.remove();
                            if (!projectItems().length && !projectEmpty()) {
                                const empty = document.createElement('p');
                                empty.className = 'candidate-project-empty';
                                empty.dataset.projectEmpty = '';
                                empty.textContent = '---';
                                projectList.insertBefore(empty, projectList.querySelector('.candidate-project-add-outline') || null);
                            }
                            renderProjectNumbers();
                            refreshProjectAddActions();
                            if (response && response.message && typeof displaySuccessMessage === 'function') {
                                displaySuccessMessage(response.message);
                            }
                        }).catch(function (error) {
                            const message = projectMessage(error);
                            if (message && typeof displayErrorMessage === 'function') {
                                displayErrorMessage(message);
                            }
                        });
                    }
                });

                if (typeof Quill !== 'undefined' && projectDescriptionEditor) {
                    projectQuill = new Quill(projectDescriptionEditor, {
                        theme: 'snow',
                        placeholder: projectDescriptionEditor.dataset.placeholder || '',
                        modules: {
                            toolbar: [['bold', 'italic'], [{ list: 'bullet' }]],
                        },
                    });
                    projectQuill.on('text-change', function () {
                        const text = projectQuill.getText();
                        if (text.length > 301) {
                            projectQuill.deleteText(300, text.length);
                        }
                        refreshProjectCounter();
                    });
                }

                projectClose?.addEventListener('click', function () {
                    closeProjectForm();
                });

                projectForm?.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const values = {
                        title: projectTitleInput.value.trim(),
                        issued: projectIssuedInput.value.trim(),
                        url: projectUrlInput.value.trim(),
                        description: projectDescriptionText(),
                        descriptionHtml: projectQuill && projectDescriptionText() ? projectQuill.root.innerHTML : '',
                    };
                    if (!values.title) {
                        projectTitleInput.focus();
                        return;
                    }
                    if (!values.issued) {
                        projectIssuedInput.focus();
                        return;
                    }
                    if (!values.description) {
                        projectDescriptionEditor?.focus();
                        return;
                    }
                    if (values.description.length > 300) {
                        values.description = values.description.slice(0, 300);
                    }
                    if (projectQuill && projectDescriptionText()) {
                        projectDescriptionInput.value = projectQuill.root.innerHTML;
                    }

                    const formData = new FormData(projectForm);
                    const requestUrl = activeProjectItem ? activeProjectItem.dataset.updateUrl : projectForm.dataset.storeUrl;
                    if (activeProjectItem) {
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
                        const project = response && response.data ? response.data : values;

                        if (activeProjectItem) {
                            syncProjectItem(activeProjectItem, project);
                            activeProjectItem.classList.remove('d-none');
                        } else {
                            projectEmpty()?.remove();
                            const footerAdd = projectList.querySelector('.candidate-project-add-outline');
                            projectList.insertBefore(makeProjectItem(project), footerAdd || null);
                        }

                        closeProjectForm();
                        renderProjectNumbers();
                        refreshProjectAddActions();
                        if (response && response.message && typeof displaySuccessMessage === 'function') {
                            displaySuccessMessage(response.message);
                        }
                    }).catch(function (error) {
                        const message = projectMessage(error);
                        if (message && typeof displayErrorMessage === 'function') {
                            displayErrorMessage(message);
                        }
                    });
                });

                closeProjectForm();
                renderProjectNumbers();
                refreshProjectAddActions();
            }

            if (otherList && otherForm) {
                const otherTitleInput = otherForm.querySelector('[data-other-title-input]');
                const otherIssuedInput = otherForm.querySelector('[data-other-issued-input]');
                const otherUrlInput = otherForm.querySelector('[data-other-url-input]');
                const otherDescriptionInput = otherForm.querySelector('[data-other-description-input]');
                const otherDescriptionEditor = otherForm.querySelector('[data-other-description-editor]');
                const otherEditingId = otherForm.querySelector('[data-other-editing-id]');
                const otherFormTitle = otherForm.querySelector('[data-other-form-title]');
                const otherCounter = otherForm.querySelector('[data-other-character-count]');
                const otherSubmit = otherForm.querySelector('[data-other-submit]');
                const otherClose = otherForm.querySelector('[data-other-close]');
                const otherToken = otherForm.querySelector('input[name="_token"]')?.value || '';
                let activeOtherItem = null;
                let otherQuill = null;
                const otherFormHome = document.createElement('div');
                otherForm.after(otherFormHome);
                const otherLabels = {
                    otherAccomplishment: @json(__('messages.candidate_profile.other_accomplishment')),
                    save: @json(__('messages.candidate_profile.save')),
                    close: @json(__('messages.candidate_profile.close')),
                    update: @json(__('messages.candidate_profile.update')),
                    cancel: @json(__('messages.candidate_profile.cancel')),
                    edit: @json(__('messages.candidate_profile.edit')),
                    delete: @json(__('messages.candidate_profile.delete')),
                    issuedOn: @json(__('messages.candidate_profile.issued_on')),
                    url: @json(__('messages.candidate_profile.url')),
                    description: @json(__('messages.candidate_profile.description')),
                    confirmDelete: @json(__('messages.candidate_profile.confirm_delete_other_accomplishment')),
                };

                const otherItems = function () {
                    return Array.from(otherList.querySelectorAll('[data-other-item]'));
                };

                const otherEmpty = function () {
                    return otherList.querySelector('[data-other-empty]');
                };

                const otherMessage = function (error) {
                    return error && error.message
                        ? error.message
                        : (error && error.errors ? Object.values(error.errors).flat().shift() : null);
                };

                const otherDescriptionText = function () {
                    return otherQuill
                        ? otherQuill.getText().replace(/\n$/, '').trim()
                        : (otherDescriptionEditor?.innerText || '').replace(/\n$/, '').trim();
                };

                const refreshOtherCounter = function () {
                    const length = otherDescriptionText().length;
                    if (otherCounter) {
                        otherCounter.textContent = Math.min(length, 300) + '/300';
                    }
                    if (otherDescriptionInput) {
                        otherDescriptionInput.value = otherQuill && otherDescriptionText() ? otherQuill.root.innerHTML : '';
                    }
                };

                const renderOtherNumbers = function () {
                    const items = otherItems();
                    items.forEach(function (item, index) {
                        const title = item.querySelector('.candidate-other-item__header h2');
                        if (title) {
                            title.textContent = (index + 1) + '. ' + (item.dataset.otherTitle || '---');
                        }
                    });
                    otherEmpty()?.classList.toggle('d-none', items.length > 0);
                };

                const refreshOtherEmpty = function () {
                    otherEmpty()?.classList.toggle('d-none', Boolean(otherItems().length));
                };

                const refreshOtherAddActions = function () {
                    const isMax = otherItems().length >= maxOtherItems;
                    otherAddActions.forEach(function (action) {
                        const header = action.closest('.candidate-education-panel__header');
                        const sectionOpen = !header || !header.classList.contains('collapsed');
                        action.classList.toggle('d-none', isMax || !sectionOpen);
                    });
                };

                const setOtherFormValues = function (item) {
                    otherTitleInput.value = item ? (item.dataset.otherTitle || '') : '';
                    setAccomplishmentDateValue(otherIssuedInput, item ? (item.dataset.otherIssuedValue || item.dataset.otherIssued || '') : '');
                    otherUrlInput.value = item ? (item.dataset.otherUrl || '') : '';
                    const description = item ? (item.querySelector('[data-other-description-text]')?.innerHTML || '') : '';
                    if (otherQuill) {
                        otherQuill.root.innerHTML = description;
                    } else if (otherDescriptionEditor) {
                        otherDescriptionEditor.innerHTML = description;
                    }
                    refreshOtherCounter();
                };

                const syncOtherItem = function (item, values) {
                    item.dataset.otherId = values.id || item.dataset.otherId || '';
                    item.dataset.otherTitle = values.title || '---';
                    item.dataset.otherIssued = values.issued_on || values.issued || '---';
                    item.dataset.otherIssuedValue = values.issued_on_value || values.issued_on || values.issued || '';
                    item.dataset.otherUrl = values.url || '';
                    item.dataset.updateUrl = values.update_url || item.dataset.updateUrl || '';
                    item.dataset.deleteUrl = values.delete_url || item.dataset.deleteUrl || '';
                    const issuedNode = item.querySelector('[data-other-issued-text]');
                    const urlNode = item.querySelector('[data-other-url-text]');
                    const urlEmptyNode = item.querySelector('[data-other-url-empty]');
                    const descriptionNode = item.querySelector('[data-other-description-text]');
                    if (issuedNode) {
                        issuedNode.textContent = values.issued_on || values.issued || '---';
                    }
                    if (urlNode) {
                        urlNode.textContent = values.url || '';
                        urlNode.href = values.url ? (/^https?:\/\//i.test(values.url) ? values.url : 'https://' + values.url) : '#';
                        urlNode.classList.toggle('d-none', !values.url);
                    }
                    if (urlEmptyNode) {
                        urlEmptyNode.classList.toggle('d-none', Boolean(values.url));
                    }
                    if (descriptionNode) {
                        descriptionNode.innerHTML = values.description || values.descriptionHtml || '---';
                    }
                };

                const makeOtherItem = function (values) {
                    const item = document.createElement('div');
                    item.className = 'candidate-other-item';
                    item.dataset.otherItem = '';
                    item.innerHTML = [
                        '<div class="candidate-other-item__header">',
                        '<h2></h2>',
                        '<div class="candidate-other-actions">',
                        '<button type="button" data-other-edit><i class="fa-regular fa-pen-to-square"></i> ' + otherLabels.edit + '</button>',
                        '<button type="button" data-other-delete><i class="fa-regular fa-trash-can"></i> ' + otherLabels.delete + '</button>',
                        '</div>',
                        '</div>',
                        '<div class="candidate-other-field"><span>' + otherLabels.issuedOn + '</span><strong data-other-issued-text></strong></div>',
                        '<div class="candidate-other-field"><span>' + otherLabels.url + '</span><a target="_blank" rel="noopener" data-other-url-text></a><strong data-other-url-empty>---</strong></div>',
                        '<div class="candidate-other-field"><span>' + otherLabels.description + '</span><div data-other-description-text></div></div>',
                    ].join('');
                    syncOtherItem(item, values);
                    return item;
                };

                const closeOtherForm = function () {
                    otherForm.classList.add('d-none');
                    if (otherEditingId) {
                        otherEditingId.value = '';
                    }
                    setOtherFormValues(null);
                    if (otherFormTitle) {
                        otherFormTitle.textContent = otherLabels.otherAccomplishment;
                    }
                    if (otherSubmit) {
                        otherSubmit.textContent = otherLabels.save;
                    }
                    if (otherClose) {
                        otherClose.textContent = otherLabels.close;
                    }
                    if (activeOtherItem) {
                        activeOtherItem.classList.remove('d-none');
                        activeOtherItem = null;
                    }
                    otherForm.classList.remove('candidate-other-form--inline');
                    otherFormHome.appendChild(otherForm);
                    refreshOtherAddActions();
                    renderOtherNumbers();
                };

                const openOtherForm = function (item) {
                    const section = document.getElementById('candidateOtherAccomplishmentInformationPanelBody');
                    if (section && typeof bootstrap !== 'undefined') {
                        bootstrap.Collapse.getOrCreateInstance(section, { toggle: false }).show();
                    }
                    if (!item && otherItems().length >= maxOtherItems) {
                        refreshOtherAddActions();
                        return;
                    }
                    closeOtherForm();
                    activeOtherItem = item || null;
                    if (otherEditingId) {
                        otherEditingId.value = item ? (item.dataset.otherId || '') : '';
                    }
                    setOtherFormValues(item);
                    otherForm.classList.remove('d-none');
                    if (otherSubmit) {
                        otherSubmit.textContent = item ? otherLabels.update : otherLabels.save;
                    }
                    if (otherClose) {
                        otherClose.textContent = item ? otherLabels.cancel : otherLabels.close;
                    }
                    if (item) {
                        otherForm.classList.add('candidate-other-form--inline');
                        item.insertAdjacentElement('beforebegin', otherForm);
                        item.classList.add('d-none');
                    } else {
                        otherForm.classList.remove('candidate-other-form--inline');
                        const footerAdd = otherList.querySelector('.candidate-other-add-outline');
                        otherList.insertBefore(otherForm, footerAdd || null);
                    }
                    otherTitleInput.focus();
                    refreshOtherEmpty();
                    refreshOtherAddActions();
                };

                otherAddActions.forEach(function (action) {
                    action.addEventListener('click', function () {
                        openOtherForm(null);
                    });
                });

                otherList.addEventListener('click', function (event) {
                    const editButton = event.target.closest('[data-other-edit]');
                    const deleteButton = event.target.closest('[data-other-delete]');
                    const item = event.target.closest('[data-other-item]');
                    if (editButton && item) {
                        openOtherForm(item);
                    }
                    if (deleteButton && item) {
                        if (!window.confirm(otherLabels.confirmDelete)) {
                            return;
                        }

                        const formData = new FormData();
                        formData.append('_method', 'DELETE');
                        if (otherToken) {
                            formData.append('_token', otherToken);
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
                            if (activeOtherItem === item) {
                                closeOtherForm();
                            }
                            item.remove();
                            if (!otherItems().length && !otherEmpty()) {
                                const empty = document.createElement('p');
                                empty.className = 'candidate-other-empty';
                                empty.dataset.otherEmpty = '';
                                empty.textContent = '---';
                                otherList.insertBefore(empty, otherList.querySelector('.candidate-other-add-outline') || null);
                            }
                            renderOtherNumbers();
                            refreshOtherAddActions();
                            if (response && response.message && typeof displaySuccessMessage === 'function') {
                                displaySuccessMessage(response.message);
                            }
                        }).catch(function (error) {
                            const message = otherMessage(error);
                            if (message && typeof displayErrorMessage === 'function') {
                                displayErrorMessage(message);
                            }
                        });
                    }
                });

                if (typeof Quill !== 'undefined' && otherDescriptionEditor) {
                    otherQuill = new Quill(otherDescriptionEditor, {
                        theme: 'snow',
                        placeholder: otherDescriptionEditor.dataset.placeholder || '',
                        modules: {
                            toolbar: [['bold', 'italic'], [{ list: 'bullet' }]],
                        },
                    });
                    otherQuill.on('text-change', function () {
                        const text = otherQuill.getText();
                        if (text.length > 301) {
                            otherQuill.deleteText(300, text.length);
                        }
                        refreshOtherCounter();
                    });
                }

                otherClose?.addEventListener('click', function () {
                    closeOtherForm();
                });

                otherForm.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const values = {
                        title: otherTitleInput.value.trim(),
                        issued: otherIssuedInput.value.trim(),
                        url: otherUrlInput.value.trim(),
                        description: otherDescriptionText(),
                        descriptionHtml: otherQuill && otherDescriptionText() ? otherQuill.root.innerHTML : '',
                    };
                    if (!values.title) {
                        otherTitleInput.focus();
                        return;
                    }
                    if (!values.issued) {
                        otherIssuedInput.focus();
                        return;
                    }
                    if (!values.description) {
                        otherDescriptionEditor?.focus();
                        return;
                    }
                    if (values.description.length > 300) {
                        values.description = values.description.slice(0, 300);
                    }
                    if (otherQuill && otherDescriptionText()) {
                        otherDescriptionInput.value = otherQuill.root.innerHTML;
                    }

                    const formData = new FormData(otherForm);
                    const requestUrl = activeOtherItem ? activeOtherItem.dataset.updateUrl : otherForm.dataset.storeUrl;
                    if (activeOtherItem) {
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
                        const other = response && response.data ? response.data : values;

                        if (activeOtherItem) {
                            syncOtherItem(activeOtherItem, other);
                            activeOtherItem.classList.remove('d-none');
                        } else {
                            otherEmpty()?.remove();
                            const footerAdd = otherList.querySelector('.candidate-other-add-outline');
                            otherList.insertBefore(makeOtherItem(other), footerAdd || null);
                        }

                        closeOtherForm();
                        renderOtherNumbers();
                        refreshOtherAddActions();
                        if (response && response.message && typeof displaySuccessMessage === 'function') {
                            displaySuccessMessage(response.message);
                        }
                    }).catch(function (error) {
                        const message = otherMessage(error);
                        if (message && typeof displayErrorMessage === 'function') {
                            displayErrorMessage(message);
                        }
                    });
                });

                closeOtherForm();
                renderOtherNumbers();
                refreshOtherAddActions();
            }

            accomplishmentSectionBodies.forEach(function (section) {
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
                    const portfolioAddAction = header ? header.querySelector('[data-portfolio-add-action]') : null;
                    if (portfolioAddAction) {
                        const portfolioCount = document.querySelectorAll('[data-portfolio-item]').length;
                        portfolioAddAction.classList.toggle('d-none', !isOpen || portfolioCount >= maxPortfolioItems);
                    }
                    const publicationAddAction = header ? header.querySelector('[data-publication-add-action]') : null;
                    if (publicationAddAction) {
                        const publicationCount = document.querySelectorAll('[data-publication-item]').length;
                        publicationAddAction.classList.toggle('d-none', !isOpen || publicationCount >= maxPublicationItems);
                    }
                    const awardAddAction = header ? header.querySelector('[data-award-add-action]') : null;
                    if (awardAddAction) {
                        const awardCount = document.querySelectorAll('[data-award-item]').length;
                        awardAddAction.classList.toggle('d-none', !isOpen || awardCount >= maxAwardItems);
                    }
                    const projectAddAction = header ? header.querySelector('[data-project-add-action]') : null;
                    if (projectAddAction) {
                        const projectCount = document.querySelectorAll('[data-project-item]').length;
                        projectAddAction.classList.toggle('d-none', !isOpen || projectCount >= maxProjectItems);
                    }
                    const otherAddAction = header ? header.querySelector('[data-other-add-action]') : null;
                    if (otherAddAction) {
                        const otherCount = document.querySelectorAll('[data-other-item]').length;
                        otherAddAction.classList.toggle('d-none', !isOpen || otherCount >= maxOtherItems);
                    }
                };

                section.addEventListener('shown.bs.collapse', function () {
                    closeAccomplishmentSections(section);
                    setPanelToggleState(true);
                    if (panel) {
                        setActiveAccomplishmentSection(panel.id);
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

            accomplishmentSectionLinks.forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    const panel = document.getElementById(link.dataset.accomplishmentSectionLink);
                    const section = panel ? panel.querySelector('.candidate-profile-section__collapse') : null;

                    if (!panel || !section || typeof bootstrap === 'undefined') {
                        return;
                    }

                    closeAccomplishmentSections(section);
                    bootstrap.Collapse.getOrCreateInstance(section, { toggle: false }).show();
                    setActiveAccomplishmentSection(panel.id);
                    window.scrollCandidateProfileSection(panel);
                });
            });
        });
    </script>
@endpush
