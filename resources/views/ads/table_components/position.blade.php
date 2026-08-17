<div>
    <span class="badge bg-light-primary text-primary mb-1 me-1">{{ __('messages.ad.positions.' . $row->position) }}</span>
    @php
        $pages = $row->page_array;
        $allPages = array_values(array_diff(array_keys(\App\Models\Ad::PAGES), ['all']));
        $hasAll = in_array(\App\Models\Ad::PAGE_ALL, $pages, true) || count(array_intersect($pages, $allPages)) === count($allPages);
    @endphp
    @if ($hasAll)
        <span class="badge bg-light-info text-info mb-1 me-1">{{ __('messages.ad.pages.all') }}</span>
    @else
        @foreach ($pages as $p)
            <span class="badge bg-light-info text-info mb-1 me-1">{{ __('messages.ad.pages.' . $p) }}</span>
        @endforeach
    @endif
    <span class="text-muted small d-block">{{ __('messages.ad.sort_order') }}: {{ $row->sort_order }}</span>
</div>
