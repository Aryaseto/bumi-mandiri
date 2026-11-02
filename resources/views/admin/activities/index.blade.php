@extends('layouts.admin')

@section('title', 'Aktivitas | Admin Bumi Mandiri')
@section('page_title', 'Aktivitas')

@section('content')
<x-admin.page-header
    eyebrow="Katalog"
    title="Aktivitas"
    description="Atur harga, durasi, kapasitas, dan status tampil setiap aktivitas.">
    <a href="{{ route('admin.activities.create') }}" class="btn btn-primary btn-sm">
        <x-icon name="plus" :size="15" />
        Tambah aktivitas
    </a>
</x-admin.page-header>

<form method="GET" class="mb-4 flex flex-wrap items-center gap-2">
    <div class="relative w-full max-w-[240px]">
        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-charcoal/70">
            <x-icon name="search" :size="15" />
        </span>
        <label for="activity-search" class="sr-only">Cari aktivitas</label>
        <input id="activity-search" type="text" name="q" value="{{ request('q') }}"
               placeholder="Cari nama aktivitas" class="field py-2.5 pl-9">
    </div>

    <label for="filter-category" class="sr-only">Kategori</label>
    <select id="filter-category" name="category" class="field w-auto py-2.5">
        <option value="">Semua kategori</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
        @endforeach
    </select>

    <label for="filter-destination" class="sr-only">Destinasi</label>
    <select id="filter-destination" name="destination" class="field w-auto py-2.5">
        <option value="">Semua destinasi</option>
        @foreach($destinations as $dest)
            <option value="{{ $dest->id }}" @selected(request('destination') == $dest->id)>{{ $dest->name }}</option>
        @endforeach
    </select>

    <label for="filter-status" class="sr-only">Status</label>
    <select id="filter-status" name="status" class="field w-auto py-2.5">
        <option value="">Semua status</option>
        <option value="active" @selected(request('status') === 'active')>Aktif</option>
        <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
    </select>

    <button type="submit" class="btn btn-secondary btn-sm">Terapkan</button>

    @if(request()->hasAny(['q', 'category', 'destination', 'status']))
        <a href="{{ route('admin.activities.index') }}" class="text-xs font-semibold text-charcoal/70 transition hover:text-forest">Reset</a>
    @endif

    <p class="ml-auto text-xs text-charcoal/70">{{ $activities->total() }} aktivitas</p>
</form>

<div class="admin-card overflow-hidden">
    @if($activities->isEmpty())
        <x-empty-state
            icon="compass"
            :title="request()->hasAny(['q', 'category', 'destination', 'status']) ? 'Aktivitas tidak ditemukan' : 'Belum ada aktivitas'"
            :description="request()->hasAny(['q', 'category', 'destination', 'status']) ? 'Coba longgarkan filter atau reset pencarian.' : 'Tambahkan aktivitas pertama yang bisa dipesan pengunjung.'"
            class="py-16">
            <a href="{{ request()->hasAny(['q', 'category', 'destination', 'status']) ? route('admin.activities.index') : route('admin.activities.create') }}" class="btn btn-primary btn-sm">
                {{ request()->hasAny(['q', 'category', 'destination', 'status']) ? 'Reset filter' : 'Tambah aktivitas' }}
            </a>
        </x-empty-state>
    @else
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Aktivitas</th>
                        <th>Kategori</th>
                        <th>Destinasi</th>
                        <th>Harga</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activities as $activity)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <img src="{{ $activity->imageUrl() }}" alt=""
                                         class="h-11 w-16 shrink-0 rounded-lg object-cover" width="128" height="88" loading="lazy">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            <p class="font-semibold text-charcoal/85">{{ $activity->name }}</p>
                                            @if($activity->is_featured)
                                                <span class="rounded-full bg-earth/10 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-earth">Unggulan</span>
                                            @endif
                                        </div>
                                        <p class="mt-0.5 text-xs text-charcoal/70">{{ $activity->durationLabel() }} &middot; {{ $activity->capacity }} peserta</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-charcoal/70">{{ $activity->category?->name ?? 'Belum diisi' }}</td>
                            <td class="text-charcoal/70">{{ $activity->destination?->name ?? 'Belum diisi' }}</td>
                            <td class="whitespace-nowrap font-semibold text-forest">Rp {{ number_format($activity->price, 0, ',', '.') }}</td>
                            <td>
                                <span class="inline-flex items-center gap-1 text-sm">
                                    <x-icon name="star" :size="13" class="fill-current text-earth" />
                                    {{ number_format($activity->rating_avg, 1) }}
                                    <span class="text-xs text-charcoal/70">({{ $activity->review_count }})</span>
                                </span>
                            </td>
                            <td><x-status-badge :status="$activity->is_active ? 'active' : 'inactive'" /></td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.activities.edit', $activity) }}" class="admin-action admin-action-edit">
                                        <x-icon name="edit" :size="14" />
                                        Edit
                                    </a>
                                    @if($activity->booking_items_count > 0)
                                        <span class="inline-flex cursor-not-allowed items-center px-2.5 py-1.5 text-xs text-charcoal/70"
                                              title="Sudah memiliki riwayat booking. Nonaktifkan saja.">
                                            Hapus
                                        </span>
                                    @else
                                        <form action="{{ route('admin.activities.destroy', $activity) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Hapus aktivitas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-action admin-action-delete">
                                                <x-icon name="trash" :size="14" />
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@if($activities->hasPages())
    <div class="pagination-wrap mt-5">{{ $activities->links() }}</div>
@endif
@endsection
