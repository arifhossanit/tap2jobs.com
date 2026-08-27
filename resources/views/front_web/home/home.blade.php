@extends('front_web.layouts.app')

@section('title', __('web.home'))

@section('page_css')
<style>
    :root {
        --bd-blue: #28588f;
        --bd-dark: #244b78;
        --bd-pale: #f4f7fa;
        --bd-text: #18263c;
    }

    body {
        background: #eee;
        color: var(--bd-text);
        font-family: Arial, Helvetica, sans-serif;
    }

    .header-padding {
        display: none !important;
    }

    .bd-shell {
        width: min(1140px, calc(100% - 32px));
        margin: auto;
    }

    .bd-home {
        --bd-section-gap: 18px;
        --bd-card-padding: 18px;
    }

    .bd-header a {
        text-decoration: none;
    }

    /* Promo Section */
    .bd-promo {
        height: 84px;
        background: #f4fff5 radial-gradient(circle at 50% -100%, #c8e7ca 0, transparent 44%);
        overflow: hidden;
    }

    .bd-promo__content {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 28px;
        color: #24609b;
        font-weight: 700;
    }

    .bd-promo__tagline {
        font-size: 29px;
        color: #1764aa;
    }

    .bd-promo__brand {
        color: #444;
        font-size: 42px;
        font-weight: 400;
        letter-spacing: -2px;
    }

    .bd-promo__brand b {
        background: #0bb452;
        color: #fff;
        font-size: 27px;
        padding: 2px 9px 3px;
        font-style: italic;
        letter-spacing: -1px;
    }

    .bd-promo__offer {
        background: #ee3a38;
        border-radius: 50%;
        width: 122px;
        height: 122px;
        color: #fff;
        text-align: center;
        padding-top: 18px;
        line-height: 1.1;
        font-size: 15px;
        transform: rotate(-4deg);
    }

    .bd-promo__offer strong {
        display: block;
        font-size: 35px;
    }

    .bd-promo__button {
        background: #12b751;
        color: #fff;
        font-size: 20px;
        padding: 10px 17px;
        border-radius: 6px;
    }

    /* Utility Bar */
    .bd-utility {
        background: var(--bd-dark);
        height: 42px;
        color: #fff;
    }

    .bd-utility__content,
    .bd-main-nav__content {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .bd-utility a {
        color: #fff;
        font-size: 12px;
        font-weight: bold;
    }

    .bd-utility__links,
    .bd-utility__right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .bd-utility__links > span {
        height: 13px;
        width: 1px;
        background: #fff;
    }

    .bd-utility__right {
        gap: 17px;
    }

    .bd-language {
        display: flex;
        width: 96px;
        height: 28px;
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        padding: 3px;
        align-items: center;
        justify-content: space-between;
        color: #777 !important;
        font-size: 10px !important;
    }

    .bd-language b {
        background: #c23682;
        color: #fff;
        border-radius: 12px;
        padding: 5px 11px;
    }

    .bd-language em {
        font-style: normal;
        padding-right: 8px;
    }

    /* Main Navigation */
    .bd-main-nav {
        height: 74px;
        background: #fff;
        border-bottom: 1px solid #dce1e8;
    }

    .bd-logo {
        position: relative;
        font-size: 33px;
        font-weight: bold;
        letter-spacing: -2px;
        color: #17569a !important;
        line-height: .7;
    }

    .bd-logo span {
        color: #e64725;
    }

    .bd-logo small {
        display: block;
        font-size: 5px;
        letter-spacing: .2px;
        color: #4e5560;
        text-align: center;
        margin-top: 8px;
    }

    .bd-nav-links {
        display: flex;
        align-items: center;
        gap: 17px;
        margin-left: 72px;
        flex: 1;
    }

    .bd-nav-links a {
        color: #25374f;
        font-size: 17px;
        white-space: nowrap;
    }

    .bd-nav-links a > i {
        color: #d31d83;
        font-style: italic;
    }

    .bd-nav-links a .fa-angle-down {
        font-size: 12px;
        color: #57677c;
        font-style: normal;
        margin-left: 4px;
    }

    .bd-nav-links b {
        height: 19px;
        width: 1px;
        background: #c6ccd5;
    }

    .bd-nav-links strong {
        background: #0db04d;
        color: #fff;
        font-size: 13px;
        padding: 2px 4px;
        font-style: italic;
    }

    /* Account Section */
    .bd-account {
        display: flex;
        align-items: center;
        gap: 21px;
        font-size: 20px;
    }

    .bd-account > a {
        color: #1a2940;
    }

    .bd-notification {
        position: relative;
    }

    .bd-notification span {
        position: absolute;
        top: -9px;
        right: -10px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        color: #fff;
        background: #e63339;
        font-size: 11px;
        text-align: center;
        padding-top: 2px;
    }

    .bd-user {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        font-weight: bold;
    }

    .bd-user img {
        width: 39px;
        height: 39px;
        object-fit: cover;
        border-radius: 50%;
    }

    .bd-user > i {
        font-size: 12px;
    }

    .bd-login,
    .bd-join {
        font-size: 13px;
        font-weight: bold;
    }

    .bd-join {
        background: #198754;
        color: #fff !important;
        padding: 8px 12px;
        border-radius: 3px;
    }

    /* Profile Strip */
    .bd-profile-strip {
        background: #f7f9fb;
    }

    .bd-profile-inner {
        min-height: 96px;
        display: grid;
        grid-template-columns: 1.65fr .9fr .9fr;
        gap: 32px;
        align-items: center;
    }

    .bd-profile-intro {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .bd-flash {
        width: 52px;
        height: 52px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: #e5f5fe;
        color: #0087d6;
        font-size: 23px;
        box-shadow: 0 1px 6px #d2e9f6;
    }

    .bd-profile-intro h2 {
        font-size: 16px;
        margin: 0 0 7px;
        font-weight: bold;
    }

    .bd-profile-intro a {
        color: #0276bd;
        font-size: 13px;
        background: #ecf7ff;
        padding: 4px 7px;
        border-radius: 4px;
    }

    /* Progress Section */
    .bd-progress {
        border-left: 1px solid #e0e4e9;
        padding-left: 32px;
    }

    .bd-progress__top {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 16px;
        font-weight: bold;
    }

    .bd-progress__top span {
        border: 1px solid #e1e5e9;
        border-radius: 14px;
        padding: 3px 9px;
        font-size: 10px;
        font-weight: normal;
    }

    .bd-progress__bar {
        height: 8px;
        background: #dfe4ec;
        border-radius: 8px;
        margin: 7px 0 9px;
        overflow: hidden;
    }

    .bd-progress__bar i {
        display: block;
        height: 100%;
        background: #0a84be;
        border-radius: 8px;
    }

    .bd-progress__bar--empty i {
        width: 0 !important;
    }

    .bd-progress__bottom {
        display: flex;
        justify-content: space-between;
        color: #34445c;
        font-size: 14px;
    }

    .bd-progress__bottom a {
        color: #0074b7;
        text-decoration: underline;
    }

    /* Hero Section */
    .bd-hero {
        background: linear-gradient(90deg, rgba(247, 252, 253, .93), rgba(224, 234, 242, .72), rgba(250, 244, 229, .85)), linear-gradient(125deg, #edf7f7 18%, #bbcfdb 18.2%, #e8eceb 44%, #c9d4dd 44.3%, #f5ebd8 78%);
        min-height: 300px;
    }

    .bd-hero__inner {
        position: relative;
        min-height: 300px;
        padding: 32px 270px 28px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .bd-hero h1 {
        font-size: 25px;
        margin: 0 0 24px;
        color: #000;
        font-weight: bold;
        text-align: center;
    }

    /* Stats */
    .bd-stats {
        display: flex;
        justify-content: center;
        gap: 38px;
        margin-bottom: var(--bd-section-gap);
    }

    .bd-stat {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #205b9d;
    }

    .bd-stat__icon {
        display: grid;
        place-items: center;
        border: 3px solid #245d9b;
        border-radius: 50%;
        width: 53px;
        height: 53px;
        font-size: 18px;
    }

    .bd-stat span {
        font-size: 14px;
        white-space: nowrap;
    }

    .bd-stat b {
        display: block;
        font-size: 22px;
    }

    /* Search */
    .bd-search {
        display: flex;
        gap: 10px;
        background: #245b99;
        border-radius: 4px;
        padding: 15px;
    }

    .bd-search input,
    .bd-search select {
        height: 36px;
        border: 0;
        border-radius: 4px;
        padding: 0 11px;
        font-size: 14px;
        color: #777;
        outline: 0;
    }

    .bd-search input {
        flex: 1.1;
    }

    .bd-search select {
        flex: .9;
    }

    .bd-search button {
        background: #b8dcb9;
        border: 0;
        border-radius: 4px;
        width: 122px;
        color: #000;
        font-size: 14px;
    }

    .find-job-section {
        width: 100%;
    }

    .find-job-section > .row {
        justify-content: center;
        margin-left: 0;
        margin-right: 0;
        row-gap: 0;
    }

    .find-job-section .find-job {
        border-radius: 8px;
        box-shadow: 0 8px 20px rgba(24, 38, 60, .10);
        padding: 2px;
        top: 0 !important;
    }

    .find-job-section .find-job form > .row {
        row-gap: 12px;
    }

    .find-job-section .find-job h3 {
        margin-bottom: 5px !important;
    }

    .find-job-section .find-job input {
        padding-bottom: 0 !important;
    }

    .find-job-section .find-jobs-btn {
        min-height: 44px;
    }

    /* Division Links & Counters */
    .bd-city-links {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: var(--bd-section-gap);
    }

    .bd-city-links a {
        color: #ffffff;
        font-size: 13px;
        background: #1967d2;
        padding: 6px 14px;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.2s ease-in-out;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 2px 4px rgba(25, 103, 210, 0.15);
    }

    .bd-city-links a:hover {
        background: #1152b1;
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(25, 103, 210, 0.3);
    }

    /* Quick Links */
    .bd-quick-links {
        height: 100%;
        width: 263px;
        background: #1967d2;
        color: #fff;
        padding: 16px 18px;
    }

    .bd-quick-links h2 {
        font-size: 16px;
        line-height: 1.2 !important;
        margin: 0;
    }

    .bd-quick-links a {
        display: block;
        color: #fff;
        font-size: 12px;
    }

    .bd-quick-links a::before {
        content: '» ';
        font-size: 16px;
    }

    .bd-quick-links mark {
        background: #f6df35;
        border-radius: 7px;
        padding: 1px 5px;
        font-size: 9px;
    }

    /* Directory */
    .bd-directory {
        padding: var(--bd-section-gap) 0 24px;
    }

    .bd-directory__layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 263px;
        gap: 16px;
        align-items: stretch;
    }

    .bd-category-card {
        background: #fff;
        border: 1px solid #d6dce3;
        border-radius: 4px;
        padding: 0 var(--bd-card-padding) 14px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
        min-width: 0;
        overflow: hidden;
    }

    .bd-category-card__title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 4px 10px;
        color: #265997;
        font-size: 16px;
    }

    .bd-tabs {
        border: 1px solid #1967d2;
        border-radius: 50px;
        overflow: hidden;
        font-size: 12px;
        font-weight: normal;
    }

    .bd-tabs button {
        display: inline-block;
        padding: 8px 15px;
        color: #637084;
        background: transparent;
        border: 0;
        cursor: pointer;
        font-size: inherit;
        line-height: inherit;
    }

    .bd-tabs button.active {
        color: #fff;
        background: #666;
    }

    .bd-category-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        column-gap: 28px;
        row-gap: 2px;
    }

    .bd-category-grid > div {
        min-width: 0;
    }

    .bd-category-grid a {
        color: #4e4e4e;
        font-size: 14px;
        display: flex;
        align-items: flex-start;
        padding: 7px 0;
        white-space: normal;
        word-break: break-word;
        overflow-wrap: anywhere;
        line-height: 1.4;
    }

    .bd-category-grid a::before {
        content: '›';
        color: #3f4c58;
        font-size: 25px;
        vertical-align: -2px;
        line-height: 1;
        margin-right: 7px;
        flex-shrink: 0;
    }

    .bd-more {
        float: right;
        color: #236b2a !important;
        font-size: 13px !important;
        font-weight: bold;
    }

    @media (max-width: 991.98px) {
        .bd-category-grid a.bd-category-link--mobile-extra {
            display: none;
        }
    }

    .bd-directory-panel[hidden] {
        display: none !important;
    }

    /* Sidebar */
    .bd-sidebar {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .bd-side-card {
        background: #fff;
        border-radius: 4px;
        padding: 14px 12px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, .11);
        color: #555;
    }

    .bd-side-card--govt {
        background: #fffdef;
    }

    .bd-side-card h3 {
        color: #215895;
        font-size: 15px;
        margin: 0 0 7px;
    }

    .bd-side-card h3 i {
        margin-right: 6px;
    }

    .bd-side-card p {
        font-size: 13px;
        margin: 5px 0;
    }

    .bd-side-card strong {
        font-size: 15px;
    }

    .bd-side-card__view {
        color: #349446;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .bd-side-card__arrows {
        float: right;
        color: #329443;
        font-size: 24px;
        letter-spacing: 8px;
        line-height: 10px;
    }

    .bd-side-card--job {
        background: #eef7fc;
    }

    .bd-side-card--job h3 {
        color: #464646;
    }

    .bd-side-card--job small {
        font-size: 11px;
    }

    .bd-side-banner {
        background: #f1fff2;
        border-top: 10px solid #1ab350;
        min-height: 226px;
        padding: 24px 14px;
        text-align: center;
        color: #1770a7;
    }

    .bd-side-banner b {
        display: block;
        font-size: 24px;
    }

    .bd-side-banner span {
        display: inline-block;
        margin-top: 17px;
        background: #e94735;
        color: #fff;
        border-radius: 20px;
        padding: 7px 18px;
        font-weight: bold;
    }

    /* ============================================================
       RESPONSIVE BREAKPOINTS (Desktop >= 1200px remains 100% untouched)
       ============================================================ */

    /* Laptop & Medium Displays (992px to 1199px) */
    @media (max-width: 1199px) {
        .bd-hero__inner {
            padding: 32px 40px 28px;
        }

        .bd-directory__layout {
            grid-template-columns: minmax(0, 1fr) 230px;
            gap: 16px;
        }

        .bd-quick-links {
            width: 100%;
            padding: 16px 14px;
        }

        .bd-category-grid {
            column-gap: 16px;
        }

        .find-job-section .find-job .row > div {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }
    }

    /* Tablet Screens (768px to 991px) */
    @media (max-width: 991px) {
        .bd-promo {
            display: none;
        }

        .bd-home {
            --bd-section-gap: 16px;
            --bd-card-padding: 16px;
        }

        .bd-nav-links {
            margin-left: 20px;
            gap: 10px;
        }

        .bd-nav-links a {
            font-size: 13px;
        }

        .bd-profile-inner {
            grid-template-columns: 1fr;
            gap: 10px;
            padding: 15px 0;
        }

        .bd-progress {
            border: 0;
            padding: 0;
        }

        .bd-hero {
            min-height: auto;
        }

        .bd-hero__inner {
            min-height: auto;
            padding: 28px 20px 24px;
        }

        .bd-hero h1 {
            font-size: 22px;
            margin-bottom: 18px;
        }

        .bd-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px 16px;
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 20px;
        }

        .bd-stat {
            justify-content: center;
        }

        .find-job-section .find-job {
            padding: 12px;
            top: 0 !important;
        }

        .find-job-section .find-job form > .row {
            row-gap: 12px;
        }

        .find-job-section .find-job .br-2 {
            border-right: none !important;
        }

        .bd-directory__layout {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .bd-category-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            column-gap: 20px;
        }

        .bd-quick-links {
            display: block !important;
            width: 100%;
            border-radius: 4px;
            padding: 20px;
        }

        .bd-quick-links h2 {
            margin-bottom: 12px;
        }

        .bd-quick-links-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px 16px;
        }
    }

    /* Mobile Screens (< 768px & < 576px) */
    @media (max-width: 767px) {
        .bd-shell {
            width: min(100% - 24px, 1140px);
        }

        .bd-utility__links,
        .bd-nav-links,
        .bd-account > a:not(.bd-login):not(.bd-join),
        .bd-utility__right a:last-child {
            display: none;
        }

        .bd-main-nav {
            height: 64px;
        }

        .bd-logo {
            font-size: 29px;
        }

        .bd-account {
            gap: 8px;
        }

        .bd-hero {
            background: #1e528e !important;
        }

        .bd-hero__inner {
            padding: 20px 14px 20px;
        }

        .bd-hero h1 {
            color: #ffffff !important;
            font-size: 22px;
            margin-bottom: 16px;
            line-height: 1.3;
            font-weight: 700;
        }

        /* 1. Live jobs, vacancies, companies, new jobs hidden on mobile */
        .bd-stats {
            display: none !important;
        }

        /* 3. Mobile Searchbar & Button design matching reference image */
        .find-job-section .find-job {
            background: transparent !important;
            padding: 0 !important;
            box-shadow: none !important;
        }

        .find-job-section .find-job form > .row > div {
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
        }

        .find-job-section .find-job form > .row > div:last-child {
            margin-bottom: 0 !important;
        }

        .find-job-section .find-job .autocomplete-wrapper {
            display: none !important;
        }

        .find-job-section .find-job h3 {
            display: none !important;
        }

        .find-job-section .find-job .br-2 {
            border: none !important;
            padding: 0 !important;
        }

        .mobile-input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #555555;
            font-size: 15px;
            z-index: 5;
            pointer-events: none;
        }

        .find-job-section .find-job input {
            background: #ffffff !important;
            border: 1px solid #ffffff !important;
            border-radius: 6px !important;
            height: 46px !important;
            padding: 0 14px 0 38px !important;
            font-size: 15px !important;
            color: #333333 !important;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08) !important;
        }

        .find-job-section .find-job input::placeholder {
            color: #888888 !important;
        }

        .find-job-section .find-jobs-btn {
            background-color: #b8dcb9 !important;
            border-color: #b8dcb9 !important;
            color: #1a3c1c !important;
            font-weight: 700 !important;
            font-size: 16px !important;
            border-radius: 6px !important;
            height: 46px !important;
            min-height: 46px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            width: 100% !important;
            box-shadow: none !important;
        }

        .find-job-section .find-jobs-btn:hover {
            background-color: #a6d4a8 !important;
            color: #0f2711 !important;
        }

        /* Mobile Division Links (positioned before footer) */
        .bd-city-links--mobile {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 8px 10px !important;
            margin-top: 16px !important;
            margin-bottom: 24px !important;
            justify-content: center !important;
            padding: 0 15px !important;
        }

        .bd-city-links--mobile a {
            background: #1967d2 !important;
            color: #ffffff !important;
            font-size: 12px !important;
            font-weight: 500 !important;
            padding: 5px 12px !important;
            border-radius: 20px !important;
            box-shadow: 0 2px 5px rgba(25, 103, 210, 0.15) !important;
            text-shadow: none !important;
        }

        .bd-city-links--mobile a:hover {
            background: #1152b1 !important;
            text-decoration: none !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 8px rgba(25, 103, 210, 0.25) !important;
        }

        .bd-directory {
            padding: 16px 0 20px;
        }

        .bd-category-grid {
            grid-template-columns: 1fr;
        }

        .bd-category-card {
            padding: 0 12px 12px;
        }

        .bd-category-card__title {
            font-size: 15px;
            padding: 12px 0 10px;
            flex-wrap: wrap;
            gap: 8px;
        }

        /* Hide Quick Links on mobile */
        .bd-quick-links {
            display: none !important;
        }

        .bd-quick-links-grid {
            grid-template-columns: 1fr;
        }

        /* Stay Connected title font size on mobile */
        footer.front-shared-footer .stay-connected-container h4,
        .stay-connected-container h4 {
            font-size: 16px !important;
        }
    }
