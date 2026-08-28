@extends('candidate.layouts.app')
@section('title')
    {{ __('messages.favourite_companies') }}
@endsection
@section('css')
    <style>
        /* Favourite Companies - mobile/tablet horizontal scroll only.
           Desktop (>= lg, 992px) keeps the original table design exactly. */
        @media (max-width: 991.98px) {
            .candidate-favourite-companies-page,
            .candidate-favourite-companies-page > div,
            .candidate-favourite-companies-page [wire\:id] {
                min-width: 0;
                max-width: 100%;
            }

            .candidate-favourite-companies-page .candidate-favourite-companies-table-wrap {
                display: block;
                width: 100%;
                max-width: 100%;
                overflow-x: auto;
                overflow-y: visible;
                padding-bottom: 2px;
                scrollbar-width: none;
                -webkit-overflow-scrolling: touch;
            }

            .candidate-favourite-companies-page .candidate-favourite-companies-table-wrap::-webkit-scrollbar {
                display: none;
            }

            .candidate-favourite-companies-page .candidate-favourite-companies-table {
                width: 760px !important;
                min-width: 760px !important;
                max-width: none !important;
                margin-bottom: 0;
            }

            .candidate-favourite-companies-page .mobile-table-scrollbar {
                width: 100%;
                height: 18px;
                padding: 5px 0;
                cursor: pointer;
                touch-action: none;
            }

            .candidate-favourite-companies-page .mobile-table-scrollbar-track {
                position: relative;
                width: 100%;
                height: 8px;
                overflow: hidden;
                border-radius: 4px;
                background: #dfe5ec;
            }

            .candidate-favourite-companies-page .mobile-table-scrollbar-thumb {
                position: absolute;
                top: 0;
                left: 0;
                height: 8px;
                min-width: 44px;
                border-radius: 4px;
                background: #8b95a5;
                transform: translateX(0);
            }

            .candidate-favourite-companies-page .d-flex.align-items-center.flex-xxl-row.flex-column.mb-5.mt-3 {
                flex-direction: row !important;
                flex-wrap: wrap;
                justify-content: center;
                gap: 12px;
            }

            .candidate-favourite-companies-page .d-flex.align-items-center.flex-xxl-row.flex-column.mb-5.mt-3 > div {
                margin-bottom: 0 !important;
            }

            .candidate-favourite-companies-page .d-flex.align-items-center.flex-xxl-row.flex-column.mb-5.mt-3 > .row {
                width: auto;
                margin: 0;
            }

            .candidate-favourite-companies-page .d-flex.align-items-center.flex-xxl-row.flex-column.mb-5.mt-3 > .row > .col-12 {
                padding: 0;
            }

            .candidate-favourite-companies-page .d-flex.align-items-center.flex-xxl-row.flex-column.mb-5.mt-3 > .row .fs-4 {
                margin: 0 !important;
                white-space: nowrap;
            }
        }

        @media (min-width: 992px) {
            .candidate-favourite-companies-page .mobile-table-scrollbar {
                display: none !important;
            }
        }
    </style>
@endsection
@section('content')
    <div class="d-flex flex-column candidate-favourite-companies-page">
        <livewire:favourite-company-table lazy/>
    </div>
@endsection

@section('scripts')
    <script>
        (() => {
            const pageSelector = '.candidate-favourite-companies-page';
            const tableSelector = '.candidate-favourite-companies-table-wrap';

            function setupMobileTableScrollbar() {
                const page = document.querySelector(pageSelector);
                const tableWrap = page?.querySelector(tableSelector);

                if (!tableWrap || tableWrap.dataset.mobileScrollbarReady === 'true') {
                    return;
                }

                tableWrap.dataset.mobileScrollbarReady = 'true';

                const scrollbar = document.createElement('div');
                scrollbar.className = 'mobile-table-scrollbar';
                scrollbar.setAttribute('aria-label', 'Scroll table horizontally');
                scrollbar.innerHTML = '<div class="mobile-table-scrollbar-track"><div class="mobile-table-scrollbar-thumb"></div></div>';
                tableWrap.insertAdjacentElement('afterend', scrollbar);

                const track = scrollbar.firstElementChild;
                const thumb = track.firstElementChild;
                let dragStartX = 0;
                let dragStartScrollLeft = 0;

                const updateThumb = () => {
                    const maxScroll = tableWrap.scrollWidth - tableWrap.clientWidth;
                    const trackWidth = track.clientWidth;
                    const thumbWidth = maxScroll > 0
                        ? Math.max(44, trackWidth * (tableWrap.clientWidth / tableWrap.scrollWidth))
                        : trackWidth;
                    const maxThumbMove = Math.max(0, trackWidth - thumbWidth);
                    const thumbLeft = maxScroll > 0
                        ? (tableWrap.scrollLeft / maxScroll) * maxThumbMove
                        : 0;

                    thumb.style.width = `${thumbWidth}px`;
                    thumb.style.transform = `translateX(${thumbLeft}px)`;
                    scrollbar.style.display = maxScroll > 0 ? '' : 'none';
                };

                const scrollFromPointer = (pointerX) => {
                    const maxScroll = tableWrap.scrollWidth - tableWrap.clientWidth;
                    const maxThumbMove = track.clientWidth - thumb.offsetWidth;

                    if (maxScroll <= 0 || maxThumbMove <= 0) {
                        return;
                    }

                    const pointerDelta = pointerX - dragStartX;
                    tableWrap.scrollLeft = dragStartScrollLeft + (pointerDelta / maxThumbMove) * maxScroll;
                };

                thumb.addEventListener('pointerdown', (event) => {
                    event.preventDefault();
                    dragStartX = event.clientX;
                    dragStartScrollLeft = tableWrap.scrollLeft;
                    thumb.setPointerCapture(event.pointerId);
                });

                thumb.addEventListener('pointermove', (event) => {
                    if (thumb.hasPointerCapture(event.pointerId)) {
                        scrollFromPointer(event.clientX);
                    }
                });

                track.addEventListener('pointerdown', (event) => {
                    if (event.target === thumb) {
                        return;
                    }

                    const trackRect = track.getBoundingClientRect();
                    const maxScroll = tableWrap.scrollWidth - tableWrap.clientWidth;
                    const clickRatio = (event.clientX - trackRect.left) / trackRect.width;
                    tableWrap.scrollLeft = Math.max(0, Math.min(maxScroll, clickRatio * maxScroll));
                });

                tableWrap.addEventListener('scroll', updateThumb, { passive: true });
                window.addEventListener('resize', updateThumb, { passive: true });

                if ('ResizeObserver' in window) {
                    new ResizeObserver(updateThumb).observe(tableWrap);
                }

                requestAnimationFrame(updateThumb);
            }

            const scheduleSetup = () => requestAnimationFrame(setupMobileTableScrollbar);

            document.addEventListener('DOMContentLoaded', scheduleSetup);
            document.addEventListener('livewire:init', scheduleSetup);
            document.addEventListener('livewire:navigated', scheduleSetup);
            document.addEventListener('turbo:load', scheduleSetup);

            new MutationObserver(scheduleSetup).observe(document.documentElement, {
                childList: true,
                subtree: true,
            });

            scheduleSetup();
        })();
    </script>
@endsection
