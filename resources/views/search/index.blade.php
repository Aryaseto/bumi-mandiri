@extends('layouts.app')

@section('title', 'Pencarian | Bumi Mandiri')

@section('content')
<section class="border-b border-forest/10 bg-sand-light/60 pb-10 pt-12 lg:pt-16">
    <div class="container-page">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-earth">Pencarian</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-forest sm:text-4xl">
            @if($keyword !== '')
                Hasil untuk "{{ $keyword }}"
            @else
                Cari aktivitas atau paket
            @endif
        </h1>

        <form action="{{ route('search') }}" method="GET" class="mt-6 flex max-w-2xl flex-col gap-3 sm:flex-row">
            <div class="relative flex-1">
                <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-charcoal/70">
                    <x-icon name="search" :size="18" />
                </span>
                <label for="search-page" class="sr-only">Kata kunci</label>
                <input id="search-page" type="text" name="q" value="{{ $keyword }}" placeholder="Tubing, camping, outbound..."
                       class="field bg-white pl-11">
            </div>
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>
    </div>
</section>

<section class="container-page py-12 lg:py-16">
    @if($keyword === '')
        <div class="rounded-2xl border border-dashed border-forest/20 px-6 py-16 text-center">
            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-sand-light text-charcoal/70">
                <x-icon name="search" :size="24" />
            </span>
            <h2 class="mt-4 font-heading text-xl font-bold text-forest">Mulai dengan kata kunci.</h2>
            <p class="mx-auto mt-2 max-w-sm text-sm text-charcoal/70">Coba "tubing", "camping", atau "outbound".</p>
        </div>
    @elseif($activities->isEmpty() && $packages->isEmpty() && $articles->isEmpty())
        <div class="rounded-2xl border border-dashed border-forest/20 px-6 py-16 text-center">
            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-sand-light text-charcoal/70">
                <x-icon name="search" :size="24" />
            </span>
            <h2 class="mt-4 font-heading text-xl font-bold text-forest">Tidak ada hasil yang cocok.</h2>
            <p class="mx-auto mt-2 max-w-sm text-sm text-charcoal/70">Coba kata kunci yang lebih singkat atau lihat semua aktivitas.</p>
            <a href="{{ route('activities.index') }}" class="btn btn-secondary btn-sm mt-5">Lihat semua aktivitas</a>
        </div>
    @else
        @if($activities->isNotEmpty())
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-extrabold tracking-tight text-forest">Aktivitas</h2>
                <span class="text-sm text-charcoal/70">{{ $activities->count() }} hasil</span>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($activities as $activity)
                    <x-activity-card :activity="$activity" />
                @endforeach
            </div>
        @endif

        @if($packages->isNotEmpty())
            <div class="mb-4 mt-12 flex items-center justify-between">
                <h2 class="text-xl font-extrabold tracking-tight text-forest">Paket</h2>
                <span class="text-sm text-charcoal/70">{{ $packages->count() }} hasil</span>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach($packages as $package)
                    <a href="{{ route('packages.show', $package) }}"
                       class="group flex gap-4 rounded-2xl border border-forest/10 bg-white p-4 transition hover:border-forest/20 hover:shadow-soft">
                        <div class="h-20 w-24 shrink-0 overflow-hidden rounded-xl bg-sand">
                            <img src="{{ $package->imageUrl() }}" alt="" class="h-full w-full object-cover" loading="lazy" width="200" height="160">
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-earth">{{ $package->target_segment }}</p>
                            <h3 class="mt-1 font-heading text-sm font-bold text-forest group-hover:text-earth">{{ $package->name }}</h3>
                            <p class="mt-1 text-xs text-charcoal/70">Mulai Rp {{ number_format($package->base_price, 0, ',', '.') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        @if($articles->isNotEmpty())
            <div class="mb-4 mt-12 flex items-center justify-between">
                <h2 class="text-xl font-extrabold tracking-tight text-forest">Cerita</h2>
                <span class="text-sm text-charcoal/70">{{ $articles->count() }} hasil</span>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($articles as $article)
                    <a href="{{ route('articles.show', $article) }}"
                       class="group rounded-2xl border border-forest/10 bg-white p-5 transition hover:border-forest/20 hover:shadow-soft">
                        <p class="text-[11px] text-charcoal/70">{{ $article->reading_time }} menit baca</p>
                        <h3 class="mt-2 font-heading text-sm font-bold leading-snug text-forest group-hover:text-earth">{{ $article->title }}</h3>
                        <p class="mt-2 line-clamp-2 text-xs leading-relaxed text-charcoal/70">{{ $article->excerpt }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    @endif
</section>
@endsection
