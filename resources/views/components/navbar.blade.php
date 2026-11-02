@php
    $onHome = request()->routeIs('home');
    $solid = ! $onHome;
    $whatsapp = \App\Models\Setting::get('whatsapp');
    $hasWhatsapp = $whatsapp && ! str_contains((string) $whatsapp, '[');
    $instagram = \App\Models\Setting::get('instagram');
    $hasInstagram = $instagram && ! str_contains((string) $instagram, '[');
    $email = \App\Models\Setting::get('email');

    $links = [
        ['label' => 'Destinasi', 'route' => 'destinations.index'],
        ['label' => 'Aktivitas', 'route' => 'activities.index'],
        ['label' => 'Paket', 'route' => 'packages.index'],
        ['label' => 'Galeri', 'route' => 'gallery.index'],
        ['label' => 'Cerita', 'route' => 'articles.index'],
        ['label' => 'Tentang', 'route' => 'about'],
    ];
@endphp

<div class="hidden bg-forest-dark text-cream/70 lg:block">
    <div class="container-page flex h-9 items-center justify-between text-[11px] tracking-wide">
        <div class="flex items-center gap-5">
            <span class="inline-flex items-center gap-1.5">
                <x-icon name="map-pin" :size="13" class="text-earth-light" />
                {{ \App\Models\Setting::get('address', 'Sukabumi, Jawa Barat') }}
            </span>
            @if($email)
                <a href="mailto:{{ $email }}" class="inline-flex items-center gap-1.5 transition hover:text-cream">
                    <x-icon name="mail" :size="13" class="text-earth-light" />
                    {{ $email }}
                </a>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <span class="inline-flex items-center gap-1.5">
                <x-icon name="clock" :size="13" class="text-earth-light" />
                Senin - Minggu, 07.00 - 17.00 WIB
            </span>
            @if($hasInstagram)
                <a href="{{ $instagram }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 transition hover:text-cream">
                    <x-icon name="brand-instagram" :size="13" />
                    Instagram
                </a>
            @endif
        </div>
    </div>
</div>

<header x-data="{ open: false, scrolled: false }"
        @keydown.escape.window="open = false"
        x-init="scrolled = window.scrollY > 24; window.addEventListener('scroll', () => scrolled = window.scrollY > 24, { passive: true })"
        :class="(scrolled || {{ $solid ? 'true' : 'false' }}) ? 'bg-cream/95 shadow-[0_1px_0_rgba(23,59,44,0.10)] backdrop-blur' : 'bg-gradient-to-b from-forest-dark/90 via-forest-dark/55 to-transparent [text-shadow:0_1px_3px_rgba(11,31,22,0.55)]'"
        class="sticky top-0 z-50 transition-colors duration-300">
    <nav class="container-page flex h-[70px] items-center justify-between gap-6" aria-label="Navigasi utama">
        <a href="{{ route('home') }}" class="group flex items-center gap-3" aria-label="Bumi Mandiri, ke beranda">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Bumi Mandiri"
                 class="h-10 w-10 shrink-0 rounded-xl object-cover ring-1 ring-cream/25 shadow-sm transition duration-300 group-hover:scale-105"
                 width="40" height="40">
            <span class="leading-none">
                <span :class="(scrolled || {{ $solid ? 'true' : 'false' }}) ? 'text-forest' : 'text-cream'"
                      class="block font-heading text-[17px] font-extrabold tracking-[0.08em] transition-colors">BUMI MANDIRI</span>
                <span :class="(scrolled || {{ $solid ? 'true' : 'false' }}) ? 'text-charcoal/70' : 'text-cream/75'"
                      class="mt-1 block text-[10px] font-medium uppercase tracking-[0.22em] transition-colors">Sukabumi</span>
            </span>
        </a>

        <div :class="(scrolled || {{ $solid ? 'true' : 'false' }}) ? 'text-forest' : 'text-cream'"
             class="hidden items-center gap-1 text-sm font-medium transition-colors lg:flex">
            @foreach($links as $link)
                <a href="{{ route($link['route']) }}"
                   class="rounded-lg px-3 py-2 opacity-75 transition hover:bg-forest/5 hover:opacity-100 {{ request()->routeIs($link['route'] . '*') ? 'opacity-100' : '' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>

        <div class="hidden items-center gap-3 lg:flex">
            @auth
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}"
                   class="inline-flex items-center gap-2 rounded-lg px-2 py-2 text-sm font-semibold transition"
                   :class="(scrolled || {{ $solid ? 'true' : 'false' }}) ? 'text-forest hover:bg-forest/5' : 'text-cream hover:bg-cream/10'">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-earth text-[11px] font-bold text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                    Dashboard
                </a>
                @if($hasWhatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" target="_blank" rel="noopener"
                       class="btn btn-sm btn-accent">
                        <x-icon name="brand-whatsapp" :size="15" />
                        WhatsApp
                    </a>
                @else
                    <a href="{{ route('contact.create') }}"
                       :class="(scrolled || {{ $solid ? 'true' : 'false' }}) ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-light'"
                       class="btn btn-sm">Hubungi Kami</a>
                @endif
            @else
                <a href="{{ route('login') }}"
                   :class="(scrolled || {{ $solid ? 'true' : 'false' }}) ? 'text-forest hover:bg-forest/5' : 'text-cream hover:bg-cream/10'"
                   class="rounded-lg px-3 py-2 text-sm font-semibold transition">Masuk</a>
                <a href="{{ route('contact.create') }}"
                   :class="(scrolled || {{ $solid ? 'true' : 'false' }}) ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-light'"
                   class="btn btn-sm">Rencanakan Kunjungan</a>
            @endauth
        </div>

        <button @click="open = !open" type="button"
                class="flex h-11 w-11 items-center justify-center rounded-xl border transition lg:hidden"
                :class="(scrolled || {{ $solid ? 'true' : 'false' }}) ? 'border-forest/15 text-forest' : 'border-cream/30 text-cream'"
                :aria-expanded="open" aria-controls="menu-mobile" aria-label="Buka menu navigasi">
            <x-icon name="menu" :size="22" x-show="!open" />
            <x-icon name="x" :size="22" x-show="open" x-cloak />
        </button>
    </nav>

    <div x-show="open" x-cloak x-transition.origin.top
         id="menu-mobile"
         class="border-t border-sand bg-cream px-5 pb-6 pt-4 shadow-lift lg:hidden">
        <div class="flex flex-col">
            @foreach($links as $link)
                <a href="{{ route($link['route']) }}"
                   class="border-b border-sand/70 py-3.5 text-[15px] font-medium text-forest {{ request()->routeIs($link['route'] . '*') ? 'text-earth' : '' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>

        <div class="mt-5 flex flex-col gap-2">
            @auth
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="btn btn-secondary">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn w-full border border-red-200 text-red-700 hover:bg-red-50">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-secondary">Masuk</a>
                <a href="{{ route('contact.create') }}" class="btn btn-primary">Rencanakan Kunjungan</a>
            @endauth
        </div>
    </div>
</header>
