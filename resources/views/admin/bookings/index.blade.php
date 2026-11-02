@extends('layouts.admin')

@section('title', 'Booking | Admin Bumi Mandiri')
@section('page_title', 'Booking')

@section('content')
<x-admin.page-header
    eyebrow="Transaksi"
    title="Booking"
    description="Tinjau permintaan masuk, konfirmasi jadwal, dan perbarui status kunjungan.">
</x-admin.page-header>

<form method="GET" class="mb-4 flex flex-wrap items-center gap-2">
    <label for="booking-status" class="text-xs font-semibold text-charcoal/70">Status</label>
    <select id="booking-status" name="status" onchange="this.form.submit()" class="field w-auto py-2.5">
        <option value="">Semua status</option>
        @foreach(['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $value => $label)
            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
        @endforeach
    </select>
    @if(request()->filled('status'))
        <a href="{{ route('admin.bookings.index') }}" class="text-xs font-semibold text-charcoal/70 transition hover:text-forest">Reset</a>
    @endif
    <p class="ml-auto text-xs text-charcoal/70">{{ $bookings->total() }} booking</p>
</form>

<div class="admin-card overflow-hidden">
    @if($bookings->isEmpty())
        <x-empty-state
            icon="ticket"
            :title="request()->filled('status') ? 'Tidak ada booking dengan status ini' : 'Belum ada booking'"
            description="Booking dari pengunjung akan tampil di sini."
            class="py-16">
            @if(request()->filled('status'))
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary btn-sm">Lihat semua booking</a>
            @endif
        </x-empty-state>
    @else
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Pelanggan</th>
                        <th>Aktivitas</th>
                        <th>Kunjungan</th>
                        <th>Peserta</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        <tr>
                            <td class="whitespace-nowrap font-mono text-xs font-semibold text-charcoal/65">{{ $booking->booking_code }}</td>
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <x-admin.avatar :name="$booking->user->name ?? '?'" size="sm" />
                                    <div class="min-w-0">
                                        <p class="whitespace-nowrap text-sm font-medium text-charcoal/80">{{ $booking->user->name ?? 'Pengguna dihapus' }}</p>
                                        <p class="max-w-[160px] truncate text-[11px] text-charcoal/70">{{ $booking->user->email ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="max-w-[200px]">
                                <p class="truncate">{{ $booking->items->first()?->activity?->name ?? 'Aktivitas' }}</p>
                                @if($booking->items->count() > 1)
                                    <p class="text-[11px] text-charcoal/70">+{{ $booking->items->count() - 1 }} lainnya</p>
                                @endif
                            </td>
                            <td class="whitespace-nowrap">{{ $booking->booking_date->translatedFormat('d M Y') }}</td>
                            <td class="text-charcoal/70">{{ $booking->participants }}</td>
                            <td class="whitespace-nowrap font-semibold text-forest">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                            <td><x-status-badge :status="$booking->status" /></td>
                            <td class="text-right">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="admin-action admin-action-edit">
                                    <x-icon name="eye" :size="14" />
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

@if($bookings->hasPages())
    <div class="pagination-wrap mt-5">{{ $bookings->links() }}</div>
@endif
@endsection
