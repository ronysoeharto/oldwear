@extends('layouts.admin')

@section('title', 'Kelola Testimoni')

@section('content')
<div class="card">
    <div style="padding: 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-size: 18px; margin: 0;">Daftar Testimoni</h2>
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
            <x-icon name="plus" /> Tambah Testimoni
        </a>
    </div>

    <div class="table-responsive">
        <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--surface-2);">
                    <th style="padding: 12px 24px; font-size: 13px; color: var(--muted); font-weight: 600;">Pelanggan</th>
                    <th style="padding: 12px 24px; font-size: 13px; color: var(--muted); font-weight: 600;">Ulasan</th>
                    <th style="padding: 12px 24px; font-size: 13px; color: var(--muted); font-weight: 600; text-align: center;">Tampilkan di Web</th>
                    <th style="padding: 12px 24px; font-size: 13px; color: var(--muted); font-weight: 600; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($testimonials as $testimonial)
                <tr style="border-bottom: 1px solid var(--line);">
                    <td style="padding: 16px 24px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            @if($testimonial->photo)
                                <div style="width: 48px; height: 60px; border-radius: 8px; overflow: hidden; background: var(--surface-2); border: 1px solid var(--line); flex-shrink: 0;">
                                    <img src="{{ $testimonial->photo_url }}" alt="{{ $testimonial->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            @else
                                <div style="width: 48px; height: 60px; border-radius: 8px; background: var(--surface-2); color: var(--muted); display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 600; flex-shrink: 0; border: 1px dashed var(--line);">
                                    {{ $testimonial->initials }}
                                </div>
                            @endif
                            <div>
                                <div style="font-weight: 600;">{{ $testimonial->name }}</div>
                                <div style="font-size: 12px; color: #f59e0b;">★ {{ $testimonial->rating }}/5</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 16px 24px;">
                        <div style="font-size: 14px; max-width: 320px; line-height: 1.5;">
                            {{ \Illuminate\Support\Str::limit($testimonial->message ?? $testimonial->content, 100) }}
                        </div>
                    </td>
                    <td style="padding: 16px 24px; text-align: center;">
                        <form action="{{ route('admin.testimonials.toggle', $testimonial) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $testimonial->is_active ? 'btn-wa' : 'btn-light' }}" style="border-radius: 999px; font-size: 12px;">
                                {{ $testimonial->is_active ? 'Aktif' : 'Disembunyikan' }}
                            </button>
                        </form>
                    </td>
                    <td style="padding: 16px 24px; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                            <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn btn-light btn-sm" title="Edit">
                                <x-icon name="pencil" />
                            </a>
                            <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus testimoni ini?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-sm" style="color: var(--danger); border-color: var(--danger-soft);" title="Hapus">
                                    <x-icon name="trash" />
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 48px; text-align: center;">
                        <div style="color: var(--muted); margin-bottom: 16px;">
                            <x-icon name="chat-bubble-left-ellipsis" style="width: 48px; height: 48px;" />
                        </div>
                        <div style="font-size: 16px; font-weight: 600; margin-bottom: 8px;">Tidak ada testimoni</div>
                        <p class="text-muted">Belum ada testimoni pelanggan yang ditambahkan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($testimonials->hasPages())
    <div style="padding: 24px; border-top: 1px solid var(--line);">
        {{ $testimonials->links('partials.pagination') }}
    </div>
    @endif
</div>
@endsection
