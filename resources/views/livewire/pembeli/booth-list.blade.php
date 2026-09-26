<div class="max-w-4xl mx-auto p-8">
    <h2 class="text-2xl font-bold mb-2">Pilih booth</h2>
    <p class="text-gray-500 text-sm mb-4">Jam istirahat: 12.00‑13.00 • {{ $booths->count() }} booth terbuka</p>
    <div class="flex flex-col">
    @foreach ($booths as $booth)
        <a href="/booth/{{ $booth->id }}" wire:navigate class="flex justify-between items-center bg-[#F4782A] hover:border hover:border-[#FBF3E4] rounded-xl py-4 px-5 mb-3 transition-colors">
            <div>
                <h3 class="text-white font-semibold text-lg">{{ $booth->name }}</h3>
                <p class="text-white/85 text-sm">{{ $booth->description }}</p>
            </div>
            @php $badge = $this->badgeData($booth->available_menus_count); @endphp
            <span class="{{ $badge['bg'] }} {{ $badge['text'] }} px-2 py-0.5 rounded text-xs font-medium">
                {{ $booth->available_menus_count }} menu
            </span>
        </a>
    @endforeach
    </div>
</div>
