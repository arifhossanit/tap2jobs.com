<div class="listing-skeleton p-4 bg-white rounded-3 shadow-sm border border-light">
    <style>
        @keyframes skeleton-shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .shimmer-element {
            background: linear-gradient(90deg, #f0f2f5 25%, #e4e7ed 37%, #f0f2f5 63%);
            background-size: 200% 100%;
            animation: skeleton-shimmer 1.5s ease-in-out infinite;
            border-radius: 6px;
        }
        [data-bs-theme="dark"] .shimmer-element,
        body.dark-mode .shimmer-element,
        .dark-mode .shimmer-element {
            background: linear-gradient(90deg, #2b303b 25%, #363c4a 37%, #2b303b 63%);
            background-size: 200% 100%;
        }
        .skeleton-spinner {
            width: 2.2rem;
            height: 2.2rem;
            border: 3px solid #e2e8f0;
            border-top-color: #6571ff;
            border-radius: 50%;
            animation: spinner-rotate 0.8s linear infinite;
        }
        @keyframes spinner-rotate {
            to { transform: rotate(360deg); }
        }
    </style>

    <!-- Header bar: Search box & Action buttons -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mb-4 gap-3">
        <div class="shimmer-element" style="width: 240px; height: 38px;"></div>
        <div class="d-flex gap-2 align-items-center">
            <div class="shimmer-element" style="width: 100px; height: 38px;"></div>
            <div class="shimmer-element" style="width: 90px; height: 38px;"></div>
        </div>
    </div>

    <!-- Table Header shimmer -->
    <div class="table-responsive">
        <div class="d-flex align-items-center p-3 mb-2 rounded bg-light border">
            <div class="shimmer-element me-auto" style="width: 30%; height: 20px;"></div>
            <div class="shimmer-element me-auto" style="width: 40%; height: 20px;"></div>
            <div class="shimmer-element" style="width: 15%; height: 20px;"></div>
        </div>

        <!-- Table Rows shimmer -->
        @for ($i = 0; $i < 5; $i++)
            <div class="d-flex align-items-center p-3 border-bottom">
                <div class="shimmer-element me-auto" style="width: {{ [28, 32, 25, 30, 35][$i] }}%; height: 16px;"></div>
                <div class="shimmer-element me-auto" style="width: {{ [45, 38, 42, 48, 40][$i] }}%; height: 16px;"></div>
                <div class="shimmer-element" style="width: 60px; height: 28px; border-radius: 4px;"></div>
            </div>
        @endfor
    </div>

    <!-- Centered Loading Spinner indicator -->
    <div class="d-flex flex-column align-items-center justify-content-center py-4 my-2">
        <div class="skeleton-spinner mb-2"></div>
        <span class="text-muted fs-7 fw-medium">Loading data...</span>
    </div>
</div>
