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
