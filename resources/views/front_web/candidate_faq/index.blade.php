@extends($faqLayout ?? 'front_web.layouts.app')

@php
    $categorizedFaqs = collect($faqCategories ?? [])->filter(fn ($category) => $category->faqs->count() > 0);
    $uncategorizedFaqs = collect($faqLists ?? [])->filter(fn ($faq) => empty($faq->faq_category_id));
    $firstFaqCategory = $categorizedFaqs->first();
    $defaultFaqAnchor = $firstFaqCategory ? $firstFaqCategory->slug : ($uncategorizedFaqs->count() > 0 ? 'other-questions' : 'candidate-faq-top');
@endphp

@section('title')
    {{ $faqPageTitle ?? __('messages.faq.candidate_faq') }}
@endsection

@section('page_css')
    <style>
        .candidate-faq-page {
            background: #f7f8fb;
            padding: 50px 0;
            width: 100%;
        }

        .candidate-faq-page--dashboard {
            border-radius: 8px;
            margin-bottom: 28px;
            padding: 32px 0 40px;
        }

        .candidate-faq-page .faq-sec-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .candidate-faq-page--dashboard .faq-sec-header {
            margin-bottom: 24px;
        }

        .candidate-faq-page .faq-sec-header h1 {
            color: #1f2937;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .candidate-faq-page .faq-search {
            margin: 0 auto;
            max-width: 640px;
        }

        .candidate-faq-page .faq-search .input-group {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 10px 28px rgba(31, 41, 55, 0.07);
            padding: 5px;
        }

        .candidate-faq-page .faq-search .form-control {
            border: 0;
            box-shadow: none;
            color: #374151;
            font-size: 15px;
            min-height: 44px;
            padding: 10px 16px;
        }

        .candidate-faq-page .faq-search .btn {
            align-items: center;
            background: #d8205f;
            border: 0;
            border-radius: 6px;
            color: #ffffff;
            display: inline-flex;
            font-weight: 600;
            gap: 8px;
            padding: 0 20px;
        }

        .candidate-faq-page .faq-category-row {
            margin-bottom: 14px;
            row-gap: 16px;
        }

        .candidate-faq-page .faq-box {
            background: #ffffff;
            border: 1px solid #edf0f4;
            border-radius: 8px;
            box-shadow: 0 10px 26px rgba(31, 41, 55, 0.06);
            height: 100%;
            margin-bottom: 0;
            text-align: center;
            transition: 0.2s ease;
        }

        .candidate-faq-page .faq-box:hover {
            border-color: #d8205f;
            box-shadow: 0 14px 32px rgba(216, 32, 95, 0.12);
            transform: translateY(-3px);
        }

        .candidate-faq-page .faq-box a {
            color: #1f2937;
            display: block;
            height: 100%;
            min-height: 132px;
            padding: 22px 14px 18px;
            text-decoration: none;
        }

        .candidate-faq-page .faq-icon {
            align-items: center;
            background: #fce8f0;
            border-radius: 50%;
            color: #d8205f;
            display: flex;
            font-size: 24px;
            height: 58px;
            justify-content: center;
            margin: 0 auto 12px;
            width: 58px;
        }

        .candidate-faq-page .faq-box h3 {
            color: #253044;
            font-size: 16px;
            font-weight: 700;
            line-height: 1.35;
            margin: 0;
        }

        .candidate-faq-page .j-faq-box {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 10px 26px rgba(31, 41, 55, 0.06);
            margin-top: 18px;
            padding: 24px;
        }

        .candidate-faq-page--dashboard .j-faq-box {
            margin-left: auto;
            margin-right: auto;
            max-width: 760px;
        }

        .candidate-faq-page .j-faq-box h3 {
            color: #1f2937;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .candidate-faq-page .faq-card {
            background: transparent;
            border: 0;
            margin-bottom: 10px;
        }

        .candidate-faq-page .accordion-title {
            align-items: center;
            background: #f7f8fb;
            border: 0;
            border-radius: 8px;
            color: #253044;
            display: flex;
            font-size: 15px;
            font-weight: 600;
            gap: 14px;
            line-height: 1.5;
            padding: 14px 16px;
            text-align: left;
            text-decoration: none;
            white-space: normal;
            width: 100%;
        }

        .candidate-faq-page .accordion-title i {
            align-items: center;
            background: #ffffff;
            border-radius: 50%;
            color: #d8205f;
            display: inline-flex;
            flex: 0 0 30px;
            height: 30px;
            justify-content: center;
            width: 30px;
        }

        .candidate-faq-page .accordion-title:not(.collapsed) {
            background: #d8205f;
            color: #ffffff;
        }

        .candidate-faq-page .accordion-title:not(.collapsed) i {
            color: #d8205f;
        }

        .candidate-faq-page .accordion-body {
            color: #5d6678;
            font-size: 14px;
            line-height: 1.7;
            padding: 16px 16px 6px 58px;
        }

        .candidate-faq-page .faq-empty {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 10px 26px rgba(31, 41, 55, 0.06);
            color: #5d6678;
            padding: 35px;
            text-align: center;
        }

        @media (max-width: 575px) {
            .candidate-faq-page {
                padding: 34px 0 52px;
            }

            .candidate-faq-page .faq-sec-header h1 {
                font-size: 26px;
            }

            .candidate-faq-page .faq-search .btn {
                padding: 0 12px;
            }

            .candidate-faq-page .faq-category-row {
                row-gap: 12px;
            }

            .candidate-faq-page .faq-box a {
                min-height: 118px;
                padding: 18px 10px 16px;
            }

            .candidate-faq-page .faq-icon {
                height: 52px;
                margin-bottom: 10px;
                width: 52px;
            }

            .candidate-faq-page .j-faq-box {
                padding: 18px 14px;
            }

            .candidate-faq-page .accordion-body {
                padding-left: 18px;
            }
        }
    </style>
