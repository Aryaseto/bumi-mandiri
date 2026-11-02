@extends('layouts.admin')

@section('title', 'Pengaturan Situs | Admin Bumi Mandiri')
@section('page_title', 'Pengaturan')

@php
    $groups = [
        'Identitas situs' => [
            'site_name' => ['label' => 'Nama situs', 'type' => 'text', 'placeholder' => 'Bumi Mandiri'],
            'tagline' => ['label' => 'Tagline', 'type' => 'text', 'placeholder' => 'Wisata alam dan aktivitas outdoor di Sukabumi'],
            'email' => ['label' => 'Email', 'type' => 'email', 'placeholder' => 'nama@email.com'],
        ],
        'Kontak' => [
            'phone' => ['label' => 'Nomor telepon', 'type' => 'tel', 'placeholder' => '08xx xxxx xxxx'],
            'whatsapp' => ['label' => 'Nomor WhatsApp', 'type' => 'tel', 'placeholder' => '08xx xxxx xxxx'],
            'instagram' => ['label' => 'Instagram', 'type' => 'text', 'placeholder' => '@namakun'],
        ],
        'Lokasi' => [
            'address' => ['label' => 'Alamat', 'type' => 'text', 'placeholder' => 'Alamat lengkap lokasi'],
            'google_maps_url' => ['label' => 'Tautan Google Maps', 'type' => 'url', 'placeholder' => 'https://maps.google.com/...'],
        ],
    ];
@endphp

@section('content')
<x-admin.page-header
    eyebrow="Sistem"
    title="Pengaturan situs"
    description="Data ini dipakai pada halaman kontak, footer, dan tautan komunikasi di situs publik.">
    <a href="{{ route('contact.create') }}" target="_blank" rel="noopener" class="btn btn-secondary btn-sm">
        <x-icon name="external-link" :size="15" />
        Lihat halaman kontak
    </a>
</x-admin.page-header>

@if($errors->any())
    <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
        <x-icon name="alert-circle" :size="18" class="mt-0.5 shrink-0" />
        <span>Pengaturan belum bisa disimpan. Periksa kembali isian yang ditandai.</span>
    </div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}" class="max-w-2xl">
    @csrf
    @method('PUT')

    @foreach($groups as $group => $fields)
        <div class="admin-card {{ $loop->first ? '' : 'mt-5' }}">
            <div class="border-b border-sand/70 px-5 py-4">
                <h2 class="font-heading text-base font-bold text-forest">{{ $group }}</h2>
            </div>
            <div class="space-y-5 p-5">
                @foreach($fields as $key => $field)
                    @php
                        $raw = old($key, $settings[$key] ?? '');
                        $isPlaceholder = is_string($raw) && str_starts_with($raw, '[');
                        $value = $isPlaceholder ? '' : $raw;
                    @endphp
                    <div>
                        <label for="setting-{{ $key }}" class="field-label">{{ $field['label'] }}</label>
                        <input id="setting-{{ $key }}" type="{{ $field['type'] }}" name="{{ $key }}" value="{{ $value }}"
                               placeholder="{{ $field['placeholder'] }}"
                               @class(['field', 'border-red-400' => $errors->has($key)])>
                        @if($isPlaceholder)
                            <p class="mt-1.5 text-xs text-charcoal/70">Belum diisi. Nilai contoh tidak ditampilkan di situs.</p>
                        @endif
                        @error($key) <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <div class="mt-5 rounded-xl border border-earth/20 bg-earth/[0.06] px-4 py-3 text-xs leading-relaxed text-charcoal/70">
        Kolom kontak yang kosong otomatis disembunyikan di situs publik agar pengunjung tidak melihat data contoh.
    </div>

    <div class="mt-5">
        <button type="submit" class="btn btn-primary">Simpan pengaturan</button>
    </div>
</form>
@endsection
