@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')

@section('content')
<div style="max-width: 840px;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('admin.products.index') }}" class="text-muted" style="font-size: 14px; text-decoration: none;">
            <x-icon name="arrow-left" style="width:14px;height:14px;display:inline;vertical-align:middle;margin-right:4px;" /> 
            Kembali ke Daftar Produk
        </a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="card admin-form-card">
        @csrf
        
        <h2 style="font-size: 18px; margin: 0 0 24px; border-bottom: 1px solid var(--line); padding-bottom: 12px;">Informasi Produk</h2>
        
        <div class="form-group" style="margin-bottom: 20px;">
            <label class="form-label" for="name">Nama Produk <span style="color:var(--danger)">*</span></label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Kaos Vintage Band Rock 90s" required>
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label" for="category_id">Kategori <span style="color:var(--danger)">*</span></label>
                <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="price">Harga (Rp) <span style="color:var(--danger)">*</span></label>
                <input type="number" id="price" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="Contoh: 150000" min="0" required>
                @error('price')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row-3">
            <div class="form-group">
                <label class="form-label" for="condition">Kondisi Barang <span style="color:var(--danger)">*</span></label>
                <select id="condition" name="condition" class="form-control @error('condition') is-invalid @enderror" required>
                    @foreach(\App\Models\Product::CONDITIONS as $cond)
                        <option value="{{ $cond }}" {{ old('condition', 'Sangat Baik') === $cond ? 'selected' : '' }}>{{ $cond }}</option>
                    @endforeach
                </select>
                @error('condition')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="size">Ukuran (Size)</label>
                <input type="text" id="size" name="size" class="form-control @error('size') is-invalid @enderror" value="{{ old('size') }}" placeholder="Contoh: L / XL / 32">
                @error('size')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="color">Warna</label>
                <input type="text" id="color" name="color" class="form-control @error('color') is-invalid @enderror" value="{{ old('color') }}" placeholder="Contoh: Hitam Pudar / Navy">
                @error('color')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label" for="stock">Stok <span style="color:var(--danger)">*</span></label>
                <input type="number" id="stock" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 1) }}" min="0" required>
                <div class="text-muted" style="font-size: 12px; margin-top: 4px;">Untuk produk thrift biasanya stok bernilai 1.</div>
                @error('stock')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="status">Status Penjualan <span style="color:var(--danger)">*</span></label>
                <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                    <option value="available" {{ old('status', 'available') === 'available' ? 'selected' : '' }}>Tersedia (Available)</option>
                    <option value="sold" {{ old('status') === 'sold' ? 'selected' : '' }}>Terjual (Sold)</option>
                </select>
                @error('status')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label class="form-check" style="display: flex; gap: 10px; align-items: center; cursor: pointer;">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                <span style="font-weight: 500;">Tandai sebagai Produk Unggulan (Tampil di Beranda)</span>
            </label>
        </div>

        <div class="form-group" style="margin-bottom: 28px;">
            <label class="form-label" for="description">Deskripsi & Detail Minus</label>
            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="5" placeholder="Jelaskan detail ukuran (panjang x lebar), minus/cacat jika ada, dan keunggulan bahan...">{{ old('description') }}</textarea>
            @error('description')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <h2 style="font-size: 18px; margin: 32px 0 20px; border-bottom: 1px solid var(--line); padding-bottom: 12px;">Foto Produk (Opsional)</h2>

        <div class="form-group" style="margin-bottom: 20px;">
            <label class="form-label" for="image">Foto Utama</label>
            <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
            <div class="text-muted" style="font-size: 13px; margin-top: 4px;">Foto cover produk. Format: JPG, PNG, WEBP (Maksimal 5MB). Boleh dikosongkan jika belum ada foto.</div>
            @error('image')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group" style="margin-bottom: 28px;">
            <label class="form-label" for="gallery">Foto Tambahan / Galeri (Opsional)</label>
            <input type="file" id="gallery" name="gallery[]" class="form-control @error('gallery.*') is-invalid @enderror" accept="image/jpeg,image/png,image/webp" multiple>
            <div class="text-muted" style="font-size: 13px; margin-top: 4px;">Pilih foto detail (tag, kerah, jahitan, minus). Bisa pilih hingga 6 foto sekaligus.</div>
            @error('gallery.*')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="admin-form-actions">
            <a href="{{ route('admin.products.index') }}" class="btn btn-light">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Produk</button>
        </div>
    </form>
</div>
@endsection
