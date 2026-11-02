@extends('layouts.app')

@section('title', 'Kontak | Bumi Mandiri')
@section('meta_description', 'Hubungi Bumi Mandiri Sukabumi untuk pertanyaan, kunjungan rombongan, atau kerja sama.')

@section('content')
@php
    $address = \App\Models\Setting::get('address', 'Sukabumi, Jawa Barat');
    $email = \App\Models\Setting::get('email');
    $whatsapp = \App\Models\Setting::get('whatsapp');
    $hasWhatsapp = $whatsapp && ! str_contains((string) $whatsapp, '[');
    $mapsUrl = \App\Models\Setting::get('google_maps_url');
    $hasMaps = $mapsUrl && ! str_contains((string) $mapsUrl, '[');
    $mapLink = $hasMaps ? $mapsUrl : 'https://www.google.com/maps/search/?api=1&query=' . urlencode($address);
@endphp

<section class="border-b border-forest/10 bg-sand-light/60 pb-12 pt-12 lg:pt-16">
    <div class="container-page">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-earth">Kontak</p>
        <h1 class="mt-2 max-w-2xl text-3xl font-extrabold tracking-tight text-forest sm:text-4xl">Mari susun kunjungan Anda.</h1>
        <p class="mt-3 max-w-2xl text-[15px] leading-relaxed text-charcoal/65">
            Sampaikan tanggal, jumlah peserta, dan jenis kegiatan. Kami akan membantu menyiapkan alurnya.
        </p>
    </div>
</section>

<section class="container-page py-12 lg:py-16">
    <div class="grid gap-10 lg:grid-cols-[0.8fr_1.2fr] lg:gap-14">
        <aside>
            <h2 class="font-heading text-lg font-bold text-forest">Informasi kontak</h2>
            <p class="mt-2 text-sm leading-relaxed text-charcoal/70">
                Untuk respons yang lebih cepat, hubungi kanal yang tersedia di bawah ini.
            </p>

            <div class="mt-6 space-y-3">
                <div class="flex gap-3 rounded-xl border border-forest/10 bg-white p-4">
                    <x-icon name="map-pin" :size="18" class="mt-0.5 shrink-0 text-earth" />
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-charcoal/70">Lokasi</p>
                        <p class="mt-1 text-sm font-medium text-forest">{{ $address }}</p>
                        <a href="{{ $mapLink }}" target="_blank" rel="noopener" class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-earth hover:text-forest">
                            Buka peta
                            <x-icon name="external-link" :size="12" />
                        </a>
                    </div>
                </div>

                @if($email)
                    <div class="flex gap-3 rounded-xl border border-forest/10 bg-white p-4">
                        <x-icon name="mail" :size="18" class="mt-0.5 shrink-0 text-earth" />
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-charcoal/70">Email</p>
                            <a href="mailto:{{ $email }}" class="mt-1 block text-sm font-medium text-forest hover:text-earth">{{ $email }}</a>
                        </div>
                    </div>
                @endif

                @if($hasWhatsapp)
                    <div class="flex gap-3 rounded-xl border border-forest/10 bg-white p-4">
                        <x-icon name="brand-whatsapp" :size="18" class="mt-0.5 shrink-0 text-earth" />
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-charcoal/70">WhatsApp</p>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" target="_blank" rel="noopener"
                               class="mt-1 block text-sm font-medium text-forest hover:text-earth">{{ $whatsapp }}</a>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-6 rounded-xl bg-forest p-5 text-cream">
                <p class="text-sm font-semibold">Kunjungan rombongan</p>
                <p class="mt-1.5 text-xs leading-relaxed text-cream/70">
                    Sebutkan perkiraan jumlah peserta dan tanggal kunjungan agar kami bisa menyiapkan rekomendasi aktivitas yang sesuai.
                </p>
            </div>
        </aside>

        <div>
            @if(session('success'))
                <div class="mb-5 flex items-start gap-3 rounded-xl border border-nature/20 bg-nature/10 px-4 py-3 text-sm text-forest">
                    <x-icon name="check" :size="18" class="mt-0.5 shrink-0 text-nature" />
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}" class="rounded-2xl border border-forest/10 bg-white p-6 sm:p-8">
                @csrf

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="name" class="field-label">Nama lengkap <span class="text-earth">*</span></label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name"
                               class="field @error('name') border-red-400 @enderror" placeholder="Nama Anda">
                        @error('name') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email" class="field-label">Email <span class="text-earth">*</span></label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                               class="field @error('email') border-red-400 @enderror" placeholder="nama@email.com">
                        @error('email') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-5 grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="phone" class="field-label">Nomor telepon</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" autocomplete="tel"
                               class="field @error('phone') border-red-400 @enderror" placeholder="08xx xxxx xxxx">
                        @error('phone') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="subject" class="field-label">Topik</label>
                        <input id="subject" type="text" name="subject" value="{{ old('subject', request('subject')) }}"
                               class="field @error('subject') border-red-400 @enderror" placeholder="Kunjungan rombongan">
                        @error('subject') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-5">
                    <label for="message" class="field-label">Pesan <span class="text-earth">*</span></label>
                    <textarea id="message" name="message" rows="6" required
                              class="field @error('message') border-red-400 @enderror"
                              placeholder="Ceritakan tanggal, jumlah peserta, dan kegiatan yang Anda minati.">{{ old('message') }}</textarea>
                    @error('message') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-xs text-charcoal/70">Data hanya digunakan untuk menindaklanjuti pesan Anda.</p>
                    <button type="submit" class="btn btn-primary">
                        Kirim Pesan
                        <x-icon name="arrow-right" :size="16" />
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
