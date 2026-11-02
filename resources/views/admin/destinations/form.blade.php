@extends('layouts.admin')

@section('title', ($destination->exists ? 'Edit' : 'Tambah') . ' Destinasi | Admin Bumi Mandiri')
@section('page_title', 'Destinasi')

@php
    // Nilai contoh dari seeder tidak boleh tampil seolah data final.
    $mapsValue = $destination->google_maps_url;
    if ($mapsValue && str_starts_with($mapsValue, '[')) {
        $mapsValue = '';
    }
@endphp

@section('content')
<a href="{{ route('admin.destinations.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-charcoal/70 transition hover:text-forest">
    <x-icon name="arrow-left" :size="15" />
    Kembali ke destinasi
</a>

<x-admin.page-header
    eyebrow="Katalog"
    :title="$destination->exists ? 'Edit destinasi' : 'Tambah destinasi'"
    description="Lengkapi lokasi dan foto agar pengunjung mengenali tempatnya." />

@if($errors->any())
    <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
        <x-icon name="alert-circle" :size="18" class="mt-0.5 shrink-0" />
        <span>Data belum bisa disimpan. Periksa kembali isian yang ditandai.</span>
    </div>
@endif

<form method="POST" action="{{ $destination->exists ? route('admin.destinations.update', $destination) : route('admin.destinations.store') }}"
      enctype="multipart/form-data" class="max-w-2xl">
    @csrf
    @if($destination->exists) @method('PUT') @endif

    <div class="admin-card">
        <div class="border-b border-sand/70 px-5 py-4">
            <h2 class="font-heading text-base font-bold text-forest">Informasi destinasi</h2>
        </div>
        <div class="space-y-5 p-5">
            <div>
                <label for="name" class="field-label">Nama destinasi <span class="text-earth">*</span></label>
                <input id="name" type="text" name="name" value="{{ old('name', $destination->name) }}" required
                       placeholder="Contoh: Lembah Cikole" @class(['field', 'border-red-400' => $errors->has('name')])>
                @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="location" class="field-label">Lokasi</label>
                <input id="location" type="text" name="location" value="{{ old('location', $destination->location) }}"
                       placeholder="Contoh: Cikole, Sukabumi" @class(['field', 'border-red-400' => $errors->has('location')])>
                @error('location') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="field-label">Deskripsi</label>
                <textarea id="description" name="description" rows="4" class="field"
                          placeholder="Gambarkan suasana, akses, dan hal yang membuat tempat ini layak dikunjungi">{{ old('description', $destination->description) }}</textarea>
                @error('description') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="admin-card mt-5">
        <div class="border-b border-sand/70 px-5 py-4">
            <h2 class="font-heading text-base font-bold text-forest">Koordinat dan peta</h2>
            <p class="mt-0.5 text-xs text-charcoal/70">Opsional. Berguna untuk menampilkan titik lokasi di halaman destinasi.</p>
        </div>
        <div class="space-y-5 p-5">
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="latitude" class="field-label">Latitude</label>
                    <input id="latitude" type="number" step="any" name="latitude" value="{{ old('latitude', $destination->latitude) }}"
                           placeholder="-6.8" @class(['field', 'border-red-400' => $errors->has('latitude')])>
                    @error('latitude') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="longitude" class="field-label">Longitude</label>
                    <input id="longitude" type="number" step="any" name="longitude" value="{{ old('longitude', $destination->longitude) }}"
                           placeholder="106.9" @class(['field', 'border-red-400' => $errors->has('longitude')])>
                    @error('longitude') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="google_maps_url" class="field-label">Tautan Google Maps</label>
                <input id="google_maps_url" type="url" name="google_maps_url" value="{{ old('google_maps_url', $mapsValue) }}"
                       placeholder="https://maps.google.com/..." @class(['field', 'border-red-400' => $errors->has('google_maps_url')])>
                @error('google_maps_url') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="admin-card mt-5">
        <div class="border-b border-sand/70 px-5 py-4">
            <h2 class="font-heading text-base font-bold text-forest">Foto sampul</h2>
        </div>
        <div class="p-5">
            @if($destination->cover_image)
                <div class="mb-3 flex flex-wrap items-center gap-4 rounded-xl bg-sand-light/70 p-3">
                    <img src="{{ $destination->imageUrl() }}" alt="" class="h-20 w-28 rounded-lg object-cover" width="224" height="160">
                    <label class="flex cursor-pointer items-center gap-2 text-sm text-red-700">
                        <input type="checkbox" name="remove_cover_image" value="1" class="h-4 w-4 rounded border-sand text-red-600 focus:ring-red-300">
                        Hapus foto ini
                    </label>
                </div>
            @endif
            <input id="cover_image" type="file" name="cover_image" accept="image/jpeg,image/png,image/webp"
                   class="block w-full rounded-xl border border-sand bg-white text-sm text-charcoal/70 file:mr-4 file:border-0 file:bg-sand-light file:px-4 file:py-3 file:text-xs file:font-semibold file:text-forest hover:file:bg-sand">
            <p class="mt-1.5 text-xs text-charcoal/70">JPG, PNG, atau WEBP. Maksimal 2 MB.{{ $destination->cover_image ? ' Unggah berkas baru untuk mengganti.' : '' }}</p>
            @error('cover_image') <p class="field-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="mt-5 flex flex-wrap items-center gap-3">
        <button type="submit" class="btn btn-primary">{{ $destination->exists ? 'Simpan perubahan' : 'Tambah destinasi' }}</button>
        <a href="{{ route('admin.destinations.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
@endsection
