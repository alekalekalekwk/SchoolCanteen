@props(['variant' => 'neutral', 'size' => 'md'])

@php
$variantClasses = match($variant) {
    'success', 'baik' => 'bg-[#E3EFE7] text-[#3B7A57]',
    'warning', 'waspada' => 'bg-[#F5EAD8] text-[#A9761F]',
    'danger', 'buruk' => 'bg-[#F5E3DE] text-[#A63D2F]',
    default => 'bg-gray-100 text-gray-700',
};

$sizeClasses = match($size) {
    'sm' => 'px-2 py-0.5 text-xs',
    default => 'px-3 py-1 text-xs',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center font-medium rounded-full tabular-nums {$variantClasses} {$sizeClasses}"]) }}>
    {{ $slot }}
</span>
