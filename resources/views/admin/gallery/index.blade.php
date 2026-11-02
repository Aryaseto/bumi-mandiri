@extends('layouts.admin')

@section('title', 'Galeri | Admin Bumi Mandiri')
@section('page_title', 'Galeri')

@section('content')
<x-admin.page-header
    eyebrow="Konten"
    title="Galeri"
    description="Foto yang tampil di halaman galeri dan beranda. Gunakan foto asli dengan pencahayaan yang baik.">
    <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary btn-sm">
        <x-icon name="plus" :size="15" />
        Unggah foto
    </a>
</x-admin.page-header>

@if($gallery->isEmpty())
    <x-empty-state icon="photo" title="Belum ada foto" description="Unggah foto pertama untuk memperlihatkan suasana Bumi Mandiri." class="rounded-2xl border border-dashed border-forest/20 bg-white/60">
        <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary btn-sm">Unggah foto</a>
    </x-empty-state>
@else
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach($gallery as $item)
            <article class="group overflow-hidden rounded-2xl border border-forest/10 bg-white">
                <div class="relative aspect-[4/3] overflow-hidden bg-sand">
                    <img src="{{ $item->imageUrl() }}" alt="{{ $item->title }}"
                         class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                         width="640" height="480" loading="lazy">
                    @if($item->is_featured)
                        <span class="absolute left-3 top-3 rounded-full bg-cream/95 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-forest">Beranda</span>
                    @endif
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h2 class="truncate text-sm font-semibold text-forest">{{ $item->title }}</h2>
                            <p class="mt-0.5 text-xs text-charcoal/70">{{ $item->category?->name ?? 'Tanpa kategori' }}</p>
                        </div>
                        <span class="shrink-0 text-[10px] text-charcoal/70">{{ $item->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="mt-3 flex items-center gap-1 border-t border-sand/70 pt-3">
                        <a href="{{ route('admin.gallery.edit', $item) }}" class="admin-action admin-action-edit">
                            <x-icon name="edit" :size="14" />
                            Edit
                        </a>
                        <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST" class="ml-auto" onsubmit="return confirm('Hapus foto ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-action admin-action-delete">
                                <x-icon name="trash" :size="14" />
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
@endif

@if($gallery->hasPages())
    <div class="pagination-wrap mt-6">{{ $gallery->links() }}</div>
@endif
@endsection
