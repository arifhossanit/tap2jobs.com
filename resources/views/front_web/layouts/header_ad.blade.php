{{-- ============================================================
     HEADER PROMO STRIP  (site-top-banner__bar)
     ------------------------------------------------------------
     The previous image / video based header-ad banner function is
     temporarily COMMENTED OUT below (kept for later re-use).
     Currently the strip renders a multi-colour GRADIENT bar with
     the promo text:  "Post your Job Ad for Free"  (আপনার চাকরির বিজ্ঞাপন পোস্ট করুন একদম ফ্রি-তে!!!)
     IMPORTANT: Do NOT use id/class/aria containing "ad" / "ads" / "advert".
     Browser ad blockers hide those selectors even when HTML is present.
     The strip is always visible above the navbar; no JS hide-on-load.
     ============================================================ --}}

<div id="siteTopBanner" class="site-top-banner" role="region" aria-label="Announcement">
    <div class="site-top-banner__bar">
        <div class="position-relative w-100">
            {{-- =========== OLD HEADER-AD IMAGE/VIDEO FUNCTION (commented out for now) ===========
            @php
                $headerAd = getActiveAdByPosition(\App\Models\Ad::POSITION_HEADER);
            @endphp
            @if ($headerAd && !empty($headerAd->ad_media_url))
                <button type="button" class="site-top-banner__close" id="siteTopBannerClose" aria-label="Close"
                        onclick="this.closest('#siteTopBanner').classList.add('is-closed'); return false;">
                    <i class="fas fa-times"></i>
                </button>
                <div class="site-top-banner__inner container mx-auto">
                    @if ($headerAd->ad_media_type === 'video')
                        <video class="site-top-banner__image" autoplay muted loop playsinline preload="metadata">
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
            @endif
            =========== END OLD HEADER-AD IMAGE/VIDEO FUNCTION =========== --}}

            {{-- Professional promo strip --}}
            <div class="site-top-banner__inner mx-auto">
                <a href="{{ route('job.create') }}" class="site-top-banner__promo">
                    <span class="site-top-banner__promo-text">{{ __('web.post_job_ad_free') }}</span>
                    <span class="site-top-banner__yellow-btn ms-3">Post a Job</span>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Promo strip ABOVE navbar — full-width multi-colour gradient bar. */
    #siteTopBanner.site-top-banner {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        height: auto !important;
        max-height: none !important;
        overflow: hidden !important;
        position: relative !important;
        z-index: 1000 !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        clear: both !important;
        float: none !important;
    }
    #siteTopBanner .site-top-banner__bar {
        display: block !important;
        width: 100%;
        min-height: 64px;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        background: #1967d2;
    }
    #siteTopBanner .site-top-banner__inner {
        display: flex !important;
        align-items: center;
        justify-content: center;
        width: 100%;
        min-height: 78px;
        margin: 0 auto;
        padding: 16px 20px;
        box-sizing: border-box;
    }
    #siteTopBanner .site-top-banner__promo {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: #ffffff;
        line-height: 1;
        white-space: nowrap;
    }
    #siteTopBanner .site-top-banner__promo-text {
        font-size: 28px;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.35);
    }
    #siteTopBanner .site-top-banner__yellow-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #facc15;
        color: #0f172a;
        font-size: 25px;
        font-weight: 700;
        padding: 8px 22px;
        border-radius: 4px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.25);
        transition: all 0.2s ease-in-out;
        white-space: nowrap;
        text-shadow: none;
    }
    #siteTopBanner .site-top-banner__promo:hover .site-top-banner__yellow-btn {
        background: #fbbf24;
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(0, 0, 0, 0.35);
        color: #000000;
    }
    #siteTopBanner .site-top-banner__promo:hover {
        filter: brightness(1.05);
    }
    /* Keep navbar and its dropdowns above any content ads */
    body > header.bg-gradient {
        position: relative !important;
        z-index: 9999 !important;
        margin-top: 0 !important;
        top: auto !important;
    }
    @media (max-width: 768px) {
        #siteTopBanner .site-top-banner__promo-text {
            font-size: 20px;
        }
        #siteTopBanner .site-top-banner__yellow-btn {
            font-size: 14px;
            padding: 6px 16px;
        }
    }
    @media (max-width: 575.98px) {
        #siteTopBanner .site-top-banner__bar,
        #siteTopBanner .site-top-banner__inner {
            min-height: 52px;
            padding: 10px 12px;
        }
        #siteTopBanner .site-top-banner__promo {
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
        }
        #siteTopBanner .site-top-banner__promo-text {
            font-size: 14px;
        }
        #siteTopBanner .site-top-banner__yellow-btn {
            font-size: 12px;
            padding: 5px 12px;
            margin-left: 0 !important;
        }
    }
</style>
