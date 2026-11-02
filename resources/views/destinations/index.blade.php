@extends('layouts.app')

@section('title', 'Destinasi | Bumi Mandiri')
@section('meta_description', 'Jelajahi area dan destinasi kunjungan di Bumi Mandiri Sukabumi.')

@section('content')
<section class="border-b border-forest/10 bg-sand-light/60 pb-12 pt-12 lg:pt-16">
    <div class="container-page">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-earth">Area kunjungan</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-forest sm:text-4xl">Destinasi di Bumi Mandiri.</h1>
        <p class="mt-3 max-w-2xl text-[15px] leading-relaxed text-charcoal/65">
            Setiap area punya karakter dan aktivitasnya sendiri. Pilih lokasi yang paling sesuai dengan rencana kunjungan Anda.
        </p>
    </div>
</section>

<section class="container-page py-14 lg:py-20">
    @if($destinations->isEmpty())
        <div class="rounded-2xl border border-dashed border-forest/20 px-6 py-20 text-center">
            <h2 class="font-heading text-xl font-bold text-forest">Destinasi sedang disiapkan.</h2>
            <p class="mt-2 text-sm text-charcoal/70">Hubungi kami untuk informasi area yang tersedia.</p>
        </div>
    @else
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($destinations as $destination)
                <a href="{{ route('destinations.show', $destination) }}"
                   class="group overflow-hidden rounded-2xl border border-forest/10 bg-white transition hover:-translate-y-1 hover:shadow-lift">
                    <div class="relative aspect-[16/10] overflow-hidden bg-sand">
                        <img src="{{ $destination->imageUrl() }}" alt="{{ $destination->name }}"
                             class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]" loading="lazy" width="800" height="500">
                        <div class="absolute inset-0 bg-gradient-to-t from-forest-dark/40 to-transparent"></div>
                        <span class="absolute bottom-3 left-3 rounded-full bg-cream/95 px-3 py-1 text-[11px] font-semibold text-forest">
                            {{ $destination->activities_count }} aktivitas
                        </span>
                    </div>
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h2 class="font-heading text-lg font-bold text-forest">{{ $destination->name }}</h2>
                                <p class="mt-1 inline-flex items-center gap-1 text-xs text-charcoal/70">
                                    <x-icon name="map-pin" :size="13" class="text-earth" />
                                    {{ $destination->location ?: 'Sukabumi' }}
                                </p>
                            </div>
                            <x-icon name="arrow-up-right" :size="17" class="mt-1 text-charcoal/70 transition group-hover:text-earth" />
                        </div>
                        <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-charcoal/70">{{ $destination->description }}</p>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="pagination-wrap mt-9">{{ $destinations->links() }}</div>
    @endif
</section>
@endsection
