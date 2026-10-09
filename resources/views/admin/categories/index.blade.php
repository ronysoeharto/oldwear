@extends('layouts.admin')

@section('title', 'Kelola Kategori')

@section('content')
<div class="card">
    <div style="padding: 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-size: 18px; margin: 0;">Daftar Kategori</h2>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <x-icon name="plus" /> Tambah Kategori
        </a>
    </div>

    <div class="table-responsive">
        <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--surface-2);">
                    <th style="padding: 12px 24px; font-size: 13px; color: var(--muted); font-weight: 600;">Nama Kategori</th>
                    <th style="padding: 12px 24px; font-size: 13px; color: var(--muted); font-weight: 600;">Slug</th>
                    <th style="padding: 12px 24px; font-size: 13px; color: var(--muted); font-weight: 600;">Jumlah Produk</th>
                    <th style="padding: 12px 24px; font-size: 13px; color: var(--muted); font-weight: 600; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr style="border-bottom: 1px solid var(--line);">
                    <td style="padding: 16px 24px; font-weight: 600;">{{ $category->name }}</td>
                    <td style="padding: 16px 24px; color: var(--muted); font-family: monospace;">{{ $category->slug }}</td>
                    <td style="padding: 16px 24px;">{{ $category->products_count }} produk</td>
                    <td style="padding: 16px 24px; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-light btn-sm" title="Edit">
                                <x-icon name="pencil" />
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini? Semua produk di dalamnya tidak akan memiliki kategori.');" style="display: inline;">
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
                    <td colspan="4" style="padding: 48px; text-align: center;">
                        <div style="color: var(--muted); margin-bottom: 16px;">
                            <x-icon name="tag" style="width: 48px; height: 48px;" />
                        </div>
                        <div style="font-size: 16px; font-weight: 600; margin-bottom: 8px;">Tidak ada kategori</div>
                        <p class="text-muted">Belum ada kategori yang ditambahkan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
