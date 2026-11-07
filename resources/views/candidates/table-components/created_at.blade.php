<span class="badge bg-secondary">
    {{ $row->created_at?->translatedFormat('jS M, Y') ?? __('messages.n/a') }}
</span>
