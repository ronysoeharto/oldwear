@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')
<div class="page-header" style="background: var(--surface); border-bottom: 1px solid var(--line); padding: 80px 0;">
    <div class="container text-center">
        <h1 style="margin-bottom: 24px; font-size: clamp(32px, 5vw, 48px);">Tentang {{ $store->store_name }}</h1>
        <p class="text-muted" style="max-width: 600px; margin: 0 auto; font-size: 18px;">
            {{ $store->tagline ?: 'Menghadirkan gaya yang ramah lingkungan dengan koleksi thrift pilihan.' }}
        </p>
    </div>
</div>

<section class="section">
    <div class="container" style="max-width: 800px;">
        <div class="content" style="background: var(--surface); padding: 48px; border-radius: var(--radius); border: 1px solid var(--line); box-shadow: var(--shadow);">
            
            <h2 style="font-size: 24px; margin-bottom: 24px; color: var(--accent);">Kisah Kami</h2>
            
            <div style="font-size: 16px; line-height: 1.8; color: var(--ink-2);">
                @if($store->description)
                    {!! nl2br(e($store->description)) !!}
                @else
                    <p>
                        Berawal dari kecintaan terhadap fashion dan kepedulian terhadap lingkungan, <strong>{{ $store->store_name }}</strong> hadir sebagai solusi untuk kamu yang ingin tampil bergaya tanpa harus merusak bumi.
                    </p>
                    <p>
                        Kami percaya bahwa setiap pakaian memiliki ceritanya sendiri. Dengan memberikan kesempatan kedua bagi pakaian-pakaian berkualitas, kita tidak hanya mengurangi limbah tekstil, tetapi juga menghargai nilai dari setiap helai kain.
                    </p>
                    <p>
                        Setiap produk yang kami tawarkan telah melalui proses seleksi yang ketat dan pencucian yang higienis, sehingga kamu bisa langsung memakainya dengan nyaman dan percaya diri.
                    </p>
                @endif
            </div>

            @if($store->owner_photo || $store->owner_name)
            <div class="owner-card" style="margin-top: 48px; padding: 28px; background: var(--surface-2); border-radius: var(--radius); border: 1px solid var(--line); display: flex; align-items: center; gap: 24px; flex-wrap: wrap;">
                @if($store->owner_photo)
                <div style="position: relative; flex-shrink: 0; margin: 0 auto;">
                    <div style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; border: 3px solid var(--surface); box-shadow: 0 4px 16px rgba(0,0,0,0.08); background: var(--surface);">
                        <img src="{{ $store->owner_photo_url }}" alt="{{ $store->owner_name ?: 'Foto Pemilik' }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    @if($store->owner_role)
                    <span class="badge badge-accent" style="position: absolute; bottom: -8px; left: 50%; transform: translateX(-50%); white-space: nowrap; font-size: 11px; padding: 3px 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                        {{ $store->owner_role }}
                    </span>
                    @endif
                </div>
                @endif
                <div style="flex: 1; min-width: 240px;">
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 6px;">
                        <span class="eyebrow" style="margin-bottom: 0; font-size: 11px;">Mengenal Pendiri</span>
                        @if(!$store->owner_photo && $store->owner_role)
                            <span class="badge badge-accent">{{ $store->owner_role }}</span>
                        @endif
                    </div>
                    @if($store->owner_name)
                        <h3 style="font-size: 20px; margin: 0 0 8px; color: var(--ink);">{{ $store->owner_name }}</h3>
                    @endif
                    @if($store->owner_bio)
                        <p style="margin: 0; font-size: 14.5px; line-height: 1.6; color: var(--ink-2); font-style: italic;">
                            "{!! nl2br(e($store->owner_bio)) !!}"
                        </p>
                    @endif
                </div>
            </div>
            @endif

            <div style="margin-top: 48px; padding-top: 40px; border-top: 1px solid var(--line); display: flex; flex-direction: column; gap: 24px;">
                <h3 style="font-size: 18px; margin: 0;">Hubungi Kami</h3>
                
                @if($store->address || $store->google_maps_url)
                <div style="display: flex; gap: 16px; align-items: flex-start;">
                    <div style="color: var(--accent); flex-shrink: 0; padding-top: 2px;"><x-icon name="map-pin" /></div>
                    <div style="flex: 1;">
                        <strong style="display: block; margin-bottom: 4px;">Lokasi & Alamat Toko</strong>
                        @if($store->address)
                            <div style="color: var(--muted); line-height: 1.6; margin-bottom: 12px;">{!! nl2br(e($store->address)) !!}</div>
                        @endif
                        @if($store->google_maps_url)
                            <a href="{{ $store->google_maps_url }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                                <x-icon name="map" style="width: 15px; height: 15px;" />
                                Buka di Google Maps
                                <x-icon name="external" style="width: 13px; height: 13px; opacity: 0.7;" />
                            </a>
                        @endif
                    </div>
                </div>
                @endif

                @if($store->whatsapp)
                <div style="display: flex; gap: 16px;">
                    <div style="color: var(--accent);"><x-icon name="phone" /></div>
                    <div>
                        <strong style="display: block; margin-bottom: 4px;">WhatsApp</strong>
                        <a href="{{ $store->whatsappLink() }}" target="_blank" rel="noopener" style="color: var(--muted); hover: color: var(--ink);">
                            {{ $store->whatsapp }}
                        </a>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</section>
@endsection
