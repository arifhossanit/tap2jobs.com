@php
    $headerAd = getActiveAdByPosition(\App\Models\Ad::POSITION_HEADER);
@endphp
@if ($headerAd)
    {{--
      IMPORTANT: Do NOT use id/class/aria containing "ad" / "ads" / "advert".
      Browser ad blockers hide those selectors even when HTML is present.
      Always visible above the navbar; no JS hide-on-load / localStorage dismiss.
    --}}
    <div id="siteTopBanner" class="site-top-banner" role="region" aria-label="Announcement">
        <div class="site-top-banner__bar">
            <div class="position-relative">
                <button type="button" class="site-top-banner__close" id="siteTopBannerClose" aria-label="Close"
                        onclick="this.closest('#siteTopBanner').classList.add('is-closed'); return false;">
                    <i class="fas fa-times"></i>
                </button>
                <div class="site-top-banner__inner">
                    @if (!empty($headerAd->ad_image_url))
                        @if (!empty($headerAd->link_url))
                            <a href="{{ $headerAd->link_url }}" target="_blank" rel="noopener noreferrer"
                               class="site-top-banner__image-link">
                                <img src="{{ $headerAd->ad_image_url }}" alt="{{ $headerAd->title }}"
                                     class="site-top-banner__image">
                            </a>
                        @else
                            <img src="{{ $headerAd->ad_image_url }}" alt="{{ $headerAd->title }}"
                                 class="site-top-banner__image">
                        @endif
                    @endif
                    @if (!empty($headerAd->title) || !empty($headerAd->description) || (!empty($headerAd->link_url) && !empty($headerAd->cta_text)))
                        <div class="site-top-banner__content">
                            @if (!empty($headerAd->title))
                                <div class="site-top-banner__title">{{ $headerAd->title }}</div>
                            @endif
                            @if (!empty($headerAd->description))
                                <div class="site-top-banner__desc">{{ $headerAd->description }}</div>
                            @endif
                            @if (!empty($headerAd->link_url) && !empty($headerAd->cta_text))
                                <a href="{{ $headerAd->link_url }}" target="_blank" rel="noopener noreferrer"
                                   class="site-top-banner__cta">{{ $headerAd->cta_text }}</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <style>
        /* High-contrast promo strip ABOVE navbar — never gated by JS on load. */
        #siteTopBanner.site-top-banner {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            height: auto !important;
            max-height: none !important;
            overflow: visible !important;
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
            min-height: 88px;
            background: #1967d2 !important;
            background-image: none !important;
            border-bottom: 2px solid #245d9b !important;
            box-sizing: border-box;
        }
        #siteTopBanner .site-top-banner__inner {
            display: flex !important;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 16px 28px;
            min-height: 88px;
            padding: 12px 40px 12px 12px;
            box-sizing: border-box;
        }
        #siteTopBanner .site-top-banner__close {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 2;
            border: 0;
            background: #fff;
            color: #0b7a44;
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 50%;
            line-height: 1;
            box-shadow: 0 1px 3px rgba(0,0,0,.2);
        }
        #siteTopBanner .site-top-banner__close:hover {
            color: #1967d2;
            background: #f3f6fa;
        }
        #siteTopBanner .site-top-banner__image {
            display: block !important;
            max-height: 64px;
            width: auto;
            max-width: min(100%, 920px);
            object-fit: contain;
            background: #fff;
            padding: 4px;
            border-radius: 4px;
        }
        #siteTopBanner .site-top-banner__image-link {
            display: inline-block;
            line-height: 0;
        }
        #siteTopBanner .site-top-banner__content {
            text-align: center;
        }
        #siteTopBanner .site-top-banner__title {
            color: #fff !important;
            font-weight: 800;
            font-size: 1.25rem;
            line-height: 1.25;
            text-shadow: 0 1px 0 rgba(0,0,0,.15);
        }
        #siteTopBanner .site-top-banner__desc {
            color: #e8fff0 !important;
            font-size: 0.95rem;
            margin-top: 2px;
        }
        #siteTopBanner .site-top-banner__cta {
            display: inline-block;
            margin-top: 6px;
            background: #fff;
            color: #0b7a44 !important;
            font-size: 0.9rem;
            font-weight: 800;
            padding: 6px 14px;
            border-radius: 4px;
            text-decoration: none;
        }
        /* Keep light-blue navbar below the banner in stacking order */
        body > header.bg-gradient {
            position: relative !important;
            z-index: 1 !important;
            margin-top: 0 !important;
            top: auto !important;
        }
        @media (min-width: 768px) {
            #siteTopBanner .site-top-banner__content {
                text-align: left;
            }
        }
        @media (max-width: 575.98px) {
            #siteTopBanner .site-top-banner__bar,
            #siteTopBanner .site-top-banner__inner {
                min-height: 72px;
            }
            #siteTopBanner .site-top-banner__image {
                max-height: 48px;
            }
            #siteTopBanner .site-top-banner__title {
                font-size: 1.05rem;
            }
        }
        html[dir=rtl] #siteTopBanner .site-top-banner__close {
            right: auto;
            left: 10px;
        }
        html[dir=rtl] #siteTopBanner .site-top-banner__inner {
            padding: 12px 12px 12px 40px;
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
