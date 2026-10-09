@extends('layouts.app')

@section('title', 'Hubungi Kami')

@section('content')
<div class="page-header" style="background: var(--surface); border-bottom: 1px solid var(--line); padding: 60px 0;">
    <div class="container text-center">
        <h1 style="margin-bottom: 16px;">Hubungi Kami</h1>
        <p class="text-muted" style="max-width: 500px; margin: 0 auto;">
            Punya pertanyaan seputar produk atau pesanan? Jangan ragu untuk menghubungi kami.
        </p>
    </div>
</div>

<section class="section">
    <div class="container" style="max-width: 600px;">
        <div class="card" style="padding: 40px; text-align: center;">
            <div style="width: 64px; height: 64px; background: var(--wa); color: white; border-radius: 50%; display: grid; place-items: center; margin: 0 auto 24px;">
                <x-icon name="whatsapp" style="width: 32px; height: 32px;" />
            </div>
            
            <h2 style="margin-bottom: 16px;">Chat via WhatsApp</h2>
            <p class="text-muted" style="margin-bottom: 32px;">
                Kami siap membantu Anda. Jam operasional admin adalah setiap hari dari pukul 09:00 - 20:00 WIB.
            </p>

            <a href="{{ $store->whatsappLink('Halo '.$store->store_name.', saya ingin bertanya.') }}" 
               class="btn btn-wa btn-lg" 
               target="_blank" 
               rel="noopener"
               style="display: inline-flex; align-items: center; gap: 8px;">
                <x-icon name="whatsapp" />
                Hubungi Admin Sekarang
            </a>
        </div>
        
        @if($store->address || $store->google_maps_url)
        <div style="margin-top: 40px; text-align: center;">
            <h3 style="font-size: 16px; margin-bottom: 12px; color: var(--muted);">Atau kunjungi offline store kami:</h3>
            @if($store->address)
            <div style="color: var(--ink-2); line-height: 1.6; margin-bottom: 16px;">
                {!! nl2br(e($store->address)) !!}
            </div>
            @endif
            @if($store->google_maps_url)
            <a href="{{ $store->google_maps_url }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                <x-icon name="map-pin" /> Buka Lokasi di Google Maps <x-icon name="external" style="width: 13px; height: 13px; opacity: 0.7;" />
            </a>
            @endif
        </div>
        @endif
    </div>
</section>
@endsection
