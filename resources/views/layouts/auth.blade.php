<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Masuk | Bumi Mandiri')</title>
    <meta name="description" content="@yield('meta_description', 'Masuk atau daftar akun Bumi Mandiri untuk memesan aktivitas wisata alam di Sukabumi.')">
    <meta name="theme-color" content="#173B2C">
    <meta name="robots" content="noindex">

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon-192.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-cream font-body text-charcoal antialiased">
    <div class="grid min-h-screen lg:grid-cols-[1.05fr_1fr]">

        {{-- Brand panel --}}
        <aside class="relative hidden overflow-hidden bg-forest lg:flex lg:flex-col lg:justify-between">
            <img src="{{ asset('images/forest.jpg') }}" alt=""
                 class="absolute inset-0 h-full w-full object-cover opacity-45" width="1200" height="1600">
            <div class="absolute inset-0 bg-gradient-to-b from-forest-dark/85 via-forest/60 to-forest-dark/95"></div>

            <div class="relative z-10 p-10 xl:p-14">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 text-cream">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Bumi Mandiri"
                         class="h-11 w-11 shrink-0 rounded-xl object-cover ring-1 ring-cream/20 shadow-sm"
                         width="44" height="44">
                    <span>
                        <span class="block font-heading text-base font-extrabold uppercase tracking-[0.14em]">Bumi Mandiri</span>
                        <span class="block text-[11px] font-medium tracking-wide text-cream/70">Sukabumi, Jawa Barat</span>
                    </span>
                </a>
            </div>

            <div class="relative z-10 px-10 pb-12 xl:px-14 xl:pb-16">
                <p class="eyebrow text-cream/70">Ruang tamu alam terbuka</p>
                <h2 class="mt-4 max-w-md font-heading text-4xl font-extrabold leading-[1.1] text-cream xl:text-[2.75rem]">
                    Satu akun untuk semua rencana ke alam.
                </h2>
                <p class="mt-5 max-w-sm text-[15px] leading-relaxed text-cream/75">
                    Simpan aktivitas favorit, atur jadwal kunjungan, dan pantau status booking Anda tanpa perlu menghubungi admin berulang kali.
                </p>

                <ul class="mt-8 space-y-3 text-sm text-cream/80">
                    @foreach([
                        'Riwayat booking dan status tersimpan rapi',
                        'Favorit untuk rencana kunjungan berikutnya',
                        'Profil dan kontak bisa diperbarui sendiri',
                    ] as $point)
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-cream/15 text-cream">
                                <x-icon name="check" :size="12" />
                            </span>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="relative z-10 border-t border-cream/15 px-10 py-5 text-xs text-cream/70 xl:px-14">
                Wisata alam, outbound, dan camping untuk keluarga, sekolah, dan komunitas.
            </div>
        </aside>

        {{-- Form panel --}}
        <div class="flex min-h-screen flex-col">
            <header class="flex items-center justify-between px-5 py-5 sm:px-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 text-forest lg:hidden">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-forest text-cream">
                        <x-icon name="leaf" :size="18" />
                    </span>
                    <span class="font-heading text-sm font-extrabold uppercase tracking-[0.12em]">Bumi Mandiri</span>
                </a>
                <a href="{{ route('home') }}" class="ml-auto inline-flex items-center gap-1.5 text-sm font-medium text-charcoal/70 transition hover:text-forest">
                    <x-icon name="arrow-left" :size="15" />
                    Kembali ke situs
                </a>
            </header>

            <main class="flex flex-1 items-center justify-center px-5 pb-14 pt-4 sm:px-8">
                <div class="w-full max-w-[420px]">
                    @yield('content')
                </div>
            </main>

            <footer class="border-t border-forest/10 px-5 py-5 text-center text-xs text-charcoal/70 sm:px-8">
                &copy; {{ date('Y') }} Bumi Mandiri. Sukabumi, Jawa Barat.
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
