@extends('layouts.app')

@section('title', 'Ringkasan Akun | Bumi Mandiri')

@section('content')
<section class="pb-20 pt-28 lg:pt-32">
    <div class="container-page">
        <div class="flex flex-wrap items-end justify-between gap-5">
            <div>
                <p class="eyebrow text-earth">Akun saya</p>
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-forest sm:text-4xl">
                    Halo, {{ \Illuminate\Support\Str::of($user->name)->explode(' ')->first() }}.
                </h1>
                <p class="mt-3 max-w-xl text-[15px] leading-relaxed text-charcoal/70">
                    Ringkasan rencana kunjungan, riwayat booking, dan aktivitas yang Anda simpan.
                </p>
            </div>
            <a href="{{ route('activities.index') }}" class="btn btn-secondary btn-sm">
                <x-icon name="compass" :size="16" />
                Jelajahi aktivitas
            </a>
        </div>

        <div class="mt-7">
            <x-account-nav />
        </div>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_320px] lg:gap-10">
            <div class="space-y-8">

                {{-- Next trip --}}
                <section aria-labelledby="next-trip-heading">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <h2 id="next-trip-heading" class="font-heading text-lg font-bold text-forest">Rencana berikutnya</h2>
                        <a href="{{ route('bookings.index') }}" class="text-sm font-semibold text-charcoal/70 transition hover:text-forest">Semua booking</a>
                    </div>

                    @if($upcomingBooking)
                        @php $nextActivity = $upcomingBooking->items->first()?->activity; @endphp
                        <article class="overflow-hidden rounded-2xl border border-forest/10 bg-white">
                            <div class="grid sm:grid-cols-[200px_minmax(0,1fr)]">
                                <div class="relative h-40 bg-sand sm:h-full">
                                    @if($nextActivity)
                                        <img src="{{ $nextActivity->imageUrl() }}" alt="{{ $nextActivity->name }}"
                                             class="h-full w-full object-cover" width="600" height="600" loading="lazy">
                                    @endif
                                    <span class="absolute left-3 top-3">
                                        <x-status-badge :status="$upcomingBooking->status" />
                                    </span>
                                </div>
                                <div class="flex flex-col p-5 sm:p-6">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-earth">Terkonfirmasi untuk</p>
                                    <p class="mt-1 font-heading text-xl font-extrabold text-forest">
                                        {{ $upcomingBooking->booking_date->translatedFormat('d F Y') }}
                                    </p>
                                    <p class="mt-2 text-sm text-charcoal/65">
                                        {{ $nextActivity?->name ?? 'Aktivitas Bumi Mandiri' }}
                                        @if($upcomingBooking->timeLabel())
                                            <span class="text-charcoal/70">&middot;</span> {{ $upcomingBooking->timeLabel() }}
                                        @endif
                                        <span class="text-charcoal/70">&middot;</span> {{ $upcomingBooking->participants }} peserta
                                    </p>
                                    <div class="mt-4 flex flex-wrap items-center gap-3 border-t border-sand/70 pt-4">
                                        <a href="{{ route('bookings.show', $upcomingBooking) }}" class="btn btn-primary btn-sm">
                                            Lihat detail booking
                                            <x-icon name="arrow-right" :size="15" />
                                        </a>
                                        <span class="text-xs text-charcoal/70">Kode {{ $upcomingBooking->booking_code }}</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @else
                        <div class="overflow-hidden rounded-2xl border border-forest/10 bg-forest">
                            <div class="grid items-center sm:grid-cols-[minmax(0,1fr)_180px]">
                                <div class="p-6 sm:p-8">
                                    <p class="eyebrow text-cream/70">Belum ada jadwal</p>
                                    <h3 class="mt-2 font-heading text-xl font-bold text-cream">Rencana berikutnya masih terbuka.</h3>
                                    <p class="mt-2 max-w-md text-sm leading-relaxed text-cream/70">
                                        Pilih satu aktivitas, tentukan tanggal, dan tim kami akan mengonfirmasi ketersediaannya.
                                    </p>
                                    <a href="{{ route('activities.index') }}" class="btn btn-light btn-sm mt-5">
                                        Cari aktivitas
                                        <x-icon name="arrow-right" :size="15" />
                                    </a>
                                </div>
                                <div class="hidden h-full sm:block">
                                    <img src="{{ asset('images/activities/nature.jpg') }}" alt=""
                                         class="h-full w-full object-cover" width="400" height="400" loading="lazy">
                                </div>
                            </div>
                        </div>
                    @endif
                </section>

                {{-- Small stats --}}
                <section aria-label="Statistik akun" class="grid grid-cols-3 gap-3 sm:gap-4">
                    @foreach([
                        ['label' => 'Total booking', 'value' => $totalBookings, 'icon' => 'ticket'],
                        ['label' => 'Selesai', 'value' => $completedBookings, 'icon' => 'check'],
                        ['label' => 'Favorit', 'value' => $favoriteCount, 'icon' => 'heart'],
                    ] as $stat)
                        <div class="rounded-xl border border-forest/10 bg-white p-4">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-sand-light text-forest">
                                <x-icon :name="$stat['icon']" :size="15" />
                            </span>
                            <p class="mt-3 font-heading text-xl font-extrabold text-forest">{{ $stat['value'] }}</p>
                            <p class="text-[11px] font-medium text-charcoal/70">{{ $stat['label'] }}</p>
                        </div>
                    @endforeach
                </section>

                {{-- Booking history --}}
                <section aria-labelledby="history-heading">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <h2 id="history-heading" class="font-heading text-lg font-bold text-forest">Riwayat terakhir</h2>
                        <a href="{{ route('bookings.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-forest transition hover:text-earth">
                            Lihat semua
                            <x-icon name="arrow-right" :size="15" />
                        </a>
                    </div>

                    @if($recentBookings->isEmpty())
                        <x-empty-state
                            icon="ticket"
                            title="Belum ada booking"
                            description="Booking pertama Anda akan muncul di sini setelah aktivitas dipesan."
                            class="rounded-2xl border border-dashed border-forest/20 bg-white/60" />
                    @else
                        <ul class="divide-y divide-sand/70 overflow-hidden rounded-2xl border border-forest/10 bg-white">
                            @foreach($recentBookings as $booking)
                                @php $activity = $booking->items->first()?->activity; @endphp
                                <li>
                                    <a href="{{ route('bookings.show', $booking) }}"
                                       class="flex items-center gap-4 px-4 py-4 transition hover:bg-sand-light/40 sm:px-5">
                                        <div class="hidden h-14 w-14 shrink-0 overflow-hidden rounded-lg bg-sand sm:block">
                                            @if($activity)
                                                <img src="{{ $activity->imageUrl() }}" alt=""
                                                     class="h-full w-full object-cover" loading="lazy" width="112" height="112">
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-sm font-semibold text-forest">{{ $activity?->name ?? 'Aktivitas Bumi Mandiri' }}</p>
                                            <p class="mt-1 text-xs text-charcoal/70">
                                                {{ $booking->booking_date->translatedFormat('d M Y') }}
                                                <span class="text-charcoal/70">&middot;</span> {{ $booking->booking_code }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-semibold text-forest">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                                            <div class="mt-1.5">
                                                <x-status-badge :status="$booking->status" />
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-5">
                <div class="rounded-2xl border border-forest/10 bg-white p-5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-forest font-heading text-base font-bold text-cream">
                            {{ \Illuminate\Support\Str::of($user->name)->substr(0, 1)->upper() }}
                        </span>
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-forest">{{ $user->name }}</p>
                            <p class="truncate text-xs text-charcoal/70">{{ $user->email }}</p>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="btn btn-secondary btn-sm mt-4 w-full">Atur profil</a>
                </div>

                <div class="rounded-2xl border border-forest/10 bg-white p-5">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="font-heading text-sm font-bold text-forest">Aktivitas favorit</h3>
                        <a href="{{ route('favorites.index') }}" class="text-xs font-semibold text-charcoal/70 transition hover:text-forest">Semua</a>
                    </div>

                    @if($favoriteActivities->isEmpty())
                        <p class="mt-3 text-sm leading-relaxed text-charcoal/70">
                            Belum ada yang disimpan. Tekan ikon hati pada aktivitas untuk menyimpannya.
                        </p>
                    @else
                        <ul class="mt-4 space-y-3">
                            @foreach($favoriteActivities as $favorite)
                                <li>
                                    <a href="{{ route('activities.show', $favorite) }}" class="group flex items-center gap-3">
                                        <span class="h-11 w-11 shrink-0 overflow-hidden rounded-lg bg-sand">
                                            <img src="{{ $favorite->imageUrl() }}" alt=""
                                                 class="h-full w-full object-cover" loading="lazy" width="88" height="88">
                                        </span>
                                        <span class="min-w-0">
                                            <span class="block truncate text-sm font-semibold text-forest group-hover:text-earth">{{ $favorite->name }}</span>
                                            <span class="block text-xs text-charcoal/70">Rp {{ number_format($favorite->price, 0, ',', '.') }} / orang</span>
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="rounded-2xl border border-forest/10 bg-white p-5">
                    <h3 class="font-heading text-sm font-bold text-forest">Notifikasi</h3>
                    <div class="mt-3 space-y-3">
                        @forelse($notifications as $note)
                            <div class="border-l-2 border-earth/40 pl-3">
                                <p class="text-sm leading-relaxed text-charcoal/70">{{ $note->data['message'] ?? 'Pembaruan akun' }}</p>
                                <p class="mt-1 text-[11px] text-charcoal/70">{{ $note->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-charcoal/70">Tidak ada notifikasi baru.</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
