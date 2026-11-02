@extends('layouts.admin')

@section('title', ($activity->exists ? 'Edit' : 'Tambah') . ' Aktivitas | Admin Bumi Mandiri')
@section('page_title', 'Aktivitas')

@php
    // Checkbox yang tidak dicentang tidak ikut terkirim, jadi old() kosong bukan berarti "pakai default".
    $hasOld = session()->hasOldInput();
    $isFeatured = $hasOld ? (bool) old('is_featured') : (bool) $activity->is_featured;
    $isActive = $hasOld ? (bool) old('is_active') : ($activity->exists ? (bool) $activity->is_active : true);
@endphp

@section('content')
<a href="{{ route('admin.activities.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-charcoal/70 transition hover:text-forest">
    <x-icon name="arrow-left" :size="15" />
    Kembali ke aktivitas
</a>

<x-admin.page-header
    eyebrow="Katalog"
    :title="$activity->exists ? 'Edit aktivitas' : 'Tambah aktivitas'"
    description="Lengkapi informasi yang dibutuhkan pengunjung sebelum memesan." />

@if($categories->isEmpty() || $destinations->isEmpty())
    <div class="mb-5 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900" role="alert">
        <x-icon name="alert-circle" :size="18" class="mt-0.5 shrink-0" />
        <span>
            Aktivitas membutuhkan minimal satu
            @if($categories->isEmpty())
                <a href="{{ route('admin.categories.create') }}" class="font-semibold underline underline-offset-2">kategori</a>
            @endif
            @if($categories->isEmpty() && $destinations->isEmpty()) dan satu @endif
            @if($destinations->isEmpty())
                <a href="{{ route('admin.destinations.create') }}" class="font-semibold underline underline-offset-2">destinasi</a>
            @endif
            sebelum bisa disimpan.
        </span>
    </div>
@endif

@if($errors->any())
    <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
        <x-icon name="alert-circle" :size="18" class="mt-0.5 shrink-0" />
        <span>Data belum bisa disimpan. Periksa kembali isian yang ditandai.</span>
    </div>
@endif

