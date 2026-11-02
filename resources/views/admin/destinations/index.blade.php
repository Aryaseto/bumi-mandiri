@extends('layouts.admin')

@section('title', 'Destinasi | Admin Bumi Mandiri')
@section('page_title', 'Destinasi')

@section('content')
<x-admin.page-header
    eyebrow="Katalog"
    title="Destinasi"
    description="Lokasi dan titik kumpul yang menjadi bagian dari pengalaman Bumi Mandiri.">
    <a href="{{ route('admin.destinations.create') }}" class="btn btn-primary btn-sm">
        <x-icon name="plus" :size="15" />
        Tambah destinasi
    </a>
</x-admin.page-header>

<form method="GET" class="mb-4 flex flex-wrap items-center gap-2">
    <div class="relative w-full max-w-xs">
        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-charcoal/70">
            <x-icon name="search" :size="15" />
        </span>
        <label for="destination-search" class="sr-only">Cari destinasi</label>
        <input id="destination-search" type="text" name="q" value="{{ request('q') }}"
               placeholder="Cari nama atau lokasi" class="field py-2.5 pl-9">
    </div>
    <button type="submit" class="btn btn-secondary btn-sm">Cari</button>
    @if(request()->filled('q'))
        <a href="{{ route('admin.destinations.index') }}" class="text-xs font-semibold text-charcoal/70 transition hover:text-forest">Reset</a>
    @endif
    <p class="ml-auto text-xs text-charcoal/70">{{ $destinations->total() }} destinasi</p>
</form>

<div class="admin-card overflow-hidden">
    @if($destinations->isEmpty())
        <x-empty-state
            icon="map-pin"
            :title="request()->filled('q') ? 'Destinasi tidak ditemukan' : 'Belum ada destinasi'"
            :description="request()->filled('q') ? 'Coba kata kunci lain atau reset pencarian.' : 'Tambahkan destinasi pertama untuk mulai menyusun katalog.'"
            class="py-16">
            <a href="{{ request()->filled('q') ? route('admin.destinations.index') : route('admin.destinations.create') }}" class="btn btn-primary btn-sm">
                {{ request()->filled('q') ? 'Reset pencarian' : 'Tambah destinasi' }}
            </a>
        </x-empty-state>
    @else
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Destinasi</th>
                        <th>Lokasi</th>
                        <th>Aktivitas</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($destinations as $destination)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <img src="{{ $destination->imageUrl() }}" alt=""
                                         class="h-11 w-16 shrink-0 rounded-lg object-cover" width="128" height="88" loading="lazy">
                                    <div class="min-w-0">
                                        <p class="font-semibold text-charcoal/85">{{ $destination->name }}</p>
                                        <p class="mt-0.5 max-w-[260px] truncate text-xs text-charcoal/70">{{ $destination->description ?: 'Belum ada deskripsi' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-charcoal/70">
                                <span class="inline-flex items-center gap-1.5">
                                    <x-icon name="map-pin" :size="14" class="text-charcoal/70" />
                                    {{ $destination->location ?: 'Belum diisi' }}
                                </span>
                            </td>
                            <td>
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-sand-light px-2.5 py-1 text-[11px] font-semibold text-charcoal/70">
                                    {{ $destination->activities_count }} aktivitas
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.destinations.edit', $destination) }}" class="admin-action admin-action-edit">
                                        <x-icon name="edit" :size="14" />
                                        Edit
                                    </a>
                                    @if($destination->activities_count > 0)
                                        <span class="inline-flex cursor-not-allowed items-center px-2.5 py-1.5 text-xs text-charcoal/70"
                                              title="Masih memiliki {{ $destination->activities_count }} aktivitas">
                                            Hapus
                                        </span>
                                    @else
                                        <form action="{{ route('admin.destinations.destroy', $destination) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Hapus destinasi ini?')">
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

@if($destinations->hasPages())
    <div class="pagination-wrap mt-5">{{ $destinations->links() }}</div>
@endif
@endsection
