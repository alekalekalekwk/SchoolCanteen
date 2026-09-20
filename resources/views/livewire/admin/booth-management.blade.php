<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Kelola Booth & Penjual</h2>
        <div class="flex gap-4">
             <a href="{{ route('admin.dashboard') }}" wire:navigate class="text-sm text-blue-600 hover:underline">← Laporan</a>
             <a href="{{ route('admin.users') }}" wire:navigate class="text-sm text-blue-600 hover:underline">Kelola Siswa →</a>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- List Booths -->
        <div class="lg:col-span-2 overflow-x-auto bg-white rounded-lg shadow border">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booth</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pemilik / Penjual</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($booths as $booth)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $booth->name }}</div>
                                <div class="text-xs text-gray-500">{{ $booth->description ?? 'Tanpa deskripsi' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $booth->owner->name ?? 'Belum ada' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="editBooth({{ $booth->id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Sidebar Forms -->
        <div class="space-y-6">
            <!-- Form Booth -->
            <div class="bg-white p-4 rounded-lg border shadow-sm">
                <h3 class="font-bold text-gray-800 mb-4">{{ $editingBoothId ? 'Edit Booth' : 'Tambah Booth Baru' }}</h3>
                <form wire:submit="{{ $editingBoothId ? 'updateBooth' : 'createBooth' }}">
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-600 uppercase">Nama Booth</label>
                        <input type="text" wire:model="name" class="w-full border rounded px-3 py-2 text-sm mt-1">
                        @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-600 uppercase">Deskripsi</label>
                        <textarea wire:model="description" class="w-full border rounded px-3 py-2 text-sm mt-1" rows="2"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-600 uppercase">Pemilik (Penjual)</label>
                        <select wire:model="owner_id" class="w-full border rounded px-3 py-2 text-sm mt-1">
                            <option value="">-- Pilih Penjual --</option>
                            @foreach($sellers as $seller)
                                <option value="{{ $seller->id }}">{{ $seller->name }} ({{ $seller->email }})</option>
                            @endforeach
                        </select>
                        @error('owner_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                            {{ $editingBoothId ? 'Simpan Perubahan' : 'Tambah Booth' }}
                        </button>
                        @if($editingBoothId)
                            <button type="button" wire:click="cancelEdit" class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm">Batal</button>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Form Buat Akun Penjual Baru -->
            @if(!$editingBoothId)
                <div class="bg-gray-50 p-4 rounded-lg border shadow-sm">
                    <h3 class="font-bold text-gray-700 mb-2 text-sm">Buat Akun Penjual Baru</h3>
                    <p class="text-xs text-gray-500 mb-3">Password otomatis: "password"</p>
                    <form wire:submit="createSeller">
                        <div class="mb-3">
                            <input type="text" wire:model="seller_name" placeholder="Nama Penjual" class="w-full border rounded px-3 py-1.5 text-xs">
                            @error('seller_name') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <input type="email" wire:model="seller_email" placeholder="Email Penjual" class="w-full border rounded px-3 py-1.5 text-xs">
                            @error('seller_email') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="w-full bg-gray-700 text-white px-3 py-1.5 rounded text-xs hover:bg-gray-800">
                            + Akun Penjual
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>