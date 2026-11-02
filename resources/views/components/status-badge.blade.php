@props(['status', 'label' => null, 'class' => ''])

@php
    $value = strtolower((string) $status);

    $tone = match ($value) {
        'pending' => 'bg-amber-50 text-amber-800 ring-amber-600/20',
        'confirmed' => 'bg-forest/10 text-forest ring-forest/20',
        'completed', 'active', 'approved', 'published', 'paid' => 'bg-nature/10 text-nature ring-nature/25',
        'cancelled', 'rejected', 'failed' => 'bg-red-50 text-red-700 ring-red-600/15',
        'inactive', 'draft', 'unpaid', 'hidden' => 'bg-charcoal/5 text-charcoal/70 ring-charcoal/10',
        default => 'bg-sand-light text-charcoal/70 ring-charcoal/10',
    };

    $text = $label ?? match ($value) {
        'pending' => 'Menunggu',
        'confirmed' => 'Dikonfirmasi',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
        'active' => 'Aktif',
        'inactive' => 'Nonaktif',
        'approved' => 'Tampil',
        'published' => 'Terbit',
        'draft' => 'Draf',
        'paid' => 'Lunas',
        'unpaid' => 'Belum bayar',
        default => ucfirst($value),
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset $tone $class"]) }}>
    <span class="h-1.5 w-1.5 rounded-full bg-current opacity-70" aria-hidden="true"></span>
    {{ $text }}
</span>
