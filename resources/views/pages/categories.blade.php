@extends('layouts.app')

@section('title', 'Kategori Produk')
@section('meta_description', 'Jelajahi berbagai kategori pakaian second hand pilihan di ' . $store->store_name)

@section('content')
<div class="page-header" style="background: var(--surface); border-bottom: 1px solid var(--line); padding: 60px 0;">
    <div class="container text-center">
        <h1 style="margin-bottom: 16px;">Kategori Produk</h1>
        <p class="text-muted" style="max-width: 500px; margin: 0 auto;">
            Temukan pakaian thrift favorit Anda berdasarkan kategori yang kami sediakan.
        </p>
    </div>
</div>

<section class="section">
    <div class="container">
        @if($categories->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">
                    <x-icon name="archive" />
                </div>
                <h3>Belum Ada Kategori</h3>
                <p class="text-muted">Kategori produk belum ditambahkan.</p>
            </div>
        @else
            <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px;">
                @foreach($categories as $category)
                <a href="{{ route('categories.show', $category) }}" class="card" style="padding: 32px 24px; transition: transform .3s, box-shadow .3s; display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <h2 style="font-size: 22px; margin-bottom: 8px;">{{ $category->name }}</h2>
                        <p class="text-muted" style="margin: 0; font-size: 14px;">
                            @if($category->description)
                                {{ \Illuminate\Support\Str::limit($category->description, 60) }}<br>
                            @endif
                            <strong style="color: var(--ink);">{{ $category->products_count }}</strong> Produk Tersedia
                        </p>
                    </div>
                    <div style="background: var(--surface-2); color: var(--ink); width: 48px; height: 48px; border-radius: 50%; display: grid; place-items: center;">
                        <x-icon name="arrow-right" />
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
