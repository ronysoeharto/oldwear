@extends('layouts.app')

@section('title', 'Masuk - Admin')

@section('content')
<section class="section" style="min-height: calc(100vh - 200px); display: flex; align-items: center; padding: 40px 0;">
    <div class="container" style="max-width: 440px;">
        <div class="card" style="padding: 32px;">
            <div style="text-align: center; margin-bottom: 32px;">
                <h1 style="font-size: 24px; margin-bottom: 8px;">Masuk</h1>
                <p class="text-muted">Gunakan kredensial admin Anda</p>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-check" style="display: flex; gap: 8px; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span style="font-size: 14px;">Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">Masuk</button>
            </form>

            <div style="margin: 24px 0; display: flex; align-items: center; text-align: center; color: var(--muted); font-size: 13px;">
                <div style="flex: 1; height: 1px; background: var(--line);"></div>
                <span style="padding: 0 12px; font-weight: 500;">atau</span>
                <div style="flex: 1; height: 1px; background: var(--line);"></div>
            </div>

            <a href="{{ route('auth.google.redirect') }}" class="btn btn-light btn-block btn-lg" style="display: flex; align-items: center; justify-content: center; gap: 10px; font-weight: 600; text-decoration: none; border: 1px solid var(--line);">
                <x-icon name="google" style="width: 20px; height: 20px;" />
                <span>Masuk dengan Google</span>
            </a>
        </div>
    </div>
</section>
@endsection
