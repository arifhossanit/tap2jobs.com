@php
    /** @var \Illuminate\Support\Collection $ads */
    $ads = $ads ?? collect();
@endphp
@if ($ads->isNotEmpty())
    <div class="register-side-ad-stack">
        @foreach ($ads as $ad)
            <div class="register-side-ad">
                @if (!empty($ad->ad_media_url))
                    @php
                        $adClickUrl = !empty($ad->click_url) ? $ad->click_url : 'javascript:void(0)';
                    @endphp
                    @if ($ad->ad_media_type === 'video')
                        <a href="{{ $adClickUrl }}" class="register-side-ad__media d-inline-block"
                           target="_blank" rel="noopener noreferrer">
                            <video class="img-fluid register-side-ad__image register-side-ad__video"
                                   autoplay muted loop playsinline preload="metadata">
                                <source src="{{ $ad->ad_media_url }}" type="{{ optional($ad->getFirstMedia(\App\Models\Ad::PATH))->mime_type }}">
                            </video>
                            <div class="register-side-ad__overlay">
                                <span class="register-side-ad__read-more">{{ __('web.post_menu.send_message') }}</span>
                            </div>
                        </a>
                    @else
                        <a href="{{ $adClickUrl }}" class="register-side-ad__media d-inline-block"
                           target="_blank" rel="noopener noreferrer">
                            <img src="{{ $ad->ad_media_url }}" alt="{{ $ad->title }}" class="img-fluid register-side-ad__image">
                            <div class="register-side-ad__overlay">
                                <span class="register-side-ad__read-more">{{ __('web.post_menu.send_message') }}</span>
                            </div>
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
                               class="btn btn-primary btn-sm" target="_blank" rel="noopener noreferrer">{{ $ad->cta_text }}</a>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif
