@props(['leftAds' => collect(), 'rightAds' => collect()])

@php
    $leftAds = collect($leftAds);
    $rightAds = collect($rightAds);
    $hasAds = $leftAds->isNotEmpty() || $rightAds->isNotEmpty();
@endphp

<div {{ $attributes->class(['front-side-ad-layout', 'front-side-ad-layout--empty' => ! $hasAds]) }}>
    @if ($hasAds)
        <aside class="front-side-ad-layout__aside front-side-ad-layout__aside--left d-none d-xxl-block">
            @include('front_web.common.register_side_ad', ['ads' => $leftAds])
        </aside>
    @endif

    <div class="front-side-ad-layout__main">
        {{ $slot }}
    </div>

    @if ($hasAds)
        <aside class="front-side-ad-layout__aside front-side-ad-layout__aside--right d-none d-xxl-block">
            @include('front_web.common.register_side_ad', ['ads' => $rightAds])
        </aside>

        <div class="front-side-ad-layout__mobile d-xxl-none">
            @if ($leftAds->isNotEmpty())
                <div>@include('front_web.common.register_side_ad', ['ads' => $leftAds])</div>
            @endif
            @if ($rightAds->isNotEmpty())
                <div>@include('front_web.common.register_side_ad', ['ads' => $rightAds])</div>
            @endif
        </div>
    @endif
</div>
