@extends('layouts.admin')

@section('title', 'Dashboard Admin | Bumi Mandiri')
@section('page_title', 'Dashboard')

@php
    $statusMeta = [
        'pending' => ['label' => 'Menunggu', 'bar' => 'bg-amber-400'],
        'confirmed' => ['label' => 'Dikonfirmasi', 'bar' => 'bg-forest'],
        'completed' => ['label' => 'Selesai', 'bar' => 'bg-nature'],
        'cancelled' => ['label' => 'Dibatalkan', 'bar' => 'bg-red-400'],
    ];
@endphp

@section('content')
<x-admin.page-header
    eyebrow="Ringkasan operasional"
    title="Dashboard"
    description="Pantau booking, pendapatan, dan konten yang perlu ditindaklanjuti.">
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary btn-sm">
        <x-icon name="ticket" :size="15" />
        Kelola booking
    </a>
    <a href="{{ route('admin.activities.create') }}" class="btn btn-primary btn-sm">
        <x-icon name="plus" :size="15" />
        Aktivitas baru
    </a>
</x-admin.page-header>

{{-- KPI --}}
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @php
    $kpiCards = [
        [
            'label' => 'Pendapatan lunas',
            'value' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
            'note'  => 'Rp ' . number_format($revenueThisMonth, 0, ',', '.') . ' bulan ini',
            'icon'  => 'wallet',
            'tone'  => 'bg-forest text-cream',
            'labelTone' => 'text-cream/70',
            'valueTone' => 'text-cream',
            'noteTone' => 'text-cream/70',
            'iconTone' => 'bg-cream/10 text-cream/80',
        ],
        [
            'label' => 'Total booking',
            'value' => number_format($totalBooking, 0, ',', '.'),
            'note'  => $todayBookings . ' kunjungan hari ini',
            'icon'  => 'ticket',
            'tone'  => 'bg-white ring-1 ring-forest/10',
            'labelTone' => 'text-charcoal/70',
            'valueTone' => 'text-forest',
            'noteTone' => 'text-charcoal/70',
            'iconTone' => 'bg-sand-light text-forest',
        ],
        [
            'label' => 'Menunggu konfirmasi',
            'value' => number_format($pendingBooking, 0, ',', '.'),
            'note'  => $pendingBooking > 0 ? 'Perlu ditindaklanjuti' : 'Semua sudah ditangani',
            'icon'  => 'clock',
            'tone'  => 'bg-white ring-1 ring-forest/10',
            'labelTone' => 'text-charcoal/70',
            'valueTone' => 'text-forest',
            'noteTone' => $pendingBooking > 0 ? 'text-amber-700' : 'text-charcoal/70',
            'iconTone' => 'bg-sand-light text-forest',
        ],
        [
            'label' => 'Rating pengunjung',
            'value' => number_format($averageRating, 1) . ' / 5',
            'note'  => $approvedReviewCount . ' rating tampil' . ($pendingReviewCount > 0 ? ' · ' . $pendingReviewCount . ' menunggu' : ''),
            'icon'  => 'star',
            'tone'  => 'bg-white ring-1 ring-forest/10',
            'labelTone' => 'text-charcoal/70',
            'valueTone' => 'text-forest',
            'noteTone' => $pendingReviewCount > 0 ? 'text-amber-700' : 'text-charcoal/70',
            'iconTone' => 'bg-earth/10 text-earth',
            'route' => 'admin.reviews.index',
        ],
    ];
    @endphp

    @foreach($kpiCards as $kpi)
        @php $tag = isset($kpi['route']) ? 'a' : 'div'; @endphp
        <{{ $tag }}
            @if(isset($kpi['route'])) href="{{ route($kpi['route']) }}" @endif
            class="group rounded-2xl p-5 transition {{ $kpi['tone'] }} {{ isset($kpi['route']) ? 'hover:-translate-y-0.5 hover:shadow-soft' : '' }}">
            <div class="flex items-start justify-between gap-3">
                <p class="text-[11px] font-semibold uppercase tracking-[0.12em] {{ $kpi['labelTone'] }}">
                    {{ $kpi['label'] }}
                </p>
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $kpi['iconTone'] }}">
                    <x-icon :name="$kpi['icon']" :size="16" />
                </span>
            </div>
            <p class="mt-3 font-heading text-[1.35rem] font-extrabold leading-tight {{ $kpi['valueTone'] }}">
                {{ $kpi['value'] }}
            </p>
            <p class="mt-1 text-xs {{ $kpi['noteTone'] }}">{{ $kpi['note'] }}</p>
        </{{ $tag }}>
    @endforeach
