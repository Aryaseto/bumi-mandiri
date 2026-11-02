@extends('layouts.admin')

@section('title', ($user->exists ? 'Edit' : 'Tambah') . ' Pengguna | Admin Bumi Mandiri')
@section('page_title', 'Pengguna')

@section('content')
<a href="{{ route('admin.users.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-charcoal/70 transition hover:text-forest">
    <x-icon name="arrow-left" :size="15" />
    Kembali ke pengguna
</a>

<x-admin.page-header
    eyebrow="Sistem"
    :title="$user->exists ? 'Edit pengguna' : 'Tambah pengguna'"
    :description="$user->exists ? 'Perbarui data akun, peran, atau password pengguna.' : 'Buat akun baru untuk admin atau pelanggan.'" />

@if($errors->any())
    <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
        <x-icon name="alert-circle" :size="18" class="mt-0.5 shrink-0" />
        <span>Data belum bisa disimpan. Periksa kembali isian yang ditandai.</span>
    </div>
@endif

<form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" class="max-w-2xl">
    @csrf
    @if($user->exists) @method('PUT') @endif

    <div class="admin-card">
        <div class="border-b border-sand/70 px-5 py-4">
            <h2 class="font-heading text-base font-bold text-forest">Informasi akun</h2>
        </div>

        <div class="space-y-5 p-5">
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="name" class="field-label">Nama lengkap <span class="text-earth">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                           autocomplete="name" placeholder="Nama pengguna"
                           @class(['field', 'border-red-400' => $errors->has('name')])>
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
                <label for="email" class="field-label">Email <span class="text-earth">*</span></label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                       autocomplete="email" placeholder="nama@email.com"
                       @class(['field', 'border-red-400' => $errors->has('email')])>
                @error('email') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="role_id" class="field-label">Peran <span class="text-earth">*</span></label>
                <select id="role_id" name="role_id" required class="field">
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>
                            {{ $role->name === 'admin' ? 'Admin' : 'Pelanggan' }}
                        </option>
                    @endforeach
                </select>
                @error('role_id') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="admin-card mt-5">
        <div class="border-b border-sand/70 px-5 py-4">
            <h2 class="font-heading text-base font-bold text-forest">Keamanan</h2>
            <p class="mt-0.5 text-xs text-charcoal/70">
                {{ $user->exists ? 'Biarkan kosong jika tidak ingin mengubah password.' : 'Minimal 8 karakter.' }}
            </p>
        </div>

        <div class="p-5">
            <label for="password" class="field-label">
                {{ $user->exists ? 'Password baru' : 'Password' }}
                @if(! $user->exists) <span class="text-earth">*</span> @endif
            </label>
            <input id="password" type="password" name="password" {{ $user->exists ? '' : 'required' }}
                   autocomplete="new-password" placeholder="{{ $user->exists ? 'Kosongkan jika tetap' : 'Minimal 8 karakter' }}"
                   @class(['field', 'border-red-400' => $errors->has('password')])>
            @error('password') <p class="field-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="mt-5 flex flex-wrap items-center gap-3">
        <button type="submit" class="btn btn-primary">{{ $user->exists ? 'Simpan perubahan' : 'Tambah pengguna' }}</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
@endsection
