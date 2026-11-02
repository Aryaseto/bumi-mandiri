@extends('layouts.admin')

@section('title', 'Paket | Admin Bumi Mandiri')
@section('page_title', 'Paket')

@section('content')
<x-admin.page-header
    eyebrow="Katalog"
    title="Paket"
    description="Gabungkan beberapa aktivitas menjadi paket untuk segmen pengunjung tertentu.">
    <a href="{{ route('admin.packages.create') }}" class="btn btn-primary btn-sm">
        <x-icon name="plus" :size="15" />
        Tambah paket
    </a>
</x-admin.page-header>

<div class="admin-card overflow-hidden">
    @if($packages->isEmpty())
        <x-empty-state icon="package" title="Belum ada paket" description="Paket membantu pengunjung memilih rangkaian aktivitas yang sudah disusun." class="py-16">
            <a href="{{ route('admin.packages.create') }}" class="btn btn-primary btn-sm">Tambah paket</a>
        </x-empty-state>
    @else
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Paket</th>
                        <th>Segmen</th>
                        <th>Harga dasar</th>
                        <th>Aktivitas</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packages as $package)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <img src="{{ $package->imageUrl() }}" alt=""
                                         class="h-11 w-16 shrink-0 rounded-lg object-cover" width="128" height="88" loading="lazy">
                                    <div class="min-w-0">
                                        <p class="font-semibold text-charcoal/85">{{ $package->name }}</p>
                                        <p class="mt-0.5 max-w-[260px] truncate text-xs text-charcoal/70">{{ $package->description ?: 'Belum ada deskripsi' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="capitalize text-charcoal/70">{{ $package->target_segment ?: 'Umum' }}</td>
                            <td class="whitespace-nowrap font-semibold text-forest">Rp {{ number_format($package->base_price, 0, ',', '.') }}</td>
                            <td>
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-sand-light px-2.5 py-1 text-[11px] font-semibold text-charcoal/70">
                                    {{ $package->activities_count }} aktivitas
                                </span>
                            </td>
                            <td><x-status-badge :status="$package->is_active ? 'active' : 'inactive'" /></td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.packages.edit', $package) }}" class="admin-action admin-action-edit">
                                        <x-icon name="edit" :size="14" />
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" class="inline" onsubmit="return confirm('Hapus paket ini?')">
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

@if($packages->hasPages())
    <div class="pagination-wrap mt-5">{{ $packages->links() }}</div>
@endif
@endsection
