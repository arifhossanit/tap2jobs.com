@php($isDarkSkeleton = auth()->check() && getLoggedInUser()->theme_mode)
<div class="listing-skeleton listing-skeleton-modern {{ $isDarkSkeleton ? 'admin-dark-listing-skeleton' : 'bg-white border-light' }} p-4 rounded-3 shadow-sm border">
    @include('livewire_lazy_load.partials.listing-skeleton-style')

    <div class="d-flex justify-content-between mb-4">
        <div class="shimmer-element" style="width: 240px; height: 38px;"></div>
    </div>

    @include('livewire_lazy_load.partials.listing-skeleton-table', ['isDarkSkeleton' => $isDarkSkeleton])
</div>
