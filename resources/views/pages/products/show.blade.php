@extends('layouts.app')

@section('title', $product->name)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($product->description), 155))
@section('og_image', $product->image_url)
@section('og_type', 'product')

@php
    $galleryList = collect();
    if ($product->image) {
        $galleryList->push($product->image_url);
    }
    foreach ($product->images as $extraImg) {
        $galleryList->push($extraImg->url);
    }
    $mainImageSrc = $galleryList->first() ?? asset('images/no-image.svg');
    $isSold = $product->isSold();
@endphp

@section('content')
<div class="container" style="padding: 40px 20px;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('products.index') }}" class="text-muted" style="font-size: 14px; text-decoration: none;">
            <x-icon name="arrow-left" style="width:14px;height:14px;display:inline;vertical-align:middle;margin-right:4px;"/> 
            Kembali ke Katalog
        </a>
    </div>

    <div class="product-layout-grid">
        
        {{-- Product Images Gallery (Swipeable Slider) --}}
        <div class="product-gallery">
            <div class="gallery-slider-wrap">
                @if($product->is_featured)
                    <div class="badge badge-olive" style="position: absolute; top: 16px; left: 16px; z-index: 10;">Unggulan</div>
                @endif
                @if($isSold)
                    <div class="sold-ribbon" style="z-index: 10;"><span>SOLD OUT</span></div>
                @endif

                @if($galleryList->count() > 1)
                    <div class="gallery-counter" id="galleryCounter">
                        <span id="galleryCurrentIndex">1</span> / {{ $galleryList->count() }}
                    </div>

                    <button type="button" class="gallery-nav-btn prev" id="galleryPrevBtn" aria-label="Foto sebelumnya" onclick="navigateGallery(-1)">
                        <x-icon name="arrow-left" style="width: 18px; height: 18px;" />
                    </button>
                    <button type="button" class="gallery-nav-btn next" id="galleryNextBtn" aria-label="Foto berikutnya" onclick="navigateGallery(1)">
                        <x-icon name="arrow-right" style="width: 18px; height: 18px;" />
                    </button>
                @endif

                <div class="gallery-track" id="galleryTrack" tabindex="0" aria-label="Galeri foto produk">
                    @forelse($galleryList as $index => $imgSrc)
                        <div class="gallery-slide" data-slide-index="{{ $index }}">
                            <img src="{{ $imgSrc }}" alt="{{ $product->name }} - Foto {{ $index + 1 }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                        </div>
                    @empty
                        <div class="gallery-slide" data-slide-index="0">
                            <img src="{{ asset('images/no-image.svg') }}" alt="{{ $product->name }}">
                        </div>
                    @endforelse
                </div>
            </div>
            
            @if($galleryList->count() > 1)
            <div class="gallery-thumbs" id="galleryThumbs">
                @foreach($galleryList as $index => $imgSrc)
                    <button type="button" 
                            data-gallery-thumb="{{ $imgSrc }}"
                            data-thumb-index="{{ $index }}"
                            onclick="selectGalleryImage(this, {{ $index }})" 
                            class="thumbnail-btn {{ $index === 0 ? 'is-active' : '' }}" 
                            aria-label="Lihat foto {{ $index + 1 }}">
                        <img src="{{ $imgSrc }}" alt="Foto detail {{ $index + 1 }}">
                    </button>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Product Info --}}
        <div class="product-details">
            <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 12px; flex-wrap: wrap;">
                @if($product->category)
                    <a href="{{ route('categories.show', $product->category) }}" class="badge" style="text-decoration: none;">
                        {{ $product->category->name }}
                    </a>
                @endif
                <span class="badge badge-olive">{{ $product->condition }}</span>
            </div>

            <h1 style="margin-bottom: 12px; font-size: 28px; line-height: 1.3;">{{ $product->name }}</h1>
            
            <div style="font-family: var(--font-display); font-size: 32px; font-weight: 700; color: var(--accent); margin-bottom: 24px;">
                {{ $product->formatted_price }}
            </div>

            {{-- Spec details (size, color, stock, status) --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 12px; margin-bottom: 24px; padding: 16px; background: var(--surface-2); border-radius: 12px; border: 1px solid var(--line);">
                <div>
                    <div style="font-size: 12px; color: var(--muted); margin-bottom: 2px;">Ukuran (Size)</div>
                    <strong style="font-size: 14px;">{{ $product->size ?: '-' }}</strong>
                </div>
                <div>
                    <div style="font-size: 12px; color: var(--muted); margin-bottom: 2px;">Warna</div>
                    <strong style="font-size: 14px;">{{ $product->color ?: '-' }}</strong>
                </div>
                <div>
                    <div style="font-size: 12px; color: var(--muted); margin-bottom: 2px;">Kondisi</div>
                    <strong style="font-size: 14px;">{{ $product->condition }}</strong>
                </div>
                <div>
                    <div style="font-size: 12px; color: var(--muted); margin-bottom: 2px;">Status</div>
                    <strong style="font-size: 14px; {{ !$isSold ? 'color: #1E9E53;' : 'color: var(--danger);' }}">
                        {{ !$isSold ? 'Tersedia' : 'Terjual' }}
                    </strong>
                </div>
            </div>

            <div class="content" style="margin-bottom: 32px;">
                <h3 style="font-size: 16px; margin-bottom: 10px;">Deskripsi & Kondisi</h3>
                <div style="color: var(--ink-2); line-height: 1.7; font-size: 15px; white-space: pre-line;">{{ $product->description ?: 'Tidak ada deskripsi tambahan untuk produk ini.' }}</div>
            </div>

            @if(!$isSold)
                <a href="{{ $product->whatsappUrl($store) }}" 
                   class="btn btn-wa btn-lg btn-block" 
                   target="_blank" 
                   rel="noopener"
                   style="display: flex; justify-content: center; align-items: center; gap: 10px; font-weight: 600; text-decoration: none;">
                    <x-icon name="whatsapp" style="width: 22px; height: 22px;" />
                    Beli via WhatsApp
                </a>
            @else
                <button class="btn btn-lg btn-block" disabled style="display: flex; justify-content: center; align-items: center; gap: 10px; opacity: 0.6; cursor: not-allowed;">
                    <x-icon name="archive-box" style="width: 20px; height: 20px;" />
                    Barang Sudah Terjual (Sold Out)
                </button>
            @endif

            <div style="margin-top: 24px; text-align: center; font-size: 13px; color: var(--muted); display: flex; justify-content: center; align-items: center; gap: 6px;">
                <x-icon name="check-circle" style="width: 16px; height: 16px; color: var(--olive);" />
                Barang thrift dikurasi teliti, siap pakai, & fast response via WhatsApp
            </div>
        </div>

    </div>

    {{-- Related Products --}}
    @if($related->count() > 0)
    <div style="margin-top: 64px; padding-top: 40px; border-top: 1px solid var(--line);">
        <h2 style="margin-bottom: 24px; font-size: 22px;">Produk Serupa</h2>
        <div class="grid grid-products">
            @foreach($related as $rel)
                @include('partials.product-card', ['product' => $rel])
            @endforeach
        </div>
    </div>
    @endif
</div>

<style>
.product-layout-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 48px;
    align-items: start;
}

.gallery-thumbs {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-top: 14px;
}

.thumbnail-btn {
    width: 100%;
    aspect-ratio: 1 / 1;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid var(--line);
    cursor: pointer;
    padding: 0;
    background: var(--surface-2);
    position: relative;
    transition: all 0.2s ease;
    opacity: 0.78;
    display: block;
}

.thumbnail-btn img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.thumbnail-btn:hover {
    opacity: 1;
    border-color: var(--accent);
    transform: translateY(-2px);
}

.gallery-slider-wrap {
    position: relative;
    border-radius: var(--radius);
    overflow: hidden;
    background: var(--surface-2);
    margin-bottom: 16px;
    border: 1px solid var(--line);
    touch-action: pan-y pinch-zoom;
}

.gallery-track {
    display: flex;
    overflow-x: auto;
    overflow-y: hidden;
    scroll-snap-type: x mandatory;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    width: 100%;
    margin: 0;
    padding: 0;
    user-select: none;
    -webkit-user-select: none;
    cursor: grab;
}

.gallery-track::-webkit-scrollbar {
    display: none;
}

.gallery-track.is-dragging {
    scroll-behavior: auto;
    scroll-snap-type: none;
    cursor: grabbing;
}

.gallery-slide {
    flex: 0 0 100%;
    width: 100%;
    scroll-snap-align: start;
    scroll-snap-stop: always;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--surface-2);
}

