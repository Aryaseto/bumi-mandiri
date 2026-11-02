@extends('layouts.app')

@section('title', 'Aktivitas Favorit | Bumi Mandiri')

@section('content')
<section class="pb-20 pt-28 lg:pt-32">
    <div class="container-page">
        <div class="flex flex-wrap items-end justify-between gap-5">
            <div>
                <p class="eyebrow text-earth">Akun saya</p>
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-forest sm:text-4xl">Aktivitas favorit.</h1>
                <p class="mt-3 max-w-xl text-[15px] leading-relaxed text-charcoal/70">
                    Simpanan aktivitas untuk rencana kunjungan berikutnya.
                </p>
            </div>
            <p class="text-sm text-charcoal/70">{{ $favorites->total() }} aktivitas disimpan</p>
        </div>

        <div class="mt-7">
            <x-account-nav />
        </div>

        @if($favorites->isEmpty())
            <x-empty-state
                icon="heart"
                title="Belum ada aktivitas favorit"
                description="Tekan ikon hati pada kartu aktivitas untuk menyimpannya di halaman ini."
                class="rounded-2xl border border-dashed border-forest/20 bg-white/60">
                <a href="{{ route('activities.index') }}" class="btn btn-primary btn-sm">Cari aktivitas</a>
            </x-empty-state>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($favorites as $favorite)
                    @if($favorite->activity)
                        <x-activity-card :activity="$favorite->activity" />
                    @endif
                @endforeach
            </div>
            <div class="pagination-wrap mt-9">{{ $favorites->links() }}</div>
        @endif
    </div>
</section>
@endsection
