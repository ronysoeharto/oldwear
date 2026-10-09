@extends('layouts.admin')

@section('title', 'Tambah Kategori')

@section('content')
<div style="max-width: 600px;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('admin.categories.index') }}" class="text-muted" style="font-size: 14px;">
            <x-icon name="arrow-left" style="width:14px;height:14px;display:inline;vertical-align:middle;"/> 
            Kembali ke Daftar Kategori
        </a>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="card" style="padding: 32px;">
        @csrf
        
        <div class="form-group">
            <label class="form-label" for="name">Nama Kategori</label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-label" for="description">Deskripsi (Opsional)</label>
            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
            @error('description')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-label" for="image">Gambar Kategori (Opsional)</label>
            <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
            <span style="font-size: 12px; color: var(--muted); display: block; margin-top: 4px;">Format: JPG, PNG, WEBP (maks. 2 MB).</span>
            @error('image')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px;">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-light">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Kategori</button>
        </div>
    </form>
</div>
@endsection
