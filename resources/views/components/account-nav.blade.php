@php
    $items = [
        ['route' => 'dashboard', 'label' => 'Ringkasan', 'icon' => 'layout-dashboard'],
        ['route' => 'bookings.index', 'label' => 'Booking saya', 'icon' => 'ticket'],
        ['route' => 'favorites.index', 'label' => 'Favorit', 'icon' => 'heart'],
        ['route' => 'profile.edit', 'label' => 'Profil', 'icon' => 'user'],
    ];
@endphp

<nav aria-label="Navigasi akun" class="mb-8 grid grid-cols-4 border-b border-forest/10 sm:flex sm:gap-1">
    @foreach($items as $item)
        @php $active = request()->routeIs($item['route']); @endphp
        <a href="{{ route($item['route']) }}"
           @if($active) aria-current="page" @endif
           class="relative flex flex-col items-center justify-center gap-1 rounded-t-lg px-1 py-2.5 text-[11px] font-semibold transition sm:inline-flex sm:shrink-0 sm:flex-row sm:gap-2 sm:px-4 sm:py-3 sm:text-sm {{ $active ? 'text-forest' : 'text-charcoal/70 hover:bg-forest/[0.03] hover:text-forest' }}">
            <x-icon :name="$item['icon']" :size="16" />
            {{ $item['label'] }}
            @if($active)
                <span class="absolute inset-x-3 -bottom-px h-0.5 rounded-full bg-earth" aria-hidden="true"></span>
            @endif
        </a>
    @endforeach
</nav>
