@if ($paginator->hasPages())
    @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $pages = [];
        $showEllipsis = false;

        if ($lastPage <= 10) {
            $pages = range(1, $lastPage);
        } elseif ($currentPage < ($lastPage / 2)) {
            $pages = array_merge(range(1, min(max(8, $currentPage + 3), $lastPage - 1)), [$lastPage]);
            $showEllipsis = true;
        } else {
            $pages = array_merge([1], range(max(2, min($currentPage - 3, $lastPage - 7)), $lastPage));
            $showEllipsis = true;
        }

        $pages = collect($pages)
            ->filter(fn ($page) => $page >= 1 && $page <= $lastPage)
            ->unique()
            ->values()
            ->all();
    @endphp

    <nav class="front-pagination-nav w-100" aria-label="Pagination">
        <ul role="navigation" class="pagination front-pagination mb-0 justify-content-center flex-nowrap">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link previous" aria-hidden="true">
                        <i class="fa-solid fa-angle-left text-gray"></i>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <button type="button" wire:click="gotoPage({{ $currentPage - 1 }})" rel="prev" class="page-link previous"
                            aria-label="@lang('pagination.previous')">
                        <i class="fa-solid fa-angle-left text-gray"></i>
                    </button>
                </li>
            @endif

            @foreach ($pages as $index => $page)
                @if ($showEllipsis && $index > 0 && $page > $pages[$index - 1] + 1)
                    <li class="page-item disabled front-pagination__ellipsis" aria-disabled="true">
                        <span class="page-link">...</span>
                    </li>
                @endif

                @if ($page == $currentPage)
                    <li class="page-item active" aria-current="page">
                        <span class="page-link active">{{ $page }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <button type="button" wire:click="gotoPage({{ $page }})" class="page-link text-gray">
                            {{ $page }}
                        </button>
                    </li>
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <button type="button" wire:click="gotoPage({{ $currentPage + 1 }})" rel="next"
                            aria-label="@lang('pagination.next')" class="page-link next">
                        <i class="fa-solid fa-angle-right text-gray"></i>
                    </button>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link next" aria-hidden="true">
                        <i class="fa-solid fa-angle-right text-gray"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
