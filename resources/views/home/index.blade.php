@extends('layouts.app')

@section('title', 'Bumi Mandiri | Wisata Alam & Aktivitas Outdoor Sukabumi')
@section('meta_description', 'Aktivitas outdoor, camping, tubing, outbound, dan paket kunjungan di Bumi Mandiri Sukabumi untuk keluarga, sekolah, komunitas, dan perusahaan.')

@section('content')
@php
    $address = \App\Models\Setting::get('address', 'Sukabumi, Jawa Barat');
    $email = \App\Models\Setting::get('email');
    $whatsapp = \App\Models\Setting::get('whatsapp');
    $hasWhatsapp = $whatsapp && ! str_contains((string) $whatsapp, '[');
    $mapsUrl = \App\Models\Setting::get('google_maps_url');
    $hasMaps = $mapsUrl && ! str_contains((string) $mapsUrl, '[');
    $mapLink = $hasMaps ? $mapsUrl : 'https://www.google.com/maps/search/?api=1&query=' . urlencode($address);
    $categoryIcons = [
        'adventure' => 'mountain',
        'nature' => 'leaf',
        'team-building' => 'users-group',
        'skill' => 'target',
        'education' => 'book',
    ];
@endphp

{{-- HERO --}}
<section class="relative flex min-h-[640px] items-center overflow-hidden lg:min-h-[740px]">
    <img src="{{ asset('images/hero.jpg') }}" alt="Perbukitan hijau Bumi Mandiri Sukabumi"
         class="absolute inset-0 h-full w-full object-cover" width="1400" height="933" fetchpriority="high">
    <div class="absolute inset-0 bg-gradient-to-r from-forest-dark/85 via-forest-dark/55 to-forest-dark/20"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-forest-dark/60 via-transparent to-transparent"></div>

    <div class="container-page relative z-10 py-24">
        <div class="max-w-2xl text-cream">
            <p class="eyebrow mb-5 flex items-center gap-2 text-cream/70">
                <span class="h-px w-8 bg-earth-light" aria-hidden="true"></span>
                Bumi Mandiri &middot; Sukabumi
            </p>

            <h1 class="text-4xl font-extrabold leading-[1.06] tracking-tight sm:text-5xl lg:text-6xl">
                Alam yang dekat,<br>cerita yang tinggal.
            </h1>

            <p class="mt-5 max-w-xl text-base leading-relaxed text-cream/75 sm:text-lg">
                Aktivitas outdoor untuk keluarga, sekolah, komunitas, dan tim perusahaan.
            </p>

            @if($siteRatingCount > 0)
                <a href="#rating-pengunjung" class="mt-6 inline-flex flex-wrap items-center gap-x-2 gap-y-1 rounded-full border border-cream/15 bg-cream/[0.08] px-4 py-2 text-xs text-cream/75 backdrop-blur-sm transition hover:border-cream/30 hover:bg-cream/[0.14]">
                    <x-stars :rating="$siteRating" :size="13" tone="text-earth-light" />
                    <span class="font-semibold text-cream">{{ number_format($siteRating, 1) }}</span>
                    <span class="text-cream/60">dari {{ $siteRatingCount }} rating pengunjung</span>
                </a>
            @endif

            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('activities.index') }}" class="btn btn-light">
                    Lihat Aktivitas
                    <x-icon name="arrow-right" :size="17" />
                </a>
                <a href="{{ route('contact.create') }}" class="btn btn-ghost-light">
                    Rencanakan Rombongan
                </a>
            </div>
        </div>
    </div>
</section>

