@if ($paginator->hasPages())
    @php
        $elements = \Illuminate\Pagination\UrlWindow::make($paginator);
    @endphp

    <nav class="categories-pagination-nav" aria-label="Pagination">
        <ul class="pagination categories-pagination mb-0 justify-content-center flex-wrap">
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                @if ($paginator->onFirstPage())
                    <span class="page-link" aria-disabled="true" aria-label="@lang('pagination.previous')">
                        <i class="fa-solid fa-angle-left" aria-hidden="true"></i>
                    </span>
                @else
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        aria-label="@lang('pagination.previous')">
                        <i class="fa-solid fa-angle-left" aria-hidden="true"></i>
                    </a>
                @endif
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled d-none d-sm-block" aria-disabled="true">
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
                                <a class="page-link" href="{{ $url }}" aria-label="{{ $page }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                @if ($paginator->hasMorePages())
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"
                        aria-label="@lang('pagination.next')">
                        <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
                    </a>
                @else
                    <span class="page-link" aria-disabled="true" aria-label="@lang('pagination.next')">
                        <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
                    </span>
                @endif
            </li>
        </ul>
    </nav>
@endif
