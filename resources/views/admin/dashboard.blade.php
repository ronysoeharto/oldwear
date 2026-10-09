@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 40px;">
        
        <div class="card" style="padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <div>
                    <h3 style="font-size: 14px; color: var(--muted); margin: 0 0 4px; font-weight: 500;">Total Produk</h3>
                    <div style="font-size: 32px; font-family: var(--font-display); font-weight: 700;">{{ $stats['products'] }}</div>
                </div>
                <div style="width: 48px; height: 48px; background: var(--surface-2); border-radius: 12px; display: grid; place-items: center; color: var(--accent);">
                    <x-icon name="archive-box" style="width: 24px; height: 24px;" />
                </div>
            </div>
            <div style="font-size: 13px; color: var(--muted); margin-top: 4px;">
                <span style="color: var(--wa); font-weight: 600;">{{ $stats['available'] }}</span> tersedia &bull; <span style="color: var(--danger); font-weight: 600;">{{ $stats['sold'] }}</span> terjual
            </div>
        </div>

        <div class="card" style="padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <div>
                    <h3 style="font-size: 14px; color: var(--muted); margin: 0 0 4px; font-weight: 500;">Kategori</h3>
                    <div style="font-size: 32px; font-family: var(--font-display); font-weight: 700;">{{ $stats['categories'] }}</div>
                </div>
                <div style="width: 48px; height: 48px; background: var(--surface-2); border-radius: 12px; display: grid; place-items: center; color: var(--accent);">
                    <x-icon name="tag" style="width: 24px; height: 24px;" />
                </div>
            </div>
        </div>

        <div class="card" style="padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <div>
                    <h3 style="font-size: 14px; color: var(--muted); margin: 0 0 4px; font-weight: 500;">Testimoni</h3>
                    <div style="font-size: 32px; font-family: var(--font-display); font-weight: 700;">{{ $stats['testimonials'] }}</div>
                </div>
                <div style="width: 48px; height: 48px; background: var(--surface-2); border-radius: 12px; display: grid; place-items: center; color: var(--accent);">
                    <x-icon name="chat-bubble-left-ellipsis" style="width: 24px; height: 24px;" />
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div style="padding: 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 18px; margin: 0;">Produk Terbaru</h2>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: var(--surface-2);">
                        <th style="padding: 12px 24px; font-size: 13px; color: var(--muted); font-weight: 600;">Produk</th>
                        <th style="padding: 12px 24px; font-size: 13px; color: var(--muted); font-weight: 600;">Kategori</th>
                        <th style="padding: 12px 24px; font-size: 13px; color: var(--muted); font-weight: 600;">Harga</th>
                        <th style="padding: 12px 24px; font-size: 13px; color: var(--muted); font-weight: 600;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestProducts as $product)
                    <tr style="border-bottom: 1px solid var(--line);">
                        <td style="padding: 16px 24px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 48px; height: 48px; border-radius: 8px; background: var(--surface-2); overflow: hidden;">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div style="font-weight: 600;">{{ $product->name }}</div>
                            </div>
                        </td>
                        <td style="padding: 16px 24px; color: var(--muted);">{{ $product->category?->name ?? '-' }}</td>
                        <td style="padding: 16px 24px; font-weight: 600;">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td style="padding: 16px 24px;">
                            @if($product->status === 'available')
                                <span class="badge badge-success">Tersedia ({{ $product->stock }})</span>
                            @else
                                <span class="badge badge-danger">Terjual</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 24px; text-align: center; color: var(--muted);">Belum ada produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
