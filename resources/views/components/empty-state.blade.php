@props(['icon' => 'search', 'title', 'description' => null])

<div {{ $attributes->merge(['class' => 'px-6 py-12 text-center']) }}>
    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-sand-light text-charcoal/70">
        <x-icon :name="$icon" :size="22" />
    </span>
    <h3 class="mt-4 font-heading text-base font-bold text-forest">{{ $title }}</h3>
    @if($description)
        <p class="mx-auto mt-1.5 max-w-sm text-sm leading-relaxed text-charcoal/70">{{ $description }}</p>
    @endif
    @if(! $slot->isEmpty())
        <div class="mt-5 flex flex-wrap justify-center gap-3">{{ $slot }}</div>
    @endif
</div>
