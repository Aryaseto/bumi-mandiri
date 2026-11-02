@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'align' => 'left',
    'level' => 2,
    'class' => '',
])

@php
    $alignment = $align === 'center' ? 'text-center mx-auto items-center' : 'text-left items-start';
    $tag = 'h' . $level;
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col ' . $alignment . ' ' . $class]) }}>
    @if($eyebrow)
        <p class="eyebrow mb-3 text-earth">{{ $eyebrow }}</p>
    @endif

    <{{ $tag }} class="max-w-3xl text-2xl font-extrabold leading-tight tracking-tight text-forest sm:text-3xl lg:text-4xl">
        {{ $title }}
    </{{ $tag }}>

    @if($description)
        <p class="mt-4 max-w-2xl text-[15px] leading-relaxed text-charcoal/65">{{ $description }}</p>
    @endif
</div>
