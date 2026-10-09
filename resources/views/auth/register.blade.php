@extends('layouts.app')

@section('title', 'Daftar - Admin')

@section('content')
<section class="section" style="min-height: calc(100vh - 200px); display: flex; align-items: center; padding: 40px 0;">
    <div class="container" style="max-width: 440px;">
        <div class="card" style="padding: 32px;">
            <div style="text-align: center; margin-bottom: 32px;">
                <h1 style="font-size: 24px; margin-bottom: 8px;">Daftar Admin</h1>
                <p class="text-muted">Buat akun admin baru</p>
            </div>

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">Daftar</button>
            </form>

            <div style="margin: 24px 0; display: flex; align-items: center; text-align: center; color: var(--muted); font-size: 13px;">
                <div style="flex: 1; height: 1px; background: var(--line);"></div>
                <span style="padding: 0 12px; font-weight: 500;">atau</span>
                <div style="flex: 1; height: 1px; background: var(--line);"></div>
            </div>

            <a href="{{ route('auth.google.redirect') }}" class="btn btn-light btn-block btn-lg" style="display: flex; align-items: center; justify-content: center; gap: 10px; font-weight: 600; text-decoration: none; border: 1px solid var(--line);">
                <x-icon name="google" style="width: 20px; height: 20px;" />
                <span>Daftar / Masuk dengan Google</span>
            </a>
        </div>
    </div>
</section>
@endsection
