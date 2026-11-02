@extends('layouts.app')

@section('title', $activity->name . ' | Bumi Mandiri')
@section('meta_description', $activity->short_description)

@section('content')
<section class="container-page pb-16 pt-8 lg:pb-24 lg:pt-10">
    <nav class="flex flex-wrap items-center gap-1.5 text-xs text-charcoal/70" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="transition hover:text-forest">Beranda</a>
        <x-icon name="chevron-right" :size="13" />
        <a href="{{ route('activities.index') }}" class="transition hover:text-forest">Aktivitas</a>
        <x-icon name="chevron-right" :size="13" />
        <span class="text-charcoal/65">{{ $activity->name }}</span>
    </nav>

    <div class="mt-6 grid gap-8 lg:grid-cols-[1.6fr_1fr] lg:gap-12">
        <div>
            <div class="relative aspect-[4/3] overflow-hidden rounded-2xl bg-sand">
                <img src="{{ $activity->imageUrl() }}" alt="{{ $activity->name }}"
                     class="h-full w-full object-cover" width="1200" height="900" fetchpriority="high">
                <div class="absolute inset-0 bg-gradient-to-t from-forest-dark/35 via-transparent to-transparent"></div>
                <span class="absolute left-4 top-4 rounded-full bg-cream/95 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-wide text-forest">
                    {{ $activity->category?->name }}
                </span>
            </div>

            <div class="mt-7">
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-charcoal/70">
                    <span class="inline-flex items-center gap-1.5">
                        <x-icon name="map-pin" :size="15" class="text-earth" />
                        {{ $activity->destination?->name ?? 'Bumi Mandiri' }}
                    </span>
                    @if($activity->review_count > 0)
                        <span class="inline-flex items-center gap-1.5">
                            <x-icon name="star" :size="15" class="fill-current text-earth" />
                            <span class="font-semibold text-forest">{{ number_format($activity->rating_avg, 1) }}</span>
                            <span>({{ $activity->review_count }} ulasan)</span>
                        </span>
                    @endif
                </div>

                <h1 class="mt-3 text-3xl font-extrabold leading-tight tracking-tight text-forest sm:text-4xl">{{ $activity->name }}</h1>
                <p class="mt-4 max-w-prose text-[15px] leading-relaxed text-charcoal/70">{{ $activity->description }}</p>

                <div class="mt-7 grid grid-cols-2 gap-3 sm:grid-cols-3">
                    <div class="rounded-xl border border-forest/10 bg-white p-4">
                        <x-icon name="clock" :size="18" class="text-earth" />
                        <p class="mt-2 text-[10px] font-semibold uppercase tracking-wider text-charcoal/70">Durasi</p>
                        <p class="mt-0.5 text-sm font-semibold text-forest">{{ $activity->durationLabel() }}</p>
                    </div>
                    <div class="rounded-xl border border-forest/10 bg-white p-4">
                        <x-icon name="users" :size="18" class="text-earth" />
                        <p class="mt-2 text-[10px] font-semibold uppercase tracking-wider text-charcoal/70">Kapasitas</p>
                        <p class="mt-0.5 text-sm font-semibold text-forest">Hingga {{ $activity->capacity }} peserta</p>
                    </div>
                    <div class="col-span-2 rounded-xl border border-forest/10 bg-white p-4 sm:col-span-1">
                        <x-icon name="route" :size="18" class="text-earth" />
                        <p class="mt-2 text-[10px] font-semibold uppercase tracking-wider text-charcoal/70">Lokasi</p>
                        <p class="mt-0.5 text-sm font-semibold text-forest">{{ $activity->destination?->location ?? 'Sukabumi' }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 rounded-2xl border border-earth/15 bg-earth/[0.05] p-5">
                <div class="flex gap-3">
                    <x-icon name="alert-circle" :size="19" class="mt-0.5 shrink-0 text-earth" />
                    <div class="text-sm leading-relaxed text-charcoal/70">
                        <p class="font-semibold text-forest">Sebelum memesan</p>
                        <p class="mt-1">Pastikan tanggal, jumlah peserta, dan kebutuhan rombongan sudah sesuai. Tim kami akan mengonfirmasi ketersediaan sebelum kegiatan berlangsung.</p>
                    </div>
                </div>
            </div>
        </div>

        <aside class="lg:self-start">
            <div class="rounded-2xl border border-forest/10 bg-white p-6 lg:sticky lg:top-24">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-charcoal/70">Harga mulai</p>
                <p class="mt-1 font-heading text-3xl font-extrabold text-forest">
                    Rp {{ number_format($activity->price, 0, ',', '.') }}
                    <span class="font-body text-sm font-medium text-charcoal/70">/orang</span>
                </p>

                <div class="mt-5 space-y-2.5 border-y border-sand/70 py-4 text-sm">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-charcoal/70">Durasi</span>
                        <span class="font-semibold text-forest">{{ $activity->durationLabel() }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-charcoal/70">Kapasitas</span>
                        <span class="font-semibold text-forest">{{ $activity->capacity }} peserta</span>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-charcoal/70">Kategori</span>
                        <span class="font-semibold text-forest">{{ $activity->category?->name }}</span>
                    </div>
                </div>

                @auth
                    <a href="{{ route('booking.start', $activity) }}" class="btn btn-primary mt-5 w-full">
                        Pesan Aktivitas Ini
                        <x-icon name="arrow-right" :size="16" />
                    </a>
                    <form method="POST" action="{{ route('favorites.toggle', $activity) }}" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-secondary w-full">
                            <x-icon name="heart" :size="16" class="{{ $isFavorited ? 'fill-current text-earth' : '' }}" />
                            {{ $isFavorited ? 'Tersimpan di favorit' : 'Simpan ke favorit' }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary mt-5 w-full">
                        Masuk untuk Memesan
                        <x-icon name="arrow-right" :size="16" />
                    </a>
                    <p class="mt-3 text-center text-xs text-charcoal/70">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-semibold text-forest underline decoration-earth/40 underline-offset-2">Daftar di sini</a>
                    </p>
                @endauth

                <a href="{{ route('contact.create') }}" class="mt-3 flex items-center justify-center gap-1.5 text-xs font-semibold text-forest transition hover:text-earth">
                    Tanya dulu sebelum memesan
                    <x-icon name="arrow-up-right" :size="13" />
                </a>
            </div>
        </aside>
    </div>

    {{-- RATING & REVIEWS --}}
    <section id="rating" class="mt-16 scroll-mt-24 border-t border-forest/10 pt-12">
        <div class="flex flex-wrap items-end justify-between gap-5">
            <div>
                <p class="eyebrow text-earth">Rating pengunjung</p>
                <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-forest sm:text-3xl">
                    Penilaian dari yang sudah datang
                </h2>
                <p class="mt-2 max-w-xl text-sm leading-relaxed text-charcoal/65">
                    Bagikan pengalaman Anda, atau lihat apa kata pengunjung lain sebelum memesan.
                </p>
            </div>

            @if($ratingCount > 0)
                <div class="flex items-center gap-3 rounded-2xl border border-forest/10 bg-white px-4 py-3">
                    <span class="font-heading text-3xl font-extrabold leading-none text-forest">{{ number_format($ratingAverage, 1) }}</span>
                    <div>
                        <x-stars :rating="$ratingAverage" :size="14" />
                        <p class="mt-1 text-xs text-charcoal/65">{{ $ratingCount }} rating pengunjung</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[minmax(0,0.85fr)_minmax(0,1.35fr)]">
            {{-- Ringkasan rating --}}
            <div class="rounded-2xl border border-forest/10 bg-white p-6">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-charcoal/70">Rata-rata rating</p>

                @if($ratingCount > 0)
                    <div class="mt-3 flex items-end gap-3">
                        <span class="font-heading text-5xl font-extrabold leading-none text-forest">{{ number_format($ratingAverage, 1) }}</span>
                        <div class="pb-1">
                            <x-stars :rating="$ratingAverage" :size="16" />
                            <p class="mt-1 text-xs text-charcoal/65">dari 5 bintang</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-2.5">
                        @foreach($ratingBreakdown as $star => $count)
                            @php $percent = $ratingCount > 0 ? round(($count / $ratingCount) * 100) : 0; @endphp
                            <div class="flex items-center gap-3">
                                <span class="w-14 shrink-0 text-xs font-medium text-charcoal/70">{{ $star }} bintang</span>
                                <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-sand-light">
                                    <div class="h-full rounded-full bg-earth" style="width: {{ $percent }}%"></div>
                                </div>
                                <span class="w-7 shrink-0 text-right text-xs font-semibold text-charcoal/70">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>

                    <p class="mt-5 border-t border-sand/70 pt-4 text-xs leading-relaxed text-charcoal/60">
                        Rating berasal dari pengunjung umum dan tamu yang menyelesaikan booking.
                    </p>

                    @if($reviews->isNotEmpty())
                        @php $latest = $reviews->first(); @endphp
                        <div class="mt-4 rounded-xl bg-sand-light/60 px-3 py-2.5">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-charcoal/50">Rating terbaru</p>
                            <div class="mt-2 flex items-center justify-between gap-3">
                                <div class="flex min-w-0 items-center gap-2">
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-forest text-[10px] font-bold text-cream">
                                        {{ $latest->initials() }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="truncate text-xs font-semibold text-forest">{{ $latest->displayName() }}</p>
                                        <p class="text-[10px] text-charcoal/50">{{ $latest->created_at->translatedFormat('d M Y') }}</p>
                                    </div>
                                </div>
                                <x-stars :rating="$latest->rating" :size="11" class="shrink-0" />
                            </div>
                        </div>
                    @endif
                @else
                    <div class="mt-4 rounded-xl border border-dashed border-forest/20 px-4 py-6 text-center">
                        <x-stars :rating="0" :size="18" class="justify-center" />
                        <p class="mt-2 text-sm font-semibold text-forest">Belum ada rating</p>
                        <p class="mt-1 text-xs leading-relaxed text-charcoal/60">Jadilah yang pertama menilai aktivitas ini.</p>
                    </div>
                @endif
            </div>

            {{-- Form rating --}}
            <div class="rounded-2xl border border-forest/10 bg-white p-6">
                <h3 class="font-heading text-lg font-bold text-forest">Beri rating Anda</h3>
                <p class="mt-1 text-sm text-charcoal/65">Cukup pilih bintang. Ulasan tertulis bersifat opsional.</p>

                @if(session('rating_success'))
                    <div class="mt-4 flex items-start gap-2.5 rounded-xl border border-nature/25 bg-nature/[0.06] px-4 py-3 text-sm text-forest" role="status">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-nature/15 text-nature">
                            <x-icon name="check" :size="13" />
                        </span>
                        {{ session('rating_success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mt-4 flex items-start gap-2.5 rounded-xl border border-red-200 bg-red-50/60 px-4 py-3 text-sm text-red-800" role="alert">
                        <x-icon name="alert-circle" :size="17" class="mt-0.5 shrink-0" />
                        <span>Rating belum terkirim. Periksa kembali data di bawah.</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('reviews.visitor.store', $activity) }}" class="mt-5"
                      x-data="{ rating: {{ (int) old('rating', 0) }}, hover: 0 }">
                    @csrf

                    {{-- Honeypot --}}
                    <div class="absolute left-[-9999px] top-auto h-px w-px overflow-hidden" aria-hidden="true">
                        <label for="rating-website">Website</label>
                        <input type="text" id="rating-website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div>
                        <span class="field-label">Rating Anda <span class="text-earth">*</span></span>
                        <div class="flex items-center gap-1.5" role="radiogroup" aria-label="Pilih rating 1 sampai 5 bintang">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer rounded-md p-0.5 transition focus-within:ring-2 focus-within:ring-earth/30">
                                    <input type="radio" name="rating" value="{{ $i }}" class="sr-only"
                                           x-model.number="rating" @checked((int) old('rating') === $i) required>
                                    <span class="block transition" :class="(hover || rating) >= {{ $i }} ? 'text-earth' : 'text-charcoal/20'"
                                          @mouseenter="hover = {{ $i }}" @mouseleave="hover = 0">
                                        <x-icon name="star" :size="30" class="fill-current" />
                                    </span>
                                    <span class="sr-only">{{ $i }} bintang</span>
                                </label>
                            @endfor
                            <span class="ml-2 text-xs font-medium text-charcoal/60"
                                  x-text="rating ? rating + ' dari 5 bintang' : 'Pilih bintang'">Pilih bintang</span>
                        </div>
                        @error('rating') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="rating-name" class="field-label">Nama <span class="text-earth">*</span></label>
                            <input id="rating-name" type="text" name="name" class="field"
                                   value="{{ old('name', auth()->user()->name ?? '') }}"
                                   placeholder="Nama Anda" required maxlength="80" autocomplete="name">
                            @error('name') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="rating-email" class="field-label">Email <span class="font-normal text-charcoal/50">(opsional)</span></label>
                            <input id="rating-email" type="email" name="email" class="field"
                                   value="{{ old('email') }}" placeholder="nama@email.com" maxlength="120" autocomplete="email">
                            <p class="mt-1 text-[11px] text-charcoal/50">Tidak ditampilkan di halaman ini.</p>
                            @error('email') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="rating-comment" class="field-label">Ulasan <span class="font-normal text-charcoal/50">(opsional)</span></label>
                        <textarea id="rating-comment" name="comment" rows="4" class="field resize-y"
                                  placeholder="Ceritakan pengalaman Anda di aktivitas ini..." maxlength="600">{{ old('comment') }}</textarea>
                        @error('comment') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
                        <p class="max-w-xs text-[11px] leading-relaxed text-charcoal/50">
                            Rating tampil langsung. Tim kami meninjau ulasan yang tidak sesuai.
                        </p>
                        <button type="submit" class="btn btn-primary">
                            Kirim Rating
                            <x-icon name="arrow-right" :size="16" />
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Daftar ulasan --}}
        <div class="mt-10">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <h3 class="font-heading text-lg font-bold text-forest">Ulasan terbaru</h3>
                @if($ratingCount > 0)
                    <p class="text-sm text-charcoal/65">{{ $ratingCount }} ulasan ditampilkan</p>
                @endif
            </div>

            <div class="mt-5 grid gap-4 md:grid-cols-2">
                @forelse($reviews as $review)
                    <article class="rounded-2xl border border-forest/10 bg-white p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex min-w-0 items-center gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-forest text-xs font-bold text-cream">
                                    {{ $review->initials() }}
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-forest">{{ $review->displayName() }}</p>
                                    <div class="mt-0.5 flex flex-wrap items-center gap-x-2 gap-y-1 text-[11px] text-charcoal/60">
                                        <span>{{ $review->created_at->translatedFormat('d M Y') }}</span>
                                        @if($review->isVerified())
                                            <span class="inline-flex items-center gap-1 rounded-full bg-nature/10 px-2 py-0.5 font-semibold text-nature">
                                                <x-icon name="shield-check" :size="11" />
                                                Terverifikasi
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <x-stars :rating="$review->rating" :size="13" class="shrink-0" />
                        </div>
                        @if($review->comment)
                            <p class="mt-3 text-sm leading-relaxed text-charcoal/70">{{ $review->comment }}</p>
                        @endif
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-forest/20 px-6 py-10 text-center text-sm text-charcoal/65 md:col-span-2">
                        Belum ada ulasan tertulis. Rating Anda akan menjadi yang pertama.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- RELATED --}}
    @if($related->isNotEmpty())
        <div class="mt-16 border-t border-forest/10 pt-12">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <h2 class="text-2xl font-extrabold tracking-tight text-forest">Aktivitas lain yang bisa dicoba</h2>
                <a href="{{ route('activities.index') }}" class="link-underline inline-flex items-center gap-1.5 text-sm">
                    Lihat semua
                    <x-icon name="arrow-right" :size="15" />
                </a>
            </div>
            <div class="mt-7 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($related as $item)
                    <x-activity-card :activity="$item" />
                @endforeach
            </div>
        </div>
    @endif
</section>
@endsection
