@extends('layouts.admin')

@section('title', 'Profil Toko')

@section('content')
<div style="max-width: 800px;">
    <form action="{{ route('admin.store-profile.update') }}" method="POST" enctype="multipart/form-data" class="card" style="padding: 32px;">
        @csrf
        @method('PUT')
        
        <h2 style="font-size: 18px; margin-bottom: 24px; border-bottom: 1px solid var(--line); padding-bottom: 12px;">Identitas Toko</h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <div class="form-group">
                <label class="form-label" for="store_name">Nama Toko <span style="color:var(--danger)">*</span></label>
                <input type="text" id="store_name" name="store_name" class="form-control @error('store_name') is-invalid @enderror" value="{{ old('store_name', $store->store_name) }}" required>
                @error('store_name')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="tagline">Tagline / Slogan Singkat</label>
                <input type="text" id="tagline" name="tagline" class="form-control @error('tagline') is-invalid @enderror" value="{{ old('tagline', $store->tagline) }}">
                @error('tagline')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-label" for="description">Deskripsi Lengkap Toko</label>
            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description', $store->description) }}</textarea>
            <div class="text-muted" style="font-size: 13px; margin-top: 4px;">Ceritakan tentang toko Anda, visi misi, atau mengapa pelanggan harus berbelanja di sini.</div>
            @error('description')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Logo Saat Ini</label>
            @if($store->logo)
                <div style="width: 80px; height: 80px; border-radius: 12px; overflow: hidden; border: 1px solid var(--line); margin-bottom: 16px;">
                    <img src="{{ $store->logo_url }}" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            @else
                <p class="text-muted" style="font-size: 14px;">Belum ada logo.</p>
            @endif

            <label class="form-label" for="logo">Ganti Logo</label>
            <input type="file" id="logo" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
            <div class="text-muted" style="font-size: 13px; margin-top: 4px;">Pilih foto baru jika ingin mengganti logo. Format: JPG, PNG. Maks 1MB.</div>
            @error('logo')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <h2 style="font-size: 18px; margin: 32px 0 24px; border-bottom: 1px solid var(--line); padding-bottom: 12px;">Kontak & Sosial Media</h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 20px;">
            <div class="form-group">
                <label class="form-label" for="whatsapp">Nomor WhatsApp <span style="color:var(--danger)">*</span></label>
                <input type="text" id="whatsapp" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" value="{{ old('whatsapp', $store->whatsapp) }}" placeholder="Contoh: 081234567890" required>
                <div class="text-muted" style="font-size: 12px; margin-top: 4px;">Nomor tujuan pesan untuk pemesanan produk.</div>
                @error('whatsapp')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="whatsapp_greeting">Pesan Pembuka WhatsApp</label>
                <input type="text" id="whatsapp_greeting" name="whatsapp_greeting" class="form-control @error('whatsapp_greeting') is-invalid @enderror" value="{{ old('whatsapp_greeting', $store->whatsapp_greeting) }}" placeholder="Halo, saya ingin membeli produk:">
                <div class="text-muted" style="font-size: 12px; margin-top: 4px;">Pesan awal default pelanggan.</div>
                @error('whatsapp_greeting')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <div class="form-group">
                <label class="form-label" for="email">Email Toko</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $store->email) }}">
                @error('email')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="instagram">Username Instagram</label>
                <input type="text" id="instagram" name="instagram" class="form-control @error('instagram') is-invalid @enderror" value="{{ old('instagram', $store->instagram) }}" placeholder="tanpa @">
                @error('instagram')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-label" for="address">Alamat Toko</label>
            <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="3" placeholder="Contoh: Jl. Diponegoro No. 12, Sleman, Yogyakarta">{{ old('address', $store->address) }}</textarea>
            @error('address')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-label" for="maps_url">Link Google Maps Lokasi Toko (Opsional)</label>
            <div style="display: flex; gap: 8px;">
                <input type="text" id="maps_url" name="maps_url" class="form-control @error('maps_url') is-invalid @enderror" value="{{ old('maps_url', $store->maps_url) }}" placeholder="https://maps.app.goo.gl/... atau https://goo.gl/maps/...">
                @if($store->google_maps_url)
                    <a href="{{ $store->google_maps_url }}" target="_blank" rel="noopener" class="btn btn-light btn-sm" style="flex-shrink: 0;" title="Buka lokasi di Google Maps">
                        <x-icon name="map-pin" /> Buka Maps
                    </a>
                @endif
            </div>
            <div class="text-muted" style="font-size: 12px; margin-top: 4px;">
                Tempelkan tautan lokasi toko dari Google Maps (buka Google Maps &rarr; cari lokasi toko/rumah &rarr; klik Bagikan / Share &rarr; Salin link). Jika dikosongkan, tombol maps di website akan otomatis diarahkan ke pencarian teks Alamat Toko di atas.
            </div>
            @error('maps_url')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        
        <h2 style="font-size: 18px; margin: 32px 0 24px; border-bottom: 1px solid var(--line); padding-bottom: 12px;">Profil Pemilik / Owner (Tentang Kami)</h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 20px;">
            <div class="form-group">
                <label class="form-label" for="owner_name">Nama Pemilik / Owner</label>
                <input type="text" id="owner_name" name="owner_name" class="form-control @error('owner_name') is-invalid @enderror" value="{{ old('owner_name', $store->owner_name) }}" placeholder="Contoh: Ronny Soeharto">
                @error('owner_name')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="owner_role">Peran / Jabatan</label>
                <input type="text" id="owner_role" name="owner_role" class="form-control @error('owner_role') is-invalid @enderror" value="{{ old('owner_role', $store->owner_role) }}" placeholder="Contoh: Founder & Owner">
                @error('owner_role')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-label">Foto Pemilik / Owner</label>
            @if($store->owner_photo)
                <div style="margin-bottom: 12px;" id="currentOwnerPhotoBox">
                    <div style="font-size: 12px; color: var(--muted); margin-bottom: 6px;">Foto Saat Ini:</div>
                    <div style="width: 140px; aspect-ratio: 1/1; border-radius: 16px; overflow: hidden; border: 1px solid var(--line); margin-bottom: 8px; background: var(--surface-2);">
                        <img src="{{ $store->owner_photo_url }}" alt="Foto Pemilik" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--danger); cursor: pointer;">
                        <input type="checkbox" name="remove_owner_photo" value="1"> Hapus foto pemilik saat ini
                    </label>
                </div>
            @endif
            <div id="ownerPhotoPreviewContainer" style="display: none; margin-bottom: 12px;">
                <div style="font-size: 12px; color: var(--accent); margin-bottom: 6px; font-weight: 600;">Pratinjau Foto Baru:</div>
                <div style="width: 140px; aspect-ratio: 1/1; border-radius: 16px; overflow: hidden; border: 2px dashed var(--accent); background: var(--surface-2);">
                    <img id="ownerPhotoPreviewImg" src="" alt="Pratinjau Foto Pemilik" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>
            <input type="file" id="owner_photo" name="owner_photo" class="form-control @error('owner_photo') is-invalid @enderror" accept="image/*" onchange="previewOwnerPhoto(this)">
            <div class="text-muted" style="font-size: 12px; margin-top: 4px;">Pilih foto Anda (owner). Format: JPG, PNG, WEBP (maks. 5 MB). Foto ini akan ditampilkan di halaman Tentang Kami.</div>
            @error('owner_photo')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-label" for="owner_bio">Pesan / Sambutan dari Pemilik (Opsional)</label>
            <textarea id="owner_bio" name="owner_bio" class="form-control @error('owner_bio') is-invalid @enderror" rows="3" placeholder="Pesan singkat atau kutipan dari Anda untuk para pelanggan...">{{ old('owner_bio', $store->owner_bio) }}</textarea>
            @error('owner_bio')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <h2 style="font-size: 18px; margin: 32px 0 24px; border-bottom: 1px solid var(--line); padding-bottom: 12px;">Info Tambahan</h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
            <div class="form-group">
                <label class="form-label" for="founded_year">Tahun Berdiri Toko</label>
                <input type="number" id="founded_year" name="founded_year" class="form-control @error('founded_year') is-invalid @enderror" value="{{ old('founded_year', $store->founded_year) }}" min="1900" max="{{ date('Y') }}">
                @error('founded_year')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary">Simpan Profil Toko</button>
        </div>
    </form>
</div>

<script>
function previewOwnerPhoto(input) {
    const container = document.getElementById('ownerPhotoPreviewContainer');
    const img = document.getElementById('ownerPhotoPreviewImg');
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
