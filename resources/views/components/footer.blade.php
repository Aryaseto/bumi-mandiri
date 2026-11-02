@php
    $email = \App\Models\Setting::get('email');
    $whatsapp = \App\Models\Setting::get('whatsapp');
    $hasWhatsapp = $whatsapp && ! str_contains((string) $whatsapp, '[');
    $instagram = \App\Models\Setting::get('instagram');
    $hasInstagram = $instagram && ! str_contains((string) $instagram, '[');
    $mapsUrl = \App\Models\Setting::get('google_maps_url');
    $hasMaps = $mapsUrl && ! str_contains((string) $mapsUrl, '[');
    $address = \App\Models\Setting::get('address', 'Sukabumi, Jawa Barat');
@endphp

<footer class="bg-forest text-cream">
    <div class="container-page py-16 lg:py-20">
        <div class="grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3" aria-label="Bumi Mandiri, ke beranda">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Bumi Mandiri"
                         class="h-11 w-11 shrink-0 rounded-xl object-cover ring-1 ring-cream/20 shadow-sm"
                         width="44" height="44">
                    <span class="font-heading text-lg font-extrabold tracking-[0.08em]">BUMI MANDIRI</span>
                </a>

                <p class="mt-5 max-w-md text-sm leading-relaxed text-cream/70">
                    Ruang untuk bermain, belajar, dan membangun kebersamaan di alam Sukabumi.
                    Kami mengelola aktivitas outdoor dan menerima kunjungan keluarga, sekolah, komunitas, serta tim perusahaan.
                </p>
                <p class="mt-4 text-[13px] italic text-cream/50">"Alam yang dekat, cerita yang tinggal."</p>

                <div class="mt-6 flex flex-wrap items-center gap-2">
                    @if($hasWhatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" target="_blank" rel="noopener" class="btn btn-sm btn-light">
                            <x-icon name="brand-whatsapp" :size="15" />
                            WhatsApp
                        </a>
                    @endif
                    @if($hasInstagram)
                        <a href="{{ $instagram }}" target="_blank" rel="noopener" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-cream/20 text-cream/80 transition hover:border-cream/50 hover:text-cream" aria-label="Instagram Bumi Mandiri">
                            <x-icon name="brand-instagram" :size="17" />
                        </a>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-3">
                <h3 class="text-xs font-semibold uppercase tracking-[0.18em] text-cream/70">Jelajahi</h3>
                <ul class="mt-4 space-y-2.5 text-sm text-cream/70">
                    <li><a href="{{ route('destinations.index') }}" class="transition hover:text-cream">Destinasi</a></li>
                    <li><a href="{{ route('activities.index') }}" class="transition hover:text-cream">Aktivitas</a></li>
                    <li><a href="{{ route('packages.index') }}" class="transition hover:text-cream">Paket wisata</a></li>
                    <li><a href="{{ route('gallery.index') }}" class="transition hover:text-cream">Galeri</a></li>
                </ul>

                <h3 class="mt-7 text-xs font-semibold uppercase tracking-[0.18em] text-cream/70">Bumi Mandiri</h3>
                <ul class="mt-4 space-y-2.5 text-sm text-cream/70">
                    <li><a href="{{ route('about') }}" class="transition hover:text-cream">Tentang kami</a></li>
                    <li><a href="{{ route('home') }}#rating-pengunjung" class="transition hover:text-cream">Rating pengunjung</a></li>
                    <li><a href="{{ route('articles.index') }}" class="transition hover:text-cream">Cerita &amp; panduan</a></li>
                    <li><a href="{{ route('contact.create') }}" class="transition hover:text-cream">Kontak</a></li>
                    @guest
                        <li><a href="{{ route('login') }}" class="transition hover:text-cream">Masuk akun</a></li>
                    @endguest
                </ul>
            </div>

            <div class="lg:col-span-3">
                <h3 class="text-xs font-semibold uppercase tracking-[0.18em] text-cream/70">Kontak</h3>
                <ul class="mt-4 space-y-3 text-sm text-cream/70">
                    <li class="flex gap-2.5">
                        <x-icon name="map-pin" :size="17" class="mt-0.5 shrink-0 text-earth-light" />
                        <span>{{ $address }}</span>
                    </li>
                    @if($email)
                        <li class="flex gap-2.5">
                            <x-icon name="mail" :size="17" class="mt-0.5 shrink-0 text-earth-light" />
                            <a href="mailto:{{ $email }}" class="transition hover:text-cream">{{ $email }}</a>
                        </li>
                    @endif
                    @if($hasWhatsapp)
                        <li class="flex gap-2.5">
                            <x-icon name="phone" :size="17" class="mt-0.5 shrink-0 text-earth-light" />
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" target="_blank" rel="noopener" class="transition hover:text-cream">{{ $whatsapp }}</a>
                        </li>
                    @endif
                </ul>

                @if($hasMaps)
                    <a href="{{ $mapsUrl }}" target="_blank" rel="noopener" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-cream transition hover:text-earth-light">
                        Buka di Google Maps
                        <x-icon name="external-link" :size="15" />
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="border-t border-cream/10">
        <div class="container-page flex flex-col gap-2 py-5 text-xs text-cream/70 sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} Bumi Mandiri Sukabumi. Seluruh hak cipta dilindungi.</p>
            <a href="{{ route('contact.create') }}" class="transition hover:text-cream">Rencanakan kunjungan Anda</a>
        </div>
    </div>
</footer>
