@props(['activity'])

<article class="group flex flex-col overflow-hidden rounded-2xl border border-forest/10 bg-white transition duration-300 hover:-translate-y-1 hover:border-forest/20 hover:shadow-lift">
    <a href="{{ route('activities.show', $activity) }}" class="relative block aspect-[4/3] overflow-hidden bg-sand" tabindex="-1" aria-hidden="true">
        <img src="{{ $activity->imageUrl() }}" alt="{{ $activity->name }}"
             class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]"
             loading="lazy" width="800" height="600">
        <div class="absolute inset-0 bg-gradient-to-t from-forest-dark/45 via-transparent to-transparent"></div>
        <span class="absolute left-4 top-4 rounded-full bg-cream/95 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-forest">
            {{ $activity->category?->name }}
        </span>
    </a>

    <div class="flex flex-1 flex-col p-5">
        <div class="flex items-center gap-1.5 text-xs text-charcoal/70">
            <x-icon name="map-pin" :size="14" class="text-earth" />
            <span>{{ $activity->destination?->name ?? 'Bumi Mandiri' }}</span>
            @if($activity->review_count > 0)
                <span aria-hidden="true">&middot;</span>
                <span class="inline-flex items-center gap-1">
                    <x-icon name="star" :size="13" class="fill-current text-earth" />
                    {{ number_format($activity->rating_avg, 1) }}
                </span>
                <span class="text-charcoal/70">({{ $activity->review_count }})</span>
            @endif
        </div>

        <h3 class="mt-2.5 font-heading text-lg font-bold leading-snug text-forest">
            <a href="{{ route('activities.show', $activity) }}" class="transition hover:text-earth">
                {{ $activity->name }}
            </a>
        </h3>

        <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-charcoal/70">
            {{ trim($activity->short_description) }}
        </p>

        <div class="mt-4 flex items-center gap-3 text-xs text-charcoal/70">
            <span class="inline-flex items-center gap-1.5">
                <x-icon name="clock" :size="14" />
                {{ $activity->durationLabel() }}
            </span>
            <span class="inline-flex items-center gap-1.5">
                <x-icon name="users" :size="14" />
                {{ $activity->capacity }} peserta
            </span>
        </div>

        <div class="mt-auto flex items-end justify-between gap-3 border-t border-sand/70 pt-4">
            <div>
                <span class="block text-[10px] font-medium uppercase tracking-wider text-charcoal/70">Mulai dari</span>
                <span class="font-heading text-base font-extrabold text-forest">Rp {{ number_format($activity->price, 0, ',', '.') }}</span>
                <span class="text-xs text-charcoal/70">/orang</span>
            </div>
            <a href="{{ route('activities.show', $activity) }}"
               class="inline-flex items-center gap-1 text-sm font-semibold text-forest transition hover:text-earth">
                Detail
                <x-icon name="arrow-right" :size="15" />
            </a>
        </div>
    </div>
</article>
