<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Daftar Booth Kantin</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach ($booths as $booth)
            <a href="/booth/{{ $booth->id }}" wire:navigate class="block border rounded-lg p-4 hover:shadow-lg transition">
                <h3 class="text-lg font-semibold">{{ $booth->name }}</h3>
                <p class="text-gray-600 text-sm mb-2">{{ $booth->description }}</p>
                <span class="text-sm text-blue-600">{{ $booth->available_menus_count }} menu tersedia</span>
            </a>
        @endforeach
    </div>
</div>
