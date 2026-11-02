@extends('layouts.admin')

@section('title', 'Booking ' . $booking->booking_code . ' | Admin Bumi Mandiri')
@section('page_title', 'Detail Booking')

@section('content')
<a href="{{ route('admin.bookings.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-charcoal/70 transition hover:text-forest">
    <x-icon name="arrow-left" :size="15" />
    Kembali ke daftar booking
</a>

<div class="flex flex-wrap items-end justify-between gap-4">
    <div>
        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-earth">Kode booking</p>
        <div class="mt-1 flex flex-wrap items-center gap-3">
            <h1 class="font-heading text-2xl font-extrabold tracking-tight text-forest">{{ $booking->booking_code }}</h1>
            <x-status-badge :status="$booking->status" />
        </div>
        <p class="mt-1 text-xs text-charcoal/70">Dibuat {{ $booking->created_at->translatedFormat('d F Y, H:i') }}</p>
    </div>
</div>

<div class="mt-6 grid gap-5 xl:grid-cols-[minmax(0,1fr)_340px] xl:items-start">
    <div class="space-y-5">
        <div class="admin-card overflow-hidden">
            <div class="border-b border-sand/70 px-5 py-4">
                <h2 class="font-heading text-base font-bold text-forest">Aktivitas</h2>
            </div>
            <div class="divide-y divide-sand/60">
                @foreach($booking->items as $item)
                    <div class="flex items-center gap-4 p-5">
                        <div class="h-14 w-14 shrink-0 overflow-hidden rounded-lg bg-sand">
                            @if($item->activity)
                                <img src="{{ $item->activity->imageUrl() }}" alt=""
                                     class="h-full w-full object-cover" width="112" height="112" loading="lazy">
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-charcoal/85">{{ $item->activity?->name ?? 'Aktivitas' }}</p>
                            <p class="mt-0.5 text-xs text-charcoal/70">{{ $item->quantity }} peserta &middot; Rp {{ number_format($item->subtotal / max($item->quantity, 1), 0, ',', '.') }} / orang</p>
                        </div>
                        <p class="whitespace-nowrap text-sm font-semibold text-forest">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="admin-card p-5">
            <h2 class="font-heading text-base font-bold text-forest">Detail kunjungan</h2>
            <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                @foreach([
                    ['label' => 'Tanggal', 'value' => $booking->booking_date->translatedFormat('l, d F Y'), 'icon' => 'calendar'],
                    ['label' => 'Waktu', 'value' => $booking->timeLabel() ?: 'Belum ditentukan', 'icon' => 'clock'],
                    ['label' => 'Jumlah peserta', 'value' => $booking->participants . ' orang', 'icon' => 'users'],
                    ['label' => 'Paket', 'value' => $booking->package?->name ?? 'Tanpa paket', 'icon' => 'package'],
                ] as $row)
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-sand-light text-forest">
                            <x-icon :name="$row['icon']" :size="15" />
                        </span>
                        <div>
                            <dt class="text-[11px] font-medium uppercase tracking-wider text-charcoal/70">{{ $row['label'] }}</dt>
                            <dd class="mt-0.5 text-sm font-medium text-charcoal/80">{{ $row['value'] }}</dd>
                        </div>
                    </div>
                @endforeach
            </dl>

            @if($booking->notes)
                <div class="mt-5 rounded-xl bg-sand-light/70 p-4">
                    <p class="text-[11px] font-medium uppercase tracking-wider text-charcoal/70">Catatan pengunjung</p>
                    <p class="mt-1 text-sm leading-relaxed text-charcoal/70">{{ $booking->notes }}</p>
                </div>
            @endif
        </div>

        @if($booking->payment)
            <div class="admin-card p-5">
                <h2 class="font-heading text-base font-bold text-forest">Pembayaran</h2>
                <div class="mt-4 flex flex-wrap items-center justify-between gap-4 rounded-xl border border-sand/80 px-4 py-3">
                    <div>
                        <p class="font-mono text-xs font-semibold text-charcoal/65">{{ $booking->payment->payment_code }}</p>
                        <p class="mt-1 text-xs text-charcoal/70">{{ $booking->payment->payment_method }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-forest">Rp {{ number_format($booking->payment->amount, 0, ',', '.') }}</p>
                        <div class="mt-1"><x-status-badge :status="$booking->payment->payment_status" /></div>
                    </div>
                </div>
            </div>
        @endif

        @if($booking->review)
            <div class="admin-card p-5">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="font-heading text-base font-bold text-forest">Review pengunjung</h2>
                    <span class="inline-flex items-center gap-1 text-sm font-semibold text-earth">
                        {{ $booking->review->rating }}
                        <x-icon name="star" :size="13" class="fill-current" />
                    </span>
                </div>
                <p class="mt-3 text-sm leading-relaxed text-charcoal/70">{{ $booking->review->comment }}</p>
                <div class="mt-3">
                    <x-status-badge :status="$booking->review->is_approved ? 'approved' : 'pending'" :label="$booking->review->is_approved ? 'Tampil di situs' : 'Menunggu persetujuan'" />
                </div>
            </div>
        @endif
    </div>

    <aside class="space-y-5">
        <div class="admin-card p-5">
            <h2 class="font-heading text-base font-bold text-forest">Pelanggan</h2>
            <div class="mt-4 flex items-center gap-3">
                <x-admin.avatar :name="$booking->user->name ?? '?'" size="lg" />
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-charcoal/85">{{ $booking->user->name ?? 'Pengguna dihapus' }}</p>
                    <p class="truncate text-xs text-charcoal/70">{{ $booking->user->email ?? '' }}</p>
                </div>
            </div>
            @if($booking->user->phone)
                <p class="mt-3 inline-flex items-center gap-1.5 text-sm text-charcoal/70">
                    <x-icon name="phone" :size="14" class="text-charcoal/70" />
                    {{ $booking->user->phone }}
                </p>
            @endif
        </div>

        <div class="admin-card p-5">
            <h2 class="font-heading text-base font-bold text-forest">Perbarui status</h2>
            <p class="mt-0.5 text-xs text-charcoal/70">Status akan terlihat oleh pengunjung.</p>
            <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" class="mt-4 space-y-3">
                @csrf
                @method('PUT')
                <label for="booking-status" class="sr-only">Status booking</label>
                <select id="booking-status" name="status" class="field">
                    @foreach(['pending' => 'Menunggu konfirmasi', 'confirmed' => 'Dikonfirmasi', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $value => $label)
                        <option value="{{ $value }}" @selected($booking->status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary w-full">Simpan status</button>
            </form>
        </div>

        <div class="admin-card p-5">
            <h2 class="font-heading text-base font-bold text-forest">Ringkasan biaya</h2>
            <dl class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between gap-3">
                    <dt class="text-charcoal/70">Subtotal</dt>
                    <dd class="font-medium">Rp {{ number_format($booking->subtotal, 0, ',', '.') }}</dd>
                </div>
                @if((float) $booking->discount > 0)
                    <div class="flex justify-between gap-3">
                        <dt class="text-charcoal/70">Diskon</dt>
                        <dd class="font-medium text-nature">- Rp {{ number_format($booking->discount, 0, ',', '.') }}</dd>
                    </div>
                @endif
                <div class="flex justify-between gap-3 border-t border-sand/70 pt-3">
                    <dt class="font-semibold text-forest">Total</dt>
                    <dd class="font-heading text-lg font-extrabold text-forest">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</dd>
                </div>
            </dl>
        </div>
    </aside>
</div>
@endsection
