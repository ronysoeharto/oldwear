<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <a href="{{ route('home') }}" class="brand">{{ $store->store_name }}</a>
                <p style="max-width:340px;font-size:14px">{{ \Illuminate\Support\Str::limit($store->description, 160) }}</p>
                <a href="{{ $store->whatsappLink('Halo '.$store->store_name.', saya ingin bertanya.') }}" target="_blank" rel="noopener" class="btn btn-wa btn-sm" id="footer-wa">
                    <x-icon name="whatsapp" /> Chat WhatsApp
                </a>
            </div>
            <div>
                <h4>Navigasi</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('products.index') }}">Produk</a></li>
                    <li><a href="{{ route('about') }}">Tentang Kami</a></li>
                    <li><a href="{{ route('testimonials') }}">Testimoni</a></li>
                    <li><a href="{{ route('contact') }}">Kontak</a></li>
                </ul>
            </div>
            <div>
                <h4>Kategori</h4>
                <ul>
                    @forelse ($footerCategories as $cat)
                        <li><a href="{{ route('categories.show', $cat) }}">{{ $cat->name }}</a></li>
                    @empty
                        <li class="text-muted">Belum ada kategori</li>
                    @endforelse
                </ul>
            </div>
            <div>
                <h4>Kontak</h4>
                <ul class="footer-contact">
                    <li><x-icon name="whatsapp" /><a href="{{ $store->whatsappLink() }}" target="_blank" rel="noopener">+{{ $store->whatsapp }}</a></li>
                    @if ($store->instagram)
                        <li><x-icon name="instagram" /><a href="{{ $store->instagram_url }}" target="_blank" rel="noopener">{{ $store->instagram_handle }}</a></li>
                    @endif
                    @if ($store->email)
                        <li><x-icon name="mail" /><a href="mailto:{{ $store->email }}">{{ $store->email }}</a></li>
                    @endif
                    @if ($store->address)
                        <li>
                            <x-icon name="map" />
                            @if ($store->google_maps_url)
                                <a href="{{ $store->google_maps_url }}" target="_blank" rel="noopener" title="Buka di Google Maps">
                                    <span>{{ $store->address }}</span>
                                </a>
                            @else
                                <span>{{ $store->address }}</span>
                            @endif
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} {{ $store->store_name }}. All rights reserved.</span>
            <span>{{ $store->tagline }}</span>
        </div>
    </div>
</footer>
