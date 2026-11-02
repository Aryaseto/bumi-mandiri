@extends('layouts.auth')

@section('title', 'Daftar | Bumi Mandiri')

@section('content')
<div x-data="{ showPassword: false }">
    <p class="eyebrow text-earth">Akun baru</p>
    <h1 class="mt-2 font-heading text-3xl font-extrabold tracking-tight text-forest">Mulai rencana ke alam.</h1>
    <p class="mt-3 text-sm leading-relaxed text-charcoal/70">
        Buat akun untuk memesan aktivitas dan menyimpan rencana kunjungan Anda.
    </p>

    @if($errors->any())
        <div class="mt-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
            <x-icon name="alert-circle" :size="18" class="mt-0.5 shrink-0" />
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="mt-7 space-y-5">
        @csrf

        <div>
            <label for="name" class="field-label">Nama lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                   autocomplete="name" placeholder="Nama sesuai identitas"
                   @class(['field', 'border-red-400' => $errors->has('name')])>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="email" class="field-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       autocomplete="email" placeholder="nama@email.com"
                       @class(['field', 'border-red-400' => $errors->has('email')])>
            </div>
            <div>
                <label for="phone" class="field-label">Nomor HP <span class="font-normal text-charcoal/70">(opsional)</span></label>
                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                       autocomplete="tel" placeholder="08xx xxxx xxxx"
                       @class(['field', 'border-red-400' => $errors->has('phone')])>
            </div>
        </div>

        <div>
            <label for="password" class="field-label">Password</label>
            <div class="relative">
                <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                       autocomplete="new-password" placeholder="Minimal 8 karakter"
                       @class(['field pr-12', 'border-red-400' => $errors->has('password')])>
                <button type="button" @click="showPassword = !showPassword"
                        class="absolute right-1.5 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-lg text-charcoal/70 transition hover:bg-sand-light hover:text-forest"
                        :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'">
                    <x-icon name="eye" :size="18" />
                </button>
            </div>
            @error('password') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="field-label">Ulangi password</label>
            <input id="password_confirmation" :type="showPassword ? 'text' : 'password'" name="password_confirmation" required
                   autocomplete="new-password" placeholder="Ketik ulang password"
                   class="field">
        </div>

        <button type="submit" class="btn btn-primary w-full">Buat akun</button>
    </form>

    <p class="mt-7 text-center text-sm text-charcoal/70">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-forest underline decoration-earth/50 decoration-2 underline-offset-4 transition hover:decoration-earth">Masuk di sini</a>
    </p>
</div>
@endsection
