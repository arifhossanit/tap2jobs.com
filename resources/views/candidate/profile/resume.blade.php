@extends('candidate.profile.index')
@section('section')
    <div class="mb-xl-8 candidate-accomplishment-page">
        <div class="candidate-education-panel" id="candidatePortfolioInformation">
            <div class="candidate-education-panel__header">
                <h1>Portfolio</h1>
                <div class="candidate-education-panel__actions">
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
                    <div class="candidate-other-summary">
                        <h2>Portfolio</h2>
                        <p>---</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel" id="candidatePublicationInformation">
            <div class="candidate-education-panel__header collapsed">
                <h1>Publication</h1>
                <div class="candidate-education-panel__actions">
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
                    <div class="candidate-other-summary">
                        <h2>Publication</h2>
                        <p>---</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel" id="candidateAwardHonorInformation">
            <div class="candidate-education-panel__header collapsed">
                <h1>Award/Honor</h1>
                <div class="candidate-education-panel__actions">
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
                    <div class="candidate-other-summary">
                        <h2>Award/Honor</h2>
                        <p>---</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel" id="candidateProjectInformation">
            <div class="candidate-education-panel__header collapsed">
                <h1>Project</h1>
                <div class="candidate-education-panel__actions">
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
                    <div class="candidate-other-summary">
                        <h2>Project</h2>
                        <p>---</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel" id="candidateOtherAccomplishmentInformation">
            <div class="candidate-education-panel__header collapsed">
                <h1>Other</h1>
                <div class="candidate-education-panel__actions">
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
                    <div class="candidate-other-summary">
                        <h2>Other</h2>
                        <p>---</p>
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
                    panel.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                    });
                });
            });
        });
    </script>
@endpush
