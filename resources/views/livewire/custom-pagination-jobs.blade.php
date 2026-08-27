@if ($paginator->hasPages())
    <nav class="find-jobs-pagination-nav" aria-label="Pagination">
        <ul class="pagination find-jobs-pagination" role="navigation">
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                @if ($paginator->onFirstPage())
                    <span class="page-link" aria-disabled="true" aria-label="@lang('pagination.previous')">
                        <i class="fa-solid fa-angle-left" aria-hidden="true"></i>
                    </span>
                @else
                    <button type="button" class="page-link" wire:click="previousPage" rel="prev"
                            aria-label="@lang('pagination.previous')">
                        <i class="fa-solid fa-angle-left" aria-hidden="true"></i>
                    </button>
                @endif
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled find-jobs-pagination__ellipsis d-none d-sm-block" aria-disabled="true">
                        <span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @php
                            $showOnMobile = $page === 1
                                || $page === $paginator->lastPage()
                                || abs($page - $paginator->currentPage()) <= 1;
                        @endphp
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item {{ $showOnMobile ? '' : 'd-none d-sm-block' }}">
                                <button type="button" class="page-link" wire:click="gotoPage({{ $page }})"
                                        aria-label="{{ $page }}">
                                    {{ $page }}
                                </button>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                @if ($paginator->hasMorePages())
                    <button type="button" class="page-link" wire:click="nextPage" rel="next"
                            aria-label="@lang('pagination.next')">
                        <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
                    </button>
                @else
                    <span class="page-link" aria-disabled="true" aria-label="@lang('pagination.next')">
                        <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
                    </span>
                @endif
            </li>
        </ul>
    </nav>
@endif
