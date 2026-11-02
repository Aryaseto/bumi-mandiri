@extends('layouts.app')

@section('title', 'Galeri | Bumi Mandiri')
@section('meta_description', 'Lihat suasana aktivitas, area camping, dan momen kebersamaan di Bumi Mandiri Sukabumi.')

@section('content')
<section class="border-b border-forest/10 bg-sand-light/60 pb-10 pt-12 lg:pt-16">
    <div class="container-page">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-earth">Galeri</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-forest sm:text-4xl">Suasana yang menunggu Anda.</h1>
        <p class="mt-3 max-w-2xl text-[15px] leading-relaxed text-charcoal/65">
            Kumpulan momen dari aktivitas dan area kunjungan di Bumi Mandiri.
        </p>

        <div class="no-scrollbar mt-7 flex gap-2 overflow-x-auto pb-1">
            <a href="{{ route('gallery.index') }}"
               class="whitespace-nowrap rounded-full px-4 py-2 text-xs font-semibold transition {{ ! request('category') ? 'bg-forest text-cream' : 'border border-forest/10 bg-white text-forest hover:border-forest/25' }}">
                Semua
            </a>
            @foreach($categories as $category)
                <a href="{{ route('gallery.index', ['category' => $category->slug]) }}"
                   class="whitespace-nowrap rounded-full px-4 py-2 text-xs font-semibold transition {{ request('category') === $category->slug ? 'bg-forest text-cream' : 'border border-forest/10 bg-white text-forest hover:border-forest/25' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="container-page py-12 lg:py-16" x-data="{ lightbox: null }" @keydown.escape.window="lightbox = null">
    @if($gallery->isEmpty())
        <div class="rounded-2xl border border-dashed border-forest/20 px-6 py-20 text-center">
            <h2 class="font-heading text-xl font-bold text-forest">Belum ada foto di kategori ini.</h2>
            <a href="{{ route('gallery.index') }}" class="btn btn-secondary btn-sm mt-5">Lihat semua foto</a>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($gallery as $item)
                <button type="button"
                        @click="lightbox = {{ Js::from(['src' => $item->imageUrl(), 'title' => $item->title, 'category' => $item->category?->name]) }}"
                        class="group relative block w-full overflow-hidden rounded-2xl bg-sand text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-earth focus-visible:ring-offset-2"
                        aria-label="Buka foto {{ $item->title }}">
                    <img src="{{ $item->imageUrl() }}" alt="{{ $item->title }}"
                         class="aspect-[4/3] w-full object-cover transition duration-700 group-hover:scale-[1.03]"
                         loading="lazy" width="800" height="600">
                    <div class="absolute inset-0 bg-gradient-to-t from-forest-dark/90 via-forest-dark/45 to-transparent transition group-hover:from-forest-dark/95"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <p class="text-sm font-semibold text-cream">{{ $item->title }}</p>
                        <p class="mt-0.5 text-[11px] text-cream/90">{{ $item->category?->name }}</p>
                    </div>
                </button>
            @endforeach
        </div>
        <div class="pagination-wrap mt-8">{{ $gallery->withQueryString()->links() }}</div>
    @endif

    <div x-show="lightbox" x-cloak x-transition.opacity
         class="fixed inset-0 z-[70] flex items-center justify-center bg-forest-dark/95 p-4 sm:p-8"
         @click.self="lightbox = null"
         role="dialog" aria-modal="true" aria-label="Pratinjau foto">
        <button type="button" @click="lightbox = null"
                class="absolute right-4 top-4 flex h-11 w-11 items-center justify-center rounded-full bg-cream/10 text-cream transition hover:bg-cream/20"
                aria-label="Tutup pratinjau">
            <x-icon name="x" :size="22" />
        </button>
        <template x-if="lightbox">
            <figure class="max-h-full w-full max-w-4xl">
                <img :src="lightbox.src" :alt="lightbox.title" class="mx-auto max-h-[78vh] w-auto rounded-xl object-contain">
                <figcaption class="mt-4 text-center">
                    <p class="text-sm font-semibold text-cream" x-text="lightbox.title"></p>
                    <p class="mt-1 text-xs text-cream/70" x-text="lightbox.category"></p>
                </figcaption>
            </figure>
        </template>
    </div>
</section>
@endsection
