<div class="max-w-[1360px] mx-auto p-8">
    <div class="mb-8">
        <a href="/dashboard" wire:navigate class="text-sm text-gray-500 hover:underline">&larr; Kembali ke daftar booth</a>
        <h1 class="text-4xl font-bold mt-2 text-stone-800 font-['Space_Grotesk']">{{ $booth->name }}</h1>
        <p class="text-xl text-stone-800/60 mt-2 font-['Space_Grotesk']">{{ $booth->description }}</p>
    </div>

    <form wire:submit="placeOrder" class="grid grid-cols-1 md:grid-cols-5 gap-10">
        <div class="md:col-span-3 space-y-6">
            @foreach ($booth->menuItems as $item)
                <div class="flex items-center justify-between py-4 border-b border-black">
                    <div>
                        <h3 class="text-xl font-bold font-['Space_Grotesk'] {{ $item->stock_qty <= 0 ? 'text-black/40' : 'text-black' }}">{{ $item->name }}</h3>
                        <p class="text-xl font-bold font-['Space_Grotesk'] {{ $item->stock_qty <= 0 ? 'text-black/40' : 'text-black' }}">
                            {{ $item->stock_qty <= 0 ? 'Habis' : 'Rp. ' . number_format($item->price, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-4">
                        @if ($item->stock_qty <= 0)
                            <span class="px-4 py-1 text-sm bg-black/60 text-white rounded-full font-bold">Habis</span>
                        @else
                            <input type="number" wire:model="quantities.{{ $item->id }}" min="0" max="{{ $item->stock_qty }}" 
                                class="w-20 text-center border border-black rounded-lg py-1 font-bold">
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="md:col-span-2 bg-[#F4782A] rounded-2xl p-8 text-white h-fit">
            <h3 class="text-xl font-bold mb-4 font-['Space_Grotesk']">Jam Ambil</h3>
            <div class="flex flex-wrap gap-2 mb-8">
                @foreach ($timeSlots as $value => $label)
                    <button type="button" wire:click="$set('pickup_time', '{{ $value }}')"
                        class="px-4 py-2 rounded-lg border border-white font-bold {{ $pickup_time == $value ? 'bg-[#3C8DB3]' : 'bg-[#F4782A]' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <h3 class="text-xl font-bold mb-4 font-['Space_Grotesk']">Ringkasan</h3>
            <div class="space-y-2 mb-6 text-base font-normal">
                @foreach($quantities as $id => $qty)
                    @if($qty > 0)
                        @php $item = $booth->menuItems->find($id); @endphp
                        <div class="flex justify-between">
                            <span>{{ $item->name }} x {{ $qty }}</span>
                            <span>Rp {{ number_format($item->price * $qty, 0, ',', '.') }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
            
            <div class="border-t border-white pt-4 flex justify-between text-xl font-bold mb-8">
                <span>Total</span>
                <span>Rp {{ number_format($this->totalPrice, 0, ',', '.') }}</span>
            </div>

            <button type="submit" class="w-full py-4 bg-[#3C8DB3] rounded-2xl text-xl font-bold text-white hover:bg-cyan-700 transition">
                Pesan Sekarang
            </button>
        </div>
    </form>
</div>