@extends('layouts.app')

@section('title', $destination->name . ' | Bumi Mandiri')
@section('meta_description', $destination->description)

@section('content')
<section class="relative flex min-h-[360px] items-end overflow-hidden">
    <img src="{{ $destination->imageUrl() }}" alt="{{ $destination->name }}"
         class="absolute inset-0 h-full w-full object-cover" width="1400" height="800" fetchpriority="high">
    <div class="absolute inset-0 bg-gradient-to-t from-forest-dark/90 via-forest-dark/40 to-forest-dark/15"></div>
    <div class="container-page relative z-10 py-10 text-cream">
        <nav class="flex flex-wrap items-center gap-1.5 text-xs text-cream/70" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="transition hover:text-cream">Beranda</a>
            <x-icon name="chevron-right" :size="13" />
            <a href="{{ route('destinations.index') }}" class="transition hover:text-cream">Destinasi</a>
            <x-icon name="chevron-right" :size="13" />
            <span class="text-cream/80">{{ $destination->name }}</span>
        </nav>
        <h1 class="mt-4 text-3xl font-extrabold tracking-tight sm:text-4xl">{{ $destination->name }}</h1>
        <p class="mt-2 inline-flex items-center gap-1.5 text-sm text-cream/70">
            <x-icon name="map-pin" :size="15" class="text-earth-light" />
            {{ $destination->location ?: 'Sukabumi, Jawa Barat' }}
        </p>
    </div>
</section>

<section class="container-page py-14 lg:py-20">
    <div class="grid gap-10 lg:grid-cols-12">
        <div class="lg:col-span-8">
            <h2 class="text-2xl font-extrabold tracking-tight text-forest">Tentang area ini</h2>
            <p class="mt-4 max-w-prose text-[15px] leading-relaxed text-charcoal/70">{{ $destination->description }}</p>

            <div class="mt-12 flex flex-wrap items-end justify-between gap-4">
                <h2 class="text-2xl font-extrabold tracking-tight text-forest">Aktivitas di sini</h2>
                <p class="text-sm text-charcoal/70">{{ $destination->activities->count() }} aktivitas</p>
            </div>

            @if($destination->activities->isNotEmpty())
                <div class="mt-6 grid gap-6 sm:grid-cols-2">
                    @foreach($destination->activities as $activity)
                        <x-activity-card :activity="$activity" />
                    @endforeach
                </div>
            @else
                <div class="mt-6 rounded-2xl border border-dashed border-forest/20 px-6 py-12 text-center text-sm text-charcoal/70">
                    Belum ada aktivitas di area ini.
                </div>
            @endif
        </div>

        <aside class="lg:col-span-4">
            <div class="rounded-2xl border border-forest/10 bg-white p-6 lg:sticky lg:top-24">
                <h3 class="font-heading text-base font-bold text-forest">Rencanakan kunjungan</h3>
                <p class="mt-2 text-sm leading-relaxed text-charcoal/70">
                    Sampaikan jumlah peserta dan tanggal kunjungan. Tim kami akan membantu menyusun aktivitas yang sesuai.
                </p>
                <a href="{{ route('contact.create') }}" class="btn btn-primary mt-5 w-full">
                    Hubungi Bumi Mandiri
                    <x-icon name="arrow-right" :size="16" />
                </a>
                <a href="{{ route('activities.index') }}" class="btn btn-secondary mt-2 w-full">Lihat Semua Aktivitas</a>

                <div class="mt-6 space-y-2.5 border-t border-sand/70 pt-5 text-sm">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-charcoal/70">Jumlah aktivitas</span>
                        <span class="font-semibold text-forest">{{ $destination->activities->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-charcoal/70">Lokasi</span>
                        <span class="font-semibold text-forest">{{ $destination->location ?: 'Sukabumi' }}</span>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</section>
@endsection
