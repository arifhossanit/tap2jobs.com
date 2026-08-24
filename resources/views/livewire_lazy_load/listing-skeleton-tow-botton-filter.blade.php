@php($isDarkSkeleton = auth()->check() && getLoggedInUser()->theme_mode)
<div class="listing-skeleton listing-skeleton-modern {{ $isDarkSkeleton ? 'admin-dark-listing-skeleton' : 'bg-white border-light' }} p-4 rounded-3 shadow-sm border">
    @include('livewire_lazy_load.partials.listing-skeleton-style')

    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mb-4 gap-3">
        <div class="shimmer-element" style="width: 240px; height: 38px;"></div>
        <div class="d-flex gap-2 align-items-center">
            <div class="shimmer-element" style="width: 46px; height: 38px;"></div>
            <div class="shimmer-element" style="width: 100px; height: 38px;"></div>
            <div class="shimmer-element" style="width: 90px; height: 38px;"></div>
        </div>
    </div>

    @include('livewire_lazy_load.partials.listing-skeleton-table', ['isDarkSkeleton' => $isDarkSkeleton])
</div>
