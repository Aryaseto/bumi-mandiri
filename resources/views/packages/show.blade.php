@extends('layouts.app')

@section('title', $package->name . ' | Bumi Mandiri')
@section('meta_description', $package->description)

@section('content')
<section class="container-page pb-16 pt-8 lg:pb-24 lg:pt-10">
    <nav class="flex flex-wrap items-center gap-1.5 text-xs text-charcoal/70" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="transition hover:text-forest">Beranda</a>
        <x-icon name="chevron-right" :size="13" />
        <a href="{{ route('packages.index') }}" class="transition hover:text-forest">Paket</a>
        <x-icon name="chevron-right" :size="13" />
        <span class="text-charcoal/65">{{ $package->name }}</span>
    </nav>

    <div class="mt-6 grid gap-8 lg:grid-cols-[1.5fr_1fr] lg:gap-12">
        <div>
            <div class="aspect-[16/9] overflow-hidden rounded-2xl bg-sand">
                <img src="{{ $package->imageUrl() }}" alt="{{ $package->name }}"
                     class="h-full w-full object-cover" width="1200" height="675" fetchpriority="high">
            </div>

            <p class="mt-6 text-xs font-semibold uppercase tracking-[0.16em] text-earth">{{ $package->target_segment }}</p>
            <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-forest sm:text-4xl">{{ $package->name }}</h1>
            <p class="mt-4 max-w-prose text-[15px] leading-relaxed text-charcoal/70">{{ $package->description }}</p>

            <div class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-3">
                <div class="rounded-xl border border-forest/10 bg-white p-4">
                    <x-icon name="clock" :size="18" class="text-earth" />
                    <p class="mt-2 text-[10px] font-semibold uppercase tracking-wider text-charcoal/70">Durasi</p>
                    <p class="mt-0.5 text-sm font-semibold text-forest">{{ $package->estimated_duration }}</p>
                </div>
                <div class="rounded-xl border border-forest/10 bg-white p-4">
                    <x-icon name="users" :size="18" class="text-earth" />
                    <p class="mt-2 text-[10px] font-semibold uppercase tracking-wider text-charcoal/70">Peserta</p>
                    <p class="mt-0.5 text-sm font-semibold text-forest">{{ $package->min_participants }}{{ $package->max_participants ? '-' . $package->max_participants : '+' }} orang</p>
                </div>
                <div class="col-span-2 rounded-xl border border-forest/10 bg-white p-4 sm:col-span-1">
                    <x-icon name="package" :size="18" class="text-earth" />
                    <p class="mt-2 text-[10px] font-semibold uppercase tracking-wider text-charcoal/70">Aktivitas</p>
                    <p class="mt-0.5 text-sm font-semibold text-forest">{{ $package->activities->count() }} kegiatan</p>
                </div>
            </div>

            <div class="mt-10">
                <h2 class="text-2xl font-extrabold tracking-tight text-forest">Aktivitas dalam paket</h2>
                <div class="mt-5 overflow-hidden rounded-2xl border border-forest/10 bg-white">
                    @foreach($package->activities as $activity)
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-sand/70 px-5 py-4 last:border-b-0">
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-sand-light text-xs font-bold text-forest">
                                    {{ $loop->iteration }}
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-forest">{{ $activity->name }}</p>
                                    <p class="mt-0.5 text-xs text-charcoal/70">{{ $activity->category?->name }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1.5 text-xs text-charcoal/70">
                                <x-icon name="clock" :size="14" />
                                {{ $activity->durationLabel() }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <aside class="lg:self-start">
            <div class="rounded-2xl border border-forest/10 bg-white p-6 lg:sticky lg:top-24">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-charcoal/70">Harga paket mulai</p>
                <p class="mt-1 font-heading text-3xl font-extrabold text-forest">Rp {{ number_format($package->base_price, 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-charcoal/70">Rincian biaya dikonfirmasi sesuai jumlah peserta dan kebutuhan kegiatan.</p>

                <div class="mt-5 space-y-2.5 border-y border-sand/70 py-4 text-sm">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-charcoal/70">Durasi</span>
                        <span class="font-semibold text-forest">{{ $package->estimated_duration }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-charcoal/70">Peserta</span>
                        <span class="font-semibold text-forest">{{ $package->min_participants }}{{ $package->max_participants ? '-' . $package->max_participants : '+' }} orang</span>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-charcoal/70">Segmen</span>
                        <span class="text-right font-semibold text-forest">{{ $package->target_segment }}</span>
                    </div>
                </div>

                <a href="{{ route('contact.create', ['subject' => $package->name]) }}" class="btn btn-primary mt-5 w-full">
                    Ajukan Paket Ini
                    <x-icon name="arrow-right" :size="16" />
                </a>
                <a href="{{ route('packages.index') }}" class="btn btn-secondary mt-2 w-full">Bandingkan Paket Lain</a>

                <p class="mt-4 text-center text-xs leading-relaxed text-charcoal/70">
                    Tim Bumi Mandiri akan menghubungi Anda untuk mengonfirmasi tanggal dan ketersediaan.
                </p>
            </div>
        </aside>
    </div>
</section>
@endsection
