@props(['title', 'description' => null, 'eyebrow' => null])

<div {{ $attributes->merge(['class' => 'mb-6 flex flex-wrap items-end justify-between gap-4']) }}>
    <div>
        @if($eyebrow)
            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-earth">{{ $eyebrow }}</p>
        @endif
        <h1 class="mt-1 font-heading text-2xl font-extrabold tracking-tight text-forest">{{ $title }}</h1>
        @if($description)
            <p class="mt-1.5 max-w-2xl text-sm leading-relaxed text-charcoal/70">{{ $description }}</p>
        @endif
    </div>
    @if(! $slot->isEmpty())
        <div class="flex flex-wrap items-center gap-2">{{ $slot }}</div>
    @endif
</div>
