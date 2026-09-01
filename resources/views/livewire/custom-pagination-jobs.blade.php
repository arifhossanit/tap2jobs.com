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

            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                
                // Custom pagination logic to avoid multiple ellipses
                $customElements = [];
                
                if ($lastPage <= 7) {
                    // Show all pages if 7 or fewer
                    $customElements[] = range(1, $lastPage);
                } else {
                    if ($currentPage <= 4) {
                        // Near the beginning: 1 2 3 4 5 ... 49 50
                        $customElements[] = range(1, max(5, $currentPage + 1));
                        $customElements[] = '...';
                        $customElements[] = [$lastPage - 1, $lastPage];
                    } elseif ($currentPage >= $lastPage - 3) {
                        // Near the end: 1 2 ... 46 47 48 49 50
                        $customElements[] = [1, 2];
                        $customElements[] = '...';
                        $customElements[] = range(min($lastPage - 4, $currentPage - 1), $lastPage);
                    } else {
                        // Middle: User requested NO multiple ellipses and NO "1, 2" if above page 5.
                        // Example: 7 8 9 10 11 ... 49 50
                        $customElements[] = [$currentPage - 2, $currentPage - 1, $currentPage, $currentPage + 1, $currentPage + 2];
                        $customElements[] = '...';
                        $customElements[] = [$lastPage - 1, $lastPage];
                    }
                }
            @endphp

            @foreach ($customElements as $index => $element)
                @if (is_string($element))
                    <li class="page-item disabled find-jobs-pagination__ellipsis d-none d-sm-block" aria-disabled="true" wire:key="ellipsis-{{ $index }}">
                        <span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page)
                        @php
                            $showOnMobile = $page === 1
                                || $page === $lastPage
                                || abs($page - $currentPage) <= 1;
                        @endphp
                        @if ($page == $currentPage)
                            <li class="page-item active" aria-current="page" wire:key="page-{{ $page }}">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item {{ $showOnMobile ? '' : 'd-none d-sm-block' }}" wire:key="page-{{ $page }}">
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
