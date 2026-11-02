<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bumi Mandiri | Wisata Alam & Aktivitas Outdoor Sukabumi')</title>
    <meta name="description" content="@yield('meta_description', 'Bumi Mandiri Sukabumi: aktivitas outdoor, camping, tubing, outbound, dan paket kunjungan untuk keluarga, sekolah, komunitas, serta perusahaan.')">
    <meta name="theme-color" content="#173B2C">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Bumi Mandiri">
    <meta property="og:title" content="@yield('title', 'Bumi Mandiri | Wisata Alam & Aktivitas Outdoor Sukabumi')">
    <meta property="og:description" content="@yield('meta_description', 'Aktivitas outdoor dan paket kunjungan di Sukabumi untuk keluarga, sekolah, komunitas, dan perusahaan.')">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Bumi Mandiri | Wisata Alam & Aktivitas Outdoor Sukabumi')">
    <meta name="twitter:description" content="@yield('meta_description', 'Aktivitas outdoor dan paket kunjungan di Sukabumi untuk keluarga, sekolah, komunitas, dan perusahaan.')">
    <meta name="twitter:image" content="{{ asset('images/logo.png') }}">

    {{-- Favicon / Tab Icons --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon-192.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @if(request()->routeIs('home'))
        <link rel="preload" as="image" href="{{ asset('images/hero.jpg') }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-cream font-body text-charcoal antialiased">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[100] focus:rounded-lg focus:bg-forest focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-cream">
        Lewati ke konten utama
    </a>

    <x-navbar />

    <main id="main-content">
        @yield('content')
    </main>

    <x-footer />

    @stack('scripts')
</body>
</html>
