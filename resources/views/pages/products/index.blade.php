@extends('layouts.app')

@section('title', $activeCategory ? $activeCategory->name : 'Koleksi Lengkap')

@section('content')
<div class="page-header" style="background: var(--surface); border-bottom: 1px solid var(--line); padding: 40px 0;">
    <div class="container text-center">
        <h1 style="margin-bottom: 12px;">{{ $activeCategory ? $activeCategory->name : 'Koleksi Lengkap' }}</h1>
        <p class="text-muted" style="max-width: 500px; margin: 0 auto;">
            {{ $activeCategory ? $activeCategory->description ?: 'Jelajahi produk di kategori '.$activeCategory->name.'.' : 'Temukan berbagai pilihan thrift terbaik untuk melengkapi gayamu.' }}
        </p>
    </div>
</div>

<section class="section" style="padding-top: 40px;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 260px 1fr; gap: 40px; align-items: start;">
            
            <aside class="filter-sidebar" style="position: sticky; top: 100px;">
                <form action="{{ route('products.index') }}" method="GET" class="card" style="padding: 24px;">
                    <h3 style="font-size: 16px; margin-bottom: 16px; border-bottom: 1px solid var(--line); padding-bottom: 12px;">Filter Produk</h3>
                    
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="form-label">Cari</label>
                        <input type="text" name="q" class="form-control" placeholder="Nama, bahan..." value="{{ request('q') }}">
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="form-label">Kategori</label>
                        <select name="category" class="form-control">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ (request('category') === $cat->slug || ($activeCategory && $activeCategory->slug === $cat->slug)) ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="form-label">Urutkan</label>
                        <select name="sort" class="form-control">
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="form-check" style="display: flex; gap: 8px; align-items: center; cursor: pointer;">
                            <input type="checkbox" name="available" value="1" {{ request('available') ? 'checked' : '' }}>
                            <span>Hanya yang tersedia</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Terapkan Filter</button>
                    @if(request()->anyFilled(['q', 'category', 'sort', 'available', 'min_price', 'max_price']))
                        <a href="{{ route('products.index') }}" class="btn btn-ghost btn-block" style="margin-top: 8px; text-align: center; font-size: 13px;">Reset</a>
                    @endif
                </form>
            </aside>

            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid var(--line); padding-bottom: 16px;">
                    <div class="text-muted" style="font-size: 14px;">
                        Menampilkan <strong>{{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }}</strong> dari <strong>{{ $products->total() }}</strong> produk
                    </div>
                </div>

                @if($products->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon">
                            <x-icon name="search" />
                        </div>
                        <h3>Tidak ada produk</h3>
                        <p class="text-muted">Coba ubah filter pencarian Anda untuk melihat lebih banyak hasil.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-outline">Lihat Semua Produk</a>
                    </div>
                @else
                    <div class="grid grid-products">
                        @foreach($products as $product)
                            @include('partials.product-card', ['product' => $product])
                        @endforeach
                    </div>
                    
                    <div style="margin-top: 40px;">
                        {{ $products->links('partials.pagination') }}
                    </div>
                @endif
            </div>
            
        </div>
    </div>
</section>

<style>
@media (max-width: 860px) {
    .section > .container > div {
        grid-template-columns: 1fr !important;
        gap: 24px !important;
    }
    .filter-sidebar { position: static !important; }
}
</style>
@endsection
