@php($isDarkSkeleton = auth()->check() && getLoggedInUser()->theme_mode)
<div class="listing-skeleton listing-skeleton-modern {{ $isDarkSkeleton ? 'admin-dark-listing-skeleton' : 'bg-white border-light' }} p-4 rounded-3 shadow-sm border">
    @include('livewire_lazy_load.partials.listing-skeleton-style')
    @include('livewire_lazy_load.partials.listing-skeleton-table', ['isDarkSkeleton' => $isDarkSkeleton])
</div>
