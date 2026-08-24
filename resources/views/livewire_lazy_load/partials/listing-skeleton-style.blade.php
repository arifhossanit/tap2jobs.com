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
