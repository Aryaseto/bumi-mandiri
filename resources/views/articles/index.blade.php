@extends('layouts.app')

@section('title', 'Cerita & Panduan | Bumi Mandiri')
@section('meta_description', 'Panduan aktivitas, tips kunjungan, dan cerita dari Bumi Mandiri Sukabumi.')

@section('content')
<section class="border-b border-forest/10 bg-sand-light/60 pb-12 pt-12 lg:pt-16">
    <div class="container-page">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-earth">Cerita &amp; panduan</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-forest sm:text-4xl">Baca dulu, lalu berangkat.</h1>
        <p class="mt-3 max-w-2xl text-[15px] leading-relaxed text-charcoal/65">
            Catatan lapangan, panduan aktivitas, dan hal kecil yang membantu kunjungan terasa lebih siap.
        </p>
    </div>
</section>

<section class="container-page py-14 lg:py-20">
    @if($articles->isEmpty())
        <div class="rounded-2xl border border-dashed border-forest/20 px-6 py-20 text-center">
            <h2 class="font-heading text-xl font-bold text-forest">Belum ada cerita yang diterbitkan.</h2>
        </div>
    @else
        @php $featured = $articles->first(); @endphp

        <a href="{{ route('articles.show', $featured) }}" class="group grid overflow-hidden rounded-2xl border border-forest/10 bg-white transition hover:border-forest/20 hover:shadow-lift lg:grid-cols-2">
            <div class="relative aspect-[16/10] overflow-hidden bg-sand lg:aspect-auto lg:h-full">
                <img src="{{ $featured->imageUrl() }}" alt="{{ $featured->title }}"
                     class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.03]" width="1000" height="700" fetchpriority="high">
            </div>
            <div class="flex flex-col justify-center p-6 lg:p-9">
                <div class="flex items-center gap-2 text-[11px] text-charcoal/70">
                    <span class="rounded-full bg-earth/10 px-2.5 py-1 font-semibold uppercase tracking-wide text-earth">Terbaru</span>
                    <span>{{ $featured->created_at->format('d M Y') }}</span>
                    <span aria-hidden="true">&middot;</span>
                    <span>{{ $featured->reading_time }} menit baca</span>
                </div>
                <h2 class="mt-4 text-2xl font-extrabold leading-tight tracking-tight text-forest transition group-hover:text-earth sm:text-3xl">
                    {{ $featured->title }}
                </h2>
                <p class="mt-3 line-clamp-3 max-w-prose text-sm leading-relaxed text-charcoal/70">{{ $featured->excerpt }}</p>
                <span class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-forest">
                    Baca cerita
                    <x-icon name="arrow-right" :size="15" />
                </span>
            </div>
        </a>

        @if($articles->count() > 1)
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($articles->skip(1) as $article)
                    <a href="{{ route('articles.show', $article) }}" class="group flex flex-col overflow-hidden rounded-2xl border border-forest/10 bg-white transition hover:-translate-y-0.5 hover:shadow-soft">
                        <div class="aspect-[16/10] overflow-hidden bg-sand">
                            <img src="{{ $article->imageUrl() }}" alt="{{ $article->title }}"
                                 class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]" loading="lazy" width="700" height="440">
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <div class="flex items-center gap-2 text-[11px] text-charcoal/70">
                                <span>{{ $article->created_at->format('d M Y') }}</span>
                                <span aria-hidden="true">&middot;</span>
                                <span>{{ $article->reading_time }} menit baca</span>
                            </div>
                            <h3 class="mt-2 font-heading text-base font-bold leading-snug text-forest transition group-hover:text-earth">{{ $article->title }}</h3>
                            <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-charcoal/70">{{ $article->excerpt }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        <div class="pagination-wrap mt-9">{{ $articles->links() }}</div>
    @endif
</section>
@endsection
