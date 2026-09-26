@props(['type' => 'button', 'variant' => 'primary'])

@php
$classes = match($variant) {
    'secondary' => 'bg-gray-100 text-gray-800 hover:bg-gray-200 border border-brand-cream font-medium rounded-lg px-4 py-2 text-sm transition-colors focus:outline-none',
    'danger' => 'bg-red-600 text-white hover:bg-red-700 border border-brand-cream font-medium rounded-lg px-4 py-2 text-sm transition-colors focus:outline-none',
    default => 'bg-brand-orange text-white hover:opacity-90 border border-brand-cream font-medium rounded-lg px-4 py-2 text-sm transition-colors focus:outline-none shadow-sm',
};
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>