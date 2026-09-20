<div class="p-6">
    <div class="mb-6">
        <a href="/dashboard" wire:navigate class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Booth</a>
        <h2 class="text-2xl font-bold mt-2">{{ $booth->name }}</h2>
        <p class="text-gray-600">{{ $booth->description }}</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    @error('order')
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ $message }}</div>
    @enderror

    @error('quantities')
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ $message }}</div>
    @enderror

    <form wire:submit="placeOrder">
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Pengambilan</label>
            <select wire:model="pickup_time" class="border rounded px-3 py-2 w-full md:w-1/3">
                <option value="">-- Pilih Jam --</option>
                @foreach ($timeSlots as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            @error('pickup_time')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-4 mb-6">
            @foreach ($booth->menuItems as $item)
                <div class="flex items-center justify-between border rounded-lg p-4 bg-white shadow-sm">
                    <div>
                        <h3 class="font-semibold text-lg">{{ $item->name }}</h3>
                        <p class="text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        <span class="text-xs text-gray-500">Stok: {{ $item->stock_qty }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @if ($item->stock_qty <= 0)
                            <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Habis</span>
                            <input type="number" disabled value="0" class="w-16 border rounded px-2 py-1 text-center bg-gray-100 cursor-not-allowed">
                        @else
                            <input type="number" wire:model="quantities.{{ $item->id }}" min="0" max="{{ $item->stock_qty }}" class="w-16 border rounded px-2 py-1 text-center">
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Pesan Sekarang
        </button>
    </form>
</div>