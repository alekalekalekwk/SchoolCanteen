<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Kelola Stok & Menu</h2>

    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('message') }}
        </div>
    @endif

    <!-- Form Tambah Menu Baru -->
    <div class="mb-8 bg-white p-4 rounded-lg border shadow-sm max-w-xl">
        <h3 class="text-lg font-bold mb-4">Tambah Menu Baru</h3>
        <form wire:submit="addMenuItem" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Menu</label>
                <input type="text" wire:model="newName" class="mt-1 block w-full border rounded-md p-2 text-sm">
                @error('newName') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                    <input type="number" wire:model="newPrice" min="0" class="mt-1 block w-full border rounded-md p-2 text-sm">
                    @error('newPrice') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Stok Awal</label>
                    <input type="number" wire:model="newStock" min="0" class="mt-1 block w-full border rounded-md p-2 text-sm">
                    @error('newStock') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Foto Menu (Opsional)</label>
                <input type="file" wire:model="newPhoto" accept="image/*" class="mt-1 block w-full text-sm text-gray-500">
                @error('newPhoto') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                Tambah Menu
            </button>
        </form>
    </div>

    <!-- Tabel Daftar Menu -->
    @if($items->isEmpty())
        <p class="text-gray-500">Belum ada menu yang terdaftar.</p>
    @else
        <div class="overflow-x-auto bg-white rounded-lg shadow border">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booth</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Menu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Saat Ini</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->photo)
                                    <img src="{{ asset('storage/'.$item->photo) }}" alt="{{ $item->name }}" class="w-12 h-12 object-cover rounded">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-gray-400 text-xs">
                                        No img
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $item->booth->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ $item->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <input type="number" wire:model="stock.{{ $item->id }}" min="0" class="w-24 border rounded px-2 py-1 text-center">
                                @error("stock.{$item->id}")
                                    <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="saveStock({{ $item->id }})" class="px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                    Simpan
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>