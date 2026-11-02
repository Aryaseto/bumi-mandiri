<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin | Bumi Mandiri')</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#173B2C">

    {{-- Favicon / Tab Icons --}}
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
<body class="min-h-screen bg-[#F4EFE5] font-body text-charcoal antialiased" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">
    <a href="#admin-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[100] focus:rounded-lg focus:bg-forest focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-cream">
        Lewati ke konten
    </a>

    <x-admin.sidebar />

    <div class="min-h-screen lg:pl-72">
        <x-admin.topbar />

        <main id="admin-content" class="mx-auto w-full max-w-[1440px] px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            @if(session('success'))
                <div class="mb-5 flex items-start gap-3 rounded-xl border border-nature/25 bg-white px-4 py-3 text-sm text-forest shadow-soft" role="status">
                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-nature/15 text-nature">
                        <x-icon name="check" :size="13" />
                    </span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-white px-4 py-3 text-sm text-red-800 shadow-soft" role="alert">
                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-50 text-red-700">
                        <x-icon name="alert-circle" :size="13" />
                    </span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
