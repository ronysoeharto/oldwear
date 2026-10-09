@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="hero">
        <div class="container hero-grid">
            <div>
                <div class="badge badge-olive" style="margin-bottom: 16px;">
                    <x-icon name="sparkles" />
                    Koleksi Terbaru
                </div>
                <h1>Temukan pakaian <em>second hand</em> berkualitas.</h1>
                <p class="hero-lead">Kami menyeleksi pakaian thrift terbaik dengan kondisi prima. Tampil gaya tidak harus mahal dan merusak lingkungan.</p>
                <div class="hero-actions">
                    <a href="{{ route('products.index') }}" class="btn btn-accent btn-lg">
                        Lihat Koleksi
                        <x-icon name="arrow-right" />
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn btn-light btn-lg">
                        Cari Kategori
                    </a>
                </div>
                <div class="hero-stats">
                    <div>
                        <strong>1K+</strong>
                        <span>Produk Terjual</span>
                    </div>
                    <div>
                        <strong>99%</strong>
                        <span>Pelanggan Puas</span>
                    </div>
                    <div>
                        <strong>100%</strong>
                        <span>Original Thrift</span>
                    </div>
                </div>
            </div>
            <div class="hero-media">
                <img src="{{ $store->banner_url ?? asset('images/hero.jpg') }}" alt="{{ $store->store_name }} Hero" onerror="this.src='{{ asset('images/no-image.svg') }}'">
                @if($store->banner_badge)
                <div class="hero-chip">
                    <div class="chip-icon">
                        <x-icon name="check-circle" />
                    </div>
                    <div>
                        <strong>{{ $store->banner_badge }}</strong>
                        @if($store->banner_text)<span>{{ $store->banner_text }}</span>@endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    @if($featured->count() > 0)
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2>Pilihan <span class="text-accent">Terbaik</span></h2>
                <a href="{{ route('products.index') }}" class="btn btn-ghost btn-sm">
                    Lihat Semua <x-icon name="arrow-right" />
                </a>
            </div>
            <div class="grid grid-products">
                @foreach($featured as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>
    @else
    <section class="section">
        <div class="container text-center" style="padding: 40px 20px;">
            <div class="card" style="max-width: 560px; margin: 0 auto; padding: 40px 24px;">
                <div style="font-size: 40px; margin-bottom: 16px;">✨</div>
                <h3 style="margin-bottom: 8px; font-size: 20px;">Katalog Baru Sedang Disiapkan</h3>
                <p class="text-muted" style="margin-bottom: 24px; font-size: 15px;">Admin sedang mengkurasi produk thrift terbaik untuk dipajang. Hubungi kami langsung melalui WhatsApp untuk menanyakan ketersediaan produk.</p>
                @if($store->whatsapp)
                <div>
                    <a href="{{ $store->whatsappLink('Halo, saya ingin menanyakan katalog produk thrift terbaru') }}" target="_blank" class="btn btn-wa">
                        <x-icon name="whatsapp" /> Chat WhatsApp
                    </a>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    @if($categories->count() > 0)
    <section class="section section-bg">
        <div class="container">
            <div class="section-header text-center" style="max-width: 540px; margin: 0 auto 40px;">
                <h2>Kategori Pilihan</h2>
                <p class="text-muted">Jelajahi koleksi kami berdasarkan kategori yang paling kamu cari.</p>
            </div>
            <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));">
                @foreach($categories as $category)
                <a href="{{ route('categories.show', $category) }}" class="card" style="display: flex; align-items: center; justify-content: space-between; padding: 24px; transition: transform .3s, box-shadow .3s;">
                    <div>
                        <h3 style="margin-bottom: 4px; font-size: 18px;">{{ $category->name }}</h3>
                        <span class="text-muted" style="font-size: 14px;">{{ $category->products_count }} Produk</span>
                    </div>
                    <div class="btn btn-light btn-sm" style="border-radius: 50%; padding: 8px;">
                        <x-icon name="arrow-right" />
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if($testimonials->count() > 0)
    <section class="section">
        <div class="container">
            <div class="section-header text-center" style="max-width: 540px; margin: 0 auto 40px;">
                <h2>Kata Mereka</h2>
                <p class="text-muted">Ulasan asli dari pelanggan yang sudah berbelanja di {{ $store->store_name }}.</p>
            </div>
            <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));">
                @foreach($testimonials as $testimonial)
                    @include('partials.testimonial-card', ['testimonial' => $testimonial])
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection
