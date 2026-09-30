<div>
    <div class="max-w-7xl mx-auto p-8">
        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold font-heading text-black">Kelola Stok &amp; Menu</h1>
            <p class="text-sm text-gray-600 font-sans mt-1">Atur ketersediaan menu, harga, dan perbarui foto produk booth Anda.</p>
        </div>

        {{-- Flash message --}}
        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-[#E3EFE7] text-[#3B7A57] rounded-xl font-medium flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        {{-- Form Tambah Menu Baru --}}
        <x-card class="mb-8 max-w-2xl">
            <h2 class="text-xl font-bold font-heading text-black mb-4">Tambah Menu Baru</h2>
            <form wire:submit="addMenuItem" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Menu</label>
                    <input type="text" wire:model="newName" placeholder="Contoh: Ayam Geprek Sambal Matah"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm font-sans focus:outline-none focus:border-[#F4782A]">
                    @error('newName') <span class="text-[#A63D2F] text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                        <input type="number" wire:model="newPrice" min="0" placeholder="15000"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm font-sans tabular-nums focus:outline-none focus:border-[#F4782A]">
                        @error('newPrice') <span class="text-[#A63D2F] text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok Awal</label>
                        <input type="number" wire:model="newStock" min="0" placeholder="20"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm font-sans tabular-nums focus:outline-none focus:border-[#F4782A]">
                        @error('newStock') <span class="text-[#A63D2F] text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Menu (Opsional)</label>
                    <input type="file" wire:model="newPhoto" accept="image/*"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                    @error('newPhoto') <span class="text-[#A63D2F] text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-2">
                    <x-button variant="primary" type="submit">
                        Tambah Menu
                    </x-button>
                </div>
            </form>
        </x-card>

        {{-- Divider sage halus --}}
        <div class="border-b border-brand-sage/20 mb-8"></div>

        {{-- Section Title: Daftar Menu --}}
        <div class="flex items-center gap-2 mb-6">
            <svg class="w-5 h-5 text-brand-sage" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
            </svg>
            <h2 class="text-2xl font-bold font-heading text-black">Daftar Menu Booth</h2>
        </div>

        {{-- Items List --}}
        @if($items->isEmpty())
            <x-card class="text-center py-12">
                <p class="text-gray-500 font-heading text-lg">Belum ada menu yang terdaftar.</p>
                <p class="text-xs text-gray-400 mt-1">Gunakan formulir di atas untuk menambahkan menu pertama Anda.</p>
            </x-card>
        @else
            <div class="space-y-4">
                @foreach($items as $item)
                    <x-card class="space-y-3">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            {{-- Left: Thumbnail + Name + Price --}}
                            <div class="flex items-center gap-4">
                                {{-- Thumbnail --}}
                                @if($item->photo)
                                    <img src="{{ Storage::url($item->photo) }}" alt="{{ $item->name }}"
                                        class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                @else
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-sm flex-shrink-0">
                                        📷
                                    </div>
                                @endif

                                <div>
                                    <h3 class="font-heading font-bold text-lg text-black">{{ $item->name }}</h3>
                                    <p class="text-sm text-gray-600 font-sans tabular-nums">Rp {{ number_format($item->price, 0, ',', '.') }}</p>

                                    {{-- Photo Action Links --}}
                                    <div class="mt-1">
                                        @if($item->photo)
                                            <button type="button" wire:click="$set('editingItem', {{ $item->id }})"
                                                class="text-xs text-[#F4782A] hover:underline font-medium">
                                                Ubah foto
                                            </button>
                                        @else
                                            <button type="button" wire:click="$set('editingItem', {{ $item->id }})"
                                                class="text-xs text-[#A63D2F] hover:underline font-medium">
                                                + Tambah foto
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Right: Stock Badge + Stock Update Control --}}
                            <div class="flex items-center gap-4 flex-wrap">
                                {{-- Semantic Stock Badge --}}
                                @if($item->stock_qty > 10)
                                    <x-badge variant="success">Stok Aman ({{ $item->stock_qty }})</x-badge>
                                @elseif($item->stock_qty > 0)
                                    <x-badge variant="warning">Stok Menipis ({{ $item->stock_qty }})</x-badge>
                                @else
                                    <x-badge variant="danger">Habis</x-badge>
                                @endif

                                {{-- Stock Input & Save --}}
                                <div class="flex items-center gap-2">
                                    <input type="number" wire:model="stock.{{ $item->id }}" min="0"
                                        class="w-20 text-center border border-gray-300 rounded-lg py-1.5 font-bold tabular-nums focus:outline-none focus:border-[#F4782A]">
                                    <x-button variant="secondary" wire:click="saveStock({{ $item->id }})">
                                        Simpan
                                    </x-button>
                                </div>
                            </div>
                        </div>

                        {{-- Stock Validation Error --}}
                        @error("stock.{$item->id}")
                            <p class="text-right text-xs text-[#A63D2F] font-medium">{{ $message }}</p>
                        @enderror

                        {{-- Inline Panel Ubah / Tambah Foto --}}
                        @if($editingItem === $item->id)
                            <div class="mt-3 p-4 bg-gray-50 border border-gray-200 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex-1">
                                    <label class="block text-xs font-bold text-gray-700 mb-1">
                                        {{ $item->photo ? 'Pilih Foto Baru untuk Menggantikan' : 'Upload Foto Menu' }}
                                    </label>
                                    <input type="file" wire:model="newPhotos.{{ $item->id }}" accept="image/*"
                                        class="text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300">
                                    @error("newPhotos.{$item->id}")
                                        <span class="text-[#A63D2F] text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex items-center gap-2">
                                    <x-button variant="primary" wire:click="replacePhoto({{ $item->id }})">
                                        Simpan Foto
                                    </x-button>
                                    <x-button variant="secondary" wire:click="$set('editingItem', null)">
                                        Batal
                                    </x-button>
                                </div>
                            </div>
                        @endif
                    </x-card>
                @endforeach
            </div>
        @endif
    </div>
</div>
