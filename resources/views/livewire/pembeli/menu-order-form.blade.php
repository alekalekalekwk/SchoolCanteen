<div>
    <div class="max-w-7xl mx-auto p-8">
        {{-- Back button --}}
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" wire:navigate class="text-sm font-medium text-gray-500 hover:text-black transition-colors">
                &larr; Kembali ke daftar booth
            </a>
        </div>

        {{-- Header orange card: booth name & description --}}
        <x-card variant="orange" class="mb-6">
            <h2 class="text-3xl md:text-4xl font-bold font-heading text-white mb-2">{{ $booth->name }}</h2>
            <p class="text-lg md:text-xl text-white/85">{{ $booth->description }}</p>
        </x-card>

        {{-- Accent divider line (sage) --}}
        <div class="border-b border-brand-sage/25 mb-8"></div>

        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-[#E3EFE7] text-[#3B7A57] rounded-xl font-medium">
                {{ session('success') }}
            </div>
        @endif

        @error('order')
            <div class="mb-6 p-4 bg-[#F5E3DE] text-[#A63D2F] rounded-xl font-medium">
                {{ $message }}
            </div>
        @enderror

        @error('quantities')
            <div class="mb-6 p-4 bg-[#F5E3DE] text-[#A63D2F] rounded-xl font-medium">
                {{ $message }}
            </div>
        @enderror

        <form wire:submit="placeOrder">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">
                {{-- Menu items --}}
                <div class="lg:col-span-3 space-y-4">
                    <div class="flex items-center gap-2 pb-2 border-b border-brand-sage/20">
                        <svg class="w-5 h-5 text-brand-sage" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        <span class="font-heading font-bold text-base text-gray-800">Daftar Menu</span>
                    </div>

                    @foreach ($booth->menuItems as $item)
                        <x-card class="flex items-center gap-4 py-4">
                            {{-- thumbnail --}}
                            @if($item->photo)
                                <img src="{{ Storage::url($item->photo) }}" alt="{{ $item->name }}" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                            @else
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-base flex-shrink-0">
                                    📷
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <h3 class="font-heading text-lg text-black font-bold truncate">{{ $item->name }}</h3>
                                <p class="text-sm text-gray-600 font-sans tabular">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>

                            @if($item->stock_qty <= 0)
                                <x-badge variant="danger">Habis</x-badge>
                            @else
                                <div class="flex items-center gap-2">
                                    <input type="number" wire:model.live="quantities.{{ $item->id }}" min="0" max="{{ $item->stock_qty }}" class="w-20 text-center border border-gray-300 rounded-lg py-1.5 font-bold tabular focus:outline-none focus:border-[#F4782A]">
                                </div>
                            @endif
                        </x-card>
                    @endforeach
                </div>

                {{-- Summary & Pickup Card --}}
                <div class="lg:col-span-2">
                    <x-card variant="orange">
                        @if(empty($timeSlots))
                            <p class="text-center text-white/85 py-4 font-medium">Kantin sudah tutup untuk hari ini.</p>
                        @else
                            <h3 class="text-xl font-bold font-heading text-white mb-3">Jam Ambil</h3>
                            <div class="flex flex-wrap gap-2 mb-6">
                                @foreach ($timeSlots as $value => $label)
                                    <button type="button" wire:click="$set('pickup_time', '{{ $value }}')"
                                        class="px-3.5 py-1.5 rounded-lg border font-heading text-sm font-bold transition-all {{ $pickup_time === $value ? 'bg-[#3C8DB3] border-white text-white' : 'bg-white text-[#3C8DB3] border-transparent hover:border-white' }}">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                            @error('pickup_time')
                                <p class="text-xs text-white bg-black/30 px-2 py-1 rounded mb-4">{{ $message }}</p>
                            @enderror

                            <h3 class="text-xl font-bold font-heading text-white mb-3">Ringkasan</h3>
                            <div class="space-y-2 text-sm text-white mb-6">
                                @php $hasItems = false; @endphp
                                @foreach($quantities as $id => $qty)
                                    @if($qty > 0)
                                        @php
                                            $hasItems = true;
                                            $itm = $booth->menuItems->find($id);
                                        @endphp
                                        @if($itm)
                                            <div class="flex justify-between items-center py-1 border-b border-white/20">
                                                <span>{{ $itm->name }} x {{ $qty }}</span>
                                                <span class="font-bold tabular">Rp {{ number_format($itm->price * $qty, 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach

                                @if(!$hasItems)
                                    <p class="text-white/70 italic text-xs">Belum ada menu yang dipilih</p>
                                @endif
                            </div>

                            <div class="border-t border-white pt-4 flex justify-between items-center text-xl font-bold font-heading text-white mb-6">
                                <span>Total</span>
                                <span class="tabular">Rp {{ number_format($this->totalPrice, 0, ',', '.') }}</span>
                            </div>

                            <button type="submit" class="w-full py-3 bg-[#3C8DB3] hover:bg-[#2b7294] text-white font-heading font-bold rounded-xl transition-all shadow-sm">
                                Pesan Sekarang
                            </button>
                        @endif
                    </x-card>
                </div>
            </div>
        </form>
    </div>
</div>
