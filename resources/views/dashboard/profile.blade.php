@extends('layouts.app')

@section('title', 'Profil Saya | Bumi Mandiri')

@section('content')
<section class="pb-20 pt-28 lg:pt-32">
    <div class="container-page">
        <div class="flex flex-wrap items-end justify-between gap-5">
            <div>
                <p class="eyebrow text-earth">Akun saya</p>
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-forest sm:text-4xl">Profil saya.</h1>
                <p class="mt-3 max-w-xl text-[15px] leading-relaxed text-charcoal/70">
                    Perbarui data kontak agar kami mudah menghubungi Anda terkait booking.
                </p>
            </div>
        </div>

        <div class="mt-7">
            <x-account-nav />
        </div>

        @if(session('success'))
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-nature/25 bg-nature/10 px-4 py-3 text-sm text-nature" role="status">
                <x-icon name="check" :size="18" class="mt-0.5 shrink-0" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_300px] lg:items-start">
            <form method="POST" action="{{ route('profile.update') }}" class="rounded-2xl border border-forest/10 bg-white">
                @csrf
                @method('PUT')

                <div class="border-b border-sand/70 px-5 py-4 sm:px-7">
                    <h2 class="font-heading text-base font-bold text-forest">Data diri</h2>
                    <p class="mt-0.5 text-xs text-charcoal/70">Nama dan email dipakai pada konfirmasi booking.</p>
                </div>

                <div class="space-y-5 p-5 sm:p-7">
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="name" class="field-label">Nama lengkap</label>
                            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   autocomplete="name" @class(['field', 'border-red-400' => $errors->has('name')])>
                            @error('name') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="phone" class="field-label">Nomor HP</label>
                            <input id="phone" type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                   autocomplete="tel" placeholder="08xx xxxx xxxx"
                                   @class(['field', 'border-red-400' => $errors->has('phone')])>
                            @error('phone') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="email" class="field-label">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                               autocomplete="email" @class(['field', 'border-red-400' => $errors->has('email')])>
                        @error('email') <p class="field-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="border-t border-sand/70 pt-5">
                        <h3 class="font-heading text-sm font-bold text-forest">Ganti password</h3>
                        <p class="mt-0.5 text-xs text-charcoal/70">Biarkan kosong jika tidak ingin mengubah password.</p>

                        <div class="mt-4 grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="password" class="field-label">Password baru</label>
                                <input id="password" type="password" name="password" autocomplete="new-password"
                                       placeholder="Minimal 8 karakter"
                                       @class(['field', 'border-red-400' => $errors->has('password')])>
                                @error('password') <p class="field-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="field-label">Ulangi password baru</label>
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                       autocomplete="new-password" placeholder="Ketik ulang password" class="field">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 border-t border-sand/70 px-5 py-4 sm:px-7">
                    <button type="submit" class="btn btn-primary">Simpan perubahan</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>

            <aside class="rounded-2xl border border-forest/10 bg-white p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-14 w-14 items-center justify-center rounded-full bg-forest font-heading text-lg font-bold text-cream">
                        {{ \Illuminate\Support\Str::of($user->name)->substr(0, 1)->upper() }}
                    </span>
                    <div class="min-w-0">
                        <p class="truncate font-semibold text-forest">{{ $user->name }}</p>
                        <p class="truncate text-xs text-charcoal/70">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="mt-5 space-y-3 border-t border-sand/70 pt-4 text-sm">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-charcoal/70">Status akun</span>
                        <x-status-badge status="active" label="Aktif" />
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-charcoal/70">Nomor HP</span>
                        <span class="font-medium text-charcoal/75">{{ $user->phone ?: 'Belum diisi' }}</span>
                    </div>
                </div>

                <p class="mt-5 rounded-lg bg-sand-light/70 p-3 text-xs leading-relaxed text-charcoal/70">
                    Password hanya dapat diubah dari halaman ini. Kami tidak pernah meminta password melalui telepon atau pesan.
                </p>
            </aside>
        </div>
    </div>
</section>
@endsection
