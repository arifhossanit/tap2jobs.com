@php
    $headerAd = getActiveAdByPosition(\App\Models\Ad::POSITION_HEADER);
@endphp
@if ($headerAd && !empty($headerAd->ad_media_url))
    {{--
      IMPORTANT: Do NOT use id/class/aria containing "ad" / "ads" / "advert".
      Browser ad blockers hide those selectors even when HTML is present.
      Always visible above the navbar; no JS hide-on-load / localStorage dismiss.
    --}}
    <div id="siteTopBanner" class="site-top-banner" role="region" aria-label="Announcement">
        <div class="site-top-banner__bar">
            <div class="position-relative w-100">
                <button type="button" class="site-top-banner__close" id="siteTopBannerClose" aria-label="Close"
                        onclick="this.closest('#siteTopBanner').classList.add('is-closed'); return false;">
                    <i class="fas fa-times"></i>
                </button>
                <div class="site-top-banner__inner container mx-auto">
                    @if ($headerAd->ad_media_type === 'video')
                        <video class="site-top-banner__image" controls muted playsinline preload="metadata">
                            <source src="{{ $headerAd->ad_media_url }}" type="{{ optional($headerAd->getFirstMedia(\App\Models\Ad::PATH))->mime_type }}">
                        </video>
                    @elseif (!empty($headerAd->link_url))
                        <a href="{{ $headerAd->link_url }}" target="_blank" rel="noopener noreferrer"
                           class="site-top-banner__image-link">
                            <img src="{{ $headerAd->ad_media_url }}" alt="{{ $headerAd->title ?? 'Header Ad' }}"
                                 class="site-top-banner__image">
                        </a>
                    @else
                        <img src="{{ $headerAd->ad_media_url }}" alt="{{ $headerAd->title ?? 'Header Ad' }}"
                             class="site-top-banner__image">
                    @endif
                </div>
            </div>
        </div>
    </div>
    <style>
        /* Promo strip ABOVE navbar — full container banner image. */
        #siteTopBanner.site-top-banner {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            height: auto !important;
            max-height: none !important;
            overflow: hidden !important;
            position: relative !important;
            z-index: 1100 !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            clear: both !important;
            float: none !important;
        }
        #siteTopBanner.site-top-banner.is-closed {
            display: none !important;
        }
        #siteTopBanner .site-top-banner__bar {
            display: block !important;
            width: 100%;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background: linear-gradient(to right, #e4f2fb, #eaf0fc);
        }
        #siteTopBanner .site-top-banner__inner {
            display: block !important;
            width: 100%;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        #siteTopBanner .site-top-banner__close {
            position: absolute;
            top: 10px;
            right: 15px;
            z-index: 10;
            border: 0;
            background: rgba(255, 255, 255, 0.85);
            color: #333;
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 50%;
            line-height: 1;
            box-shadow: 0 2px 4px rgba(0,0,0,.25);
            transition: background 0.2s ease, color 0.2s ease;
        }
        #siteTopBanner .site-top-banner__close:hover {
            color: #000;
            background: #ffffff;
        }
        #siteTopBanner .site-top-banner__image-link {
            display: block;
            width: 100%;
            line-height: 0;
        }
        #siteTopBanner .site-top-banner__image {
            display: block !important;
            width: 100%;
            max-width: 100%;
            height: auto;
            object-fit: cover;
            margin: 0 auto;
        }
        /* Keep light-blue navbar below the banner in stacking order */
        body > header.bg-gradient {
            position: relative !important;
            z-index: 1 !important;
            margin-top: 0 !important;
            top: auto !important;
        }
        @media (max-width: 575.98px) {
            #siteTopBanner .site-top-banner__close {
                top: 6px;
                right: 8px;
                width: 24px;
                height: 24px;
                font-size: 12px;
            }
        }
        html[dir=rtl] #siteTopBanner .site-top-banner__close {
            right: auto;
            left: 15px;
        }
        @media (max-width: 575.98px) {
            html[dir=rtl] #siteTopBanner .site-top-banner__close {
                left: 8px;
            }
        }
    </style>
    <script>
        /* Clear legacy dismiss keys from older banner versions. Never hide on load. */
        try {
            localStorage.removeItem('jobsportal_header_ad_dismissed');
            localStorage.removeItem('jobsportal_site_header_ad_dismissed');
        } catch (e) {}
    </script>
@endif
