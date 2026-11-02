@php
    $title = trim($__env->yieldContent('page_title')) ?: 'Dashboard';
    $user = auth()->user();
@endphp

<header class="sticky top-0 z-30 border-b border-forest/10 bg-[#F4EFE5]/90 backdrop-blur">
    <div class="mx-auto flex h-16 w-full max-w-[1440px] items-center gap-3 px-4 sm:px-6 lg:px-8">

        <button type="button" @click="sidebarOpen = true"
                class="flex h-10 w-10 items-center justify-center rounded-lg border border-forest/15 bg-white text-forest transition hover:border-forest/25 lg:hidden"
                aria-label="Buka navigasi">
            <x-icon name="menu" :size="19" />
        </button>

        <div class="min-w-0">
            <div class="hidden items-center gap-1.5 sm:flex">
                <p class="text-[11px] font-medium text-charcoal/70">Panel pengelola</p>
                <x-icon name="chevron-right" :size="12" class="shrink-0 text-charcoal/40" />
                <p class="truncate text-[11px] font-medium text-charcoal/70">{{ $title }}</p>
            </div>
            <p class="truncate font-heading text-sm font-bold text-forest">{{ $title }}</p>
        </div>

        <div class="ml-auto flex items-center gap-2 sm:gap-3">
            <a href="{{ route('home') }}" target="_blank" rel="noopener"
               class="hidden items-center gap-1.5 rounded-lg px-3 py-2 text-xs font-semibold text-charcoal/70 transition hover:bg-white hover:text-forest sm:inline-flex">
                <x-icon name="external-link" :size="14" />
                Situs
            </a>

            <span class="hidden h-6 w-px bg-forest/10 sm:block" aria-hidden="true"></span>

            <div x-data="{ open: false }" class="relative">
                <button type="button" @click="open = !open" @keydown.escape.window="open = false"
                        class="flex items-center gap-2 rounded-full py-1 pl-1 pr-2 transition hover:bg-white"
                        :aria-expanded="open" aria-haspopup="menu">
                    <x-admin.avatar :name="$user->name" size="sm" />
                    <span class="hidden text-left sm:block">
                        <span class="block max-w-[140px] truncate text-xs font-semibold text-forest">{{ $user->name }}</span>
                        <span class="block text-[10px] capitalize text-charcoal/70">{{ $user->role?->name ?? 'admin' }}</span>
                    </span>
                    <x-icon name="chevron-down" :size="14" class="text-charcoal/70" />
                </button>

                <div x-show="open" x-cloak @click.outside="open = false"
                     class="absolute right-0 top-full z-50 mt-2 w-60 overflow-hidden rounded-xl border border-forest/10 bg-white shadow-lift"
                     role="menu">
                    <div class="border-b border-sand/70 px-4 py-3">
                        <p class="truncate text-sm font-semibold text-forest">{{ $user->name }}</p>
                        <p class="truncate text-xs text-charcoal/70">{{ $user->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" role="menuitem"
                                class="flex w-full items-center gap-2.5 px-4 py-3 text-left text-sm font-medium text-red-700 transition hover:bg-red-50">
                            <x-icon name="logout" :size="16" />
                            Keluar dari panel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
