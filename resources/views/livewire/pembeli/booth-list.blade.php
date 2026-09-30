<div class="max-w-7xl mx-auto p-8">
    <h1 class="text-2xl font-bold font-heading text-black mb-4">Pilih Booth</h1>
    <div class="space-y-4">
        @foreach ($booths as $booth)
            <a href="{{ route('booth.show', $booth->id) }}" wire:navigate class="block">
                <x-card>
                    <div class="flex items-center gap-4">
                        {{-- Avatar inisial --}}
                        <div class="flex-shrink-0 w-9 h-9 rounded-full bg-[#E5E7EB] flex items-center justify-center text-[#374151] font-heading text-sm">
                            {{ strtoupper(substr($booth->name,0,1)) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="font-heading text-lg text-black font-bold">{{ $booth->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $booth->description }}</p>
                        </div>
                        {{-- Badge jumlah menu (neutral) --}}
                        <x-badge variant="neutral" class="flex-shrink-0">{{ $booth->available_menus_count }}</x-badge>
                    </div>
                </x-card>
            </a>
        @endforeach
    </div>
</div>