</div>

{{-- Chart + status --}}
<div class="mt-6 grid gap-5 xl:grid-cols-[minmax(0,1.55fr)_minmax(0,1fr)]">
    <div class="admin-card p-5 sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="font-heading text-base font-bold text-forest">Booking masuk</h2>
                <p class="mt-0.5 text-xs text-charcoal/70">Enam bulan terakhir berdasarkan tanggal dibuat</p>
            </div>
            <span class="rounded-full bg-sand-light px-3 py-1 text-xs font-semibold text-charcoal/70">
                {{ array_sum($bookingChartData) }} total
            </span>
        </div>

        <div class="mt-6 flex h-44 items-end gap-2 sm:gap-4" role="img"
             aria-label="Grafik booking enam bulan terakhir: {{ implode(', ', $bookingChartData) }}">
            @foreach($bookingChartData as $index => $total)
                @php $height = max(2, (int) round(($total / $bookingChartMax) * 100)); @endphp
                <div class="group flex h-full flex-1 flex-col items-center justify-end gap-2">
                    <div class="flex w-full flex-1 flex-col items-center justify-end">
                        <span class="mb-1 text-[11px] font-semibold text-charcoal/70 transition-opacity duration-200 group-hover:text-forest">{{ $total }}</span>
                        <div class="w-full rounded-t-md bg-forest/85 transition-all duration-700 group-hover:bg-forest" style="height: {{ $height }}%"></div>
                    </div>
                    <span class="text-[11px] font-medium text-charcoal/70 transition-colors duration-200 group-hover:font-semibold group-hover:text-forest">{{ $bookingChartLabels[$index] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="admin-card p-5 sm:p-6">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h2 class="font-heading text-base font-bold text-forest">Status booking</h2>
                <p class="mt-0.5 text-xs text-charcoal/70">Distribusi seluruh booking</p>
            </div>
            <x-icon name="chart-bar" :size="18" class="text-charcoal/70" />
        </div>

        <div class="mt-5 space-y-4">
            @foreach($statusMeta as $status => $meta)
                @php
                    $count = $statusBreakdown[$status] ?? 0;
                    $percent = $totalBooking > 0 ? round(($count / $totalBooking) * 100) : 0;
                @endphp
                <div>
                    <div class="flex items-center justify-between gap-3 text-sm">
                        <a href="{{ route('admin.bookings.index', ['status' => $status]) }}" class="font-medium text-charcoal/70 transition hover:text-forest">
                            {{ $meta['label'] }}
                        </a>
                        <span class="text-xs font-semibold text-charcoal/70">{{ $count }}</span>
                    </div>
                    <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-sand-light">
                        <div class="h-full rounded-full {{ $meta['bar'] }}" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5 grid grid-cols-3 gap-2 border-t border-sand/70 pt-4 text-center">
            <div class="rounded-lg bg-sand-light/70 px-2 py-2.5">
                <p class="font-heading text-lg font-extrabold text-forest">{{ $activeUsers }}</p>
                <p class="text-[10px] font-medium uppercase tracking-wider text-charcoal/70">Pelanggan</p>
            </div>
            <div class="rounded-lg bg-sand-light/70 px-2 py-2.5">
                <p class="font-heading text-lg font-extrabold text-forest">{{ $newUsersThisMonth }}</p>
                <p class="text-[10px] font-medium uppercase tracking-wider text-charcoal/70">Baru</p>
            </div>
            <div class="rounded-lg bg-sand-light/70 px-2 py-2.5">
                <p class="font-heading text-lg font-extrabold text-forest">{{ $totalExperiences }}</p>
                <p class="text-[10px] font-medium uppercase tracking-wider text-charcoal/70">Aktivitas</p>
            </div>
        </div>
    </div>
</div>

{{-- Bookings --}}
<div class="mt-6 admin-card overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-sand/70 px-5 py-4">
            <div>
                <h2 class="font-heading text-base font-bold text-forest">Booking terbaru</h2>
                <p class="mt-0.5 text-xs text-charcoal/70">Enam permintaan terakhir yang masuk</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-forest transition hover:text-earth">
                Lihat semua
                <x-icon name="arrow-right" :size="14" />
            </a>
        </div>

        @if($recentBookings->isEmpty())
            <x-empty-state icon="ticket" title="Belum ada booking" description="Booking dari pengunjung akan muncul di sini." class="py-12" />
        @else
            <div class="overflow-x-auto">
                <table class="admin-table admin-table-compact">
                    <thead>
                        <tr>
                            <th>Pelanggan</th>
                            <th>Aktivitas</th>
                            <th>Total Nilai Booking</th>
                            <th>Status</th>
                            <th class="text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentBookings as $booking)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-2.5">
                                        <x-admin.avatar :name="$booking->user->name ?? '?'" size="sm" />
                                        <div class="min-w-0">
                                            <p class="whitespace-nowrap text-sm font-medium text-charcoal/80">{{ $booking->user->name ?? 'Pengguna dihapus' }}</p>
                                            <p class="whitespace-nowrap font-mono text-[10px] text-charcoal/70">{{ $booking->booking_code }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="max-w-[170px]">
                                    <p class="truncate">{{ $booking->items->first()?->activity?->name ?? 'Aktivitas' }}</p>
                                    <p class="text-[11px] text-charcoal/70">{{ $booking->booking_date->translatedFormat('d M Y') }}</p>
                                </td>
                                <td class="whitespace-nowrap font-semibold text-forest">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                <td><x-status-badge :status="$booking->status" /></td>
                                <td class="text-right">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="admin-action admin-action-edit">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

<div class="mt-6 grid items-start gap-5 lg:grid-cols-2">
        <div class="admin-card p-5">
            <div class="flex items-center justify-between gap-3">
                <h2 class="font-heading text-base font-bold text-forest">Jadwal terdekat</h2>
                <span class="text-[10px] font-semibold uppercase tracking-wider text-charcoal/70">Hari ini dan seterusnya</span>
            </div>

            @if($upcomingBookings->isEmpty())
                <p class="mt-4 rounded-lg bg-sand-light/60 px-3 py-4 text-sm text-charcoal/70">Belum ada kunjungan terjadwal.</p>
            @else
                <ul class="mt-4 space-y-3">
                    @foreach($upcomingBookings as $booking)
                        <li class="flex items-start gap-3">
                            @php $isToday = $booking->booking_date->isToday(); @endphp
                            <div class="flex h-11 w-11 shrink-0 flex-col items-center justify-center rounded-lg {{ $isToday ? 'bg-earth/10 text-earth' : 'bg-sand-light text-forest' }}">
                                <span class="font-heading text-sm font-extrabold leading-none">{{ $booking->booking_date->format('d') }}</span>
                                <span class="text-[9px] font-semibold uppercase tracking-wide {{ $isToday ? 'text-earth/80' : 'text-charcoal/70' }}">{{ $booking->booking_date->translatedFormat('M') }}</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="block truncate text-sm font-semibold text-forest transition hover:text-earth">
                                    {{ $booking->items->first()?->activity?->name ?? 'Aktivitas' }}
                                </a>
                                <p class="mt-0.5 truncate text-xs text-charcoal/70">
                                    {{ $booking->user->name ?? 'Pengguna' }} &middot; {{ $booking->participants }} peserta
                                </p>
                            </div>
                            <x-status-badge :status="$booking->status" class="shrink-0" />
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="admin-card p-5">
            <h2 class="font-heading text-base font-bold text-forest">Akses cepat</h2>
            <p class="mt-0.5 text-xs text-charcoal/70">Pintasan untuk tindakan yang sering dilakukan</p>
            <div class="mt-4 grid grid-cols-2 gap-2">
                @foreach([
                    ['label' => 'Tambah aktivitas', 'desc' => 'Daftarkan kegiatan alam baru', 'route' => 'admin.activities.create', 'icon' => 'compass'],
                    ['label' => 'Tambah paket', 'desc' => 'Buat penawaran paket wisata', 'route' => 'admin.packages.create', 'icon' => 'package'],
                    ['label' => 'Tulis artikel', 'desc' => 'Publikasikan konten blog', 'route' => 'admin.articles.create', 'icon' => 'article'],
                    ['label' => 'Unggah foto', 'desc' => 'Tambah gambar ke galeri', 'route' => 'admin.gallery.create', 'icon' => 'photo'],
                ] as $action)
                    <a href="{{ route($action['route']) }}"
                       class="group flex flex-col gap-2.5 rounded-xl border border-forest/10 bg-white p-3.5 transition hover:border-forest/25 hover:bg-sand-light/30">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-sand-light text-forest transition group-hover:bg-forest group-hover:text-cream">
                            <x-icon :name="$action['icon']" :size="15" />
                        </span>
                        <span>
                            <span class="block text-xs font-semibold text-charcoal/80">{{ $action['label'] }}</span>
                            <span class="mt-0.5 block text-[11px] leading-snug text-charcoal/55">{{ $action['desc'] }}</span>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
</div>

{{-- Popular + reviews --}}
<div class="mt-6 grid items-start gap-5 lg:grid-cols-2">
    <div class="admin-card p-5 sm:p-6">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h2 class="font-heading text-base font-bold text-forest">Aktivitas populer</h2>
                <p class="mt-0.5 text-xs text-charcoal/70">Berdasarkan jumlah item booking</p>
            </div>
            <a href="{{ route('admin.activities.index') }}" class="text-xs font-semibold text-forest transition hover:text-earth">Kelola</a>
        </div>

        @if($popularActivities->isEmpty())
            <p class="mt-4 text-sm text-charcoal/70">Belum ada data aktivitas.</p>
        @else
            @php $topCount = max(1, $popularActivities->max('booking_items_count')); @endphp
            <ul class="mt-5 space-y-4">
                @foreach($popularActivities as $activity)
                    <li>
                        <div class="flex items-center justify-between gap-3 text-sm">
                            <a href="{{ route('admin.activities.edit', $activity) }}" class="truncate font-medium text-charcoal/75 transition hover:text-forest">
                                {{ $activity->name }}
                            </a>
                            <span class="shrink-0 text-xs text-charcoal/70">{{ $activity->booking_items_count }} item</span>
                        </div>
                        <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-sand-light">
                            <div class="h-full rounded-full bg-earth/70" style="width: {{ max(4, round(($activity->booking_items_count / $topCount) * 100)) }}%"></div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="admin-card p-5 sm:p-6">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h2 class="font-heading text-base font-bold text-forest">Review menunggu</h2>
                <p class="mt-0.5 text-xs text-charcoal/70">{{ $pendingReviewCount }} review belum ditampilkan</p>
            </div>
            <a href="{{ route('admin.reviews.index') }}" class="text-xs font-semibold text-forest transition hover:text-earth">Tinjau</a>
        </div>

        <div class="mt-4 flex flex-wrap items-center justify-between gap-2 rounded-xl bg-sand-light/70 px-3 py-2.5">
            <div class="flex items-center gap-2">
                <x-stars :rating="$averageRating" :size="13" />
                <span class="font-heading text-sm font-extrabold text-forest">{{ number_format($averageRating, 1) }}</span>
            </div>
            <span class="text-[11px] text-charcoal/60">{{ $approvedReviewCount }} rating tampil</span>
        </div>

        @if($pendingReviews->isEmpty())
            <div class="mt-4 flex items-center gap-2 rounded-lg bg-nature/5 px-3 py-3 text-sm text-nature">
                <x-icon name="check" :size="16" />
                Belum ada review yang perlu ditinjau.
            </div>
        @else
            <ul class="mt-4 divide-y divide-sand/60">
                @foreach($pendingReviews as $review)
                    <li class="py-3 first:pt-0 last:pb-0">
                        <div class="flex items-center justify-between gap-3">
                            <p class="truncate text-sm font-semibold text-forest">{{ $review->activity?->name ?? 'Aktivitas' }}</p>
                            <span class="inline-flex shrink-0 items-center gap-1 text-xs font-semibold text-earth">
                                {{ $review->rating }}
                                <x-icon name="star" :size="12" class="fill-current" />
                            </span>
                        </div>
                        <p class="mt-1 line-clamp-2 text-xs leading-relaxed text-charcoal/70">
                            {{ $review->comment ?: 'Rating tanpa ulasan tertulis.' }}
                        </p>
                        <p class="mt-1 text-[11px] text-charcoal/70">{{ $review->displayName() }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
