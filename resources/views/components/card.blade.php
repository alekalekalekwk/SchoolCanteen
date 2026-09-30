@props(['variant' => 'white'])

@php
$classes = match($variant) {
    'orange' => 'bg-[#F4782A] text-white border border-[#FBF3E4]/40 rounded-2xl p-6 shadow-sm',
    default => 'bg-white border-1.5 border-[#FBF3E4] rounded-2xl p-6 transition-all hover:border-[#F4782A]',
};
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
