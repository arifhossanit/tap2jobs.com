@php
    $headerAd = getActiveAdByPosition(\App\Models\Ad::POSITION_HEADER);
@endphp
@if ($headerAd)
    {{-- Always rendered visible above the navbar. No JS hide-on-load / localStorage dismiss. --}}
    <div id="siteHeaderAd" class="site-header-ad" role="region" aria-label="{{ __('messages.ads') }}">
        <div class="site-header-ad__bar">
            <div class="container position-relative">
                <button type="button" class="site-header-ad__close" id="siteHeaderAdClose" aria-label="Close"
                        onclick="this.closest('#siteHeaderAd').classList.add('is-closed'); return false;">
                    <i class="fas fa-times"></i>
                </button>
                <div class="site-header-ad__inner">
                    @if (!empty($headerAd->ad_image_url))
                        @if (!empty($headerAd->link_url))
                            <a href="{{ $headerAd->link_url }}" target="_blank" rel="noopener noreferrer"
                               class="site-header-ad__image-link">
                                <img src="{{ $headerAd->ad_image_url }}" alt="{{ $headerAd->title }}"
                                     class="site-header-ad__image">
                            </a>
                        @else
                            <img src="{{ $headerAd->ad_image_url }}" alt="{{ $headerAd->title }}"
                                 class="site-header-ad__image">
                        @endif
                    @endif
                    @if (!empty($headerAd->title) || !empty($headerAd->description) || (!empty($headerAd->link_url) && !empty($headerAd->cta_text)))
                        <div class="site-header-ad__content">
                            @if (!empty($headerAd->title))
                                <div class="site-header-ad__title">{{ $headerAd->title }}</div>
                            @endif
                            @if (!empty($headerAd->description))
                                <div class="site-header-ad__desc">{{ $headerAd->description }}</div>
                            @endif
                            @if (!empty($headerAd->link_url) && !empty($headerAd->cta_text))
                                <a href="{{ $headerAd->link_url }}" target="_blank" rel="noopener noreferrer"
                                   class="site-header-ad__cta">{{ $headerAd->cta_text }}</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <style>
        /* Promo strip ABOVE navbar — always visible; never gated by JS. */
        #siteHeaderAd.site-header-ad {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            height: auto !important;
            max-height: none !important;
            overflow: visible !important;
            position: relative !important;
            z-index: 1050 !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        /* Close is session-only (this page view). Never persisted; refresh shows ad again. */
        #siteHeaderAd.site-header-ad.is-closed {
            display: none !important;
        }
        #siteHeaderAd .site-header-ad__bar {
            display: block !important;
            min-height: 84px;
            background: #e8f8ea;
            background-image: radial-gradient(circle at 50% -80%, #b8e0bc 0, transparent 50%);
            border-bottom: 2px solid #12b751;
            box-sizing: border-box;
        }
        #siteHeaderAd .site-header-ad__inner {
            display: flex !important;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 16px 28px;
            min-height: 84px;
            padding: 10px 36px 10px 12px;
            box-sizing: border-box;
        }
        #siteHeaderAd .site-header-ad__close {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 2;
            border: 0;
            background: rgba(255, 255, 255, 0.7);
            color: #444;
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 50%;
            line-height: 1;
        }
        #siteHeaderAd .site-header-ad__close:hover {
            color: #1967d2;
            background: #fff;
        }
        #siteHeaderAd .site-header-ad__image {
            display: block !important;
            max-height: 64px;
            width: auto;
            max-width: min(100%, 920px);
            object-fit: contain;
        }
        #siteHeaderAd .site-header-ad__image-link {
            display: inline-block;
            line-height: 0;
        }
        #siteHeaderAd .site-header-ad__content {
            text-align: center;
        }
        #siteHeaderAd .site-header-ad__title {
            color: #1764aa;
            font-weight: 700;
            font-size: 1.15rem;
            line-height: 1.25;
        }
        #siteHeaderAd .site-header-ad__desc {
            color: #444;
            font-size: 0.9rem;
            margin-top: 2px;
        }
        #siteHeaderAd .site-header-ad__cta {
            display: inline-block;
            margin-top: 6px;
            background: #12b751;
            color: #fff !important;
            font-size: 0.9rem;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 4px;
            text-decoration: none;
        }
        @media (min-width: 768px) {
            #siteHeaderAd .site-header-ad__content {
                text-align: left;
            }
        }
        @media (max-width: 575.98px) {
            #siteHeaderAd .site-header-ad__bar,
            #siteHeaderAd .site-header-ad__inner {
                min-height: 64px;
            }
            #siteHeaderAd .site-header-ad__image {
                max-height: 48px;
            }
            #siteHeaderAd .site-header-ad__title {
                font-size: 1rem;
            }
        }
        html[dir=rtl] #siteHeaderAd .site-header-ad__close {
            right: auto;
            left: 8px;
        }
        html[dir=rtl] #siteHeaderAd .site-header-ad__inner {
            padding: 10px 12px 10px 36px;
        }
    </style>
@endif
