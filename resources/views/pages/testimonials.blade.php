@extends('layouts.app')

@section('title', 'Testimoni')

@section('content')
<div class="page-header" style="background: var(--surface); border-bottom: 1px solid var(--line); padding: 60px 0;">
    <div class="container text-center">
        <h1 style="margin-bottom: 16px;">Kata Mereka</h1>
        <p class="text-muted" style="max-width: 500px; margin: 0 auto;">
            Ulasan dan pengalaman berbelanja dari pelanggan setia {{ $store->store_name }}.
        </p>
    </div>
</div>

<section class="section">
    <div class="container">
        @if($testimonials->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">
                    <x-icon name="chat-bubble-left-ellipsis" />
                </div>
                <h3>Belum Ada Testimoni</h3>
                <p class="text-muted">Belum ada ulasan dari pelanggan saat ini.</p>
            </div>
        @else
            <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));">
                @foreach($testimonials as $testimonial)
                    @include('partials.testimonial-card', ['testimonial' => $testimonial])
                @endforeach
            </div>

            <div style="margin-top: 40px;">
                {{ $testimonials->links('partials.pagination') }}
            </div>
        @endif
    </div>
</section>
@endsection
