@props(['type' => 'button', 'variant' => 'primary'])

@php
$classes = match($variant) {
    'secondary' => 'bg-white text-[#3C8DB3] border-1.5 border-[#3C8DB3] hover:border-[#F4782A] hover:text-[#F4782A] font-heading font-bold rounded-xl px-5 py-2.5 text-sm transition-all focus:outline-none cursor-pointer',
    'danger' => 'bg-[#A63D2F] text-white hover:bg-[#8B3226] font-heading font-bold rounded-xl px-5 py-2.5 text-sm transition-all focus:outline-none cursor-pointer shadow-sm',
    'ghost' => 'bg-transparent text-gray-700 hover:bg-gray-100 font-heading font-bold rounded-xl px-4 py-2 text-sm transition-all focus:outline-none cursor-pointer',
    default => 'bg-[#F4782A] text-white hover:bg-[#E06A25] border border-[#FBF3E4] font-heading font-bold rounded-xl px-5 py-2.5 text-sm transition-all focus:outline-none cursor-pointer shadow-sm',
};
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
