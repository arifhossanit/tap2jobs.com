@php
    /** @var \Illuminate\Support\Collection $ads */
    $ads = $ads ?? collect();
@endphp
@foreach ($ads as $ad)
    <div class="register-side-ad mb-1">
        @if (!empty($ad->ad_media_url))
            @if ($ad->ad_media_type === 'video')
                <video class="img-fluid register-side-ad__image register-side-ad__video"
                       autoplay muted loop playsinline preload="metadata">
                    <source src="{{ $ad->ad_media_url }}" type="{{ optional($ad->getFirstMedia(\App\Models\Ad::PATH))->mime_type }}">
                </video>
            @elseif (!empty($ad->link_url))
                <a href="{{ $ad->link_url }}" target="_blank" rel="noopener noreferrer" class="d-inline-block">
                    <img src="{{ $ad->ad_media_url }}" alt="{{ $ad->title }}" class="img-fluid register-side-ad__image">
                </a>
            @else
                <img src="{{ $ad->ad_media_url }}" alt="{{ $ad->title }}" class="img-fluid register-side-ad__image">
            @endif
        @endif
        @if (!empty($ad->title) || !empty($ad->description) || (!empty($ad->link_url) && !empty($ad->cta_text)))
            <div class="register-side-ad__content mt-2 text-center">
                {{-- @if (!empty($ad->title))
                    <div class="fw-semibold text-secondary mb-1">{{ $ad->title }}</div>
                @endif
                @if (!empty($ad->description))
                    <div class="text-gray small mb-2">{{ $ad->description }}</div>
                @endif --}}
                @if (!empty($ad->link_url) && !empty($ad->cta_text))
                    <a href="{{ $ad->link_url }}" target="_blank" rel="noopener noreferrer"
                       class="btn btn-primary btn-sm">{{ $ad->cta_text }}</a>
                @endif
            </div>
        @endif
    </div>
@endforeach
