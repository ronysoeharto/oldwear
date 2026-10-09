@php
    $logoSrc = ($store && $store->logo_url) ? $store->logo_url : asset('images/logo.jpg');
    $storeName = ($store && $store->store_name) ? $store->store_name : 'OLDWEAR.SCND';
@endphp

{{-- Critical inline style to prevent FOUC during early rendering --}}
<style>
    .page-preloader {
        position: fixed;
        inset: 0;
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at 50% 45%, #23201D 0%, #131211 100%);
        opacity: 1;
        visibility: visible;
        transition: opacity .4s cubic-bezier(.2, .7, .2, 1), visibility .4s cubic-bezier(.2, .7, .2, 1);
        user-select: none;
        -webkit-user-select: none;
    }
    .page-preloader.is-hidden {
        opacity: 0 !important;
        visibility: hidden !important;
        pointer-events: none !important;
    }
</style>

<div id="page-preloader" class="page-preloader" aria-live="polite" aria-busy="true">
    <div class="preloader-content">
        <div class="preloader-badge-container">
            <div class="preloader-orbital-glow" aria-hidden="true"></div>
            <div class="preloader-orbital-ring" aria-hidden="true"></div>
            <div class="preloader-badge">
                <img src="{{ $logoSrc }}" alt="{{ $storeName }}" class="preloader-logo" width="82" height="82" loading="eager">
            </div>
        </div>

        <div class="preloader-brand">
            <div class="preloader-status-text">
                MEMUAT HALAMAN {{ strtoupper($storeName) }}<span class="preloader-dots" aria-hidden="true"><span>.</span><span>.</span><span>.</span></span>
            </div>
        </div>

        <div class="preloader-track" aria-hidden="true">
            <div class="preloader-bar"></div>
        </div>
    </div>
</div>
