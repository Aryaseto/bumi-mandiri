@extends('layouts.app')

@section('title', 'Booking Saya | Bumi Mandiri')

@section('content')
<section class="pb-20 pt-28 lg:pt-32">
    <div class="container-page">
        <div class="flex flex-wrap items-end justify-between gap-5">
            <div>
                <p class="eyebrow text-earth">Akun saya</p>
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-forest sm:text-4xl">Booking saya.</h1>
                <p class="mt-3 max-w-xl text-[15px] leading-relaxed text-charcoal/70">
                    Semua pemesanan yang pernah dibuat, lengkap dengan status dan tanggal kunjungan.
                </p>
            </div>
            <a href="{{ route('activities.index') }}" class="btn btn-secondary btn-sm">
                <x-icon name="plus" :size="16" />
                Booking baru
            </a>
        </div>

        <div class="mt-7">
            <x-account-nav />
        </div>

        @if($bookings->isEmpty())
            <x-empty-state
                icon="ticket"
                title="Belum ada booking"
                description="Pilih aktivitas yang Anda suka, lalu tentukan tanggal kunjungan. Booking akan muncul di halaman ini."
                class="rounded-2xl border border-dashed border-forest/20 bg-white/60">
                <a href="{{ route('activities.index') }}" class="btn btn-primary btn-sm">Jelajahi aktivitas</a>
            </x-empty-state>
        @else
            <p class="mb-4 text-sm text-charcoal/70">{{ $bookings->total() }} booking ditemukan</p>

            <ul class="space-y-4">
                @foreach($bookings as $booking)
                    @php $activity = $booking->items->first()?->activity; @endphp
                    <li>
                        <a href="{{ route('bookings.show', $booking) }}"
                           class="group grid gap-4 rounded-2xl border border-forest/10 bg-white p-4 transition hover:border-forest/25 hover:shadow-soft sm:grid-cols-[88px_minmax(0,1fr)_auto] sm:items-center sm:p-5">
                            <div class="hidden h-[72px] w-[88px] overflow-hidden rounded-xl bg-sand sm:block">
                                @if($activity)
                                    <img src="{{ $activity->imageUrl() }}" alt=""
                                         class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                         loading="lazy" width="176" height="144">
                                @endif
                            </div>

                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-mono text-[11px] font-semibold tracking-wide text-charcoal/70">{{ $booking->booking_code }}</span>
                                    <x-status-badge :status="$booking->status" />
                                </div>
                                <h2 class="mt-1.5 truncate font-heading text-base font-bold text-forest group-hover:text-earth">
                                    {{ $activity?->name ?? 'Aktivitas Bumi Mandiri' }}
                                </h2>
                                <p class="mt-1 text-sm text-charcoal/70">
                                    {{ $booking->booking_date->translatedFormat('d F Y') }}
                                    @if($booking->timeLabel())
                                        <span class="text-charcoal/70">&middot;</span> {{ $booking->timeLabel() }}
                                    @endif
                                    <span class="text-charcoal/70">&middot;</span> {{ $booking->participants }} peserta
                                </p>
                            </div>

                            <div class="flex items-center justify-between gap-4 border-t border-sand/70 pt-3 sm:block sm:border-0 sm:pt-0 sm:text-right">
                                <div>
                                    <p class="text-[10px] font-medium uppercase tracking-wider text-charcoal/70">Total</p>
                                    <p class="font-heading text-base font-extrabold text-forest">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                                </div>
                                <span class="inline-flex items-center gap-1 text-sm font-semibold text-charcoal/70 transition group-hover:text-forest">
                                    Detail
                                    <x-icon name="arrow-right" :size="15" />
                                </span>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="pagination-wrap mt-8">{{ $bookings->links() }}</div>
        @endif
    </div>
</section>
@endsection
