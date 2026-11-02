@extends('layouts.auth')

@section('title', 'Masuk | Bumi Mandiri')

@section('content')
<div x-data="{ showPassword: false }">
    <p class="eyebrow text-earth">Akun Anda</p>
    <h1 class="mt-2 font-heading text-3xl font-extrabold tracking-tight text-forest">Selamat datang kembali.</h1>
    <p class="mt-3 text-sm leading-relaxed text-charcoal/70">
        Masuk untuk melihat jadwal, status booking, dan aktivitas favorit Anda.
    </p>

    @if($errors->any())
        <div class="mt-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
            <x-icon name="alert-circle" :size="18" class="mt-0.5 shrink-0" />
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="mt-7 space-y-5">
        @csrf

        <div>
            <label for="email" class="field-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   autocomplete="email" placeholder="nama@email.com"
                   @class(['field', 'border-red-400' => $errors->has('email')])>
        </div>

        <div>
            <div class="flex items-baseline justify-between gap-3">
                <label for="password" class="field-label">Password</label>
            </div>
            <div class="relative">
                <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                       autocomplete="current-password" placeholder="Masukkan password"
                       @class(['field pr-12', 'border-red-400' => $errors->has('password')])>
                <button type="button" @click="showPassword = !showPassword"
                        class="absolute right-1.5 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-lg text-charcoal/70 transition hover:bg-sand-light hover:text-forest"
                        :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'">
                    <x-icon name="eye" :size="18" />
                </button>
            </div>
        </div>

        <label class="flex cursor-pointer items-center gap-2.5 text-sm text-charcoal/70">
            <input type="checkbox" name="remember"
                   class="h-4 w-4 rounded border-sand text-forest focus:ring-forest/30">
            Ingat saya di perangkat ini
        </label>

        <button type="submit" class="btn btn-primary w-full">Masuk ke akun</button>
    </form>

    <p class="mt-7 text-center text-sm text-charcoal/70">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-forest underline decoration-earth/50 decoration-2 underline-offset-4 transition hover:decoration-earth">Daftar sekarang</a>
    </p>

    <p class="mt-8 rounded-xl bg-sand-light px-4 py-3 text-center text-xs leading-relaxed text-charcoal/70">
        Dengan masuk, Anda menyetujui data akun digunakan untuk memproses booking di Bumi Mandiri.
    </p>
</div>
@endsection
