@extends('front_web.layouts.app')
@section('title')
    {{ __('web.job_seekers') }}
@endsection
@section('page_css')
    <style>
        .popular-job-categories-section {
            background: #fff;
        }

        .category-search-wrap {
            max-width: 720px;
            margin: 0 auto 48px;
            position: relative;
        }

        .category-search-wrap .form-control {
            min-height: 56px;
            border-radius: 8px;
            padding: 15px 52px;
            border-color: #d8dee8;
            box-shadow: none;
        }

        .category-search-wrap .form-control:focus {
            border-color: #209776;
            box-shadow: 0 0 0 4px rgba(32, 151, 118, .12);
        }

        .category-search-icon,
        .category-search-clear {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 4;
        }

        .category-search-icon {
            left: 20px;
            color: #7d8795;
        }

        .category-search-clear {
            right: 14px;
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 50%;
            background: #f2f5f9;
            color: #6f7785;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .category-search-clear.is-visible {
            display: inline-flex;
        }

        .categories-results {
            position: relative;
            transition: opacity .2s ease;
        }

        .categories-results.is-loading {
            opacity: .55;
            pointer-events: none;
        }

        .categories-grid {
            row-gap: 32px;
        }

        .category-card {
            height: 100%;
            padding: 20px;
            border: 0;
            border-radius: 8px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, .10);
        }

        .category-card__header {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            min-width: 0;
        }

        .category-card__image-wrap {
            width: 60px;
            height: 60px;
            flex: 0 0 60px;
        }

        .category-card__image {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .category-card__content {
            min-width: 0;
            flex: 1;
        }

        .category-card__title {
            font-size: 18px;
            line-height: 1.3;
            margin-bottom: 8px;
            overflow-wrap: anywhere;
        }

        .category-card__count {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #6f7785;
            margin-bottom: 0;
        }

        .category-card__count i {
            color: #209776;
            font-size: 13px;
        }

        .category-card__featured {
            flex: 0 0 auto;
            margin-left: auto;
        }

        .category-card__footer {
            margin-top: 28px;
        }

        .category-card__action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            max-width: 100%;
            padding: 9px 16px;
            border-radius: 6px;
            background: #f2f5f9;
            color: #209776;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.2;
            white-space: normal;
        }

        .category-card__action:hover {
            color: #15765c;
        }

        .category-card__action.is-disabled {
            color: #209776;
        }

        .categories-pagination-nav {
            margin-top: 56px;
        }

        .categories-pagination {
            gap: 10px;
        }

        .categories-pagination .page-link {
            min-width: 40px;
            height: 40px;
            border-radius: 50% !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #209776;
            border-color: #d8dee8;
            box-shadow: none;
        }

        .categories-pagination .page-item.active .page-link {
            background: #209776;
            border-color: #209776;
            color: #fff !important;
        }

        .categories-pagination .page-item.disabled .page-link {
            color: #a8b0bd;
            background: #fff;
        }

        @media (max-width: 991.98px) {
            .popular-job-categories-section {
                padding-top: 70px !important;
                padding-bottom: 70px !important;
            }

            .category-search-wrap {
                margin-bottom: 36px;
            }

            .category-card {
                padding: 26px;
            }
        }

        @media (max-width: 575.98px) {
            .popular-job-categories-section {
                padding-top: 50px !important;
                padding-bottom: 50px !important;
            }

            .category-search-wrap .form-control {
                min-height: 52px;
                padding: 13px 48px;
            }

            .category-card {
                min-height: auto;
                padding: 22px;
            }

            .category-card__header {
                gap: 14px;
            }

            .category-card__image-wrap {
                width: 52px;
                height: 52px;
                flex-basis: 52px;
            }

            .category-card__title {
                font-size: 16px;
            }

            .category-card__footer {
                margin-top: 22px;
            }

            .categories-pagination {
                gap: 8px;
            }

            .categories-pagination .page-link {
                min-width: 36px;
                height: 36px;
            }
        }

        @if (\Illuminate\Support\Facades\App::getLocale() == 'ar')
            .popular-job-categories-section ul.pagination {
                direction: rtl;
            }

            .category-search-icon {
                left: auto;
                right: 20px;
            }

            .category-search-clear {
                right: auto;
                left: 14px;
            }
        @endif
    </style>
