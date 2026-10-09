<article class="testimonial-card {{ $testimonial->photo_url ? 'has-photo' : '' }} reveal">
    @if ($testimonial->photo_url)
        <div class="testimonial-media" onclick="openTestimonialModal('{{ $testimonial->photo_url }}', '{{ addslashes($testimonial->name) }}')" title="Klik untuk memperbesar bukti testimoni">
            <img src="{{ $testimonial->photo_url }}" alt="Bukti testimoni dari {{ $testimonial->name }}" loading="lazy">
            <div class="testimonial-badge">
                <x-icon name="check" style="width: 12px; height: 12px;" />
                <span>Bukti Transaksi</span>
            </div>
            <div class="testimonial-zoom-hint">
                <x-icon name="eye" style="width: 14px; height: 14px;" />
                <span>Perbesar</span>
            </div>
        </div>
    @endif

    <div class="testimonial-body">
        <div class="testimonial-meta">
            <x-stars :rating="$testimonial->rating" />
            <span class="testimonial-date">{{ $testimonial->created_at?->translatedFormat('d M Y') }}</span>
        </div>

        <p class="testimonial-msg">"{{ $testimonial->message }}"</p>

        <div class="testimonial-author">
            <div class="avatar">{{ $testimonial->initials }}</div>
            <div>
                <strong>{{ $testimonial->name }}</strong>
                <span>Pembeli Terverifikasi</span>
            </div>
        </div>
    </div>
</article>