</style>
@endsection

@section('content')
@php
    $visibleCategoryLimit = 60;
    $mobileCategoryLimit = 8;
    $visibleCategories = $jobCategories->take($visibleCategoryLimit)->values();
    $categoryColumns = $visibleCategories->chunk((int) ceil(max($visibleCategories->count(), 1) / 3));
    $typeColumns = $jobTypes->take(45)->values()->chunk((int) ceil(max($jobTypes->take(45)->count(), 1) / 3));
@endphp
<main class="bd-home">
    <section class="bd-hero">
        <div class="bd-hero__inner text-center">
            <h1>@lang('web.home_page.find_the_right_job')</h1>
            <div class="bd-stats">
                <div class="bd-stat">
                    <div class="bd-stat__icon">
                        <i class="fa-solid fa-wave-square"></i>
                    </div>
                    <span>@lang('web.home_page.live_jobs')<b>{{ number_format($dataCounts['jobs'] ?? 0) }}</b></span>
                </div>
                <div class="bd-stat">
                    <div class="bd-stat__icon">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <span>@lang('web.home_page.vacancies')<b>{{ number_format(($dataCounts['jobs'] ?? 0) * 3) }}+</b></span>
                </div>
                <div class="bd-stat">
                    <div class="bd-stat__icon">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <span>@lang('web.companies')<b>{{ number_format($dataCounts['companies'] ?? 0) }}</b></span>
                </div>
                <div class="bd-stat">
                    <div class="bd-stat__icon">
                        <i class="fa-solid fa-burst"></i>
                    </div>
                    <span>@lang('web.home_page.new_jobs')<b>{{ $latestJobs->count() }}</b></span>
                </div>
            </div>
            <!-- <form class="bd-search" action="{{ route('front.search.jobs') }}" method="get">
                <input type="text" name="keywords" placeholder="Job title, company or keywords">
                <input type="text" name="location" placeholder="Location">
                <button type="submit">Search</button>
            </form> -->
            <!--start find-job section-->
            <section class="find-job-section">
                <div class="row">
                    <div class="col-xl-10 col-lg-10">
                        <div class="find-job position-relative bg-white">
                            <form action="{{ route('front.search.jobs') }}" id='searchForm' method="get">
                                <div class="row align-items-center justify-content-around m-0">
                                    <div class="col-lg-5 br-2 ps-lg-4 px-20 position-relative">
                                        <h3 class="fs-16 text-secondary text-start mb-0">@lang('web.home_menu.keywords')</h3>
                                        <i class="fa-solid fa-tag mobile-input-icon d-md-none"></i>
                                        <input type="text" class="fs-14 text-gray mb-0" name="keywords"
                                            id="search-keywords" placeholder="@lang('web.web_home.job_title_keywords_company')" autocomplete="off" />
                                        <div id="jobsSearchResults" class="position-absolute w100 job-search"
                                            style="display: none;">
                                            <ul class="job-search-dropdown nav submenu">
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 br-2 ps-lg-4 px-20 autocomplete-wrapper position-relative">
                                        <h3 class="fs-16 text-secondary text-start mb-0">@lang('web.common.location')</h3>
                                        <i class="fa-solid fa-location-dot mobile-input-icon d-md-none"></i>
                                        <input type="text" class="fs-14 text-gray mb-0 pb-4" name="location"
                                            id="search-location" placeholder="@lang('web.web_home.city_or_postcode')" />
                                    </div>
                                    <div class="col-lg-3 text-center p-lg-3 px-20">
                                        <button class="btn btn-primary find-jobs-btn d-block p-0 px-2 pt-3 pb-3"
                                            style="width: 100%" type="submit">
                                            <span class="d-none d-md-inline">@lang('web.web_home.find_jobs')</span>
                                            <span class="d-md-none">Search</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <!--end find-job section-->
            <div class="bd-city-links d-none d-lg-flex">
                @foreach($stateJobCounts as $state)
                    <a href="{{ route('front.search.jobs', ['location' => $state->name]) }}">{{ $state->name }} ({{ $state->jobs_count }})</a>
                @endforeach
            </div>
        </div>
    </section>
    <section class="bd-directory">
        <div class="bd-shell bd-directory__layout">
            <div class="bd-category-card" id="bdDirectoryCard">
                <div class="bd-category-card__title">@lang('web.home_page.find_jobs_across_category_industry')
                    <div class="bd-tabs d-none" role="tablist">
                        <button type="button" class="active" role="tab" aria-selected="true" data-bd-tab="category">@lang('web.home_page.category')</button>
                        <button type="button" role="tab" aria-selected="false" data-bd-tab="type">@lang('web.home_page.type')</button>
                    </div>
                </div>
                <div class="bd-directory-panel" data-bd-panel="category">
                    <div class="bd-category-grid">
                        @php $categoryIndex = 0; @endphp
                        @foreach($categoryColumns as $column)
                        <div>
                            @foreach($column as $category)
                            @php $categoryIndex++; @endphp
                            <a class="{{ $categoryIndex > $mobileCategoryLimit ? 'bd-category-link--mobile-extra' : '' }}"
                               href="{{ route('front.search.jobs', ['categories' => $category->id]) }}">{{ html_entity_decode($category->name) }} ({{ $category->jobs_count }})</a>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                    <a class="bd-more" href="{{ route('front.categories') }}">@lang('web.home_page.more')&nbsp; +</a>
                </div>
                <div class="bd-directory-panel" data-bd-panel="type" hidden>
                    <div class="bd-category-grid">
                        @foreach($typeColumns as $column)
                        <div>
                            @foreach($column as $jobType)
                            <a href="{{ route('front.search.jobs', ['job_type' => $jobType->id]) }}">{{ html_entity_decode($jobType->name) }} ({{ $jobType->jobs_count }})</a>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                    <a class="bd-more" href="{{ route('front.search.jobs') }}">@lang('web.home_page.more')&nbsp; +</a>
                </div>
            </div>
            <!-- <aside class="bd-sidebar"></aside> -->
            <aside class="bd-sidebar bd-quick-links">
                <h2>@lang('web.home_page.quick_links')</h2>
                <div class="bd-quick-links-grid">
                    <a href="{{ route('front.company.lists') }}">Employer List ({{ $quickLinkCounts['employer_list'] ?? 0 }})</a>
                    <a href="{{ route('front.search.jobs', ['filter' => 'new']) }}">New Jobs ({{ $quickLinkCounts['new_jobs'] ?? 0 }})</a>
                    <a href="{{ route('front.search.jobs', ['filter' => 'deadline_tomorrow']) }}">Deadline Tomorrow ({{ $quickLinkCounts['deadline_tomorrow'] ?? 0 }})</a>
                    <a href="{{ route('front.search.jobs', ['job_type' => $quickJobTypeIds['internship'] ?? '']) }}">Internship Opportunity ({{ $quickLinkCounts['internship'] ?? 0 }})<mark>new</mark></a>
                    <a href="{{ route('front.search.jobs', ['job_type' => $quickJobTypeIds['contractual'] ?? '']) }}">Contractual Jobs ({{ $quickLinkCounts['contractual'] ?? 0 }})</a>
                    <a href="{{ route('front.search.jobs', ['job_type' => $quickJobTypeIds['part_time'] ?? '']) }}">Part time Jobs ({{ $quickLinkCounts['part_time'] ?? 0 }})</a>
                    <a href="{{ route('front.search.jobs', ['overseas' => 1]) }}">Overseas Jobs ({{ $quickLinkCounts['overseas'] ?? 0 }})</a>
                    <a href="{{ route('front.search.jobs', ['work_from_home' => 1]) }}">Work From Home ({{ $quickLinkCounts['work_from_home'] ?? 0 }})</a>
                    <a href="{{ route('front.search.jobs', ['is_fresher' => 1]) }}">Fresher Jobs ({{ $quickLinkCounts['fresher_jobs'] ?? 0 }})</a>
                </div>
            </aside>
        </div>
    </section>
    <div class="bd-city-links bd-city-links--mobile d-lg-none">
        @foreach($stateJobCounts as $state)
            <a href="{{ route('front.search.jobs', ['location' => $state->name]) }}">{{ $state->name }} ({{ $state->jobs_count }})</a>
        @endforeach
    </div>
</main>
{{ Form::hidden('homeData', json_encode(getCountries()), ['id' => 'indexHomeData']) }}
@endsection

@section('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var card = document.getElementById('bdDirectoryCard');
        if (!card) {
            return;
        }

        var tabs = card.querySelectorAll('[data-bd-tab]');
        var panels = card.querySelectorAll('[data-bd-panel]');

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                var target = tab.getAttribute('data-bd-tab');

                tabs.forEach(function (item) {
                    var isActive = item === tab;
                    item.classList.toggle('active', isActive);
                    item.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });

                panels.forEach(function (panel) {
                    if (panel.getAttribute('data-bd-panel') === target) {
                        panel.removeAttribute('hidden');
                    } else {
                        panel.setAttribute('hidden', 'hidden');
                    }
                });
            });
        });
    });
</script>
@endsection
