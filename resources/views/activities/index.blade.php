@extends('layouts.app')

@section('title', 'Aktivitas Outdoor | Bumi Mandiri')
@section('meta_description', 'Jelajahi tubing, camping, outbound, archery, dan aktivitas edukatif di Bumi Mandiri Sukabumi.')

@section('content')
<section class="border-b border-forest/10 bg-sand-light/60 pb-12 pt-12 lg:pt-16" x-data="{ filtersOpen: false }">
    <div class="container-page">
        <div class="flex flex-wrap items-end justify-between gap-5">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-earth">Katalog aktivitas</p>
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-forest sm:text-4xl">Temukan aktivitas yang pas.</h1>
                <p class="mt-3 max-w-2xl text-[15px] leading-relaxed text-charcoal/65">
                    Saring berdasarkan jenis dan durasi. Setiap aktivitas bisa ditanyakan lebih dulu sebelum dipesan.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                @if($siteRatingCount > 0)
                    <a href="{{ route('home') }}#rating-pengunjung"
                       class="inline-flex items-center gap-1.5 rounded-full border border-forest/10 bg-white px-3 py-1.5 text-xs text-charcoal/65 transition hover:border-forest/25 hover:text-forest">
                        <x-stars :rating="$siteRating" :size="12" />
                        <span class="font-semibold text-forest">{{ number_format($siteRating, 1) }}</span>
                        <span>&middot; {{ $siteRatingCount }} rating</span>
                    </a>
                @endif
                <p class="text-sm text-charcoal/70">{{ $activities->total() }} aktivitas ditemukan</p>
            </div>
        </div>

        <div class="mt-8 flex flex-wrap items-center gap-3 lg:hidden">
            <button type="button" @click="filtersOpen = !filtersOpen"
                    class="btn btn-secondary btn-sm" :aria-expanded="filtersOpen" aria-controls="activity-filters">
                <x-icon name="settings" :size="15" />
                Filter
            </button>
            <a href="{{ route('activities.index') }}" class="text-sm font-medium text-charcoal/70 hover:text-forest">Reset</a>
        </div>

        <div class="mt-8 grid gap-8 lg:mt-10 lg:grid-cols-[280px_1fr] lg:gap-10">
            <aside id="activity-filters" x-show="filtersOpen || window.innerWidth >= 1024" x-cloak class="lg:!block">
                <form method="GET" class="rounded-2xl border border-forest/10 bg-white p-5 lg:sticky lg:top-24">
                    <div>
                        <label for="activity-search" class="field-label">Cari nama aktivitas</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-charcoal/70">
                                <x-icon name="search" :size="16" />
                            </span>
                            <input id="activity-search" type="text" name="q" value="{{ request('q') }}"
                                   placeholder="Tubing, camping..." class="field pl-10">
                        </div>
                    </div>

                    <div class="mt-6">
                        <p class="field-label">Kategori</p>
                        <div class="space-y-2 text-sm">
                            <label class="flex cursor-pointer items-center gap-2.5 text-charcoal/70">
                                <input type="radio" name="category" value="" @checked(! request('category'))
                                       class="h-4 w-4 border-sand text-forest focus:ring-forest/30">
                                Semua kategori
                            </label>
                            @foreach($categories as $cat)
                                <label class="flex cursor-pointer items-center gap-2.5 text-charcoal/70">
                                    <input type="radio" name="category" value="{{ $cat->slug }}" @checked(request('category') === $cat->slug)
                                           class="h-4 w-4 border-sand text-forest focus:ring-forest/30">
                                    <span class="flex-1">{{ $cat->name }}</span>
                                    <span class="text-xs text-charcoal/70">{{ $cat->activities_count }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    @if($hasReviews)
                    <div class="mt-6">
                        <p class="field-label">Rating</p>
                        <div class="space-y-2 text-sm">
                            <label class="flex cursor-pointer items-center gap-2.5 text-charcoal/70">
                                <input type="radio" name="rating" value="" @checked(! request('rating'))
                                       class="h-4 w-4 border-sand text-forest focus:ring-forest/30">
                                Semua rating
                            </label>
                            @foreach(['4' => '4.0 ke atas', '4.5' => '4.5 ke atas'] as $value => $label)
                                <label class="flex cursor-pointer items-center gap-2.5 text-charcoal/70">
                                    <input type="radio" name="rating" value="{{ $value }}" @checked(request('rating') == $value)
                                           class="h-4 w-4 border-sand text-forest focus:ring-forest/30">
                                    <span class="inline-flex items-center gap-1">
                                        <x-icon name="star" :size="13" class="fill-current text-earth" />
                                        {{ $label }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="mt-6">
                        <p class="field-label">Durasi</p>
                        <div class="space-y-2 text-sm">
                            <label class="flex cursor-pointer items-center gap-2.5 text-charcoal/70">
                                <input type="radio" name="duration" value="" @checked(! request('duration'))
                                       class="h-4 w-4 border-sand text-forest focus:ring-forest/30">
                                Semua durasi
                            </label>
                            @foreach(['short' => 'Kurang dari 1,5 jam', 'medium' => '1,5 sampai 4 jam', 'long' => 'Lebih dari 4 jam'] as $value => $label)
                                <label class="flex cursor-pointer items-center gap-2.5 text-charcoal/70">
                                    <input type="radio" name="duration" value="{{ $value }}" @checked(request('duration') === $value)
                                           class="h-4 w-4 border-sand text-forest focus:ring-forest/30">
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-7 space-y-2">
                        <button type="submit" class="btn btn-primary w-full">Terapkan Filter</button>
                        <a href="{{ route('activities.index') }}" class="block text-center text-xs font-medium text-charcoal/70 transition hover:text-forest">
                            Reset semua filter
                        </a>
                    </div>
                </form>
            </aside>

            <div>
                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap gap-2">
                        @if(request('q'))
                            <span class="rounded-full bg-white px-3 py-1.5 text-xs text-charcoal/65 ring-1 ring-forest/10">Pencarian: {{ request('q') }}</span>
                        @endif
                        @if(request('category'))
                            <span class="rounded-full bg-white px-3 py-1.5 text-xs text-charcoal/65 ring-1 ring-forest/10">Kategori: {{ $categories->firstWhere('slug', request('category'))?->name }}</span>
                        @endif
                        @if(request('rating'))
                            <span class="rounded-full bg-white px-3 py-1.5 text-xs text-charcoal/65 ring-1 ring-forest/10">Rating {{ request('rating') }}+</span>
                        @endif
                        @if(request('duration'))
                            <span class="rounded-full bg-white px-3 py-1.5 text-xs text-charcoal/65 ring-1 ring-forest/10">Durasi: {{ ucfirst(request('duration')) }}</span>
                        @endif
                    </div>

                    <form method="GET" class="ml-auto">
                        @foreach(request()->except('sort') as $key => $value)
                            @if(is_string($value) && $value !== '')
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <label for="sort" class="sr-only">Urutkan aktivitas</label>
                        <select id="sort" name="sort" onchange="this.form.submit()" class="field w-auto py-2.5 pr-9 text-xs">
                            @php($currentSort = request('sort', $hasReviews ? 'popular' : 'price_low'))
                            <option value="popular" @selected($currentSort === 'popular')>Paling populer</option>
                            @if($hasReviews)
                                <option value="rating" @selected($currentSort === 'rating')>Rating tertinggi</option>
                            @endif
                            <option value="price_low" @selected($currentSort === 'price_low')>Harga terendah</option>
                            <option value="price_high" @selected($currentSort === 'price_high')>Harga tertinggi</option>
                        </select>
                    </form>
                </div>

                @if($activities->isEmpty())
                    <div class="rounded-2xl border border-dashed border-forest/20 bg-white/60 px-6 py-20 text-center">
                        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-sand-light text-charcoal/70">
                            <x-icon name="search" :size="24" />
                        </span>
                        <h2 class="mt-4 font-heading text-xl font-bold text-forest">Belum ada aktivitas yang cocok.</h2>
                        <p class="mx-auto mt-2 max-w-sm text-sm text-charcoal/70">Coba kata kunci lain atau lepaskan beberapa filter.</p>
                        <a href="{{ route('activities.index') }}" class="btn btn-secondary btn-sm mt-6">Tampilkan semua aktivitas</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        @foreach($activities as $activity)
                            <x-activity-card :activity="$activity" />
                        @endforeach
                    </div>
                    <div class="pagination-wrap mt-9">{{ $activities->withQueryString()->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
