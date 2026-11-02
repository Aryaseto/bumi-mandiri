@extends('layouts.admin')

@section('title', ($article->exists ? 'Edit' : 'Tulis') . ' Artikel | Admin Bumi Mandiri')
@section('page_title', 'Artikel')

@section('content')
<a href="{{ route('admin.articles.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-charcoal/70 transition hover:text-forest">
    <x-icon name="arrow-left" :size="15" />
    Kembali ke artikel
</a>

<x-admin.page-header
    eyebrow="Konten"
    :title="$article->exists ? 'Edit artikel' : 'Tulis artikel'"
    description="Tulis dengan bahasa yang jelas dan hindari klaim yang belum terverifikasi." />

@if($errors->any())
    <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
        <x-icon name="alert-circle" :size="18" class="mt-0.5 shrink-0" />
        <span>Data belum bisa disimpan. Periksa kembali isian yang ditandai.</span>
    </div>
@endif

<form method="POST" action="{{ $article->exists ? route('admin.articles.update', $article) : route('admin.articles.store') }}"
      enctype="multipart/form-data" class="max-w-3xl">
    @csrf
    @if($article->exists) @method('PUT') @endif

    <div class="admin-card">
        <div class="border-b border-sand/70 px-5 py-4">
            <h2 class="font-heading text-base font-bold text-forest">Isi artikel</h2>
        </div>
        <div class="space-y-5 p-5">
            <div>
                <label for="title" class="field-label">Judul <span class="text-earth">*</span></label>
                <input id="title" type="text" name="title" value="{{ old('title', $article->title) }}" required
                       placeholder="Judul yang spesifik dan mudah dipahami"
                       @class(['field', 'border-red-400' => $errors->has('title')])>
                @error('title') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="excerpt" class="field-label">Ringkasan</label>
                <textarea id="excerpt" name="excerpt" rows="2" class="field"
                          placeholder="Satu atau dua kalimat yang muncul di daftar artikel">{{ old('excerpt', $article->excerpt) }}</textarea>
                @error('excerpt') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="content" class="field-label">Isi artikel <span class="text-earth">*</span></label>
                <textarea id="content" name="content" rows="14" required class="field"
                          placeholder="Tulis isi artikel di sini">{{ old('content', $article->content) }}</textarea>
                <p class="mt-1.5 text-xs text-charcoal/70">Gunakan baris kosong untuk memisahkan paragraf.</p>
                @error('content') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="admin-card mt-5">
        <div class="border-b border-sand/70 px-5 py-4">
            <h2 class="font-heading text-base font-bold text-forest">Publikasi</h2>
        </div>
        <div class="grid gap-5 p-5 sm:grid-cols-2">
            <div>
                <label for="reading_time" class="field-label">Estimasi waktu baca (menit) <span class="text-earth">*</span></label>
                <input id="reading_time" type="number" name="reading_time" value="{{ old('reading_time', $article->reading_time ?? 3) }}" min="1" required class="field">
                @error('reading_time') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="cover_image" class="field-label">Foto sampul</label>
                <input id="cover_image" type="file" name="cover_image" accept="image/jpeg,image/png,image/webp"
                       class="block w-full rounded-xl border border-sand bg-white text-sm text-charcoal/70 file:mr-4 file:border-0 file:bg-sand-light file:px-4 file:py-3 file:text-xs file:font-semibold file:text-forest hover:file:bg-sand">
                <p class="mt-1.5 text-xs text-charcoal/70">JPG, PNG, atau WEBP. Maksimal 2 MB.</p>
                @error('cover_image') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="border-t border-sand/70 p-5">
            <label class="flex cursor-pointer items-start gap-3">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $article->exists ? $article->is_published : true))
                       class="mt-0.5 h-4 w-4 rounded border-sand text-forest focus:ring-forest/30">
                <span>
                    <span class="block text-sm font-semibold text-forest">Terbitkan artikel</span>
                    <span class="mt-0.5 block text-xs text-charcoal/70">Jika tidak dicentang, artikel disimpan sebagai draf.</span>
                </span>
            </label>
        </div>
    </div>

    <div class="mt-5 flex flex-wrap items-center gap-3">
        <button type="submit" class="btn btn-primary">{{ $article->exists ? 'Simpan perubahan' : 'Simpan artikel' }}</button>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
@endsection
