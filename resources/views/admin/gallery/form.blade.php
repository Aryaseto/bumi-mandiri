@extends('layouts.admin')

@section('title', ($item->exists ? 'Edit' : 'Unggah') . ' Foto | Admin Bumi Mandiri')
@section('page_title', 'Galeri')

@section('content')
<a href="{{ route('admin.gallery.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-charcoal/70 transition hover:text-forest">
    <x-icon name="arrow-left" :size="15" />
    Kembali ke galeri
</a>

<x-admin.page-header
    eyebrow="Konten"
    :title="$item->exists ? 'Edit foto' : 'Unggah foto'"
    description="Gunakan foto lanskap dengan pencahayaan alami. Ukuran maksimal 2 MB." />

@if($errors->any())
    <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
        <x-icon name="alert-circle" :size="18" class="mt-0.5 shrink-0" />
        <span>Data belum bisa disimpan. Periksa kembali isian yang ditandai.</span>
    </div>
@endif

<form method="POST" action="{{ $item->exists ? route('admin.gallery.update', $item) : route('admin.gallery.store') }}"
      enctype="multipart/form-data" class="max-w-xl">
    @csrf
    @if($item->exists) @method('PUT') @endif

    <div class="admin-card space-y-5 p-5">
        <div>
            <label for="title" class="field-label">Judul foto <span class="text-earth">*</span></label>
            <input id="title" type="text" name="title" value="{{ old('title', $item->title) }}" required
                   placeholder="Contoh: Jeram sungai saat tubing" @class(['field', 'border-red-400' => $errors->has('title')])>
            @error('title') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="category_id" class="field-label">Kategori</label>
            <select id="category_id" name="category_id" class="field">
                <option value="">Tanpa kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(old('category_id', $item->category_id) == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="image" class="field-label">Berkas gambar {{ $item->exists ? '' : '*' }}</label>
            @if($item->exists)
                <div class="mb-3 flex items-center gap-3 rounded-xl bg-sand-light/70 p-3">
                    <img src="{{ $item->imageUrl() }}" alt="" class="h-16 w-24 rounded-lg object-cover" width="192" height="128">
                    <p class="text-xs leading-relaxed text-charcoal/70">Foto saat ini. Unggah berkas baru untuk menggantinya.</p>
                </div>
            @endif
            <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp" {{ $item->exists ? '' : 'required' }}
                   class="block w-full rounded-xl border border-sand bg-white text-sm text-charcoal/70 file:mr-4 file:border-0 file:bg-sand-light file:px-4 file:py-3 file:text-xs file:font-semibold file:text-forest hover:file:bg-sand">
            <p class="mt-1.5 text-xs text-charcoal/70">JPG, PNG, atau WEBP. Maksimal 2 MB.</p>
            @error('image') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-forest/10 bg-sand-light/40 p-4">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $item->is_featured))
                   class="mt-0.5 h-4 w-4 rounded border-sand text-forest focus:ring-forest/30">
            <span>
                <span class="block text-sm font-semibold text-forest">Tampilkan di beranda</span>
                <span class="mt-0.5 block text-xs text-charcoal/70">Foto unggulan muncul pada bagian galeri di halaman utama.</span>
            </span>
        </label>
    </div>

    <div class="mt-5 flex flex-wrap items-center gap-3">
        <button type="submit" class="btn btn-primary">{{ $item->exists ? 'Simpan perubahan' : 'Unggah foto' }}</button>
        <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
@endsection
