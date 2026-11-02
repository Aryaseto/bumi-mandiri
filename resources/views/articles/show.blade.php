@extends('layouts.app')

@section('title', $article->title . ' | Bumi Mandiri')
@section('meta_description', $article->excerpt)

@section('content')
@php
    $isPlaceholder = str_contains((string) $article->content, '[DATA PERLU DIKONFIRMASI]');
@endphp

<article>
    <div class="relative flex min-h-[380px] items-end overflow-hidden">
        <img src="{{ $article->imageUrl() }}" alt="{{ $article->title }}"
             class="absolute inset-0 h-full w-full object-cover" width="1400" height="900" fetchpriority="high">
        <div class="absolute inset-0 bg-gradient-to-t from-forest-dark/95 via-forest-dark/45 to-forest-dark/15"></div>
        <div class="container-page relative z-10 py-10 text-cream">
            <nav class="flex flex-wrap items-center gap-1.5 text-xs text-cream/70" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="transition hover:text-cream">Beranda</a>
                <x-icon name="chevron-right" :size="13" />
                <a href="{{ route('articles.index') }}" class="transition hover:text-cream">Cerita</a>
                <x-icon name="chevron-right" :size="13" />
                <span class="line-clamp-1 text-cream/80">{{ $article->title }}</span>
            </nav>
            <h1 class="mt-4 max-w-3xl text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl">{{ $article->title }}</h1>
            <div class="mt-4 flex flex-wrap items-center gap-2 text-xs text-cream/70">
                <span>{{ $article->author?->name }}</span>
                <span aria-hidden="true">&middot;</span>
                <span>{{ $article->created_at->format('d F Y') }}</span>
                <span aria-hidden="true">&middot;</span>
                <span>{{ $article->reading_time }} menit baca</span>
            </div>
        </div>
    </div>

    <div class="container-page py-12 lg:py-16">
        <div class="mx-auto max-w-3xl">
            @if($isPlaceholder)
                <div class="surface-note mb-8">
                    <p class="font-semibold text-forest">Konten artikel sedang dilengkapi.</p>
                    <p class="mt-1">Tim Bumi Mandiri sedang menyiapkan isi lengkap untuk cerita ini. Sementara itu, Anda bisa melihat aktivitas dan paket yang tersedia.</p>
                </div>
            @endif

            <div class="prose-bumi">
                @if($isPlaceholder)
                    <p>{{ $article->excerpt }}</p>
                @else
                    {!! nl2br(e($article->content)) !!}
                @endif
            </div>

            <div class="mt-10 flex flex-wrap items-center gap-3 border-t border-forest/10 pt-8">
                <a href="{{ route('activities.index') }}" class="btn btn-primary">
                    Lihat Aktivitas
                    <x-icon name="arrow-right" :size="16" />
                </a>
                <a href="{{ route('articles.index') }}" class="btn btn-secondary">Kembali ke Cerita</a>
            </div>
        </div>

        @if($related->isNotEmpty())
            <div class="mx-auto mt-16 max-w-5xl border-t border-forest/10 pt-10">
                <h2 class="text-2xl font-extrabold tracking-tight text-forest">Cerita lainnya</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($related as $item)
                        <a href="{{ route('articles.show', $item) }}" class="group rounded-2xl border border-forest/10 bg-white p-5 transition hover:border-forest/20 hover:shadow-soft">
                            <p class="text-[11px] text-charcoal/70">{{ $item->reading_time }} menit baca</p>
                            <h3 class="mt-2 font-heading text-sm font-bold leading-snug text-forest transition group-hover:text-earth">{{ $item->title }}</h3>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</article>
@endsection
