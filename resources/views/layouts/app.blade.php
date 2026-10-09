<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $pageTitle = trim($__env->yieldContent('title'));
        $fullTitle = $pageTitle ? $pageTitle.' — '.$store->store_name : $store->store_name.' — '.($store->tagline ?: 'Thrift Shop');
        $metaDesc = trim($__env->yieldContent('meta_description')) ?: \Illuminate\Support\Str::limit(strip_tags($store->description ?: 'Thrift shop pakaian second hand pilihan.'), 155);
        $ogImage = trim($__env->yieldContent('og_image')) ?: asset('images/hero.jpg');
    @endphp
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ $metaDesc }}">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $store->store_name }}">
    <meta property="og:title" content="{{ $fullTitle }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:locale" content="id_ID">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="theme-color" content="#F6F1EA">

    <link rel="icon" href="{{ $store->logo_url ?? asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    @stack('head')
</head>
<body class="@yield('body_class')">
    @include('partials.preloader')

    <a href="#main" class="sr-only" style="position:absolute;left:-9999px">Lewati ke konten</a>

    @include('partials.navbar')
    @include('partials.flash')

    <main id="main">
        @yield('content')
    </main>

    @include('partials.footer')

    <a href="{{ $store->whatsappLink('Halo '.$store->store_name.', saya ingin bertanya.') }}" class="wa-float" target="_blank" rel="noopener" aria-label="Chat WhatsApp" id="wa-float">
        <x-icon name="whatsapp" />
    </a>

    {{-- Modal Lightbox Testimoni --}}
    <div id="testimonialModal" class="testimonial-lightbox" style="display: none;" onclick="closeTestimonialModal(event)">
        <div class="testimonial-lightbox-content" onclick="event.stopPropagation()">
            <button type="button" class="testimonial-lightbox-close" onclick="closeTestimonialModal()" aria-label="Tutup">
                <x-icon name="close" style="width: 20px; height: 20px;" />
            </button>
            <img id="testimonialModalImg" src="" alt="Bukti Testimoni Penuh">
            <div id="testimonialModalCaption" class="testimonial-lightbox-caption"></div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}" defer></script>
    @stack('scripts')
</body>
</html>
