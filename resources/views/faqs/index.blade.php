@extends('layouts.app')

@section('title')
    {{ __('messages.faq.faq') }}
@endsection

@section('content')
    <link rel="stylesheet" href="{{ asset('css/header-padding.css') }}">
    <style>
        .faq-admin-container {
            margin: 0 auto;
        }
        .category-block-card {
            border: 1px solid #eef2f7;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
            margin-bottom: 24px;
            background: #ffffff;
            transition: all 0.25s ease;
        }
        .category-block-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 6px 22px rgba(59, 130, 246, 0.08);
        }
        .category-block-card.inactive-category {
            opacity: 0.75;
            background-color: #f8fafc;
        }
        .category-block-header {
            padding: 18px 24px;
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            user-select: none;
        }
        .category-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-right: 16px;
        }
        .category-icon-box.employer-bg {
            background: #f0fdf4;
            color: #16a34a;
        }
        .faq-accordion-item {
            border: 1px solid #f1f5f9;
            border-radius: 8px;
            margin-bottom: 12px;
            background: #fafafa;
            overflow: hidden;
        }
        .faq-accordion-header {
            padding: 14px 20px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 600;
            color: #1e293b;
            font-size: 15px;
            transition: background 0.15s ease;
        }
        .faq-accordion-header:hover {
            background: #f8fafc;
        }
        .faq-accordion-body {
            padding: 18px 20px;
            background: #ffffff;
            border-top: 1px dashed #e2e8f0;
            color: #475569;
            font-size: 14px;
            line-height: 1.6;
        }
        .faq-action-btn {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            margin-left: 4px;
        }
        .audience-tab-btn {
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
        }
        .audience-tab-btn.active {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }
        .admin-faq-search-box {
            max-width: 320px;
            position: relative;
            width: 320px;
        }
        .admin-faq-search-box .admin-faq-search-icon {
            color: #6b7280;
            font-size: 16px;
            left: 12px;
            line-height: 1;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
        }
        .admin-faq-search-box .form-control {
            background: #ffffff;
            border: 1px solid #cfd6df;
            border-radius: 5px;
            color: #1f2937;
            font-size: 14px;
            height: 46px;
            padding-left: 34px;
            padding-right: 14px;
            width: 100%;
        }
        .admin-faq-search-box .form-control::placeholder {
            color: #5f6b7a;
            opacity: 1;
        }
        .admin-faq-search-box .form-control:focus {
            border-color: #9ca3af;
            box-shadow: none;
        }
        .cursor-pointer {
            cursor: pointer;
        }
        @media (max-width: 575.98px) {
            .admin-faq-search-box {
                width: 100%;
            }
        }
    </style>

    <div class="container-fluid faq-admin-container py-4">
        <div class="d-flex flex-column">
            @include('flash::message')

            {{-- Top Header Section --}}
            <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-3">
                <div class="admin-faq-search-box">
                    <i class="fa-solid fa-magnifying-glass admin-faq-search-icon"></i>
                    <div>
                        <input type="search" id="adminFaqSearch"
                               autocomplete="off"
                               placeholder="{{ __('web.common.search') }}" class="form-control">
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-primary addFAQCategoryModalBtn px-4">
                        <i class="fa-solid fa-folder-plus me-1"></i> Add Category
                    </button>
                    <a class="btn btn-primary addFaqModal px-4">
                        <i class="fa-solid fa-plus me-2"></i> {{ __('messages.faq.add') }}
                    </a>
                </div>
            </div>

            {{-- Filter Controls --}}
            <div class="card mb-5 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row align-items-center g-3">
                        <div class="col-md-6">
                            <div class="d-flex gap-2">
                                <button type="button" class="audience-tab-btn" data-audience="candidate">
                                    Candidate FAQs
                                </button>
                                <button type="button" class="audience-tab-btn" data-audience="employer">
                                    Employer FAQs
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Category Blocks Section --}}
            <div id="faqCategoryBlocks">
                @foreach($categories as $category)
                    @php
                        $faqsCount = $category->faqs->count();
                        $isEmployer = ($category->audience ?? '') === 'employer';
                        $isActive = $category->is_active ?? true;
                    @endphp

                    <div class="category-block-card category-item {{ $isActive ? '' : 'inactive-category' }}" data-category-id="{{ $category->id }}" data-audience="{{ $category->audience ?? 'candidate' }}">
                        {{-- Category Header --}}
                        <div class="category-block-header">
                            <div class="d-flex align-items-center flex-grow-1 cursor-pointer" data-bs-toggle="collapse" data-bs-target="#category-collapse-{{ $category->id }}" aria-expanded="true">
                                <div class="category-icon-box {{ $isEmployer ? 'employer-bg' : '' }}">
                                    <i class="{{ $category->icon ?: 'fa-solid fa-folder-open' }}"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1 text-dark fw-bolder">{{ $category->localizedName() }}</h4>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($isEmployer)
                                            <span class="badge bg-light-success text-success px-3 py-1 fw-bold">Employer</span>
                                        @else
                                            <span class="badge bg-light-primary text-primary px-3 py-1 fw-bold">Candidate</span>
                                        @endif
                                        <span class="badge bg-light-dark text-dark px-2 py-1 fw-semibold">{{ $faqsCount }} {{ Str::plural('Question', $faqsCount) }}</span>
                                        @if(!$isActive)
                                            <span class="badge bg-light-danger text-danger px-2 py-1 fw-bold">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-1 ms-3">
                                {{-- Active/Inactive Toggle Switch --}}
                                <div class="form-check form-switch form-switch-sm me-2" title="Toggle Active/Inactive">
                                    <input class="form-check-input toggleCategoryStatusBtn cursor-pointer" type="checkbox" data-id="{{ $category->id }}" {{ $isActive ? 'checked' : '' }}>
                                </div>

                                {{-- Sorting Move Up/Down Buttons --}}
                                <button type="button" title="Move Category Up" class="btn btn-icon btn-bg-light btn-active-color-primary faq-action-btn moveCategoryUpBtn" data-id="{{ $category->id }}">
                                    <i class="fa-solid fa-arrow-up text-secondary fs-7"></i>
                                </button>
                                <button type="button" title="Move Category Down" class="btn btn-icon btn-bg-light btn-active-color-primary faq-action-btn moveCategoryDownBtn" data-id="{{ $category->id }}">
                                    <i class="fa-solid fa-arrow-down text-secondary fs-7"></i>
                                </button>

                                {{-- Edit & Delete Category Buttons --}}
                                <button type="button" title="Edit Category" class="btn btn-icon btn-bg-light btn-active-color-primary faq-action-btn editFAQCategoryBtn" data-id="{{ $category->id }}" data-name-en="{{ $category->name_en ?: $category->name }}" data-name-bn="{{ $category->name_bn }}" data-audience="{{ $category->audience }}" data-icon="{{ $category->icon }}">
                                    <i class="fa-solid fa-pen text-primary"></i>
                                </button>
                                <button type="button" title="Delete Category" class="btn btn-icon btn-bg-light btn-active-color-danger faq-action-btn deleteFAQCategoryBtn" data-id="{{ $category->id }}">
                                    <i class="fa-solid fa-trash text-danger"></i>
                                </button>

                                <div class="cursor-pointer ms-2" data-bs-toggle="collapse" data-bs-target="#category-collapse-{{ $category->id }}">
                                    <i class="fa-solid fa-chevron-down text-muted transition-icon"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Category Accordion Body --}}
                        <div id="category-collapse-{{ $category->id }}" class="collapse show p-4 pt-3">
                            @if($faqsCount > 0)
                                <div class="accordion" id="accordion-cat-{{ $category->id }}">
                                    @foreach($category->faqs as $faq)
                                        <div class="faq-accordion-item faq-search-item" data-question="{{ strtolower(strip_tags($faq->title.' '.$faq->title_en.' '.$faq->title_bn)) }}" data-answer="{{ strtolower(strip_tags($faq->description.' '.$faq->description_en.' '.$faq->description_bn)) }}">
                                            <div class="faq-accordion-header">
                                                <div class="d-flex align-items-center gap-3 flex-grow-1 cursor-pointer" data-bs-toggle="collapse" data-bs-target="#faq-collapse-{{ $faq->id }}">
                                                    <i class="fa-solid fa-circle-question text-primary fs-5 me-1"></i>
                                                    <span class="text-dark fw-bold">{{ $faq->localizedTitle() }}</span>
                                                </div>
                                                <div class="d-flex align-items-center ms-3">
                                                    <button type="button" title="Show" class="btn btn-icon btn-bg-light btn-active-color-primary faq-action-btn faq-show-btn" data-id="{{ $faq->id }}">
                                                        <i class="fa-solid fa-eye text-info"></i>
                                                    </button>
                                                    <button type="button" title="Edit" class="btn btn-icon btn-bg-light btn-active-color-primary faq-action-btn faqs-edit-btn" data-id="{{ $faq->id }}">
                                                        <i class="fa-solid fa-pen text-primary"></i>
                                                    </button>
                                                    <button type="button" title="Delete" class="btn btn-icon btn-bg-light btn-active-color-danger faq-action-btn faqs-delete-btn" data-id="{{ $faq->id }}">
                                                        <i class="fa-solid fa-trash text-danger"></i>
                                                    </button>
                                                    <div class="cursor-pointer ms-3" data-bs-toggle="collapse" data-bs-target="#faq-collapse-{{ $faq->id }}">
                                                        <i class="fa-solid fa-chevron-down text-muted fs-7"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="faq-collapse-{{ $faq->id }}" class="collapse" data-bs-parent="#accordion-cat-{{ $category->id }}">
                                                <div class="faq-accordion-body">
                                                    {!! $faq->localizedDescription() !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4 text-muted">
                                    No FAQs added in this category yet.
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                {{-- Uncategorized FAQs Block --}}
                @if(isset($uncategorizedFaqs) && $uncategorizedFaqs->count() > 0)
                    <div class="category-block-card category-item" data-audience="all">
                        <div class="category-block-header">
                            <div class="d-flex align-items-center flex-grow-1 cursor-pointer" data-bs-toggle="collapse" data-bs-target="#category-collapse-uncategorized" aria-expanded="true">
                                <div class="category-icon-box bg-light-secondary text-secondary">
                                    <i class="fa-solid fa-layer-group"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1 text-dark fw-bolder">Other / Uncategorized Questions</h4>
                                    <span class="badge bg-light-dark text-dark px-2 py-1 fw-semibold">{{ $uncategorizedFaqs->count() }} Questions</span>
                                </div>
                            </div>
                            <div class="cursor-pointer ms-2" data-bs-toggle="collapse" data-bs-target="#category-collapse-uncategorized">
                                <i class="fa-solid fa-chevron-down text-muted transition-icon"></i>
                            </div>
                        </div>

                        <div id="category-collapse-uncategorized" class="collapse show p-4 pt-3">
                            <div class="accordion" id="accordion-cat-uncategorized">
                                @foreach($uncategorizedFaqs as $faq)
                                    <div class="faq-accordion-item faq-search-item" data-question="{{ strtolower(strip_tags($faq->title.' '.$faq->title_en.' '.$faq->title_bn)) }}" data-answer="{{ strtolower(strip_tags($faq->description.' '.$faq->description_en.' '.$faq->description_bn)) }}">
                                        <div class="faq-accordion-header">
                                            <div class="d-flex align-items-center gap-3 flex-grow-1 cursor-pointer" data-bs-toggle="collapse" data-bs-target="#faq-collapse-{{ $faq->id }}">
                                                <i class="fa-solid fa-circle-question text-secondary fs-5 me-1"></i>
                                                <span class="text-dark fw-bold">{{ $faq->localizedTitle() }}</span>
                                            </div>
                                            <div class="d-flex align-items-center ms-3">
                                                <button type="button" title="Show" class="btn btn-icon btn-bg-light btn-active-color-primary faq-action-btn faq-show-btn" data-id="{{ $faq->id }}">
                                                    <i class="fa-solid fa-eye text-info"></i>
                                                </button>
                                                <button type="button" title="Edit" class="btn btn-icon btn-bg-light btn-active-color-primary faq-action-btn faqs-edit-btn" data-id="{{ $faq->id }}">
                                                    <i class="fa-solid fa-pen text-primary"></i>
                                                </button>
                                                <button type="button" title="Delete" class="btn btn-icon btn-bg-light btn-active-color-danger faq-action-btn faqs-delete-btn" data-id="{{ $faq->id }}">
                                                    <i class="fa-solid fa-trash text-danger"></i>
                                                </button>
                                                <div class="cursor-pointer ms-3" data-bs-toggle="collapse" data-bs-target="#faq-collapse-{{ $faq->id }}">
                                                    <i class="fa-solid fa-chevron-down text-muted fs-7"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="faq-collapse-{{ $faq->id }}" class="collapse" data-bs-parent="#accordion-cat-uncategorized">
                                            <div class="faq-accordion-body">
                                                {!! $faq->localizedDescription() !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Empty Search Result Message --}}
                <div id="noFaqFound" class="card border-0 shadow-sm d-none">
                    <div class="card-body p-5 text-center text-muted">
                        <i class="fa-solid fa-face-frown fs-1 mb-3 text-warning"></i>
                        <h4 class="fw-bold">No FAQs found matching your search.</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('faqs.add_modal')
    @include('faqs.edit_modal')
    @include('faqs.show_modal')
    @include('faqs.add_category_modal')
    @include('faqs.edit_category_modal')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('adminFaqSearch');
            const audienceBtns = document.querySelectorAll('.audience-tab-btn');
            const categoryItems = document.querySelectorAll('.category-item');
            const noFaqFound = document.getElementById('noFaqFound');

            let currentAudience = localStorage.getItem('adminFaqAudienceTab') || 'candidate';

            // Set initial active tab button
            audienceBtns.forEach(b => {
                b.classList.toggle('active', b.dataset.audience === currentAudience);
            });

            function notifySuccess(message) {
                if (typeof displaySuccessMessage === 'function') {
                    displaySuccessMessage(message);
                } else if (typeof toastr !== 'undefined') {
                    toastr.success(message);
                }
            }

            function notifyAndReload(message) {
                notifySuccess(message);
                setTimeout(function () {
                    window.location.reload();
                }, 1000);
            }

            function notifyError(message) {
                if (typeof displayErrorMessage === 'function') {
                    displayErrorMessage(message);
                } else if (typeof toastr !== 'undefined') {
                    toastr.error(message);
                }
            }

            function filterAdminFaqs() {
                const query = searchInput.value.trim().toLowerCase();
                let visibleCategories = 0;

                categoryItems.forEach(function (categoryCard) {
                    const categoryAudience = categoryCard.dataset.audience;
                    const matchesAudience = (categoryAudience === currentAudience || categoryAudience === 'all');

                    if (!matchesAudience) {
                        categoryCard.classList.add('d-none');
                        return;
                    }

                    const itemsInCategory = categoryCard.querySelectorAll('.faq-search-item');
                    let visibleItemsInCategory = 0;

                    itemsInCategory.forEach(function (item) {
                        const questionText = item.dataset.question || '';
                        const answerText = item.dataset.answer || '';
                        const matchesQuery = !query || questionText.includes(query) || answerText.includes(query);

                        item.classList.toggle('d-none', !matchesQuery);
                        if (matchesQuery) {
                            visibleItemsInCategory++;
                        }
                    });

                    const showCategory = visibleItemsInCategory > 0 || (!query && itemsInCategory.length === 0);
                    categoryCard.classList.toggle('d-none', !showCategory);

                    if (showCategory) {
                        visibleCategories++;
                    }
                });

                if (noFaqFound) {
                    noFaqFound.classList.toggle('d-none', visibleCategories > 0);
                }
            }

            // Initial filter run on page load
            filterAdminFaqs();

            // Audience Tab Filter
            audienceBtns.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    audienceBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentAudience = this.dataset.audience;
                    localStorage.setItem('adminFaqAudienceTab', currentAudience);
                    filterAdminFaqs();
                });
            });

            // Live Search Input
            if (searchInput) {
                searchInput.addEventListener('keyup', filterAdminFaqs);
            }

            // Toggle Category Active/Inactive Status
            $(document).on('change', '.toggleCategoryStatusBtn', function (e) {
                e.stopPropagation();
                const id = $(this).data('id');
                const card = $(this).closest('.category-block-card');

                $.ajax({
                    url: "{{ url('admin/faq-categories') }}/" + id + "/status",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        if (res.success) {
                            notifySuccess(res.message);
                            card.toggleClass('inactive-category');
                        }
                    },
                    error: function (res) {
                        notifyError(res.responseJSON ? res.responseJSON.message : 'Error updating category status');
                    }
                });
            });

            // Save Category Order Helper
            function saveCategoryOrder() {
                const order = [];
                $('#faqCategoryBlocks .category-block-card[data-category-id]').each(function () {
                    const catId = $(this).data('category-id');
                    if (catId) {
                        order.push(catId);
                    }
                });

                $.ajax({
                    url: "{{ route('faq-categories.update-order') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        order: order
                    },
                    success: function (res) {
                        if (res.success) {
                            notifySuccess(res.message || 'Category order saved.');
                        }
                    },
                    error: function (res) {
                        notifyError(res.responseJSON ? res.responseJSON.message : 'Error saving category order');
                    }
                });
            }

            // Move Category Up
            $(document).on('click', '.moveCategoryUpBtn', function (e) {
                e.stopPropagation();
                e.preventDefault();
                const card = $(this).closest('.category-block-card');
                const prevCard = card.prev('.category-block-card[data-category-id]');

                if (prevCard.length) {
                    card.insertBefore(prevCard);
                    saveCategoryOrder();
                }
            });

            // Move Category Down
            $(document).on('click', '.moveCategoryDownBtn', function (e) {
                e.stopPropagation();
                e.preventDefault();
                const card = $(this).closest('.category-block-card');
                const nextCard = card.next('.category-block-card[data-category-id]');

                if (nextCard.length) {
                    card.insertAfter(nextCard);
                    saveCategoryOrder();
                }
            });

            // Add Category Modal Trigger
            $(document).on('click', '.addFAQCategoryModalBtn', function (e) {
                e.stopPropagation();
                e.preventDefault();
                $('#addFAQCategoryModal').modal('show');
            });

            // Submit Add Category Form
            $(document).on('submit', '#addFAQCategoryForm', function (e) {
                e.preventDefault();
                const btn = $('#addFAQCategorySaveBtn');
                btn.prop('disabled', true);

                $.ajax({
                    url: "{{ route('faq-categories.store') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (result) {
                        if (result.success) {
                            $('#addFAQCategoryModal').modal('hide');
                            notifyAndReload(result.message || 'Category created successfully.');
                        }
                    },
                    error: function (result) {
                        notifyError(result.responseJSON ? result.responseJSON.message : 'Error creating category');
                        btn.prop('disabled', false);
                    }
                });
            });

            // Edit Category Modal Trigger
            $(document).on('click', '.editFAQCategoryBtn', function (e) {
                e.stopPropagation();
                e.preventDefault();
                const id = $(e.currentTarget).attr('data-id');
                const nameEn = $(e.currentTarget).attr('data-name-en');
                const nameBn = $(e.currentTarget).attr('data-name-bn');
                const audience = $(e.currentTarget).attr('data-audience');
                const icon = $(e.currentTarget).attr('data-icon');

                $('#editFAQCategoryId').val(id);
                $('#editFAQCategoryNameEn').val(nameEn);
                $('#editFAQCategoryNameBn').val(nameBn);
                $('#editFAQCategoryAudience').val(audience);
                $('#editFAQCategoryIcon').val(icon);
                $('#editFAQCategoryModal').modal('show');
            });

            // Submit Edit Category Form
            $(document).on('submit', '#editFAQCategoryForm', function (e) {
                e.preventDefault();
                const id = $('#editFAQCategoryId').val();
                const btn = $('#editFAQCategorySaveBtn');
                btn.prop('disabled', true);

                $.ajax({
                    url: "{{ url('admin/faq-categories') }}/" + id,
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function (result) {
                        if (result.success) {
                            $('#editFAQCategoryModal').modal('hide');
                            notifyAndReload(result.message || 'Category updated successfully.');
                        }
                    },
                    error: function (result) {
                        notifyError(result.responseJSON ? result.responseJSON.message : 'Error updating category');
                        btn.prop('disabled', false);
                    }
                });
            });

            // Delete Category Trigger
            $(document).on('click', '.deleteFAQCategoryBtn', function (e) {
                e.stopPropagation();
                e.preventDefault();
                const id = $(e.currentTarget).attr('data-id');
                const deleteUrl = "{{ url('admin/faq-categories') }}/" + id;

                function doDeleteCategory() {
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (res) {
                            if (res.success) {
                                notifyAndReload(res.message || 'Category deleted successfully.');
                            }
                        },
                        error: function (res) {
                            notifyError(res.responseJSON ? res.responseJSON.message : 'Error deleting category');
                        }
                    });
                }

                if (typeof swal === 'function') {
                    swal({
                        title: "Delete Category?",
                        text: "Are you sure you want to delete this FAQ Category? Associated questions will become uncategorized.",
                        icon: "warning",
                        buttons: {
                            confirm: "Yes, Delete",
                            cancel: "No, Cancel"
                        },
                        dangerMode: true,
                    }).then(function (willDelete) {
                        if (willDelete) {
                            doDeleteCategory();
                        }
                    });
                } else if (typeof Swal !== 'undefined' && typeof Swal.fire === 'function') {
                    Swal.fire({
                        title: 'Delete Category?',
                        text: 'Are you sure you want to delete this FAQ Category? Associated questions will become uncategorized.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            doDeleteCategory();
                        }
                    });
                } else {
                    if (confirm("Are you sure you want to delete this FAQ Category? Associated questions will become uncategorized.")) {
                        doDeleteCategory();
                    }
                }
            });

            // FAQ Question Delete Handler
            $(document).on('click', '.faqs-delete-btn', function (e) {
                e.stopPropagation();
                e.preventDefault();
                const id = $(e.currentTarget).attr('data-id');
                const deleteUrl = "{{ url('admin/faqs') }}/" + id;

                function doDeleteFaq() {
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (res) {
                            if (res.success) {
                                notifyAndReload(res.message || 'FAQ deleted successfully.');
                            }
                        },
                        error: function (res) {
                            notifyError(res.responseJSON ? res.responseJSON.message : 'Error deleting FAQ');
                        }
                    });
                }

                if (typeof swal === 'function') {
                    swal({
                        title: "Delete Question?",
                        text: "Are you sure you want to delete this FAQ?",
                        icon: "warning",
                        buttons: {
                            confirm: "Yes, Delete",
                            cancel: "No, Cancel"
                        },
                        dangerMode: true,
                    }).then(function (willDelete) {
                        if (willDelete) {
                            doDeleteFaq();
                        }
                    });
                } else if (typeof Swal !== 'undefined' && typeof Swal.fire === 'function') {
                    Swal.fire({
                        title: 'Delete Question?',
                        text: 'Are you sure you want to delete this FAQ?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            doDeleteFaq();
                        }
                    });
                } else {
                    if (confirm("Are you sure you want to delete this FAQ?")) {
                        doDeleteFaq();
                    }
                }
            });
        });
    </script>
@endsection