{{-- SEARCH --}}
<section class="relative z-20 -mt-8 px-5 sm:px-6 lg:px-10">
    <div class="mx-auto max-w-5xl rounded-2xl border border-forest/10 bg-white p-5 shadow-lift sm:p-6">
        <form action="{{ route('search') }}" method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-end">
            <div class="flex-1">
                <label for="home-search" class="field-label">Cari aktivitas atau paket</label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-charcoal/70">
                        <x-icon name="search" :size="18" />
                    </span>
                    <input id="home-search" type="text" name="q" placeholder="Tubing, camping, outbound..."
                           class="field pl-11" autocomplete="off">
                </div>
            </div>
            <button type="submit" class="btn btn-primary sm:mb-0">
                Cari Pengalaman
            </button>
        </form>

        <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-sand/70 pt-4">
            <span class="mr-1 text-xs text-charcoal/70">Populer:</span>
            @foreach($categories->take(5) as $category)
                <a href="{{ route('activities.index', ['category' => $category->slug]) }}"
                   class="rounded-full border border-forest/10 bg-sand-light px-3 py-1.5 text-xs font-medium text-forest transition hover:border-forest/25 hover:bg-sand">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- CATEGORIES --}}
<section class="container-page py-16 lg:py-20">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <x-section-heading
            title="Pilih cara Anda menikmati alam"
            description="Dari arus sungai sampai api unggun, setiap aktivitas punya tempo dan ceritanya sendiri." />
        <a href="{{ route('activities.index') }}" class="link-underline text-sm">
            Semua aktivitas
        </a>
    </div>

    <div class="mt-9 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($categories as $index => $category)
            @if($index === 0)
                <a href="{{ route('activities.index', ['category' => $category->slug]) }}"
                   class="group relative flex min-h-[250px] flex-col justify-end overflow-hidden rounded-2xl bg-forest p-6 text-cream sm:col-span-2 lg:row-span-2 lg:min-h-[420px]">
                    <img src="{{ asset('images/activities/adventure.jpg') }}" alt=""
                         class="absolute inset-0 h-full w-full object-cover opacity-70 transition duration-700 group-hover:scale-[1.04]" loading="lazy" width="1200" height="800">
                    <div class="absolute inset-0 bg-gradient-to-t from-forest-dark via-forest-dark/45 to-forest-dark/10"></div>
                    <div class="relative">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-cream/15 text-cream ring-1 ring-cream/20">
                            <x-icon name="{{ $categoryIcons[$category->slug] ?? 'compass' }}" :size="22" />
                        </span>
                        <h3 class="mt-4 text-2xl font-bold">{{ $category->name }}</h3>
                        <p class="mt-2 max-w-xs text-sm leading-relaxed text-cream/70">{{ $category->description }}</p>
                        <p class="mt-4 text-xs font-semibold uppercase tracking-wider text-cream/70">{{ $category->activities_count }} aktivitas</p>
                    </div>
                </a>
            @else
                <a href="{{ route('activities.index', ['category' => $category->slug]) }}"
                   class="group flex flex-col justify-between rounded-2xl border border-forest/10 bg-white p-5 transition hover:-translate-y-0.5 hover:border-forest/20 hover:shadow-soft">
                    <div>
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-sand-light text-forest transition group-hover:bg-forest group-hover:text-cream">
                            <x-icon name="{{ $categoryIcons[$category->slug] ?? 'compass' }}" :size="20" />
                        </span>
                        <h3 class="mt-3 font-heading text-base font-bold text-forest">{{ $category->name }}</h3>
                        <p class="mt-1.5 text-xs leading-relaxed text-charcoal/70">{{ $category->description }}</p>
                    </div>
                    <p class="mt-4 text-[11px] font-semibold uppercase tracking-wider text-earth">{{ $category->activities_count }} aktivitas</p>
                </a>
            @endif
        @endforeach
    </div>
</section>