@endsection

@section('content')
    <section class="candidate-faq-page {{ !empty($isDashboardFaq) ? 'candidate-faq-page--dashboard' : '' }}" id="candidate-faq-top">
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "FAQPage",
                "name": @json($faqPageTitle ?? __('messages.faq.candidate_faq')),
                "publisher": {
                    "@type": "Organization",
                    "name": @json(getAppName())
                }
            }
        </script>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="faq-sec-header">
                        <h1>{{ $faqPageTitle ?? __('messages.faq.faq') }}</h1>
                        <div class="faq-search">
                            <div class="input-group">
                                <input type="text" id="candidateFaqSearch" class="form-control" placeholder="{{ __('messages.faq.type_question_here') }}">
                                <button type="button" class="btn" id="candidateFaqSearchBtn">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    {{ __('messages.common.search') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row faq-category-row">
                @foreach ($categorizedFaqs as $category)
                    <div class="col-md-3 col-6">
                        <div class="faq-box">
                            <a href="#{{ $category->faqs->count() > 0 ? $category->slug : $defaultFaqAnchor }}">
                                <div class="faq-icon">
                                    <i class="{{ $category->icon ?: 'fa-solid fa-circle-question fa-fw' }}"></i>
                                </div>
                                <h3>{{ $category->name }}</h3>
                            </a>
                        </div>
                    </div>
                @endforeach
                @if ($uncategorizedFaqs->count() > 0)
                    <div class="col-md-3 col-6">
                        <div class="faq-box">
                            <a href="#other-questions">
                                <div class="faq-icon">
                                    <i class="fa-solid fa-circle-question fa-fw"></i>
                                </div>
                                <h3>{{ __('messages.faq.other_questions') }}</h3>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-lg-12 col-md-12">
                    @if ($categorizedFaqs->count() > 0 || $uncategorizedFaqs->count() > 0)
                        @foreach ($categorizedFaqs as $category)
                            @if ($category->faqs->count() > 0)
                                <div class="j-faq-box faq-section" id="{{ $category->slug }}">
                                    <h3>{{ $category->name }}</h3>
                                    <div class="py-3 faq-accordion accordion" id="{{ $category->slug }}-accordion">
                                        @foreach ($category->faqs as $faqList)
                                            @php
                                                $collapseId = $category->slug.'-collapse-'.$faqList->id;
                                                $headingId = $category->slug.'-heading-'.$faqList->id;
                                            @endphp
                                            <div class="faq-card faq-search-item" data-question="{{ strtolower(strip_tags($faqList->title)) }}" data-answer="{{ strtolower(strip_tags($faqList->description)) }}">
                                                <div class="faq-card-header p-0 border-0" id="{{ $headingId }}">
                                                    <button class="btn btn-link accordion-title collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#{{ $collapseId }}"
                                                        aria-expanded="false"
                                                        aria-controls="{{ $collapseId }}">
                                                        <i class="fas fa-plus"></i>
                                                        {{ html_entity_decode($faqList->title) }}
                                                    </button>
                                                </div>
                                                <div id="{{ $collapseId }}" class="collapse"
                                                    aria-labelledby="{{ $headingId }}"
                                                    data-bs-parent="#{{ $category->slug }}-accordion">
                                                    <div class="card-body accordion-body">
                                                        <div class="faq-body-cont">
                                                            {!! nl2br($faqList->description) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        @if ($uncategorizedFaqs->count() > 0)
                            <div class="j-faq-box faq-section" id="other-questions">
                                <h3>{{ __('messages.faq.other_questions') }}</h3>
                                <div class="py-3 faq-accordion accordion" id="other-questions-accordion">
                                    @foreach ($uncategorizedFaqs as $faqList)
                                        @php
                                            $collapseId = 'other-questions-collapse-'.$faqList->id;
                                            $headingId = 'other-questions-heading-'.$faqList->id;
                                        @endphp
                                        <div class="faq-card faq-search-item" data-question="{{ strtolower(strip_tags($faqList->title)) }}" data-answer="{{ strtolower(strip_tags($faqList->description)) }}">
                                            <div class="faq-card-header p-0 border-0" id="{{ $headingId }}">
                                                <button class="btn btn-link accordion-title collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#{{ $collapseId }}"
                                                    aria-expanded="false"
                                                    aria-controls="{{ $collapseId }}">
                                                    <i class="fas fa-plus"></i>
                                                    {{ html_entity_decode($faqList->title) }}
                                                </button>
                                            </div>
                                            <div id="{{ $collapseId }}" class="collapse"
                                                aria-labelledby="{{ $headingId }}"
                                                data-bs-parent="#other-questions-accordion">
                                                <div class="card-body accordion-body">
                                                    <div class="faq-body-cont">
                                                        {!! nl2br($faqList->description) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="faq-empty">
                            <h5 class="mb-0">{{ __('web.about_us_menu.faq_not_available') }}.</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('candidateFaqSearch');
            const searchButton = document.getElementById('candidateFaqSearchBtn');
            const faqItems = document.querySelectorAll('.faq-search-item');
            const faqSections = document.querySelectorAll('.faq-section');

            function filterFaqs() {
                const query = searchInput.value.trim().toLowerCase();

                faqItems.forEach(function (item) {
                    const haystack = (item.dataset.question || '') + ' ' + (item.dataset.answer || '');
                    item.classList.toggle('d-none', query && !haystack.includes(query));
                });

                faqSections.forEach(function (section) {
                    const visibleItems = section.querySelectorAll('.faq-search-item:not(.d-none)');
                    section.classList.toggle('d-none', visibleItems.length === 0 && query.length > 0);
                });
            }

            searchButton.addEventListener('click', filterFaqs);
            searchInput.addEventListener('keyup', function (event) {
                filterFaqs();

                if (event.key === 'Enter') {
                    const firstVisibleSection = document.querySelector('.faq-section:not(.d-none)');
                    if (firstVisibleSection) {
                        firstVisibleSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });
    </script>
@endsection
