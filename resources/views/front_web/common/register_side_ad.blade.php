@php
    /** @var \Illuminate\Support\Collection $ads */
    $ads = $ads ?? collect();
@endphp
@foreach ($ads as $ad)
    <div class="register-side-ad mb-1">
        @if (!empty($ad->ad_media_url))
            @if ($ad->ad_media_type === 'video')
                <a href="{{ $ad->click_url }}" class="d-inline-block">
                    <video class="img-fluid register-side-ad__image register-side-ad__video"
                           autoplay muted loop playsinline preload="metadata">
                        <source src="{{ $ad->ad_media_url }}" type="{{ optional($ad->getFirstMedia(\App\Models\Ad::PATH))->mime_type }}">
                    </video>
                </a>
            @else
                <a href="{{ $ad->click_url }}" class="d-inline-block">
                    <img src="{{ $ad->ad_media_url }}" alt="{{ $ad->title }}" class="img-fluid register-side-ad__image">
                </a>
            @endif
        @endif
        @if (!empty($ad->title) || !empty($ad->description) || !empty($ad->cta_text))
            <div class="register-side-ad__content mt-2 text-center">
                {{-- @if (!empty($ad->title))
                    <div class="fw-semibold text-secondary mb-1">{{ $ad->title }}</div>
                @endif
                @if (!empty($ad->description))
                    <div class="text-gray small mb-2">{{ $ad->description }}</div>
                @endif --}}
                @if (!empty($ad->cta_text))
                    <a href="{{ $ad->click_url }}"
                       class="btn btn-primary btn-sm">{{ $ad->cta_text }}</a>
                @endif
            </div>
        @endif
    </div>
@endforeach
