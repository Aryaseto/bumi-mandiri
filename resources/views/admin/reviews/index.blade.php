@extends('layouts.admin')

@section('title', 'Rating Pengunjung | Admin Bumi Mandiri')
@section('page_title', 'Rating Pengunjung')

@section('content')
<x-admin.page-header
    eyebrow="Transaksi"
    title="Rating & review pengunjung"
    description="Pantau penilaian pengunjung, setujui ulasan, atau sembunyikan konten yang tidak sesuai.">
    <a href="{{ route('home') }}#rating-pengunjung" target="_blank" rel="noopener" class="btn btn-secondary btn-sm">
        <x-icon name="external-link" :size="15" />
        Lihat di situs
    </a>
</x-admin.page-header>

{{-- Statistik --}}
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach([
        ['label' => 'Total rating', 'value' => number_format($stats['total'], 0, ',', '.'), 'note' => 'Seluruh rating masuk', 'icon' => 'star', 'tone' => 'bg-white ring-1 ring-forest/10', 'iconTone' => 'bg-earth/10 text-earth'],
        ['label' => 'Rata-rata', 'value' => number_format($stats['average'], 1) . ' / 5', 'note' => 'Dari rating yang tampil', 'icon' => 'chart-bar', 'tone' => 'bg-forest text-cream', 'iconTone' => 'bg-cream/10 text-cream/80', 'valueTone' => 'text-cream', 'labelTone' => 'text-cream/70', 'noteTone' => 'text-cream/70'],
        ['label' => 'Tampil', 'value' => number_format($stats['approved'], 0, ',', '.'), 'note' => 'Terlihat oleh pengunjung', 'icon' => 'eye', 'tone' => 'bg-white ring-1 ring-forest/10', 'iconTone' => 'bg-nature/10 text-nature'],
        ['label' => 'Menunggu', 'value' => number_format($stats['pending'], 0, ',', '.'), 'note' => 'Perlu ditinjau admin', 'icon' => 'clock', 'tone' => 'bg-white ring-1 ring-forest/10', 'iconTone' => 'bg-amber-50 text-amber-700'],
    ] as $card)
        <div class="rounded-2xl p-5 {{ $card['tone'] }}">
            <div class="flex items-start justify-between gap-3">
                <p class="text-[11px] font-semibold uppercase tracking-[0.12em] {{ $card['labelTone'] ?? 'text-charcoal/70' }}">{{ $card['label'] }}</p>
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $card['iconTone'] }}">
                    <x-icon :name="$card['icon']" :size="16" />
                </span>
            </div>
            <p class="mt-3 font-heading text-[1.35rem] font-extrabold leading-tight {{ $card['valueTone'] ?? 'text-forest' }}">{{ $card['value'] }}</p>
            <p class="mt-1 text-xs {{ $card['noteTone'] ?? 'text-charcoal/70' }}">{{ $card['note'] }}</p>
        </div>
    @endforeach
</div>

<div class="mt-6 admin-card overflow-hidden">
    {{-- Filter & pencarian --}}
    <div class="flex flex-col gap-3 border-b border-sand/70 px-5 py-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex flex-wrap gap-1.5" role="tablist" aria-label="Filter status review">
            @foreach([
                'all' => 'Semua',
                'approved' => 'Tampil',
                'pending' => 'Menunggu',
            ] as $key => $label)
                <a href="{{ route('admin.reviews.index', array_filter(['status' => $key, 'q' => $search ?: null])) }}"
                   role="tab"
                   aria-selected="{{ $status === $key ? 'true' : 'false' }}"
                   class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold transition {{ $status === $key ? 'bg-forest text-cream' : 'text-charcoal/70 hover:bg-sand-light' }}">
                    {{ $label }}
                    <span class="rounded-full px-1.5 py-0.5 text-[10px] {{ $status === $key ? 'bg-cream/15 text-cream' : 'bg-sand text-charcoal/60' }}">
                        {{ $statusCounts[$key] }}
                    </span>
                </a>
            @endforeach
        </div>

        <form action="{{ route('admin.reviews.index') }}" method="GET" class="flex items-center gap-2">
            <input type="hidden" name="status" value="{{ $status }}">
            <label for="review-search" class="sr-only">Cari review</label>
            <div class="relative">
                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-charcoal/40">
                    <x-icon name="search" :size="15" />
                </span>
                <input id="review-search" type="search" name="q" value="{{ $search }}"
                       placeholder="Cari nama, aktivitas, ulasan..."
                       class="w-full rounded-lg border border-sand bg-white py-2 pl-9 pr-3 text-xs text-charcoal placeholder:text-charcoal/40 focus:border-nature focus:outline-none focus:ring-2 focus:ring-nature/20 sm:w-64">
            </div>
            <button type="submit" class="btn btn-secondary btn-sm">Cari</button>
        </form>
    </div>

    @if($reviews->isEmpty())
        <x-empty-state
            icon="star"
            title="Belum ada rating"
            :description="$search !== '' ? 'Tidak ada rating yang cocok dengan pencarian Anda.' : 'Rating dari pengunjung akan muncul di sini.'"
            class="py-16" />
    @else
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Pengunjung</th>
                        <th>Aktivitas</th>
                        <th>Rating</th>
                        <th>Ulasan</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                        <tr>
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <x-admin.avatar :name="$review->displayName()" size="sm" />
                                    <div class="min-w-0">
                                        <p class="whitespace-nowrap text-sm font-medium text-charcoal/80">{{ $review->displayName() }}</p>
                                        <div class="mt-0.5 flex flex-wrap items-center gap-x-2 gap-y-0.5 text-[11px] text-charcoal/60">
                                            <span>{{ $review->created_at->translatedFormat('d M Y') }}</span>
                                            @if($review->isVerified())
                                                <span class="inline-flex items-center gap-0.5 font-semibold text-nature">
                                                    <x-icon name="shield-check" :size="10" />
                                                    Booking
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-0.5 font-medium text-earth">
                                                    <x-icon name="user" :size="10" />
                                                    Umum
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="max-w-[180px]">
                                @if($review->activity)
                                    <a href="{{ route('activities.show', $review->activity) }}" target="_blank" rel="noopener"
                                       class="block truncate text-charcoal/75 transition hover:text-forest">
                                        {{ $review->activity->name }}
                                    </a>
                                @else
                                    <span class="text-charcoal/50">Aktivitas dihapus</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1.5">
                                    <span class="font-heading text-sm font-extrabold text-forest">{{ $review->rating }}</span>
                                    <x-stars :rating="$review->rating" :size="12" />
                                </div>
                            </td>
                            <td class="max-w-xs">
                                <p class="line-clamp-2 text-charcoal/65">{{ $review->comment ?: '—' }}</p>
                            </td>
                            <td>
                                <x-status-badge :status="$review->is_approved ? 'approved' : 'pending'" :label="$review->is_approved ? 'Tampil' : 'Menunggu'" />
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="admin-action {{ $review->is_approved ? 'text-charcoal/70 hover:bg-charcoal/5' : 'admin-action-edit' }}">
                                            <x-icon :name="$review->is_approved ? 'eye' : 'check'" :size="14" />
                                            {{ $review->is_approved ? 'Sembunyikan' : 'Setujui' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline" onsubmit="return confirm('Hapus rating ini? Tindakan ini tidak bisa dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-action admin-action-delete">
                                            <x-icon name="trash" :size="14" />
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@if($reviews->hasPages())
    <div class="pagination-wrap mt-5">{{ $reviews->links() }}</div>
@endif
@endsection
