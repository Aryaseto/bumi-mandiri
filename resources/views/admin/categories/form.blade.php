@extends('layouts.admin')

@section('title', ($category->exists ? 'Edit' : 'Tambah') . ' Kategori | Admin Bumi Mandiri')
@section('page_title', 'Kategori')

@section('content')
<a href="{{ route('admin.categories.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-charcoal/70 transition hover:text-forest">
    <x-icon name="arrow-left" :size="15" />
    Kembali ke kategori
</a>

<x-admin.page-header
    eyebrow="Katalog"
    :title="$category->exists ? 'Edit kategori' : 'Tambah kategori'"
    description="Nama kategori tampil sebagai label pada kartu aktivitas." />

@if($errors->any())
    <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
        <x-icon name="alert-circle" :size="18" class="mt-0.5 shrink-0" />
        <span>Data belum bisa disimpan. Periksa kembali isian yang ditandai.</span>
    </div>
@endif

<form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="max-w-xl">
    @csrf
    @if($category->exists) @method('PUT') @endif

    <div class="admin-card space-y-5 p-5">
        <div>
            <label for="name" class="field-label">Nama kategori <span class="text-earth">*</span></label>
            <input id="name" type="text" name="name" value="{{ old('name', $category->name) }}" required
                   placeholder="Contoh: Petualangan" @class(['field', 'border-red-400' => $errors->has('name')])>
            @error('name') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="icon" class="field-label">Ikon <span class="font-normal text-charcoal/70">(opsional)</span></label>
            <input id="icon" type="text" name="icon" value="{{ old('icon', $category->icon) }}"
                   placeholder="Contoh: mountain, leaf, users" class="field">
            <p class="mt-1.5 text-xs text-charcoal/70">Penanda internal untuk kebutuhan tampilan.</p>
        </div>

        <div>
            <label for="description" class="field-label">Deskripsi <span class="font-normal text-charcoal/70">(opsional)</span></label>
            <textarea id="description" name="description" rows="3" class="field"
                      placeholder="Ringkasan singkat jenis aktivitas dalam kategori ini">{{ old('description', $category->description) }}</textarea>
            @error('description') <p class="field-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="mt-5 flex flex-wrap items-center gap-3">
        <button type="submit" class="btn btn-primary">{{ $category->exists ? 'Simpan perubahan' : 'Tambah kategori' }}</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
@endsection