@endsection
@section('content')
    <div class="job-seekers-page">
        <section class="hero-section position-relative bg-gradient pt-15 pb-40">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6 text-center mb-lg-0 mb-md-5 mb-sm-4">
                        <div class="hero-content">
                            <h1 class="text-secondary mb-2">@lang('web.post_menu.categories')</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('front.home') }}" class="fs-18 text-gray">{{ __('web.home') }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item text-primary fs-18" aria-current="page">
                                        @lang('web.post_menu.categories')
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="popular-job-categories-section py-100">
            <div class="container">
                <div class="job-card">
                    <div class="category-search-wrap">
                        <i class="fa-solid fa-magnifying-glass category-search-icon"></i>
                        <input type="search" id="categorySearchInput" class="form-control"
                            value="{{ $search }}" autocomplete="off"
                            placeholder="{{ __('messages.common.search') }} {{ __('web.post_menu.categories') }}"
                            data-url="{{ route('front.categories') }}">
                        <button type="button" id="categorySearchClear"
                            class="category-search-clear {{ $search !== '' ? 'is-visible' : '' }}"
                            aria-label="{{ __('messages.common.reset') }}">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div id="categoryResults" class="categories-results">
                        @include('front_web.categories.partials.category_list', ['jobCategories' => $jobCategories, 'search' => $search])
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('categorySearchInput');
            const clearButton = document.getElementById('categorySearchClear');
            const results = document.getElementById('categoryResults');

            if (!searchInput || !clearButton || !results) {
                return;
            }

            const baseUrl = searchInput.dataset.url;
            let searchTimer = null;
            let activeController = null;

            const setClearVisibility = function () {
                clearButton.classList.toggle('is-visible', searchInput.value.trim() !== '');
            };

            const buildUrl = function (pageUrl = null) {
                const url = new URL(pageUrl || baseUrl, window.location.origin);
                const search = searchInput.value.trim();

                if (search) {
                    url.searchParams.set('search', search);
                } else {
                    url.searchParams.delete('search');
                }

                if (!pageUrl) {
                    url.searchParams.delete('page');
                }

                return url;
            };

            const loadCategories = function (pageUrl = null, pushState = true) {
                const url = buildUrl(pageUrl);

                if (activeController) {
                    activeController.abort();
                }

                activeController = new AbortController();
                results.classList.add('is-loading');

                fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    signal: activeController.signal
                })
                    .then(response => response.text())
                    .then(html => {
                        results.innerHTML = html;
                        results.classList.remove('is-loading');
                        setClearVisibility();

                        if (pushState) {
                            history.pushState({}, '', url.toString());
                        }
                    })
                    .catch(error => {
                        if (error.name !== 'AbortError') {
                            results.classList.remove('is-loading');
                        }
                    });
            };

            searchInput.addEventListener('input', function () {
                window.clearTimeout(searchTimer);
                setClearVisibility();
                searchTimer = window.setTimeout(function () {
                    loadCategories();
                }, 300);
            });

            clearButton.addEventListener('click', function () {
                searchInput.value = '';
                searchInput.focus();
                setClearVisibility();
                loadCategories();
            });

            results.addEventListener('click', function (event) {
                const paginationLink = event.target.closest('.categories-pagination a.page-link');
                if (!paginationLink) {
                    return;
                }

                event.preventDefault();
                loadCategories(paginationLink.href);
                document.querySelector('.popular-job-categories-section').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });

            window.addEventListener('popstate', function () {
                const params = new URLSearchParams(window.location.search);
                searchInput.value = params.get('search') || '';
                setClearVisibility();
                loadCategories(window.location.href, false);
            });
        });
    </script>
@endsection
