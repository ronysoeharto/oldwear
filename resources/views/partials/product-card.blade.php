@php $sold = $product->isSold(); @endphp
<article @class(['product-card', 'reveal', 'is-sold' => $sold])>
    <a href="{{ route('products.show', $product) }}" class="product-media" aria-label="{{ $product->name }}">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" width="300" height="400">
        @if ($sold)
            <div class="sold-ribbon"><span>SOLD OUT</span></div>
        @else
            <span class="badge badge-success">Tersedia</span>
        @endif
    </a>
    <div class="product-body">
        <div class="product-meta">
            <span>{{ $product->category->name ?? '-' }}</span>
            @if ($product->size)<span>Size {{ $product->size }}</span>@endif
        </div>
        <h3 class="product-title"><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h3>
        <div class="product-price">{{ $product->formatted_price }}</div>
        <div class="product-tags">
            <span class="badge badge-olive">{{ $product->condition }}</span>
        </div>
        <div class="spacer"></div>
        <a href="{{ route('products.show', $product) }}" class="btn btn-outline btn-sm btn-block" id="detail-{{ $product->slug }}">Lihat Detail</a>
    </div>
</article>
