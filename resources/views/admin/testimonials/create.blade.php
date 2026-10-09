@extends('layouts.admin')

@section('title', 'Tambah Testimoni')

@section('content')
<div style="max-width: 600px;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('admin.testimonials.index') }}" class="text-muted" style="font-size: 14px;">
            <x-icon name="arrow-left" style="width:14px;height:14px;display:inline;vertical-align:middle;"/> 
            Kembali ke Daftar Testimoni
        </a>
    </div>

    <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="card" style="padding: 32px;">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <div class="form-group">
                <label class="form-label" for="name">Nama Pelanggan</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="rating">Rating (1-5)</label>
                <input type="number" id="rating" name="rating" class="form-control @error('rating') is-invalid @enderror" value="{{ old('rating', 5) }}" min="1" max="5" required>
                @error('rating')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-label" for="message">Isi Ulasan / Testimoni</label>
            <textarea id="message" name="message" class="form-control @error('message') is-invalid @enderror" rows="4" required>{{ old('message') }}</textarea>
            @error('message')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-label" for="photo">Foto Bukti Testimoni (Screenshot Chat / Bukti Transaksi / Foto Produk)</label>
            <div id="photoPreviewContainer" style="display: none; margin-bottom: 12px;">
                <div style="width: 180px; aspect-ratio: 4/5; border-radius: 12px; overflow: hidden; background: var(--surface-2); border: 2px dashed var(--line); position: relative;">
                    <img id="photoPreviewImg" src="" alt="Pratinjau Foto" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>
            <input type="file" id="photo" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*" onchange="previewTestimonialPhoto(this)">
            <span style="font-size: 12px; color: var(--muted); display: block; margin-top: 4px;">Opsional. Format: JPG, PNG, WEBP (maks. 5 MB). Foto akan ditampilkan proporsional seukuran kartu produk agar bukti testimoni terlihat meyakinkan.</span>
            @error('photo')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        
        <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-check" style="display: flex; gap: 8px; align-items: center; cursor: pointer;">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <span style="font-weight: 500;">Tampilkan di website</span>
            </label>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px;">
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-light">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Testimoni</button>
        </div>
    </form>
</div>

<script>
function previewTestimonialPhoto(input) {
    const container = document.getElementById('photoPreviewContainer');
    const img = document.getElementById('photoPreviewImg');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        container.style.display = 'none';
        img.src = '';
    }
}
</script>
@endsection
