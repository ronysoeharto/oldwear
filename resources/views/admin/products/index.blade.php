@extends('layouts.admin')

@section('title', 'Kelola Produk')

@section('content')
<div class="card">
    <div style="padding: 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <form action="{{ route('admin.products.index') }}" method="GET" style="display: flex; gap: 8px;">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari produk..." style="width: 240px;">
            <button type="submit" class="btn btn-light"><x-icon name="search" /></button>
            @if(request('q'))
                <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">Reset</a>
            @endif
        </form>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <x-icon name="plus" /> Tambah Produk
        </a>
    </div>

    <div class="table-responsive">
        <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--surface-2);">
                    <th style="padding: 12px 24px; font-size: 13px; color: var(--muted); font-weight: 600;">Produk</th>
                    <th style="padding: 12px 24px; font-size: 13px; color: var(--muted); font-weight: 600;">Kategori</th>
                    <th style="padding: 12px 24px; font-size: 13px; color: var(--muted); font-weight: 600;">Harga & Stok</th>
                    <th style="padding: 12px 24px; font-size: 13px; color: var(--muted); font-weight: 600;">Status</th>
                    <th style="padding: 12px 24px; font-size: 13px; color: var(--muted); font-weight: 600; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr style="border-bottom: 1px solid var(--line);">
                    <td style="padding: 16px 24px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; border-radius: 8px; background: var(--surface-2); overflow: hidden;">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div>
                                <div style="font-weight: 600;">{{ $product->name }}</div>
                                @if($product->is_featured)
                                    <div style="font-size: 11px; color: var(--accent); font-weight: 600; margin-top: 4px;">★ Unggulan</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="padding: 16px 24px; color: var(--muted);">{{ $product->category?->name ?? '-' }}</td>
                    <td style="padding: 16px 24px;">
                        <div style="font-weight: 600;">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        <div style="font-size: 12px; color: var(--muted); margin-top: 4px;">Stok: {{ $product->stock }}</div>
                    </td>
                    <td style="padding: 16px 24px;">
                        @if($product->status === 'available')
                            <span class="badge badge-success">Tersedia</span>
                        @else
                            <span class="badge badge-danger">Terjual</span>
                        @endif
                    </td>
                    <td style="padding: 16px 24px; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-light btn-sm" title="Edit">
                                <x-icon name="pencil" />
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-sm" style="color: var(--danger); border-color: var(--danger-soft);" title="Hapus">
                                    <x-icon name="trash" />
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 48px; text-align: center;">
                        <div style="color: var(--muted); margin-bottom: 16px;">
                            <x-icon name="archive-box" style="width: 48px; height: 48px;" />
                        </div>
                        <div style="font-size: 16px; font-weight: 600; margin-bottom: 8px;">Tidak ada produk</div>
                        <p class="text-muted">Belum ada produk yang ditambahkan atau tidak ada yang sesuai pencarian.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($products->hasPages())
    <div style="padding: 24px; border-top: 1px solid var(--line);">
        {{ $products->links('partials.pagination') }}
    </div>
    @endif
</div>
@endsection
