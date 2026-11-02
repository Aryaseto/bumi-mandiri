@extends('layouts.admin')

@section('title', 'Kategori | Admin Bumi Mandiri')
@section('page_title', 'Kategori')

@section('content')
<x-admin.page-header
    eyebrow="Katalog"
    title="Kategori"
    description="Kelompokkan aktivitas agar pengunjung mudah menemukan jenis pengalaman yang diinginkan.">
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
        <x-icon name="plus" :size="15" />
        Tambah kategori
    </a>
</x-admin.page-header>

<div class="admin-card overflow-hidden">
    @if($categories->isEmpty())
        <x-empty-state icon="tag" title="Belum ada kategori" description="Kategori diperlukan sebelum aktivitas dapat ditambahkan." class="py-16">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">Tambah kategori</a>
        </x-empty-state>
    @else
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Slug</th>
                        <th>Aktivitas</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-sand-light text-forest">
                                        <x-icon name="tag" :size="16" />
                                    </span>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-charcoal/85">{{ $category->name }}</p>
                                        @if($category->description)
                                            <p class="mt-0.5 max-w-md truncate text-xs text-charcoal/70">{{ $category->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td><span class="font-mono text-xs text-charcoal/70">{{ $category->slug }}</span></td>
                            <td>
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-sand-light px-2.5 py-1 text-[11px] font-semibold text-charcoal/70">
                                    {{ $category->activities_count }} aktivitas
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="admin-action admin-action-edit">
                                        <x-icon name="edit" :size="14" />
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?')">
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

@if($categories->hasPages())
    <div class="pagination-wrap mt-5">{{ $categories->links() }}</div>
@endif
@endsection