<form method="POST" action="{{ $activity->exists ? route('admin.activities.update', $activity) : route('admin.activities.store') }}"
      enctype="multipart/form-data" class="max-w-3xl">
    @csrf
    @if($activity->exists) @method('PUT') @endif

    <div class="admin-card">
        <div class="border-b border-sand/70 px-5 py-4">
            <h2 class="font-heading text-base font-bold text-forest">Informasi utama</h2>
        </div>
        <div class="space-y-5 p-5">
            <div>
                <label for="name" class="field-label">Nama aktivitas <span class="text-earth">*</span></label>
                <input id="name" type="text" name="name" value="{{ old('name', $activity->name) }}" required
                       placeholder="Contoh: Tubing Sungai Cikole" @class(['field', 'border-red-400' => $errors->has('name')])>
                @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="category_id" class="field-label">Kategori <span class="text-earth">*</span></label>
                    <select id="category_id" name="category_id" required class="field">
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $activity->category_id) == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="destination_id" class="field-label">Destinasi <span class="text-earth">*</span></label>
                    <select id="destination_id" name="destination_id" required class="field">
                        <option value="">Pilih destinasi</option>
                        @foreach($destinations as $dest)
                            <option value="{{ $dest->id }}" @selected(old('destination_id', $activity->destination_id) == $dest->id)>{{ $dest->name }}</option>
                        @endforeach
                    </select>
                    @error('destination_id') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="short_description" class="field-label">Deskripsi singkat <span class="text-charcoal/70">*</span></label>
                <input id="short_description" type="text" name="short_description" value="{{ old('short_description', $activity->short_description) }}" maxlength="255"
                       placeholder="Satu kalimat yang muncul di kartu aktivitas"
                       @class(['field', 'border-red-400' => $errors->has('short_description')])>
                @error('short_description') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="field-label">Deskripsi lengkap</label>
                <textarea id="description" name="description" rows="6" class="field"
                          placeholder="Jelaskan alur, suasana, dan hal yang perlu diketahui pengunjung">{{ old('description', $activity->description) }}</textarea>
                @error('description') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="admin-card mt-5">
        <div class="border-b border-sand/70 px-5 py-4">
            <h2 class="font-heading text-base font-bold text-forest">Harga dan operasional</h2>
        </div>
        <div class="grid gap-5 p-5 sm:grid-cols-3">
            <div>
                <label for="price" class="field-label">Harga per orang <span class="text-earth">*</span></label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-medium text-charcoal/70">Rp</span>
                    <input id="price" type="number" name="price" value="{{ old('price', $activity->exists ? (int) $activity->price : '') }}" required min="0"
                           class="field pl-9" placeholder="0">
                </div>
                @error('price') <p class="field-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="duration_minutes" class="field-label">Durasi (menit) <span class="text-earth">*</span></label>
                <input id="duration_minutes" type="number" name="duration_minutes" value="{{ old('duration_minutes', $activity->duration_minutes ?? 60) }}" required min="1" class="field">
                @error('duration_minutes') <p class="field-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="capacity" class="field-label">Kapasitas peserta <span class="text-earth">*</span></label>
                <input id="capacity" type="number" name="capacity" value="{{ old('capacity', $activity->capacity ?? 10) }}" required min="1" class="field">
                @error('capacity') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="admin-card mt-5">
        <div class="border-b border-sand/70 px-5 py-4">
            <h2 class="font-heading text-base font-bold text-forest">Foto sampul</h2>
        </div>
        <div class="p-5">
            @if($activity->cover_image)
                <div class="mb-3 flex flex-wrap items-center gap-4 rounded-xl bg-sand-light/70 p-3">
                    <img src="{{ $activity->imageUrl() }}" alt="" class="h-20 w-28 rounded-lg object-cover" width="224" height="160">
                    <label class="flex cursor-pointer items-center gap-2 text-sm text-red-700">
                        <input type="checkbox" name="remove_cover_image" value="1" class="h-4 w-4 rounded border-sand text-red-600 focus:ring-red-300">
                        Hapus foto ini
                    </label>
                </div>
            @endif
            <input id="cover_image" type="file" name="cover_image" accept="image/jpeg,image/png,image/webp"
                   class="block w-full rounded-xl border border-sand bg-white text-sm text-charcoal/70 file:mr-4 file:border-0 file:bg-sand-light file:px-4 file:py-3 file:text-xs file:font-semibold file:text-forest hover:file:bg-sand">
            <p class="mt-1.5 text-xs text-charcoal/70">JPG, PNG, atau WEBP. Maksimal 2 MB.{{ $activity->cover_image ? ' Unggah berkas baru untuk mengganti.' : '' }}</p>
            @error('cover_image') <p class="field-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="admin-card mt-5">
        <div class="border-b border-sand/70 px-5 py-4">
            <h2 class="font-heading text-base font-bold text-forest">Publikasi</h2>
        </div>
        <div class="grid gap-3 p-5 sm:grid-cols-2">
            <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-forest/10 bg-sand-light/30 p-4">
                <input type="checkbox" name="is_active" value="1" @checked($isActive)
                       class="mt-0.5 h-4 w-4 rounded border-sand text-forest focus:ring-forest/30">
                <span>
                    <span class="block text-sm font-semibold text-forest">Aktif dan bisa dipesan</span>
                    <span class="mt-0.5 block text-xs text-charcoal/70">Nonaktifkan tanpa menghapus riwayat booking.</span>
                </span>
            </label>

            <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-forest/10 bg-sand-light/30 p-4">
                <input type="checkbox" name="is_featured" value="1" @checked($isFeatured)
                       class="mt-0.5 h-4 w-4 rounded border-sand text-forest focus:ring-forest/30">
                <span>
                    <span class="block text-sm font-semibold text-forest">Tandai unggulan</span>
                    <span class="mt-0.5 block text-xs text-charcoal/70">Aktivitas unggulan diprioritaskan di beranda.</span>
                </span>
            </label>
        </div>
    </div>

    <div class="mt-5 flex flex-wrap items-center gap-3">
        <button type="submit" class="btn btn-primary"
                @disabled($categories->isEmpty() || $destinations->isEmpty())>
            {{ $activity->exists ? 'Simpan perubahan' : 'Tambah aktivitas' }}
        </button>
        <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
@endsection
