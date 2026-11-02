<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bumi Mandiri')</title>
    <meta name="robots" content="noindex">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon-192.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-cream px-5 font-body text-charcoal">
    <main class="w-full max-w-md text-center">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 text-forest">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Bumi Mandiri"
                 class="h-10 w-10 shrink-0 rounded-xl object-cover ring-1 ring-forest/10 shadow-sm"
                 width="40" height="40">
            <span class="font-heading text-sm font-extrabold uppercase tracking-[0.14em]">Bumi Mandiri</span>
        </a>

        <p class="mt-10 font-heading text-6xl font-extrabold tracking-tight text-forest/15">@yield('code')</p>
        <h1 class="mt-2 font-heading text-2xl font-extrabold tracking-tight text-forest">@yield('message')</h1>
        <p class="mx-auto mt-3 max-w-sm text-sm leading-relaxed text-charcoal/70">@yield('description')</p>

        <div class="mt-7 flex flex-col justify-center gap-3 sm:flex-row">
            <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke beranda</a>
            <a href="{{ route('contact.create') }}" class="btn btn-secondary">Hubungi kami</a>
        </div>
    </main>
</body>
</html>
