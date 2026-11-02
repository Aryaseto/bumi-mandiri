@extends('layouts.app')

@section('title', 'Paket Wisata | Bumi Mandiri')
@section('meta_description', 'Paket kunjungan Bumi Mandiri Sukabumi untuk keluarga, sekolah, komunitas, dan perusahaan.')

@section('content')
<section class="border-b border-forest/10 bg-sand-light/60 pb-12 pt-12 lg:pt-16">
    <div class="container-page">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-earth">Paket kunjungan</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-forest sm:text-4xl">Susunan kegiatan yang lebih praktis.</h1>
        <p class="mt-3 max-w-2xl text-[15px] leading-relaxed text-charcoal/65">
            Paket dibuat untuk memudahkan rombongan memilih kombinasi aktivitas, durasi, dan jumlah peserta.
        </p>
    </div>
</section>

<section class="container-page py-14 lg:py-20">
    @if($packages->isEmpty())
        <div class="rounded-2xl border border-dashed border-forest/20 px-6 py-20 text-center">
            <h2 class="font-heading text-xl font-bold text-forest">Paket sedang disiapkan.</h2>
            <p class="mt-2 text-sm text-charcoal/70">Hubungi kami untuk menyusun paket khusus.</p>
        </div>
    @else
        <div class="grid gap-6 lg:grid-cols-2">
            @foreach($packages as $package)
                <a href="{{ route('packages.show', $package) }}"
                   class="group grid overflow-hidden rounded-2xl border border-forest/10 bg-white transition hover:border-forest/20 hover:shadow-lift sm:grid-cols-[40%_60%]">
                    <div class="relative aspect-[4/3] overflow-hidden bg-sand sm:aspect-auto sm:h-full">
                        <img src="{{ $package->imageUrl() }}" alt="{{ $package->name }}"
                             class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]" loading="lazy" width="800" height="600">
                        <span class="absolute left-3 top-3 rounded-full bg-cream/95 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-forest">
                            {{ $package->target_segment }}
                        </span>
                    </div>
                    <div class="flex flex-col p-5 sm:p-6">
                        <h2 class="font-heading text-lg font-bold text-forest">{{ $package->name }}</h2>
                        <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-charcoal/70">{{ $package->description }}</p>

                        <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                            <span class="inline-flex items-center gap-1.5 text-charcoal/70">
                                <x-icon name="clock" :size="14" class="text-earth" />
                                {{ $package->estimated_duration }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-charcoal/70">
                                <x-icon name="users" :size="14" class="text-earth" />
                                {{ $package->min_participants }}{{ $package->max_participants ? '-' . $package->max_participants : '+' }} peserta
                            </span>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-x-3 gap-y-1.5 text-xs text-charcoal/70">
                            @foreach($package->activities->take(3) as $activity)
                                <span class="inline-flex items-center gap-1">
                                    <span class="h-1 w-1 rounded-full bg-earth" aria-hidden="true"></span>
                                    {{ $activity->name }}
                                </span>
                            @endforeach
                            @if($package->activities->count() > 3)
                                <span class="text-charcoal/70">+{{ $package->activities->count() - 3 }} lainnya</span>
                            @endif
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
            @endforeach
        </div>
    @endif
</section>
@endsection