.gallery-slide img {
    width: 100%;
    aspect-ratio: 3 / 4;
    object-fit: cover;
    display: block;
    pointer-events: none;
}

.gallery-counter {
    position: absolute;
    bottom: 14px;
    right: 14px;
    z-index: 10;
    background: rgba(26, 22, 19, 0.72);
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .03em;
    padding: 4px 10px;
    border-radius: 999px;
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    pointer-events: none;
}

.gallery-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    color: var(--ink);
    border: 1px solid var(--line);
    display: grid;
    place-items: center;
    z-index: 10;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
    transition: all 0.2s ease;
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    opacity: 0.85;
}

.gallery-nav-btn:hover {
    background: #ffffff;
    opacity: 1;
    transform: translateY(-50%) scale(1.06);
}

.gallery-nav-btn.prev {
    left: 12px;
}

.gallery-nav-btn.next {
    right: 12px;
}

.thumbnail-btn:focus-visible {
    outline: none;
    border-color: var(--accent);
}

.thumbnail-btn.is-active {
    border-color: var(--accent) !important;
    opacity: 1 !important;
    box-shadow: 0 0 0 2px rgba(184, 80, 42, 0.25);
}

@media (max-width: 860px) {
    .product-layout-grid {
        grid-template-columns: 1fr !important;
        gap: 28px !important;
    }
}

