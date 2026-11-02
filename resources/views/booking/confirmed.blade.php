@extends('layouts.app')

@section('title', 'Booking Terkirim | Bumi Mandiri')

@section('content')
<section class="pb-20 pt-28 lg:pt-32">
    <div class="container-page">
        <div class="mx-auto max-w-2xl text-center">
            <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-nature/10 text-nature ring-8 ring-nature/5">
                <x-icon name="check" :size="30" />
            </span>
            <p class="eyebrow mt-6 text-earth">Booking diterima</p>
            <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-forest sm:text-4xl">Permintaan Anda sudah masuk.</h1>
            <p class="mx-auto mt-3 max-w-lg text-[15px] leading-relaxed text-charcoal/70">
                Simpan kode booking berikut. Tim Bumi Mandiri akan menghubungi Anda melalui kontak terdaftar untuk mengonfirmasi jadwal.
            </p>
        </div>

        <div class="mx-auto mt-8 max-w-2xl overflow-hidden rounded-2xl border border-forest/10 bg-white">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-sand/70 bg-sand-light/60 px-5 py-4 sm:px-7">
                <div>
                    <p class="text-[10px] font-medium uppercase tracking-[0.16em] text-charcoal/70">Kode booking</p>
                    <p class="mt-0.5 font-heading text-xl font-extrabold tracking-tight text-forest">{{ $booking->booking_code }}</p>
                </div>
                <x-status-badge :status="$booking->status" />
            </div>

            <div class="p-5 sm:p-7">
                <dl class="grid gap-4 sm:grid-cols-2">
                    @foreach([
                        ['label' => 'Aktivitas', 'value' => $booking->items->first()?->activity?->name ?? 'Aktivitas Bumi Mandiri'],
                        ['label' => 'Tanggal', 'value' => $booking->booking_date->translatedFormat('d F Y')],
                        ['label' => 'Waktu', 'value' => $booking->timeLabel() ?: 'Fleksibel'],
                        ['label' => 'Peserta', 'value' => $booking->participants . ' orang'],
                        ['label' => 'Paket', 'value' => $booking->package?->name ?? 'Tanpa paket'],
                        ['label' => 'Total', 'value' => 'Rp ' . number_format($booking->total_price, 0, ',', '.')],
                    ] as $row)
                        <div>
                            <dt class="text-[11px] font-medium uppercase tracking-wider text-charcoal/70">{{ $row['label'] }}</dt>
                            <dd class="mt-1 text-sm font-semibold text-charcoal/80">{{ $row['value'] }}</dd>
                        </div>
                    @endforeach
                </dl>

                <div class="mt-6 rounded-xl border border-earth/20 bg-earth/[0.06] p-4 text-sm leading-relaxed text-charcoal/70">
                    <p class="font-semibold text-forest">Langkah berikutnya</p>
                    <p class="mt-1">Cek halaman booking secara berkala. Status akan berubah setelah tim kami mengonfirmasi jadwal dan detail pembayaran.</p>
                </div>
            </div>
        </div>

        <div class="mx-auto mt-6 flex max-w-2xl flex-col gap-3 sm:flex-row">
            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-primary flex-1">
                Lihat booking
                <x-icon name="arrow-right" :size="16" />
            </a>
            <a href="{{ route('home') }}" class="btn btn-secondary flex-1">Kembali ke beranda</a>
        </div>
    </div>
</section>
@endsection
