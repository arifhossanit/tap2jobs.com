@extends('candidate.profile.index')
@section('section')
    <div class="mb-xl-8 candidate-accomplishment-page">
        <div class="candidate-education-panel" id="candidatePortfolioInformation">
            <div class="candidate-education-panel__header">
                <h1>Portfolio <span class="candidate-portfolio-limit">(max 2)</span></h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-add" data-portfolio-add-action>
                        <i class="fa-solid fa-plus"></i>
                        Add Portfolio
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
                        <form class="candidate-portfolio-form d-none" data-portfolio-form>
                            <h2 data-portfolio-form-title>Portfolio</h2>
                            <input type="hidden" data-portfolio-editing-index>
                            <div class="candidate-skill-form__field">
                                <label for="candidatePortfolioTitle">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="candidatePortfolioTitle"
                                       data-portfolio-title-input placeholder="Enter your title name" required>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidatePortfolioUrl">URL</label>
                                <input type="url" class="form-control" id="candidatePortfolioUrl"
                                       data-portfolio-url-input placeholder="Enter your URL">
                            </div>
                            <div class="candidate-skill-form__field">
                                <label>Description <span class="text-danger">*</span></label>
                                <input type="hidden" data-portfolio-description-input>
                                <div class="candidate-portfolio-editor">
                                    <div class="candidate-portfolio-quill" data-portfolio-description-editor
                                         data-placeholder="Enter your writing texts..."></div>
                                </div>
                                <p class="candidate-portfolio-counter">
                                    You wrote <strong data-portfolio-character-count>0/300</strong> character(s)
                                </p>
                            </div>
                            <div class="candidate-skill-form__actions">
                                <button type="submit" class="candidate-skill-save" data-portfolio-submit>Save</button>
                                <button type="button" class="candidate-skill-close" data-portfolio-close>Close</button>
                            </div>
                        </form>

                        <div class="candidate-portfolio-item" data-portfolio-item
                             data-portfolio-title="Moynul Islam Shimanto"
                             data-portfolio-url="https://shimzo.online/"
                             data-portfolio-description="I'm a Laravel developer. My technical skills include PHP, Laravel, JavaScript, ReactJS, VueJS, HTML, CSS, Tailwind CSS, Bootstrap, WordPress, and MySQL. I have developed several projects such as an e-commerce platform, HRM system, hospital management system, smart parking system, inventory etc.">
                            <div class="candidate-portfolio-item__header">
                                <h2>1. Moynul Islam Shimanto</h2>
                                <div class="candidate-portfolio-actions">
                                    <button type="button" data-portfolio-edit>
                                        <i class="fa-regular fa-pen-to-square"></i>
                                        Edit
                                    </button>
                                    <button type="button" data-portfolio-delete>
                                        <i class="fa-regular fa-trash-can"></i>
                                        Delete
                                    </button>
                                </div>
                            </div>

                            <div class="candidate-portfolio-field">
                                <span>URL</span>
                                <a href="https://shimzo.online/" target="_blank" rel="noopener" data-portfolio-url-text>https://shimzo.online/</a>
                            </div>

                            <div class="candidate-portfolio-field">
                                <span>Description</span>
                                <div data-portfolio-description-text>I'm a Laravel developer. My technical skills include PHP, Laravel, JavaScript, ReactJS, VueJS, HTML, CSS, Tailwind CSS, Bootstrap, WordPress, and MySQL. I have developed several projects such as an e-commerce platform, HRM system, hospital management system, smart parking system, inventory etc.</div>
                            </div>
                        </div>

                        <button type="button" class="candidate-portfolio-add-outline" data-portfolio-add-action>
                            <i class="fa-solid fa-plus"></i>
                            Add Portfolio
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel" id="candidatePublicationInformation">
            <div class="candidate-education-panel__header collapsed">
                <h1>Publication <span class="candidate-portfolio-limit">(max 5)</span></h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-add d-none" data-publication-add-action>
                        <i class="fa-solid fa-plus"></i>
                        Add Publication
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
                        <form class="candidate-publication-form d-none" data-publication-form>
                            <h2 data-publication-form-title>Publication</h2>
                            <input type="hidden" data-publication-editing-index>
                            <div class="candidate-skill-form__field">
                                <label for="candidatePublicationTitle">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="candidatePublicationTitle"
                                       data-publication-title-input placeholder="Enter your title name" required>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidatePublicationIssuedOn">Issued On <span class="text-danger">*</span></label>
                                <div class="candidate-publication-date-field">
                                    <i class="fa-regular fa-calendar"></i>
                                    <input type="text" class="form-control" id="candidatePublicationIssuedOn"
                                           data-publication-issued-input placeholder="MM/DD/YY" required>
                                </div>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidatePublicationUrl">URL</label>
                                <input type="url" class="form-control" id="candidatePublicationUrl"
                                       data-publication-url-input placeholder="Enter your URL">
                            </div>
                            <div class="candidate-skill-form__field">
                                <label>Description<span class="text-danger">*</span></label>
                                <input type="hidden" data-publication-description-input>
                                <div class="candidate-publication-editor">
                                    <div class="candidate-publication-quill" data-publication-description-editor
                                         data-placeholder="Enter your writing texts..."></div>
                                </div>
                                <p class="candidate-publication-counter">
                                    You wrote <strong data-publication-character-count>0/300</strong> character(s)
                                </p>
                            </div>
                            <div class="candidate-skill-form__actions">
                                <button type="submit" class="candidate-skill-save" data-publication-submit>Save</button>
                                <button type="button" class="candidate-skill-close" data-publication-close>Close</button>
                            </div>
                        </form>

                        <p class="candidate-publication-empty" data-publication-empty>---</p>

                        <button type="button" class="candidate-publication-add-outline" data-publication-add-action>
                            <i class="fa-solid fa-plus"></i>
                            Add Publication
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel" id="candidateAwardHonorInformation">
            <div class="candidate-education-panel__header collapsed">
                <h1>Award <span class="candidate-portfolio-limit">(max 5)</span></h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-add d-none" data-award-add-action>
                        <i class="fa-solid fa-plus"></i>
                        Add Award
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
                        <form class="candidate-publication-form d-none" data-award-form>
                            <h2 data-award-form-title>Award</h2>
                            <input type="hidden" data-award-editing-index>
                            <div class="candidate-skill-form__field">
                                <label for="candidateAwardTitle">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="candidateAwardTitle"
                                       data-award-title-input placeholder="Enter your title name" required>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidateAwardIssuedOn">Issued On <span class="text-danger">*</span></label>
                                <div class="candidate-publication-date-field">
                                    <i class="fa-regular fa-calendar"></i>
                                    <input type="text" class="form-control" id="candidateAwardIssuedOn"
                                           data-award-issued-input placeholder="MM/DD/YY" required>
                                </div>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidateAwardUrl">URL</label>
                                <input type="url" class="form-control" id="candidateAwardUrl"
                                       data-award-url-input placeholder="Enter your URL">
                            </div>
                            <div class="candidate-skill-form__field">
                                <label>Description <span class="text-danger">*</span></label>
                                <input type="hidden" data-award-description-input>
                                <div class="candidate-publication-editor">
                                    <div class="candidate-award-quill" data-award-description-editor
                                         data-placeholder="Enter your writing texts..."></div>
                                </div>
                                <p class="candidate-publication-counter">
                                    You wrote <strong data-award-character-count>0/300</strong> character(s)
                                </p>
                            </div>
                            <div class="candidate-skill-form__actions">
                                <button type="submit" class="candidate-skill-save" data-award-submit>Save</button>
                                <button type="button" class="candidate-skill-close" data-award-close>Close</button>
                            </div>
                        </form>

                        <p class="candidate-publication-empty" data-award-empty>---</p>

                        <button type="button" class="candidate-publication-add-outline" data-award-add-action>
                            <i class="fa-solid fa-plus"></i>
                            Add Award
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel" id="candidateProjectInformation">
            <div class="candidate-education-panel__header collapsed">
                <h1>Project <span class="candidate-portfolio-limit">(max 5)</span></h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-add d-none" data-project-add-action>
                        <i class="fa-solid fa-plus"></i>
                        Add Project
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
                        <form class="candidate-project-form d-none" data-project-form>
                            <input type="hidden" data-project-editing-index>
                            <div class="candidate-skill-form__field">
                                <label for="candidateProjectTitle">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="candidateProjectTitle"
                                       data-project-title-input placeholder="Enter your title name" required>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidateProjectIssuedOn">Issued On <span class="text-danger">*</span></label>
                                <div class="candidate-publication-date-field">
                                    <i class="fa-regular fa-calendar"></i>
                                    <input type="text" class="form-control" id="candidateProjectIssuedOn"
                                           data-project-issued-input placeholder="MM/DD/YY" required>
                                </div>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidateProjectUrl">URL</label>
                                <input type="url" class="form-control" id="candidateProjectUrl"
                                       data-project-url-input placeholder="Enter your URL">
                            </div>
                            <div class="candidate-skill-form__field">
                                <label>Description <span class="text-danger">*</span></label>
                                <input type="hidden" data-project-description-input>
                                <div class="candidate-project-editor">
                                    <div class="candidate-project-quill" data-project-description-editor
                                         data-placeholder="Enter your writing texts..."></div>
                                </div>
                                <p class="candidate-project-counter">
                                    You wrote <strong data-project-character-count>0/300</strong> character(s)
                                </p>
                            </div>
                            <div class="candidate-skill-form__actions">
                                <button type="submit" class="candidate-skill-save" data-project-submit>Save</button>
                                <button type="button" class="candidate-skill-close" data-project-close>Close</button>
                            </div>
                        </form>

                        <div class="candidate-project-item" data-project-item
                             data-project-title="Inventory System"
                             data-project-issued="21 Aug 2025"
                             data-project-url="https://vue.shimzo.online/"
                             data-project-description="Project topics: 1. Inventory management, 2. Employees, 3. Salary, 4. Expenses. Visit: https://vue.shimzo.online/">
                            <div class="candidate-project-item__header">
                                <h2>1. Inventory System</h2>
                                <div class="candidate-project-actions">
                                    <button type="button" data-project-edit>
                                        <i class="fa-regular fa-pen-to-square"></i>
                                        Edit
                                    </button>
                                    <button type="button" data-project-delete>
                                        <i class="fa-regular fa-trash-can"></i>
                                        Delete
                                    </button>
                                </div>
                            </div>
                            <div class="candidate-project-field">
                                <span>Issued On:</span>
                                <strong data-project-issued-text>21 Aug 2025</strong>
                            </div>
                            <div class="candidate-project-field">
                                <span>URL</span>
                                <a href="https://vue.shimzo.online/" target="_blank" rel="noopener" data-project-url-text>https://vue.shimzo.online/</a>
                            </div>
                            <div class="candidate-project-field">
                                <span>Description</span>
                                <div data-project-description-text>Project topics: 1. Inventory management, 2. Employees, 3. Salary, 4. Expenses. Visit: https://vue.shimzo.online/</div>
                            </div>
                        </div>

                        <div class="candidate-project-item" data-project-item
                             data-project-title="E-commerce"
                             data-project-issued="2 Jan 2026"
                             data-project-url="https://gadgetbd.shimzo.online/"
                             data-project-description="Project topics: 1. Variant wise gadget buy, 2. Cash on delivery &amp; others. Visit: https://gadgetbd.shimzo.online/">
                            <div class="candidate-project-item__header">
                                <h2>2. E-commerce</h2>
                                <div class="candidate-project-actions">
                                    <button type="button" data-project-edit>
                                        <i class="fa-regular fa-pen-to-square"></i>
                                        Edit
                                    </button>
                                    <button type="button" data-project-delete>
                                        <i class="fa-regular fa-trash-can"></i>
                                        Delete
                                    </button>
                                </div>
                            </div>
                            <div class="candidate-project-field">
                                <span>Issued On:</span>
                                <strong data-project-issued-text>2 Jan 2026</strong>
                            </div>
                            <div class="candidate-project-field">
                                <span>URL</span>
                                <a href="https://gadgetbd.shimzo.online/" target="_blank" rel="noopener" data-project-url-text>https://gadgetbd.shimzo.online/</a>
                            </div>
                            <div class="candidate-project-field">
                                <span>Description</span>
                                <div data-project-description-text>Project topics: 1. Variant wise gadget buy, 2. Cash on delivery &amp; others. Visit: https://gadgetbd.shimzo.online/</div>
                            </div>
                        </div>

                        <p class="candidate-project-empty d-none" data-project-empty>---</p>

                        <button type="button" class="candidate-project-add-outline" data-project-add-action>
                            <i class="fa-solid fa-plus"></i>
                            Add Project
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="candidate-education-panel" id="candidateOtherAccomplishmentInformation">
            <div class="candidate-education-panel__header collapsed">
                <h1>Other <span class="candidate-portfolio-limit">(max 5)</span></h1>
                <div class="candidate-education-panel__actions">
                    <button type="button" class="candidate-education-add d-none" data-other-add-action>
                        <i class="fa-solid fa-plus"></i>
                        Add Other
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
                        <form class="candidate-other-form d-none" data-other-form>
                            <h2 data-other-form-title>Other Accomplishment</h2>
                            <input type="hidden" data-other-editing-index>
                            <div class="candidate-skill-form__field">
                                <label for="candidateOtherTitle">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="candidateOtherTitle"
                                       data-other-title-input placeholder="Enter your title name" required>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidateOtherIssuedOn">Issued On <span class="text-danger">*</span></label>
                                <div class="candidate-publication-date-field">
                                    <i class="fa-regular fa-calendar"></i>
                                    <input type="text" class="form-control" id="candidateOtherIssuedOn"
                                           data-other-issued-input placeholder="MM/DD/YY" required>
                                </div>
                            </div>
                            <div class="candidate-skill-form__field">
                                <label for="candidateOtherUrl">URL</label>
                                <input type="url" class="form-control" id="candidateOtherUrl"
                                       data-other-url-input placeholder="Enter your URL">
                            </div>
                            <div class="candidate-skill-form__field">
                                <label>Description <span class="text-danger">*</span></label>
                                <input type="hidden" data-other-description-input>
                                <div class="candidate-other-editor">
                                    <div class="candidate-other-quill" data-other-description-editor
                                         data-placeholder="Enter your writing texts..."></div>
                                </div>
                                <p class="candidate-other-counter">
                                    You wrote <strong data-other-character-count>0/300</strong> character(s)
                                </p>
                            </div>
                            <div class="candidate-skill-form__actions">
                                <button type="submit" class="candidate-skill-save" data-other-submit>Save</button>
                                <button type="button" class="candidate-skill-close" data-other-close>Close</button>
                            </div>
                        </form>

                        <p class="candidate-other-empty" data-other-empty>---</p>

                        <button type="button" class="candidate-other-add-outline" data-other-add-action>
                            <i class="fa-solid fa-plus"></i>
                            Add Other
                        </button>
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
                const portfolioEditingIndex = portfolioForm.querySelector('[data-portfolio-editing-index]');
                const portfolioFormTitle = portfolioForm.querySelector('[data-portfolio-form-title]');
                const portfolioCounter = portfolioForm.querySelector('[data-portfolio-character-count]');
                const portfolioSubmit = portfolioForm.querySelector('[data-portfolio-submit]');
                const portfolioClose = portfolioForm.querySelector('[data-portfolio-close]');
                let activePortfolioItem = null;
                let portfolioQuill = null;

                const portfolioItems = function () {
                    return Array.from(portfolioList.querySelectorAll('[data-portfolio-item]'));
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
                    portfolioItems().forEach(function (item, index) {
                        const title = item.querySelector('.candidate-portfolio-item__header h2');
                        if (title) {
                            title.textContent = (index + 1) + '. ' + (item.dataset.portfolioTitle || '---');
                        }
                    });
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
                    if (portfolioQuill) {
                        portfolioQuill.root.innerHTML = item ? (item.dataset.portfolioDescription || '') : '';
                    } else if (portfolioDescriptionEditor) {
                        portfolioDescriptionEditor.textContent = item ? (item.dataset.portfolioDescription || '') : '';
                    }
                    refreshPortfolioCounter();
                };

                const syncPortfolioItem = function (item, values) {
                    item.dataset.portfolioTitle = values.title || '---';
                    item.dataset.portfolioUrl = values.url || '';
                    item.dataset.portfolioDescription = values.descriptionHtml || values.description || '---';
                    const urlNode = item.querySelector('[data-portfolio-url-text]');
                    const descriptionNode = item.querySelector('[data-portfolio-description-text]');
                    if (urlNode) {
                        urlNode.textContent = values.url || '---';
                        urlNode.href = values.url
                            ? (/^https?:\/\//i.test(values.url) ? values.url : 'https://' + values.url)
                            : '#';
                    }
                    if (descriptionNode) {
                        descriptionNode.innerHTML = values.descriptionHtml || values.description || '---';
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
                        '<button type="button" data-portfolio-edit><i class="fa-regular fa-pen-to-square"></i> Edit</button>',
                        '<button type="button" data-portfolio-delete><i class="fa-regular fa-trash-can"></i> Delete</button>',
                        '</div>',
                        '</div>',
                        '<div class="candidate-portfolio-field"><span>URL</span><a target="_blank" rel="noopener" data-portfolio-url-text></a></div>',
                        '<div class="candidate-portfolio-field"><span>Description</span><div data-portfolio-description-text></div></div>',
                    ].join('');
                    syncPortfolioItem(item, values);
                    return item;
                };

                const closePortfolioForm = function () {
                    portfolioForm.classList.add('d-none');
                    portfolioEditingIndex.value = '';
                    setPortfolioFormValues(null);
                    if (portfolioFormTitle) {
                        portfolioFormTitle.textContent = 'Portfolio';
                    }
                    if (portfolioSubmit) {
                        portfolioSubmit.textContent = 'Save';
                    }
                    if (portfolioClose) {
                        portfolioClose.textContent = 'Close';
                    }
                    if (activePortfolioItem) {
                        activePortfolioItem.classList.remove('d-none');
                        activePortfolioItem = null;
                    }
                    portfolioList.insertBefore(portfolioForm, portfolioList.firstElementChild);
                    refreshPortfolioAddActions();
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
                    portfolioEditingIndex.value = item ? String(portfolioItems().indexOf(item)) : '';
                    setPortfolioFormValues(item);
                    portfolioForm.classList.remove('d-none');
                    if (portfolioFormTitle) {
                        portfolioFormTitle.textContent = 'Portfolio';
                    }
                    if (portfolioSubmit) {
                        portfolioSubmit.textContent = item ? 'Update' : 'Save';
                    }
                    if (portfolioClose) {
                        portfolioClose.textContent = item ? 'Cancel' : 'Close';
                    }
                    if (item) {
                        item.insertAdjacentElement('beforebegin', portfolioForm);
                        item.classList.add('d-none');
                    } else {
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
                        item.remove();
                        closePortfolioForm();
                        renderPortfolioNumbers();
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
                        descriptionHtml: portfolioQuill && portfolioDescriptionText() ? portfolioQuill.root.innerHTML : '',
                    };

                    if (!values.title) {
                        portfolioTitleInput.focus();
                        return;
                    }

                    if (!values.description) {
                        portfolioDescriptionEditor?.focus();
                        return;
                    }

                    if (values.description.length > 300) {
                        values.description = values.description.slice(0, 300);
                    }

                    if (activePortfolioItem) {
                        syncPortfolioItem(activePortfolioItem, values);
                        activePortfolioItem.classList.remove('d-none');
                    } else {
                        const footerAdd = portfolioList.querySelector('.candidate-portfolio-add-outline');
                        portfolioList.insertBefore(makePortfolioItem(values), footerAdd || null);
                    }

                    closePortfolioForm();
                    renderPortfolioNumbers();
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
                const publicationEditingIndex = publicationForm.querySelector('[data-publication-editing-index]');
                const publicationFormTitle = publicationForm.querySelector('[data-publication-form-title]');
                const publicationCounter = publicationForm.querySelector('[data-publication-character-count]');
                const publicationSubmit = publicationForm.querySelector('[data-publication-submit]');
                const publicationClose = publicationForm.querySelector('[data-publication-close]');
                const publicationEmpty = publicationList.querySelector('[data-publication-empty]');
                let activePublicationItem = null;
                let publicationQuill = null;

                const publicationItems = function () {
                    return Array.from(publicationList.querySelectorAll('[data-publication-item]'));
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
                    publicationItems().forEach(function (item, index) {
                        const title = item.querySelector('.candidate-publication-item__header h2');
                        if (title) {
                            title.textContent = (index + 1) + '. ' + (item.dataset.publicationTitle || '---');
                        }
                    });
                };

                const refreshPublicationEmpty = function () {
                    if (publicationEmpty) {
                        publicationEmpty.classList.toggle('d-none', Boolean(publicationItems().length) || !publicationForm.classList.contains('d-none'));
                    }
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
                    publicationIssuedInput.value = item ? (item.dataset.publicationIssued || '') : '';
                    publicationUrlInput.value = item ? (item.dataset.publicationUrl || '') : '';
                    if (publicationQuill) {
                        publicationQuill.root.innerHTML = item ? (item.dataset.publicationDescription || '') : '';
                    } else if (publicationDescriptionEditor) {
                        publicationDescriptionEditor.textContent = item ? (item.dataset.publicationDescription || '') : '';
                    }
                    refreshPublicationCounter();
                };

                const syncPublicationItem = function (item, values) {
                    item.dataset.publicationTitle = values.title || '---';
                    item.dataset.publicationIssued = values.issued || '---';
                    item.dataset.publicationUrl = values.url || '';
                    item.dataset.publicationDescription = values.descriptionHtml || values.description || '---';
                    const issuedNode = item.querySelector('[data-publication-issued-text]');
                    const urlNode = item.querySelector('[data-publication-url-text]');
                    const descriptionNode = item.querySelector('[data-publication-description-text]');
                    if (issuedNode) {
                        issuedNode.textContent = values.issued || '---';
                    }
                    if (urlNode) {
                        urlNode.textContent = values.url || '---';
                        urlNode.href = values.url
                            ? (/^https?:\/\//i.test(values.url) ? values.url : 'https://' + values.url)
                            : '#';
                    }
                    if (descriptionNode) {
                        descriptionNode.innerHTML = values.descriptionHtml || values.description || '---';
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
                        '<button type="button" data-publication-edit><i class="fa-regular fa-pen-to-square"></i> Edit</button>',
                        '<button type="button" data-publication-delete><i class="fa-regular fa-trash-can"></i> Delete</button>',
                        '</div>',
                        '</div>',
                        '<div class="candidate-publication-field"><span>Issued On</span><strong data-publication-issued-text></strong></div>',
                        '<div class="candidate-publication-field"><span>URL</span><a target="_blank" rel="noopener" data-publication-url-text></a></div>',
                        '<div class="candidate-publication-field"><span>Description</span><div data-publication-description-text></div></div>',
                    ].join('');
                    syncPublicationItem(item, values);
                    return item;
                };

                const closePublicationForm = function () {
                    publicationForm.classList.add('d-none');
                    publicationEditingIndex.value = '';
                    setPublicationFormValues(null);
                    if (publicationFormTitle) {
                        publicationFormTitle.textContent = 'Publication';
                    }
                    if (publicationSubmit) {
                        publicationSubmit.textContent = 'Save';
                    }
                    if (publicationClose) {
                        publicationClose.textContent = 'Close';
                    }
                    if (activePublicationItem) {
                        activePublicationItem.classList.remove('d-none');
                        activePublicationItem = null;
                    }
                    publicationList.insertBefore(publicationForm, publicationList.firstElementChild);
                    refreshPublicationEmpty();
                    refreshPublicationAddActions();
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
                    publicationEditingIndex.value = item ? String(publicationItems().indexOf(item)) : '';
                    setPublicationFormValues(item);
                    publicationForm.classList.remove('d-none');
                    if (publicationFormTitle) {
                        publicationFormTitle.textContent = 'Publication';
                    }
                    if (publicationSubmit) {
                        publicationSubmit.textContent = item ? 'Update' : 'Save';
                    }
                    if (publicationClose) {
                        publicationClose.textContent = item ? 'Cancel' : 'Close';
                    }
                    if (item) {
                        item.insertAdjacentElement('beforebegin', publicationForm);
                        item.classList.add('d-none');
                    } else {
                        const footerAdd = publicationList.querySelector('.candidate-publication-add-outline');
                        publicationList.insertBefore(publicationForm, footerAdd || null);
                    }
                    publicationTitleInput.focus();
                    refreshPublicationEmpty();
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
                        item.remove();
                        closePublicationForm();
                        renderPublicationNumbers();
                        refreshPublicationEmpty();
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

                    if (activePublicationItem) {
                        syncPublicationItem(activePublicationItem, values);
                        activePublicationItem.classList.remove('d-none');
                    } else {
                        const footerAdd = publicationList.querySelector('.candidate-publication-add-outline');
                        publicationList.insertBefore(makePublicationItem(values), footerAdd || null);
                    }

                    closePublicationForm();
                    renderPublicationNumbers();
                    refreshPublicationEmpty();
                });

                closePublicationForm();
                renderPublicationNumbers();
                refreshPublicationEmpty();
            }

            if (awardList && awardForm) {
                const awardTitleInput = awardForm.querySelector('[data-award-title-input]');
                const awardIssuedInput = awardForm.querySelector('[data-award-issued-input]');
                const awardUrlInput = awardForm.querySelector('[data-award-url-input]');
                const awardDescriptionInput = awardForm.querySelector('[data-award-description-input]');
                const awardDescriptionEditor = awardForm.querySelector('[data-award-description-editor]');
                const awardEditingIndex = awardForm.querySelector('[data-award-editing-index]');
                const awardCounter = awardForm.querySelector('[data-award-character-count]');
                const awardSubmit = awardForm.querySelector('[data-award-submit]');
                const awardClose = awardForm.querySelector('[data-award-close]');
                const awardEmpty = awardList.querySelector('[data-award-empty]');
                let activeAwardItem = null;
                let awardQuill = null;

                const awardItems = function () {
                    return Array.from(awardList.querySelectorAll('[data-award-item]'));
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
                    awardItems().forEach(function (item, index) {
                        const title = item.querySelector('.candidate-publication-item__header h2');
                        if (title) {
                            title.textContent = (index + 1) + '. ' + (item.dataset.awardTitle || '---');
                        }
                    });
                };

                const refreshAwardEmpty = function () {
                    if (awardEmpty) {
                        awardEmpty.classList.toggle('d-none', Boolean(awardItems().length) || !awardForm.classList.contains('d-none'));
                    }
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
                    awardIssuedInput.value = item ? (item.dataset.awardIssued || '') : '';
                    awardUrlInput.value = item ? (item.dataset.awardUrl || '') : '';
                    if (awardQuill) {
                        awardQuill.root.innerHTML = item ? (item.dataset.awardDescription || '') : '';
                    } else if (awardDescriptionEditor) {
                        awardDescriptionEditor.textContent = item ? (item.dataset.awardDescription || '') : '';
                    }
                    refreshAwardCounter();
                };

                const syncAwardItem = function (item, values) {
                    item.dataset.awardTitle = values.title || '---';
                    item.dataset.awardIssued = values.issued || '---';
                    item.dataset.awardUrl = values.url || '';
                    item.dataset.awardDescription = values.descriptionHtml || values.description || '---';
                    const issuedNode = item.querySelector('[data-award-issued-text]');
                    const urlNode = item.querySelector('[data-award-url-text]');
                    const descriptionNode = item.querySelector('[data-award-description-text]');
                    if (issuedNode) {
                        issuedNode.textContent = values.issued || '---';
                    }
                    if (urlNode) {
                        urlNode.textContent = values.url || '---';
                        urlNode.href = values.url ? (/^https?:\/\//i.test(values.url) ? values.url : 'https://' + values.url) : '#';
                    }
                    if (descriptionNode) {
                        descriptionNode.innerHTML = values.descriptionHtml || values.description || '---';
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
                        '<button type="button" data-award-edit><i class="fa-regular fa-pen-to-square"></i> Edit</button>',
                        '<button type="button" data-award-delete><i class="fa-regular fa-trash-can"></i> Delete</button>',
                        '</div>',
                        '</div>',
                        '<div class="candidate-publication-field"><span>Issued On</span><strong data-award-issued-text></strong></div>',
                        '<div class="candidate-publication-field"><span>URL</span><a target="_blank" rel="noopener" data-award-url-text></a></div>',
                        '<div class="candidate-publication-field"><span>Description</span><div data-award-description-text></div></div>',
                    ].join('');
                    syncAwardItem(item, values);
                    return item;
                };

                const closeAwardForm = function () {
                    awardForm.classList.add('d-none');
                    awardEditingIndex.value = '';
                    setAwardFormValues(null);
                    if (awardSubmit) {
                        awardSubmit.textContent = 'Save';
                    }
                    if (awardClose) {
                        awardClose.textContent = 'Close';
                    }
                    if (activeAwardItem) {
                        activeAwardItem.classList.remove('d-none');
                        activeAwardItem = null;
                    }
                    awardList.insertBefore(awardForm, awardList.firstElementChild);
                    refreshAwardEmpty();
                    refreshAwardAddActions();
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
                    awardEditingIndex.value = item ? String(awardItems().indexOf(item)) : '';
                    setAwardFormValues(item);
                    awardForm.classList.remove('d-none');
                    if (awardSubmit) {
                        awardSubmit.textContent = item ? 'Update' : 'Save';
                    }
                    if (awardClose) {
                        awardClose.textContent = item ? 'Cancel' : 'Close';
                    }
                    if (item) {
                        item.insertAdjacentElement('beforebegin', awardForm);
                        item.classList.add('d-none');
                    } else {
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
                        item.remove();
                        closeAwardForm();
                        renderAwardNumbers();
                        refreshAwardEmpty();
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
                    if (activeAwardItem) {
                        syncAwardItem(activeAwardItem, values);
                        activeAwardItem.classList.remove('d-none');
                    } else {
                        const footerAdd = awardList.querySelector('.candidate-publication-add-outline');
                        awardList.insertBefore(makeAwardItem(values), footerAdd || null);
                    }
                    closeAwardForm();
                    renderAwardNumbers();
                    refreshAwardEmpty();
                });

                closeAwardForm();
                renderAwardNumbers();
                refreshAwardEmpty();
            }

            if (projectList) {
                const projectForm = projectList.querySelector('[data-project-form]');
                const projectTitleInput = projectForm ? projectForm.querySelector('[data-project-title-input]') : null;
                const projectIssuedInput = projectForm ? projectForm.querySelector('[data-project-issued-input]') : null;
                const projectUrlInput = projectForm ? projectForm.querySelector('[data-project-url-input]') : null;
                const projectDescriptionInput = projectForm ? projectForm.querySelector('[data-project-description-input]') : null;
                const projectDescriptionEditor = projectForm ? projectForm.querySelector('[data-project-description-editor]') : null;
                const projectEditingIndex = projectForm ? projectForm.querySelector('[data-project-editing-index]') : null;
                const projectCounter = projectForm ? projectForm.querySelector('[data-project-character-count]') : null;
                const projectSubmit = projectForm ? projectForm.querySelector('[data-project-submit]') : null;
                const projectClose = projectForm ? projectForm.querySelector('[data-project-close]') : null;
                const projectEmpty = projectList.querySelector('[data-project-empty]');
                let activeProjectItem = null;
                let projectQuill = null;

                const projectItems = function () {
                    return Array.from(projectList.querySelectorAll('[data-project-item]'));
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
                    projectItems().forEach(function (item, index) {
                        const title = item.querySelector('.candidate-project-item__header h2');
                        if (title) {
                            title.textContent = (index + 1) + '. ' + (item.dataset.projectTitle || '---');
                        }
                    });
                };

                const refreshProjectEmpty = function () {
                    if (projectEmpty) {
                        projectEmpty.classList.toggle('d-none', Boolean(projectItems().length) || (projectForm && !projectForm.classList.contains('d-none')));
                    }
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
                    projectIssuedInput.value = item ? (item.dataset.projectIssued || '') : '';
                    projectUrlInput.value = item ? (item.dataset.projectUrl || '') : '';
                    if (projectQuill) {
                        projectQuill.root.innerHTML = item ? (item.dataset.projectDescription || '') : '';
                    } else if (projectDescriptionEditor) {
                        projectDescriptionEditor.textContent = item ? (item.dataset.projectDescription || '') : '';
                    }
                    refreshProjectCounter();
                };

                const syncProjectItem = function (item, values) {
                    item.dataset.projectTitle = values.title || '---';
                    item.dataset.projectIssued = values.issued || '---';
                    item.dataset.projectUrl = values.url || '';
                    item.dataset.projectDescription = values.descriptionHtml || values.description || '---';
                    const issuedNode = item.querySelector('[data-project-issued-text]');
                    const urlNode = item.querySelector('[data-project-url-text]');
                    const descriptionNode = item.querySelector('[data-project-description-text]');
                    if (issuedNode) {
                        issuedNode.textContent = values.issued || '---';
                    }
                    if (urlNode) {
                        urlNode.textContent = values.url || '---';
                        urlNode.href = values.url ? (/^https?:\/\//i.test(values.url) ? values.url : 'https://' + values.url) : '#';
                    }
                    if (descriptionNode) {
                        descriptionNode.innerHTML = values.descriptionHtml || values.description || '---';
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
                        '<button type="button" data-project-edit><i class="fa-regular fa-pen-to-square"></i> Edit</button>',
                        '<button type="button" data-project-delete><i class="fa-regular fa-trash-can"></i> Delete</button>',
                        '</div>',
                        '</div>',
                        '<div class="candidate-project-field"><span>Issued On:</span><strong data-project-issued-text></strong></div>',
                        '<div class="candidate-project-field"><span>URL</span><a target="_blank" rel="noopener" data-project-url-text></a></div>',
                        '<div class="candidate-project-field"><span>Description</span><div data-project-description-text></div></div>',
                    ].join('');
                    syncProjectItem(item, values);
                    return item;
                };

                const closeProjectForm = function () {
                    if (!projectForm) {
                        return;
                    }
                    projectForm.classList.add('d-none');
                    projectEditingIndex.value = '';
                    setProjectFormValues(null);
                    if (projectSubmit) {
                        projectSubmit.textContent = 'Save';
                    }
                    if (projectClose) {
                        projectClose.textContent = 'Close';
                    }
                    if (activeProjectItem) {
                        activeProjectItem.classList.remove('d-none');
                        activeProjectItem = null;
                    }
                    projectList.insertBefore(projectForm, projectList.firstElementChild);
                    refreshProjectEmpty();
                    refreshProjectAddActions();
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
                    projectEditingIndex.value = item ? String(projectItems().indexOf(item)) : '';
                    setProjectFormValues(item);
                    projectForm.classList.remove('d-none');
                    if (projectSubmit) {
                        projectSubmit.textContent = item ? 'Update' : 'Save';
                    }
                    if (projectClose) {
                        projectClose.textContent = item ? 'Cancel' : 'Close';
                    }
                    if (item) {
                        item.insertAdjacentElement('beforebegin', projectForm);
                        item.classList.add('d-none');
                    } else {
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
                        item.remove();
                        closeProjectForm();
                        renderProjectNumbers();
                        refreshProjectEmpty();
                        refreshProjectAddActions();
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
                    if (activeProjectItem) {
                        syncProjectItem(activeProjectItem, values);
                        activeProjectItem.classList.remove('d-none');
                    } else {
                        const footerAdd = projectList.querySelector('.candidate-project-add-outline');
                        projectList.insertBefore(makeProjectItem(values), footerAdd || null);
                    }
                    closeProjectForm();
                    renderProjectNumbers();
                    refreshProjectEmpty();
                });

                closeProjectForm();
                renderProjectNumbers();
                refreshProjectEmpty();
                refreshProjectAddActions();
            }

            if (otherList && otherForm) {
                const otherTitleInput = otherForm.querySelector('[data-other-title-input]');
                const otherIssuedInput = otherForm.querySelector('[data-other-issued-input]');
                const otherUrlInput = otherForm.querySelector('[data-other-url-input]');
                const otherDescriptionInput = otherForm.querySelector('[data-other-description-input]');
                const otherDescriptionEditor = otherForm.querySelector('[data-other-description-editor]');
                const otherEditingIndex = otherForm.querySelector('[data-other-editing-index]');
                const otherFormTitle = otherForm.querySelector('[data-other-form-title]');
                const otherCounter = otherForm.querySelector('[data-other-character-count]');
                const otherSubmit = otherForm.querySelector('[data-other-submit]');
                const otherClose = otherForm.querySelector('[data-other-close]');
                const otherEmpty = otherList.querySelector('[data-other-empty]');
                let activeOtherItem = null;
                let otherQuill = null;

                const otherItems = function () {
                    return Array.from(otherList.querySelectorAll('[data-other-item]'));
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
                    otherItems().forEach(function (item, index) {
                        const title = item.querySelector('.candidate-other-item__header h2');
                        if (title) {
                            title.textContent = (index + 1) + '. ' + (item.dataset.otherTitle || '---');
                        }
                    });
                };

                const refreshOtherEmpty = function () {
                    if (otherEmpty) {
                        otherEmpty.classList.toggle('d-none', Boolean(otherItems().length) || !otherForm.classList.contains('d-none'));
                    }
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
                    otherIssuedInput.value = item ? (item.dataset.otherIssued || '') : '';
                    otherUrlInput.value = item ? (item.dataset.otherUrl || '') : '';
                    if (otherQuill) {
                        otherQuill.root.innerHTML = item ? (item.dataset.otherDescription || '') : '';
                    } else if (otherDescriptionEditor) {
                        otherDescriptionEditor.textContent = item ? (item.dataset.otherDescription || '') : '';
                    }
                    refreshOtherCounter();
                };

                const syncOtherItem = function (item, values) {
                    item.dataset.otherTitle = values.title || '---';
                    item.dataset.otherIssued = values.issued || '---';
                    item.dataset.otherUrl = values.url || '';
                    item.dataset.otherDescription = values.descriptionHtml || values.description || '---';
                    const issuedNode = item.querySelector('[data-other-issued-text]');
                    const urlNode = item.querySelector('[data-other-url-text]');
                    const descriptionNode = item.querySelector('[data-other-description-text]');
                    if (issuedNode) {
                        issuedNode.textContent = values.issued || '---';
                    }
                    if (urlNode) {
                        urlNode.textContent = values.url || '---';
                        urlNode.href = values.url ? (/^https?:\/\//i.test(values.url) ? values.url : 'https://' + values.url) : '#';
                    }
                    if (descriptionNode) {
                        descriptionNode.innerHTML = values.descriptionHtml || values.description || '---';
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
                        '<button type="button" data-other-edit><i class="fa-regular fa-pen-to-square"></i> Edit</button>',
                        '<button type="button" data-other-delete><i class="fa-regular fa-trash-can"></i> Delete</button>',
                        '</div>',
                        '</div>',
                        '<div class="candidate-other-field"><span>Issued On:</span><strong data-other-issued-text></strong></div>',
                        '<div class="candidate-other-field"><span>URL</span><a target="_blank" rel="noopener" data-other-url-text></a></div>',
                        '<div class="candidate-other-field"><span>Description</span><div data-other-description-text></div></div>',
                    ].join('');
                    syncOtherItem(item, values);
                    return item;
                };

                const closeOtherForm = function () {
                    otherForm.classList.add('d-none');
                    otherEditingIndex.value = '';
                    setOtherFormValues(null);
                    if (otherFormTitle) {
                        otherFormTitle.textContent = 'Other Accomplishment';
                    }
                    if (otherSubmit) {
                        otherSubmit.textContent = 'Save';
                    }
                    if (otherClose) {
                        otherClose.textContent = 'Close';
                    }
                    if (activeOtherItem) {
                        activeOtherItem.classList.remove('d-none');
                        activeOtherItem = null;
                    }
                    otherList.insertBefore(otherForm, otherList.firstElementChild);
                    refreshOtherEmpty();
                    refreshOtherAddActions();
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
                    otherEditingIndex.value = item ? String(otherItems().indexOf(item)) : '';
                    setOtherFormValues(item);
                    otherForm.classList.remove('d-none');
                    if (otherSubmit) {
                        otherSubmit.textContent = item ? 'Update' : 'Save';
                    }
                    if (otherClose) {
                        otherClose.textContent = item ? 'Cancel' : 'Close';
                    }
                    if (item) {
                        item.insertAdjacentElement('beforebegin', otherForm);
                        item.classList.add('d-none');
                    } else {
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
                        item.remove();
                        closeOtherForm();
                        renderOtherNumbers();
                        refreshOtherEmpty();
                        refreshOtherAddActions();
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
                    if (activeOtherItem) {
                        syncOtherItem(activeOtherItem, values);
                        activeOtherItem.classList.remove('d-none');
                    } else {
                        const footerAdd = otherList.querySelector('.candidate-other-add-outline');
                        otherList.insertBefore(makeOtherItem(values), footerAdd || null);
                    }
                    closeOtherForm();
                    renderOtherNumbers();
                    refreshOtherEmpty();
                });

                closeOtherForm();
                renderOtherNumbers();
                refreshOtherEmpty();
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
                    panel.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                    });
                });
            });
        });
    </script>
@endpush
