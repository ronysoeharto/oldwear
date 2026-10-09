<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Admin {{ $store->store_name }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    @stack('head')
</head>
<body style="background: var(--surface-2); min-height: 100vh;">
    @include('partials.preloader')
    
    <div class="admin-layout">
        
        {{-- Mobile Backdrop --}}
        <div class="admin-backdrop" id="admin-backdrop" aria-hidden="true"></div>

        {{-- Sidebar --}}
        <aside class="admin-sidebar" id="admin-sidebar" aria-label="Sidebar Admin">
            <div class="admin-sidebar-header">
                <a href="{{ route('home') }}" class="brand" target="_blank" style="font-family: var(--font-display); font-size: 19px; text-decoration: none;">
                    {{ $store->store_name }}
                </a>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 11px; font-weight: 700; background: var(--accent); color: white; padding: 2px 8px; border-radius: 6px; letter-spacing: .05em;">ADMIN</span>
                    <button type="button" class="admin-sidebar-close" id="admin-sidebar-close" aria-label="Tutup menu">
                        <x-icon name="close" />
                    </button>
                </div>
            </div>
            
            <nav style="flex: 1; padding: 20px 14px; display: flex; flex-direction: column; gap: 4px; overflow-y: auto;">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                    <x-icon name="chart-bar" style="width: 20px; height: 20px;" />
                    <span>Dashboard</span>
                </a>
                
                <div style="margin: 20px 0 8px 12px; font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .08em;">Katalog</div>
                <a href="{{ route('admin.products.index') }}" class="admin-nav-item {{ request()->routeIs('admin.products.*') ? 'is-active' : '' }}">
                    <x-icon name="archive-box" style="width: 20px; height: 20px;" />
                    <span>Produk</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="admin-nav-item {{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}">
                    <x-icon name="tag" style="width: 20px; height: 20px;" />
                    <span>Kategori</span>
                </a>

                <div style="margin: 20px 0 8px 12px; font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .08em;">Konten</div>
                <a href="{{ route('admin.testimonials.index') }}" class="admin-nav-item {{ request()->routeIs('admin.testimonials.*') ? 'is-active' : '' }}">
                    <x-icon name="chat-bubble-left-ellipsis" style="width: 20px; height: 20px;" />
                    <span>Testimoni</span>
                </a>

                <div style="margin: 20px 0 8px 12px; font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .08em;">Pengaturan</div>
                <a href="{{ route('admin.store-profile.edit') }}" class="admin-nav-item {{ request()->routeIs('admin.store-profile.edit') ? 'is-active' : '' }}">
                    <x-icon name="building-storefront" style="width: 20px; height: 20px;" />
                    <span>Profil Toko</span>
                </a>
                <a href="{{ route('admin.banner.edit') }}" class="admin-nav-item {{ request()->routeIs('admin.banner.*') ? 'is-active' : '' }}">
                    <x-icon name="photo" style="width: 20px; height: 20px;" />
                    <span>Banner Toko</span>
                </a>
                <a href="{{ route('admin.profile.edit') }}" class="admin-nav-item {{ request()->routeIs('admin.profile.edit') ? 'is-active' : '' }}">
                    <x-icon name="user" style="width: 20px; height: 20px;" />
                    <span>Profil Admin</span>
                </a>
            </nav>

            <div style="padding: 16px 14px; border-top: 1px solid var(--line);">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="admin-nav-item" style="width: 100%; border: none; background: transparent; cursor: pointer; color: var(--danger);">
                        <x-icon name="arrow-right-on-rectangle" style="width: 20px; height: 20px; color: var(--danger);" />
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="admin-main">
            <header class="admin-header">
                <div class="admin-header-left">
                    <button type="button" class="admin-menu-toggle" id="admin-menu-toggle" aria-controls="admin-sidebar" aria-expanded="false" aria-label="Buka navigasi admin">
                        <x-icon name="menu" />
                    </button>
                    <h1 class="admin-header-title">@yield('title')</h1>
                </div>
                <div class="admin-header-actions">
                    <a href="{{ route('home') }}" target="_blank" class="btn btn-light btn-sm" style="text-decoration: none;">
                        <x-icon name="arrow-top-right-on-square" style="width: 14px; height: 14px;" />
                        <span class="admin-action-text">Lihat Website</span>
                    </a>
                    <div style="width: 1px; height: 24px; background: var(--line);"></div>
                    <span class="admin-user-name" style="font-size: 14px; font-weight: 600; color: var(--ink);">{{ auth()->user()->name }}</span>
                </div>
            </header>

            <div class="admin-content-wrap">
                @include('partials.flash')
                @yield('content')
            </div>
        </main>
    </div>

    <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}" defer></script>
    @stack('scripts')
</body>
</html>
