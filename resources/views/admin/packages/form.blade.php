@extends('layouts.admin')

@section('title', ($package->exists ? 'Edit' : 'Tambah') . ' Paket | Admin Bumi Mandiri')
@section('page_title', 'Paket')

@section('content')
<a href="{{ route('admin.packages.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-charcoal/70 transition hover:text-forest">
    <x-icon name="arrow-left" :size="15" />
    Kembali ke paket
</a>

<x-admin.page-header
    eyebrow="Katalog"
    :title="$package->exists ? 'Edit paket' : 'Tambah paket'"
    description="Susun paket dari beberapa aktivitas, lengkap dengan harga dasar dan kapasitas." />

@if($errors->any())
    <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
        <x-icon name="alert-circle" :size="18" class="mt-0.5 shrink-0" />
        <span>Data belum bisa disimpan. Periksa kembali isian yang ditandai.</span>
    </div>
@endif

<form method="POST" action="{{ $package->exists ? route('admin.packages.update', $package) : route('admin.packages.store') }}" class="max-w-2xl">
    @csrf
    @if($package->exists) @method('PUT') @endif

    <div class="admin-card">
        <div class="border-b border-sand/70 px-5 py-4">
            <h2 class="font-heading text-base font-bold text-forest">Informasi paket</h2>
        </div>
        <div class="space-y-5 p-5">
            <div>
                <label for="name" class="field-label">Nama paket <span class="text-earth">*</span></label>
                <input id="name" type="text" name="name" value="{{ old('name', $package->name) }}" required
                       placeholder="Contoh: Paket Keluarga Sehari" @class(['field', 'border-red-400' => $errors->has('name')])>
                @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="target_segment" class="field-label">Segmen pengunjung</label>
                <input id="target_segment" type="text" name="target_segment" value="{{ old('target_segment', $package->target_segment) }}"
                       placeholder="keluarga, perusahaan, sekolah, komunitas" class="field">
                @error('target_segment') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="field-label">Deskripsi</label>
                <textarea id="description" name="description" rows="4" class="field"
                          placeholder="Jelaskan isi paket dan pengalaman yang didapat">{{ old('description', $package->description) }}</textarea>
                @error('description') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="admin-card mt-5">
        <div class="border-b border-sand/70 px-5 py-4">
            <h2 class="font-heading text-base font-bold text-forest">Harga dan kapasitas</h2>
        </div>
        <div class="grid gap-5 p-5 sm:grid-cols-3">
            <div>
                <label for="base_price" class="field-label">Harga dasar <span class="text-earth">*</span></label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-medium text-charcoal/70">Rp</span>
                    <input id="base_price" type="number" name="base_price" value="{{ old('base_price', $package->base_price) }}" required min="0"
                           class="field pl-9" placeholder="0">
                </div>
                @error('base_price') <p class="field-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="min_participants" class="field-label">Minimal peserta <span class="text-earth">*</span></label>
                <input id="min_participants" type="number" name="min_participants" value="{{ old('min_participants', $package->min_participants ?? 1) }}" required min="1" class="field">
                @error('min_participants') <p class="field-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="max_participants" class="field-label">Maksimal peserta</label>
                <input id="max_participants" type="number" name="max_participants" value="{{ old('max_participants', $package->max_participants) }}" min="1" class="field">
                @error('max_participants') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="border-t border-sand/70 p-5">
            <label for="estimated_duration" class="field-label">Estimasi durasi</label>
            <input id="estimated_duration" type="text" name="estimated_duration" value="{{ old('estimated_duration', $package->estimated_duration) }}"
                   placeholder="Contoh: 1 hari, 2 hari 1 malam" class="field">
        </div>
    </div>

    <div class="admin-card mt-5">
        <div class="border-b border-sand/70 px-5 py-4">
            <h2 class="font-heading text-base font-bold text-forest">Aktivitas dalam paket</h2>
            <p class="mt-0.5 text-xs text-charcoal/70">Pilih aktivitas yang termasuk dalam paket ini.</p>
        </div>
        <div class="p-5">
            @if($activities->isEmpty())
                <p class="rounded-lg bg-sand-light/60 px-4 py-3 text-sm text-charcoal/70">
                    Belum ada aktivitas.
                    <a href="{{ route('admin.activities.create') }}" class="font-semibold text-forest underline decoration-earth/40 underline-offset-4">Tambah aktivitas</a>
                    terlebih dahulu.
                </p>
            @else
                <div class="grid max-h-64 gap-2 overflow-y-auto rounded-xl border border-sand p-4 sm:grid-cols-2">
                    @foreach($activities as $activity)
                        <label class="flex cursor-pointer items-center gap-2.5 rounded-lg px-2 py-1.5 text-sm text-charcoal/70 transition hover:bg-sand-light/60">
                            <input type="checkbox" name="activity_ids[]" value="{{ $activity->id }}"
                                   @checked(collect(old('activity_ids', $package->activities->pluck('id')->all()))->contains($activity->id))
                                   class="h-4 w-4 rounded border-sand text-forest focus:ring-forest/30">
                            <span class="truncate">{{ $activity->name }}</span>
                        </label>
                    @endforeach
                </div>
            @endif
            @error('activity_ids') <p class="field-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="admin-card mt-5 p-5">
        <label class="flex cursor-pointer items-start gap-3">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $package->exists ? $package->is_active : true))
                   class="mt-0.5 h-4 w-4 rounded border-sand text-forest focus:ring-forest/30">
            <span>
                <span class="block text-sm font-semibold text-forest">Paket aktif</span>
                <span class="mt-0.5 block text-xs text-charcoal/70">Paket aktif dapat dipilih pengunjung saat memesan.</span>
            </span>
        </label>
    </div>

    <div class="mt-5 flex flex-wrap items-center gap-3">
        <button type="submit" class="btn btn-primary">{{ $package->exists ? 'Simpan perubahan' : 'Tambah paket' }}</button>
        <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
@endsection
