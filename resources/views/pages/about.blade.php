@extends('layouts.app')

@section('title', 'Tentang Bumi Mandiri | Wisata Alam Sukabumi')
@section('meta_description', 'Bumi Mandiri adalah ruang terbuka di Sukabumi untuk bermain, belajar, dan membangun kebersamaan di alam.')

@section('content')
<section class="container-page grid items-center gap-10 py-14 lg:grid-cols-2 lg:gap-16 lg:py-20">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-earth">Tentang kami</p>
        <h1 class="mt-3 text-3xl font-extrabold leading-tight tracking-tight text-forest sm:text-4xl lg:text-5xl">
            Ruang terbuka untuk bermain, belajar, dan bertemu.
        </h1>
        <p class="mt-5 max-w-prose text-[15px] leading-relaxed text-charcoal/70">
            Bumi Mandiri Sukabumi hadir sebagai ruang untuk menikmati alam, membangun teamwork,
            dan menciptakan pengalaman yang berkesan bagi setiap rombongan.
        </p>
        <p class="mt-4 max-w-prose text-[15px] leading-relaxed text-charcoal/70">
            Kami menerima kunjungan keluarga, sekolah, komunitas, dan perusahaan dengan alur kegiatan
            yang disesuaikan dengan kebutuhan masing-masing.
        </p>

        <div class="mt-7 flex flex-wrap gap-2">
            <span class="rounded-full border border-forest/10 bg-white px-3.5 py-1.5 text-xs font-medium text-forest">Keluarga</span>
            <span class="rounded-full border border-forest/10 bg-white px-3.5 py-1.5 text-xs font-medium text-forest">Sekolah</span>
            <span class="rounded-full border border-forest/10 bg-white px-3.5 py-1.5 text-xs font-medium text-forest">Komunitas</span>
            <span class="rounded-full border border-forest/10 bg-white px-3.5 py-1.5 text-xs font-medium text-forest">Perusahaan</span>
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('activities.index') }}" class="btn btn-primary">
                Lihat Aktivitas
                <x-icon name="arrow-right" :size="16" />
            </a>
            <a href="{{ route('contact.create') }}" class="btn btn-secondary">Hubungi Kami</a>
        </div>
    </div>

    <div class="relative">
        <div class="overflow-hidden rounded-2xl">
            <img src="{{ asset('images/story.jpg') }}" alt="Rombongan menyusuri jalur alam di Bumi Mandiri"
                 class="aspect-[4/5] w-full object-cover" width="1000" height="1250" fetchpriority="high">
        </div>
        <div class="absolute -bottom-5 left-5 right-5 rounded-xl border border-forest/10 bg-cream/95 p-4 shadow-lift backdrop-blur sm:left-8 sm:right-auto sm:w-64">
            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-earth">Basecamp</p>
            <p class="mt-1 text-sm font-semibold text-forest">{{ \App\Models\Setting::get('address', 'Sukabumi, Jawa Barat') }}</p>
        </div>
    </div>
</section>

<section class="border-y border-forest/10 bg-sand-light py-16 lg:py-20">
    <div class="container-page">
        <h2 class="max-w-2xl text-2xl font-extrabold leading-tight tracking-tight text-forest sm:text-3xl">
            Yang kami jaga di setiap kunjungan.
        </h2>

        <div class="mt-8 grid gap-6 md:grid-cols-3">
            <div class="border-t border-forest/15 pt-5">
                <x-icon name="leaf" :size="22" class="text-earth" />
                <h3 class="mt-3 font-heading text-base font-bold text-forest">Alam sebagai ruang utama</h3>
                <p class="mt-2 text-sm leading-relaxed text-charcoal/70">
                    Aktivitas dirancang agar peserta tetap terhubung dengan hutan, sungai, dan area terbuka.
                </p>
            </div>
            <div class="border-t border-forest/15 pt-5">
                <x-icon name="users-group" :size="22" class="text-earth" />
                <h3 class="mt-3 font-heading text-base font-bold text-forest">Kebersamaan yang terarah</h3>
                <p class="mt-2 text-sm leading-relaxed text-charcoal/70">
                    Setiap kegiatan punya alur dan pendamping, terutama untuk rombongan sekolah dan perusahaan.
                </p>
            </div>
            <div class="border-t border-forest/15 pt-5">
                <x-icon name="compass" :size="22" class="text-earth" />
                <h3 class="mt-3 font-heading text-base font-bold text-forest">Pengalaman yang fleksibel</h3>
                <p class="mt-2 text-sm leading-relaxed text-charcoal/70">
                    Susunan aktivitas dapat dibicarakan terlebih dahulu sesuai usia, jumlah, dan tujuan kunjungan.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="container-page py-16 lg:py-20">
    <div class="grid gap-10 lg:grid-cols-12 lg:gap-16">
        <div class="lg:col-span-5">
            <h2 class="text-2xl font-extrabold leading-tight tracking-tight text-forest sm:text-3xl">
                Cara memulai kunjungan.
            </h2>
            <p class="mt-4 max-w-prose text-[15px] leading-relaxed text-charcoal/65">
                Tidak perlu menyusun semuanya sendiri. Ceritakan kebutuhan Anda, tim kami akan membantu menyiapkan alurnya.
            </p>
            <a href="{{ route('contact.create') }}" class="btn btn-primary mt-6">Mulai Diskusi</a>
        </div>

        <div class="divide-y divide-forest/10 border-y border-forest/10 lg:col-span-7">
            @foreach([
                ['title' => 'Ceritakan rencana Anda', 'text' => 'Kirim pesan berisi tanggal, jumlah peserta, dan jenis kegiatan yang diminati.'],
                ['title' => 'Susun aktivitas bersama', 'text' => 'Kami bantu memilih kombinasi aktivitas dan durasi yang sesuai.'],
                ['title' => 'Datang dan nikmati', 'text' => 'Tim di lokasi akan mengarahkan rombongan sesuai alur yang sudah disepakati.'],
            ] as $index => $step)
                <div class="grid gap-2 py-5 sm:grid-cols-[52px_1fr] sm:gap-4">
                    <span class="font-heading text-sm font-extrabold text-earth">0{{ $index + 1 }}</span>
                    <div>
                        <h3 class="font-heading text-base font-bold text-forest">{{ $step['title'] }}</h3>
                        <p class="mt-1 text-sm leading-relaxed text-charcoal/70">{{ $step['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="relative overflow-hidden bg-forest-dark">
    <img src="{{ asset('images/forest.jpg') }}" alt=""
         class="absolute inset-0 h-full w-full object-cover opacity-30" loading="lazy" width="1400" height="900">
    <div class="container-page relative z-10 py-16 text-center lg:py-20">
        <h2 class="mx-auto max-w-2xl text-2xl font-extrabold leading-tight tracking-tight text-cream sm:text-3xl">
            Kenali Bumi Mandiri langsung dari lokasinya.
        </h2>
        <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-cream/70">
            Kami akan membantu Anda menyusun kunjungan yang sesuai dengan kebutuhan rombongan.
        </p>
        <a href="{{ route('contact.create') }}" class="btn btn-light mt-6">
            Hubungi Bumi Mandiri
            <x-icon name="arrow-right" :size="16" />
        </a>
    </div>
</section>
@endsection
