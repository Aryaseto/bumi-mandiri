@php
    $pendingReviewCount = \App\Models\Review::where('is_approved', false)->count();

    $sections = [
        'Utama' => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'layout-dashboard', 'match' => 'admin.dashboard'],
        ],
        'Katalog' => [
            ['label' => 'Destinasi', 'route' => 'admin.destinations.index', 'icon' => 'map-pin', 'match' => 'admin.destinations.*'],
            ['label' => 'Aktivitas', 'route' => 'admin.activities.index', 'icon' => 'compass', 'match' => 'admin.activities.*'],
            ['label' => 'Paket', 'route' => 'admin.packages.index', 'icon' => 'package', 'match' => 'admin.packages.*'],
            ['label' => 'Kategori', 'route' => 'admin.categories.index', 'icon' => 'tag', 'match' => 'admin.categories.*'],
        ],
        'Transaksi' => [
            ['label' => 'Booking', 'route' => 'admin.bookings.index', 'icon' => 'ticket', 'match' => 'admin.bookings.*'],
            ['label' => 'Review', 'route' => 'admin.reviews.index', 'icon' => 'star', 'match' => 'admin.reviews.*', 'badge' => $pendingReviewCount],
        ],
        'Konten' => [
            ['label' => 'Artikel', 'route' => 'admin.articles.index', 'icon' => 'article', 'match' => 'admin.articles.*'],
            ['label' => 'Galeri', 'route' => 'admin.gallery.index', 'icon' => 'photo', 'match' => 'admin.gallery.*'],
        ],
        'Sistem' => [
            ['label' => 'Pengguna', 'route' => 'admin.users.index', 'icon' => 'users', 'match' => 'admin.users.*'],
            ['label' => 'Pengaturan', 'route' => 'admin.settings.edit', 'icon' => 'settings', 'match' => 'admin.settings.*'],
        ],
    ];
@endphp

<div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
     class="fixed inset-0 z-40 bg-forest-dark/50 backdrop-blur-sm lg:hidden"
     aria-hidden="true"></div>

<aside x-cloak
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-forest text-cream transition-transform duration-300 lg:translate-x-0"
       aria-label="Navigasi admin">

    <div class="flex items-center justify-between gap-3 border-b border-cream/10 px-5 py-5">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Bumi Mandiri"
                 class="h-10 w-10 shrink-0 rounded-xl object-cover ring-1 ring-cream/20 shadow-sm"
                 width="40" height="40">
            <span>
                <span class="block font-heading text-sm font-extrabold uppercase tracking-[0.14em]">Bumi Mandiri</span>
                <span class="block text-[11px] font-medium text-cream/70">Panel pengelola</span>
            </span>
        </a>
        <button type="button" @click="sidebarOpen = false"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-cream/70 transition hover:bg-cream/10 hover:text-cream lg:hidden"
                aria-label="Tutup navigasi">
            <x-icon name="x" :size="18" />
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-5">
        @foreach($sections as $section => $items)
            <div class="{{ $loop->first ? '' : 'mt-6' }}">
                <p class="px-3 pb-2 text-[10px] font-semibold uppercase tracking-[0.18em] text-cream/70">{{ $section }}</p>
                <div class="space-y-0.5">
                    @foreach($items as $item)
                        @php $active = request()->routeIs($item['match']); @endphp
                        <a href="{{ route($item['route']) }}"
                           @if($active) aria-current="page" @endif
                           class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-[13px] font-medium transition {{ $active ? 'border-l-2 border-earth-light bg-cream/15 text-cream ring-1 ring-cream/20' : 'text-cream/70 hover:bg-cream/[0.07] hover:text-cream' }}">
                            <x-icon :name="$item['icon']" :size="17" class="{{ $active ? 'text-cream' : 'text-cream/70 group-hover:text-cream/80' }}" />
                            <span class="flex-1">{{ $item['label'] }}</span>
                            @if(! empty($item['badge']))
                                <span class="rounded-full bg-earth px-1.5 py-0.5 text-[10px] font-bold leading-none text-white">
                                    {{ $item['badge'] > 99 ? '99+' : $item['badge'] }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    <div class="border-t border-cream/10 px-3 pt-3 pb-1">
        <div class="flex items-center gap-3 rounded-lg px-3 py-2.5">
            <x-admin.avatar :name="auth()->user()->name" size="sm" />
            <div class="min-w-0 flex-1">
                <p class="truncate text-[13px] font-semibold text-cream">{{ auth()->user()->name }}</p>
                <p class="truncate text-[11px] capitalize text-cream/60">{{ auth()->user()->role?->name ?? 'admin' }}</p>
            </div>
        </div>
    </div>

    <div class="border-t border-cream/10 p-3">
        <a href="{{ route('home') }}" target="_blank" rel="noopener"
           class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-[13px] font-medium text-cream/70 transition hover:bg-cream/[0.07] hover:text-cream">
            <x-icon name="external-link" :size="17" class="text-cream/70" />
            Lihat situs publik
        </a>
    </div>
</aside>
