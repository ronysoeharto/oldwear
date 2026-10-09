@extends('layouts.admin')

@section('title', 'Banner Toko (Halaman Depan)')

@section('content')
<div style="max-width: 800px; display: grid; gap: 32px;">

    {{-- Banner Preview & Form --}}
    <form action="{{ route('admin.banner.update') }}" method="POST" enctype="multipart/form-data" class="card" style="padding: 32px;">
        @csrf
        @method('PUT')
        
        <div style="display: flex; gap: 16px; align-items: flex-start; margin-bottom: 24px;">
            <div style="width: 48px; height: 48px; background: var(--accent-soft); color: var(--accent); border-radius: 12px; display: grid; place-items: center; flex-shrink: 0;">
                <x-icon name="photo" style="width: 24px; height: 24px;" />
            </div>
            <div>
                <h2 style="font-size: 18px; margin: 0 0 4px;">Kelola Banner Utama</h2>
                <p class="text-muted" style="margin: 0; font-size: 14px;">Upload foto banner toko Anda sendiri untuk ditampilkan di samping judul halaman depan (Hero section).</p>
            </div>
        </div>

        {{-- Current Banner Preview --}}
        <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-label">Banner Saat Ini</label>
            @if($store->banner)
                <div style="position: relative; border-radius: var(--radius); overflow: hidden; border: 1px solid var(--line); max-width: 420px; aspect-ratio: 4/4.4; background: var(--surface-2); margin-bottom: 12px;">
                    <img src="{{ $store->banner_url }}" alt="Banner Toko" style="width: 100%; height: 100%; object-fit: cover;">
                    @if($store->banner_badge)
                    <div style="position: absolute; bottom: 16px; left: 16px; background: var(--surface); padding: 10px 14px; border-radius: 12px; box-shadow: var(--shadow); display: flex; align-items: center; gap: 8px;">
                        <x-icon name="check-circle" style="width: 18px; height: 18px; color: var(--olive);" />
                        <div>
                            <strong style="display: block; font-size: 13px;">{{ $store->banner_badge }}</strong>
                            @if($store->banner_text)<span class="text-muted" style="font-size: 11px;">{{ $store->banner_text }}</span>@endif
                        </div>
                    </div>
                    @endif
                </div>
            @else
                <div style="padding: 32px; background: var(--surface-2); border-radius: var(--radius); text-align: center; border: 1px dashed var(--line); max-width: 420px; margin-bottom: 12px;">
                    <x-icon name="photo" style="width: 40px; height: 40px; color: var(--muted); margin-bottom: 8px;" />
                    <p class="text-muted" style="margin: 0; font-size: 14px;">Belum ada banner kustom. Halaman depan menggunakan banner bawaan.</p>
                </div>
            @endif
        </div>

        {{-- Upload New Banner --}}
        <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-label" for="banner">Upload Foto Banner Baru</label>
            <input type="file" id="banner" name="banner" class="form-control @error('banner') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
            <div class="text-muted" style="font-size: 13px; margin-top: 4px;">Pilih foto pakaian toko Anda, display rak toko, atau foto katalog vintage. Format: JPG, PNG, WEBP (Maksimal 5MB).</div>
            @error('banner')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        {{-- Badge text options --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px;">
            <div class="form-group">
                <label class="form-label" for="banner_badge">Teks Badge (Opsional)</label>
                <input type="text" id="banner_badge" name="banner_badge" class="form-control @error('banner_badge') is-invalid @enderror" value="{{ old('banner_badge', $store->banner_badge ?? '') }}" placeholder="Contoh: Koleksi Pilihan">
                <div class="text-muted" style="font-size: 12px; margin-top: 4px;">Teks sorotan di atas banner. Kosongkan jika tidak ingin menampilkan badge.</div>
                @error('banner_badge')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="banner_text">Keterangan Badge (Opsional)</label>
                <input type="text" id="banner_text" name="banner_text" class="form-control @error('banner_text') is-invalid @enderror" value="{{ old('banner_text', $store->banner_text ?? '') }}" placeholder="Contoh: Vintage & Classic">
                <div class="text-muted" style="font-size: 12px; margin-top: 4px;">Subteks kecil di bawah teks badge.</div>
                @error('banner_text')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; align-items: center;">
            <button type="submit" class="btn btn-primary">Simpan Banner</button>
        </div>
    </form>

    {{-- Delete Banner option if exists --}}
    @if($store->banner)
    <div class="card" style="padding: 24px; border-color: var(--danger-soft);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div>
                <h3 style="font-size: 16px; margin: 0 0 4px; color: var(--danger);">Hapus Banner Kustom</h3>
                <p class="text-muted" style="margin: 0; font-size: 13px;">Hapus banner yang Anda unggah dan kembalikan ke tampilan default.</p>
            </div>
            <form action="{{ route('admin.banner.destroy') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus banner ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                    <x-icon name="trash" /> Hapus Banner
                </button>
            </form>
        </div>
    </div>
    @endif

</div>
@endsection
