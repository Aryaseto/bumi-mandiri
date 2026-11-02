@props(['name', 'size' => 'md'])

@php
    $initials = \Illuminate\Support\Str::of($name)
        ->squish()
        ->explode(' ')
        ->take(2)
        ->map(fn ($part) => \Illuminate\Support\Str::substr($part, 0, 1))
        ->implode('');

    $sizeClass = match ($size) {
        'sm' => 'h-8 w-8 text-[11px]',
        'lg' => 'h-12 w-12 text-sm',
        default => 'h-9 w-9 text-xs',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex shrink-0 items-center justify-center rounded-full bg-forest font-heading font-bold uppercase text-cream $sizeClass"]) }}
      aria-hidden="true">{{ $initials }}</span>