{{-- POPULAR ACTIVITIES --}}
<section class="border-y border-forest/10 bg-sand-light/60 py-20 lg:py-24">
    <div class="container-page">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <x-section-heading
                title="Aktivitas yang paling sering dipilih"
                description="Pengalaman yang cocok untuk kunjungan santai maupun rombongan dengan jadwal terarah." />
            <a href="{{ route('activities.index') }}" class="link-underline text-sm">
                Jelajahi semua
            </a>
        </div>

        @if($popularActivities->isNotEmpty())
            <div class="mt-9 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($popularActivities->take(3) as $activity)
                    <x-activity-card :activity="$activity" />
                @endforeach
            </div>
        @else
            <div class="mt-9 rounded-2xl border border-dashed border-forest/20 p-10 text-center text-sm text-charcoal/70">
                Aktivitas sedang disiapkan. Silakan hubungi kami untuk informasi kunjungan.
            </div>
        @endif
    </div>
</section>

{{-- STORY --}}
<section class="container-page grid items-center gap-12 py-20 lg:grid-cols-2 lg:gap-16 lg:py-28">
    <div class="relative">
        <div class="overflow-hidden rounded-2xl">
            <img src="{{ asset('images/forest.jpg') }}" alt="Jalur hutan di area kunjungan Bumi Mandiri"
                 class="aspect-[4/5] w-full object-cover" loading="lazy" width="1000" height="1250">
        </div>
        <div class="absolute -bottom-6 -right-2 hidden w-48 overflow-hidden rounded-2xl border-4 border-cream shadow-lift sm:block lg:-right-6 lg:w-56">
            <img src="{{ asset('images/night.jpg') }}" alt="Tenda dan langit malam di Bumi Mandiri"
                 class="aspect-[4/3] w-full object-cover" loading="lazy" width="600" height="450">
        </div>
        <div class="absolute left-4 top-4 rounded-xl bg-cream/95 px-3 py-2 text-[11px] font-semibold text-forest shadow-soft">
            Basecamp &middot; Sukabumi
        </div>
    </div>

    <div>
        <h2 class="text-3xl font-extrabold leading-tight tracking-tight text-forest sm:text-4xl">
            Bukan sekadar tempat datang.
        </h2>
        <p class="mt-5 max-w-prose text-[15px] leading-relaxed text-charcoal/70">
            Bumi Mandiri adalah ruang terbuka untuk bermain, belajar, dan membangun kebersamaan.
            Kami menyiapkan aktivitas yang bisa dinikmati anak-anak, keluarga, sekolah, komunitas, hingga tim perusahaan.
        </p>

        <ul class="mt-6 space-y-3">
            <li class="flex items-start gap-3">
                <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-earth/10 text-earth">
                    <x-icon name="users" :size="14" />
                </span>
                <span class="text-sm leading-relaxed text-charcoal/70">Cocok untuk keluarga, sekolah, dan tim perusahaan dari 10 hingga ratusan peserta.</span>
            </li>
            <li class="flex items-start gap-3">
                <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-earth/10 text-earth">
                    <x-icon name="map-pin" :size="14" />
                </span>
                <span class="text-sm leading-relaxed text-charcoal/70">Berlokasi di Sukabumi, mudah dijangkau untuk kunjungan sehari maupun menginap.</span>
            </li>
            <li class="flex items-start gap-3">
                <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-earth/10 text-earth">
                    <x-icon name="calendar" :size="14" />
                </span>
                <span class="text-sm leading-relaxed text-charcoal/70">Paket dapat disesuaikan: pilih aktivitas, durasi, dan jadwal bersama tim kami.</span>
            </li>
        </ul>

        <div class="mt-8 space-y-4">
            @foreach([
                ['icon' => 'leaf', 'title' => 'Alam yang dekat', 'text' => 'Suasana hutan, sungai, dan area terbuka dalam satu kunjungan yang mudah dijangkau.'],
                ['icon' => 'users-group', 'title' => 'Cocok untuk rombongan', 'text' => 'Alur kegiatan dapat disusun untuk keluarga kecil maupun kelompok besar.'],
                ['icon' => 'shield-check', 'title' => 'Pendampingan di lapangan', 'text' => 'Setiap aktivitas diarahkan oleh pendamping agar berjalan aman dan tertib.'],
            ] as $item)
                <div class="flex gap-3.5">
                    <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-earth/10 text-earth">
                        <x-icon name="{{ $item['icon'] }}" :size="18" />
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-forest">{{ $item['title'] }}</h3>
                        <p class="mt-1 text-sm leading-relaxed text-charcoal/70">{{ $item['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <a href="{{ route('about') }}" class="link-underline mt-8 inline-block text-sm">
            Kenali Bumi Mandiri
        </a>
    </div>
</section>

{{-- IMMERSIVE BREAK --}}
<section class="relative flex min-h-[320px] items-end overflow-hidden">
    <img src="{{ asset('images/campfire.jpg') }}" alt="Peserta berkumpul di sekitar api unggun"
         class="absolute inset-0 h-full w-full object-cover" loading="lazy" width="1200" height="800">
    <div class="absolute inset-0 bg-gradient-to-t from-forest-dark/90 via-forest-dark/35 to-forest-dark/10"></div>
    <div class="container-page relative z-10 py-12">
        <p class="max-w-xl font-heading text-2xl font-bold leading-snug text-cream sm:text-3xl">
            Biarkan alam yang mengatur tempo.
        </p>
        <p class="mt-2 text-sm text-cream/70">Malam di Bumi Mandiri, ditemani api unggun dan cerita.</p>
    </div>
</section>

{{-- PACKAGES --}}
<section class="container-page py-20 lg:py-24">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <x-section-heading
            title="Paket kunjungan yang tinggal dipilih"
            description="Susunan aktivitas dan durasi yang lebih praktis untuk rombongan dengan kebutuhan berbeda." />
        <a href="{{ route('packages.index') }}" class="link-underline text-sm">
            Lihat semua paket
        </a>
    </div>

    <div class="mt-9 grid gap-5 lg:grid-cols-2">
        @forelse($packages as $package)
            <a href="{{ route('packages.show', $package) }}"
               class="group grid overflow-hidden rounded-2xl border border-forest/10 bg-white transition hover:border-forest/20 hover:shadow-lift sm:grid-cols-[42%_58%]">
                <div class="relative aspect-[4/3] overflow-hidden bg-sand sm:aspect-auto sm:h-full">
                    <img src="{{ $package->imageUrl() }}" alt="{{ $package->name }}"
                         class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]" loading="lazy" width="800" height="600">
                </div>
                <div class="flex flex-col p-5 sm:p-6">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-earth">{{ $package->target_segment }}</p>
                    <h3 class="mt-2 font-heading text-lg font-bold text-forest">{{ $package->name }}</h3>
                    <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-charcoal/70">{{ $package->description }}</p>

                    <div class="mt-4 flex flex-wrap gap-x-4 gap-y-1 text-xs text-charcoal/70">
                        @foreach($package->activities->take(3) as $activity)
                            <span class="inline-flex items-center gap-1">
                                <span class="h-1 w-1 rounded-full bg-earth" aria-hidden="true"></span>
                                {{ $activity->name }}
                            </span>
                        @endforeach
                    </div>

                    <div class="mt-auto flex items-end justify-between gap-3 border-t border-sand/70 pt-4">
                        <div>
                            <span class="block text-[10px] font-medium uppercase tracking-wider text-charcoal/70">Mulai dari</span>
                            <span class="font-heading text-base font-extrabold text-forest">Rp {{ number_format($package->base_price, 0, ',', '.') }}</span>
                            <span class="text-xs text-charcoal/70">/paket</span>
                        </div>
                        <span class="inline-flex items-center gap-1 text-sm font-semibold text-forest transition group-hover:text-earth">
                            Detail
                            <x-icon name="arrow-right" :size="15" />
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="rounded-2xl border border-dashed border-forest/20 p-10 text-center text-sm text-charcoal/70 lg:col-span-2">
                Paket kunjungan sedang disiapkan. Hubungi kami untuk menyusun rencana perjalanan.
            </div>
        @endforelse
    </div>
</section>

{{-- WHY --}}
<section class="bg-sand-light py-20 lg:py-24">
    <div class="container-page grid gap-10 lg:grid-cols-12 lg:gap-16">
        <div class="lg:col-span-5">
            <h2 class="text-3xl font-extrabold leading-tight tracking-tight text-forest sm:text-4xl">
                Kami menjaga kunjungan tetap sederhana.
            </h2>
            <p class="mt-5 max-w-prose text-[15px] leading-relaxed text-charcoal/65">
                Mulai dari memilih aktivitas sampai tiba di lokasi, tim Bumi Mandiri membantu menyiapkan alur yang jelas
                agar Anda dan rombongan bisa fokus menikmati waktu bersama.
            </p>
            <a href="{{ route('contact.create') }}" class="btn btn-primary mt-7">
                Diskusikan Kebutuhan Anda
            </a>
        </div>

        <div class="divide-y divide-forest/10 border-y border-forest/10 lg:col-span-7">
            @foreach([
                ['title' => 'Pendampingan yang jelas', 'text' => 'Instruktur dan pendamping membantu setiap sesi sesuai jenis aktivitas.'],
                ['title' => 'Fasilitas yang menyatu dengan alam', 'text' => 'Area camping, jalur aktivitas, dan ruang berkumpul dalam satu lokasi.'],
                ['title' => 'Paket yang bisa disesuaikan', 'text' => 'Susun kombinasi aktivitas, durasi, dan jumlah peserta bersama tim kami.'],
                ['title' => 'Akses yang terjangkau', 'text' => 'Berada di Sukabumi, mudah dijangkau untuk kunjungan sehari maupun menginap.'],
            ] as $index => $item)
                <div class="grid gap-2 py-5 sm:grid-cols-[48px_1fr] sm:gap-4">
                    <span class="font-heading text-sm font-extrabold text-earth">0{{ $index + 1 }}</span>
                    <div>
                        <h3 class="font-heading text-base font-bold text-forest">{{ $item['title'] }}</h3>
                        <p class="mt-1 text-sm leading-relaxed text-charcoal/70">{{ $item['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- GALLERY --}}
@if($gallery->isNotEmpty())
    <section class="container-page py-20 lg:py-24">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <x-section-heading
                title="Potongan suasana di Bumi Mandiri"
                description="Sungai, hutan, tenda, dan kebersamaan yang menjadi bagian dari kunjungan." />
            <a href="{{ route('gallery.index') }}" class="link-underline text-sm">
                Buka galeri
            </a>
        </div>

        <div class="mt-9 grid auto-rows-[150px] grid-cols-2 gap-3 sm:auto-rows-[180px] md:grid-cols-4">
            @foreach($gallery->take(6) as $index => $item)
                @php
                    $sizes = [
                        'col-span-2 row-span-2',
                        'col-span-1 row-span-1',
                        'col-span-1 row-span-2',
                        'col-span-1 row-span-1',
                        'col-span-2 row-span-1',
                        'col-span-2 row-span-1',
                    ];
                @endphp
                <a href="{{ route('gallery.index') }}"
                   class="group relative overflow-hidden rounded-xl bg-sand {{ $sizes[$index] ?? 'col-span-1 row-span-1' }}">
                    <img src="{{ $item->imageUrl() }}" alt="{{ $item->title }}"
                         class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.05]" loading="lazy" width="900" height="700">
                    <div class="absolute inset-0 bg-gradient-to-t from-forest-dark/90 via-forest-dark/30 to-transparent transition group-hover:from-forest-dark/95"></div>
                    <p class="absolute bottom-3 left-3 right-3 text-xs font-semibold text-cream">{{ $item->title }}</p>
                </a>
            @endforeach
        </div>
    </section>
@endif

{{-- RATING & TESTIMONIALS --}}
@if($testimonials->isNotEmpty())
    <section id="rating-pengunjung" class="scroll-mt-24 bg-forest py-20 text-cream lg:py-24">
        <div class="container-page">
            <div class="grid gap-8 lg:grid-cols-[minmax(0,1.4fr)_minmax(0,0.8fr)] lg:items-end">
                <div>
                    <p class="eyebrow text-earth-light">Rating pengunjung</p>
                    <h2 class="mt-3 max-w-2xl text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl">
                        Cerita dari yang sudah datang.
                    </h2>
                    <p class="mt-4 max-w-xl text-sm leading-relaxed text-cream/70">
                        Penilaian jujur dari pengunjung yang sudah mencoba aktivitas di Bumi Mandiri.
                    </p>
                </div>

                <div class="rounded-2xl border border-cream/15 bg-cream/[0.07] p-5 backdrop-blur-sm">
                    <div class="flex items-center gap-4">
                        <span class="font-heading text-4xl font-extrabold leading-none text-cream">{{ number_format($siteRating, 1) }}</span>
                        <div>
                            <x-stars :rating="$siteRating" :size="15" tone="text-earth-light" />
                            <p class="mt-1.5 text-xs text-cream/60">{{ $siteRatingCount }} rating pengunjung</p>
                        </div>
                    </div>
                    <a href="{{ route('activities.index') }}"
                       class="mt-4 inline-flex w-full items-center justify-center gap-1.5 rounded-xl border border-cream/25 px-4 py-2.5 text-xs font-semibold text-cream transition hover:border-cream/45 hover:bg-cream/10">
                        Beri rating aktivitas favorit
                        <x-icon name="arrow-right" :size="14" />
                    </a>
                </div>
            </div>

            <div class="mt-10 grid gap-4 md:grid-cols-3">
                @foreach($testimonials->take(3) as $review)
                    <figure class="flex flex-col rounded-2xl border border-cream/10 bg-forest-light/35 p-6">
                        <div class="flex items-center justify-between gap-3">
                            <x-stars :rating="$review->rating" :size="14" tone="text-earth-light" />
                            <x-icon name="quote" :size="20" class="text-cream/25" />
                        </div>

                        <blockquote class="mt-4 line-clamp-4 flex-1 text-sm leading-relaxed text-cream/80">
                            @if($review->comment)
                                &ldquo;{{ $review->comment }}&rdquo;
                            @else
                                Memberi rating {{ $review->rating }} bintang untuk aktivitas ini.
                            @endif
                        </blockquote>

                        <figcaption class="mt-5 flex items-center gap-3 border-t border-cream/10 pt-4">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-cream/10 text-[11px] font-bold text-cream ring-1 ring-cream/15">
                                {{ $review->initials() }}
                            </span>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-cream">{{ $review->displayName() }}</p>
                                <p class="mt-0.5 truncate text-xs text-cream/60">
                                    {{ $review->activity?->name }}
                                    @if($review->isVerified())
                                        <span class="text-earth-light">&middot; Terverifikasi</span>
                                    @endif
                                </p>
                            </div>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ARTICLES --}}
@if($articles->isNotEmpty())
    <section class="container-page py-20 lg:py-24">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <x-section-heading
                title="Baca dulu sebelum berangkat"
                description="Panduan singkat dan cerita dari lapangan untuk membantu persiapan kunjungan." />
            <a href="{{ route('articles.index') }}" class="link-underline text-sm">
                Semua cerita
            </a>
        </div>

        <div class="mt-9 grid gap-6 md:grid-cols-3">
            @foreach($articles as $article)
                <a href="{{ route('articles.show', $article) }}" class="group">
                    <div class="aspect-[16/10] overflow-hidden rounded-2xl bg-sand">
                        <img src="{{ $article->imageUrl() }}" alt="{{ $article->title }}"
                             class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]" loading="lazy" width="800" height="500">
                    </div>
                    <div class="mt-4 flex items-center gap-2 text-[11px] text-charcoal/70">
                        <span>{{ $article->created_at->format('d M Y') }}</span>
                        <span aria-hidden="true">&middot;</span>
                        <span>{{ $article->reading_time }} menit baca</span>
                    </div>
                    <h3 class="mt-1.5 font-heading text-base font-bold leading-snug text-forest transition group-hover:text-earth">
                        {{ $article->title }}
                    </h3>
                    <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-charcoal/70">{{ $article->excerpt }}</p>
                </a>
            @endforeach
        </div>
    </section>
@endif

{{-- LOCATION --}}
<section class="border-t border-forest/10 bg-sand-light py-16 lg:py-20">
    <div class="container-page grid gap-10 lg:grid-cols-2 lg:gap-14">
        <div>
            <h2 class="text-3xl font-extrabold leading-tight tracking-tight text-forest sm:text-4xl">
                Temukan jalan ke Bumi Mandiri.
            </h2>
            <p class="mt-4 max-w-prose text-[15px] leading-relaxed text-charcoal/65">
                Hubungi kami terlebih dahulu agar kedatangan rombongan bisa disiapkan dengan lebih baik.
            </p>

            <div class="mt-7 space-y-3 text-sm">
                <div class="flex gap-3 rounded-xl border border-forest/10 bg-white p-4">
                    <x-icon name="map-pin" :size="18" class="mt-0.5 shrink-0 text-earth" />
                    <div>
                        <p class="font-semibold text-forest">Lokasi</p>
                        <p class="mt-0.5 text-charcoal/70">{{ $address }}</p>
                    </div>
                </div>
                @if($email)
                    <div class="flex gap-3 rounded-xl border border-forest/10 bg-white p-4">
                        <x-icon name="mail" :size="18" class="mt-0.5 shrink-0 text-earth" />
                        <div>
                            <p class="font-semibold text-forest">Email</p>
                            <a href="mailto:{{ $email }}" class="mt-0.5 block text-charcoal/70 transition hover:text-forest">{{ $email }}</a>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-6 flex flex-wrap gap-2">
                <a href="{{ $mapLink }}" target="_blank" rel="noopener" class="btn btn-primary">
                    <x-icon name="map-pin" :size="17" />
                    Buka di Google Maps
                </a>
                @if($hasWhatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" target="_blank" rel="noopener" class="btn btn-secondary">
                        <x-icon name="brand-whatsapp" :size="17" />
                        WhatsApp
                    </a>
                @else
                    <a href="{{ route('contact.create') }}" class="btn btn-secondary">Kirim Pesan</a>
                @endif
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-forest/10 bg-white">
            <iframe
                title="Peta lokasi Bumi Mandiri"
                src="https://www.google.com/maps?q={{ urlencode($address) }}&output=embed"
                class="h-[320px] w-full sm:h-[420px]"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>

{{-- FINAL CTA --}}
<section class="relative overflow-hidden bg-forest-dark">
    <img src="{{ asset('images/waterfall.jpg') }}" alt=""
         class="absolute inset-0 h-full w-full object-cover opacity-25" loading="lazy" width="1400" height="900">
    <div class="container-page relative z-10 py-20 text-center lg:py-24">
        <h2 class="mx-auto max-w-2xl text-3xl font-extrabold leading-tight tracking-tight text-cream sm:text-4xl">
            Siap menyusun kunjungan Anda?
        </h2>
        <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-cream/70">
            Ceritakan jumlah peserta, tanggal, dan jenis kegiatan. Kami bantu susun alurnya.
        </p>
        <div class="mt-7 flex flex-col justify-center gap-3 sm:flex-row">
            <a href="{{ route('contact.create') }}" class="btn btn-light">
                Mulai Rencana Kunjungan
                <x-icon name="arrow-right" :size="17" />
            </a>
            <a href="{{ route('packages.index') }}" class="btn btn-ghost-light">Lihat Paket</a>
        </div>
    </div>
</section>
@endsection
