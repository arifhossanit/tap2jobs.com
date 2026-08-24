@php($isDarkSkeleton = auth()->check() && getLoggedInUser()->theme_mode)
<div class="listing-skeleton listing-skeleton-modern {{ $isDarkSkeleton ? 'admin-dark-listing-skeleton' : 'bg-white border-light' }} p-4 rounded-3 shadow-sm border">
    <style>
        @keyframes skeleton-shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .listing-skeleton-modern .shimmer-element {
            background: linear-gradient(90deg, #f0f2f5 25%, #e4e7ed 37%, #f0f2f5 63%);
            background-size: 200% 100%;
            animation: skeleton-shimmer 1.5s ease-in-out infinite;
            border-radius: 6px;
        }
        .admin-dark-listing-skeleton {
            background-color: #10131d !important;
            border-color: #242936 !important;
        }
        .admin-dark-listing-skeleton .shimmer-element {
            background: linear-gradient(90deg, #1a1f2b 25%, #252b38 37%, #1a1f2b 63%);
            background-size: 200% 100%;
        }
        .admin-dark-listing-skeleton .skeleton-table-header {
            background-color: #151a25 !important;
            border-color: #242936 !important;
        }
        .admin-dark-listing-skeleton .skeleton-table-row {
            border-color: #242936 !important;
        }
    </style>

    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mb-4 gap-3">
        <div class="shimmer-element" style="width: 240px; height: 38px;"></div>
        <div class="d-flex gap-2 align-items-center">
            <div class="shimmer-element" style="width: 100px; height: 38px;"></div>
            <div class="shimmer-element" style="width: 90px; height: 38px;"></div>
        </div>
    </div>

    <div class="table-responsive">
        <div class="skeleton-table-header d-flex align-items-center p-3 mb-2 rounded {{ $isDarkSkeleton ? '' : 'bg-light' }} border">
            <div class="shimmer-element me-auto" style="width: 30%; height: 20px;"></div>
            <div class="shimmer-element me-auto" style="width: 40%; height: 20px;"></div>
            <div class="shimmer-element" style="width: 15%; height: 20px;"></div>
        </div>

        @for ($i = 0; $i < 5; $i++)
            <div class="skeleton-table-row d-flex align-items-center p-3 border-bottom">
                <div class="shimmer-element me-auto" style="width: {{ [28, 32, 25, 30, 35][$i] }}%; height: 16px;"></div>
                <div class="shimmer-element me-auto" style="width: {{ [45, 38, 42, 48, 40][$i] }}%; height: 16px;"></div>
                <div class="shimmer-element" style="width: 60px; height: 28px; border-radius: 4px;"></div>
            </div>
        @endfor
    </div>
</div>
