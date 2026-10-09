<header class="navbar">
    <div class="container navbar-inner">
        <a href="{{ route('home') }}" class="brand" id="nav-brand">
            @if ($store->logo_url)
                <img src="{{ $store->logo_url }}" alt="{{ $store->store_name }}">
            @endif
            <span>{{ $store->store_name }}</span>
        </a>

        <button class="nav-toggle" type="button" data-toggle="#nav-menu" aria-controls="nav-menu" aria-expanded="false" aria-label="Buka menu" id="nav-toggle">
            <x-icon name="menu" />
        </button>

        <nav class="nav-menu" id="nav-menu" aria-label="Navigasi utama">
            <ul class="nav-links">
                <li><a href="{{ route('home') }}" @class(['is-active' => request()->routeIs('home')])>Home</a></li>
                <li><a href="{{ route('products.index') }}" @class(['is-active' => request()->routeIs('products.*')])>Produk</a></li>
                <li><a href="{{ route('categories.index') }}" @class(['is-active' => request()->routeIs('categories.*')])>Kategori</a></li>
                <li><a href="{{ route('about') }}" @class(['is-active' => request()->routeIs('about')])>Tentang Kami</a></li>
                <li><a href="{{ route('testimonials') }}" @class(['is-active' => request()->routeIs('testimonials')])>Testimoni</a></li>
                <li><a href="{{ route('contact') }}" @class(['is-active' => request()->routeIs('contact')])>Kontak</a></li>
            </ul>
            <div class="nav-actions">
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm" id="nav-dashboard">Dashboard</a>
                    @else
                        <span class="text-muted" style="font-size:14px">Hai, {{ \Illuminate\Support\Str::words(auth()->user()->name, 1, '') }}</span>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="inline-form">
                        @csrf
                        <button type="submit" class="btn btn-ghost btn-sm" id="nav-logout">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost btn-sm" id="nav-login">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm" id="nav-register">Register</a>
                @endauth
            </div>
        </nav>
    </div>
</header>
