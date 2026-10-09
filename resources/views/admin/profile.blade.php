@extends('layouts.admin')

@section('title', 'Profil Admin')

@section('content')
<div style="max-width: 600px; display: grid; gap: 32px;">
    
    <form action="{{ route('admin.profile.update') }}" method="POST" class="card" style="padding: 32px;">
        @csrf
        @method('PUT')
        
        <h2 style="font-size: 18px; margin-bottom: 24px; border-bottom: 1px solid var(--line); padding-bottom: 12px;">Informasi Pribadi</h2>
        
        <div class="form-group">
            <label class="form-label" for="name">Nama Lengkap</label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-label" for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div style="display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary">Simpan Profil</button>
        </div>
    </form>

    <form action="{{ route('admin.profile.password') }}" method="POST" class="card" style="padding: 32px;">
        @csrf
        @method('PUT')
        
        <h2 style="font-size: 18px; margin-bottom: 24px; border-bottom: 1px solid var(--line); padding-bottom: 12px;">Ganti Password</h2>
        
        <div class="form-group">
            <label class="form-label" for="current_password">Password Saat Ini</label>
            <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
            @error('current_password')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password Baru</label>
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        </div>

        <div style="display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary">Ganti Password</button>
        </div>
    </form>
    
</div>
@endsection
