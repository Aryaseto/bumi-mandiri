@props([
    'name',
    'size' => 20,
    'stroke' => 1.75,
    'class' => '',
])

@php
    $map = [
        // Navigation & Dashboard
        'layout-dashboard' => 'fa-solid fa-table-columns',
        'menu' => 'fa-solid fa-bars',
        'x' => 'fa-solid fa-xmark',
        'plus' => 'fa-solid fa-plus',
        'edit' => 'fa-solid fa-pen-to-square',
        'trash' => 'fa-solid fa-trash-can',
        'check' => 'fa-solid fa-check',
        'search' => 'fa-solid fa-magnifying-glass',
        'settings' => 'fa-solid fa-gear',
        'logout' => 'fa-solid fa-arrow-right-from-bracket',
        'eye' => 'fa-regular fa-eye',
        'alert-circle' => 'fa-solid fa-circle-exclamation',

        // Arrows & Navigation
        'arrow-right' => 'fa-solid fa-arrow-right',
        'arrow-left' => 'fa-solid fa-arrow-left',
        'arrow-up-right' => 'fa-solid fa-arrow-up-right-from-square',
        'chevron-down' => 'fa-solid fa-chevron-down',
        'chevron-right' => 'fa-solid fa-chevron-right',
        'chevron-left' => 'fa-solid fa-chevron-left',
        'external-link' => 'fa-solid fa-arrow-up-right-from-square',

        // Nature & Tourism
        'mountain' => 'fa-solid fa-mountain-sun',
        'compass' => 'fa-solid fa-compass',
        'leaf' => 'fa-solid fa-leaf',
        'tree' => 'fa-solid fa-tree',
        'map-pin' => 'fa-solid fa-location-dot',
        'flame' => 'fa-solid fa-fire',
        'route' => 'fa-solid fa-route',

        // Categories & Activities
        'adventure' => 'fa-solid fa-person-hiking',
        'nature' => 'fa-solid fa-leaf',
        'team-building' => 'fa-solid fa-people-group',
        'skill' => 'fa-solid fa-bullseye',
        'education' => 'fa-solid fa-book-open',
        'target' => 'fa-solid fa-bullseye',
        'users-group' => 'fa-solid fa-people-group',
        'book' => 'fa-solid fa-book-open',

        // Users & People
        'users' => 'fa-solid fa-users',
        'user' => 'fa-solid fa-user',

        // Booking & Commerce
        'package' => 'fa-solid fa-box-archive',
        'ticket' => 'fa-solid fa-ticket',
        'calendar' => 'fa-solid fa-calendar-days',
        'calendar-check' => 'fa-solid fa-calendar-check',
        'clock' => 'fa-regular fa-clock',
        'wallet' => 'fa-solid fa-wallet',
        'currency' => 'fa-solid fa-money-bill-wave',
        'tag' => 'fa-solid fa-tag',
        'chart-bar' => 'fa-solid fa-chart-column',

        // Media & Content
        'photo' => 'fa-regular fa-image',
        'article' => 'fa-regular fa-newspaper',
        'star' => 'fa-solid fa-star',
        'star-half' => 'fa-solid fa-star-half-stroke',
        'star-empty' => 'fa-regular fa-star',
        'heart' => 'fa-solid fa-heart',
        'quote' => 'fa-solid fa-quote-left',
        'building' => 'fa-solid fa-building',
        'shield-check' => 'fa-solid fa-shield-halved',

        // Communication & Social
        'mail' => 'fa-regular fa-envelope',
        'phone' => 'fa-solid fa-phone',
        'brand-whatsapp' => 'fa-brands fa-whatsapp',
        'brand-instagram' => 'fa-brands fa-instagram',
    ];

    $iconClass = $map[$name] ?? (str_starts_with($name, 'fa-') ? $name : "fa-solid fa-{$name}");

    if ($name === 'heart' && !str_contains($class, 'text-earth') && !str_contains($class, 'fill-current') && !str_contains($attributes->get('class', ''), 'text-earth')) {
        $iconClass = 'fa-regular fa-heart';
    }
@endphp

<i {{ $attributes->merge([
    'class' => "{$iconClass} {$class}",
    'style' => "font-size: {$size}px; width: {$size}px; height: {$size}px; display: inline-flex; align-items: center; justify-content: center; line-height: 1; flex-shrink: 0;"
]) }} aria-hidden="true"></i>