@media (max-width: 640px) {
    .gallery-nav-btn {
        width: 32px;
        height: 32px;
        opacity: 0.75;
    }
}

@media (max-width: 480px) {
    .gallery-thumbs {
        grid-template-columns: repeat(4, 1fr) !important;
        gap: 8px !important;
        margin-top: 10px !important;
    }
    .thumbnail-btn {
        border-radius: 8px !important;
    }
}
</style>

<script>
let currentGalleryIndex = 0;

function selectGalleryImage(btn, indexOrSrc) {
    const track = document.getElementById('galleryTrack');
    if (!track) return;
    
    let targetIndex = 0;
    if (typeof indexOrSrc === 'number') {
        targetIndex = indexOrSrc;
    } else {
        const thumbs = Array.from(document.querySelectorAll('.gallery-thumbs .thumbnail-btn'));
        targetIndex = thumbs.indexOf(btn);
        if (targetIndex < 0) targetIndex = 0;
    }

    goToSlide(targetIndex);
}

function goToSlide(index) {
    const track = document.getElementById('galleryTrack');
    if (!track) return;
    const slides = track.querySelectorAll('.gallery-slide');
    if (!slides.length) return;

    const clampedIndex = Math.max(0, Math.min(slides.length - 1, index));
    currentGalleryIndex = clampedIndex;
    
    const targetLeft = slides[clampedIndex].offsetLeft;
    track.scrollTo({ left: targetLeft, behavior: 'smooth' });
    updateActiveThumb(clampedIndex);
}

function navigateGallery(direction) {
    const track = document.getElementById('galleryTrack');
    if (!track) return;
    const slides = track.querySelectorAll('.gallery-slide');
    if (!slides.length) return;

    const newIndex = Math.max(0, Math.min(slides.length - 1, currentGalleryIndex + direction));
    goToSlide(newIndex);
}

function updateActiveThumb(index) {
    currentGalleryIndex = index;
    const counterEl = document.getElementById('galleryCurrentIndex');
    if (counterEl) {
        counterEl.textContent = (index + 1);
    }
    const thumbs = document.querySelectorAll('.gallery-thumbs .thumbnail-btn');
    thumbs.forEach((thumb, i) => {
        if (i === index) {
            thumb.classList.add('is-active');
            thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        } else {
            thumb.classList.remove('is-active');
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('galleryTrack');
    if (!track) return;

    let scrollTimeout = null;

    track.addEventListener('scroll', function() {
        if (scrollTimeout) clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            const slideWidth = track.clientWidth || 1;
            const scrollPos = track.scrollLeft;
            const newIndex = Math.round(scrollPos / slideWidth);
            if (newIndex !== currentGalleryIndex) {
                updateActiveThumb(newIndex);
            }
        }, 50);
    }, { passive: true });

    // Drag-to-scroll support for mouse (desktop touch simulation)
    let isDown = false;
    let startX = 0;
    let scrollLeftStart = 0;
    let hasMoved = false;

    track.addEventListener('mousedown', function(e) {
        isDown = true;
        hasMoved = false;
        track.classList.add('is-dragging');
        startX = e.pageX - track.offsetLeft;
        scrollLeftStart = track.scrollLeft;
    });

    window.addEventListener('mouseup', function() {
        if (!isDown) return;
        isDown = false;
        track.classList.remove('is-dragging');
        if (hasMoved) {
            const slideWidth = track.clientWidth || 1;
            const newIndex = Math.round(track.scrollLeft / slideWidth);
            goToSlide(newIndex);
        }
    });

    track.addEventListener('mousemove', function(e) {
        if (!isDown) return;
        e.preventDefault();
        hasMoved = true;
        const x = e.pageX - track.offsetLeft;
        const walk = (x - startX);
        track.scrollLeft = scrollLeftStart - walk;
    });

    // Keyboard navigation (left / right arrows)
    track.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
            navigateGallery(-1);
        } else if (e.key === 'ArrowRight') {
            navigateGallery(1);
        }
    });
});
</script>
@endsection
