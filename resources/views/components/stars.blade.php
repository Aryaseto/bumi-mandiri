@props(['rating' => 0, 'size' => 14, 'showValue' => false, 'tone' => 'text-earth'])

@php
    $value = max(0, min(5, (float) $rating));
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-0.5 {$tone}"]) }}
      aria-label="Rating {{ number_format($value, 1) }} dari 5">
    @for($i = 1; $i <= 5; $i++)
        @php
            $fill = max(0, min(1, $value - ($i - 1)));

            // Gunakan ikon Font Awesome asli: penuh, setengah, atau outline.
            // Pada ukuran kecil, bintang penuh lebih mudah dibaca.
            if ($size >= 15) {
                $icon = $fill >= 0.75 ? 'star' : ($fill >= 0.25 ? 'star-half' : 'star-empty');
            } else {
                $icon = $fill >= 0.5 ? 'star' : 'star-empty';
            }
        @endphp
        <x-icon :name="$icon" :size="$size" :class="$icon === 'star-empty' ? 'opacity-40' : 'drop-shadow-sm'" />
    @endfor
    @if($showValue)
        <span class="ml-1 text-xs font-semibold text-forest">{{ number_format($value, 1) }}</span>
    @endif
</span>
