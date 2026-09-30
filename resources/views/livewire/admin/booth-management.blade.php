<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold font-heading text-black">Kelola Booth & Penjual</h1>
            <p class="text-sm text-gray-600 font-sans mt-1">Daftar booth kantin, penetapan pemilik, dan pembuatan akun penjual.</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="p-4 bg-[#E3EFE7] text-[#3B7A57] rounded-xl font-medium flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        {{-- List Booths --}}
        <div class="lg:col-span-2">
            <x-card class="p-0 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#FBF3E4]">
                        <thead class="bg-gray-50/80">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Booth</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pemilik / Penjual</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#FBF3E4] bg-white">
                            @forelse($booths as $booth)
                                <tr class="hover:bg-brand-sage/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0 w-9 h-9 rounded-full bg-[#E5E7EB] flex items-center justify-center text-[#374151] font-heading text-sm font-bold">
                                                {{ strtoupper(substr($booth->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-black font-heading">{{ $booth->name }}</div>
                                                <div class="text-xs text-gray-500 line-clamp-1">{{ $booth->description ?? 'Tanpa deskripsi' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        @if($booth->owner)
                                            <div class="font-medium text-black">{{ $booth->owner->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $booth->owner->email }}</div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Belum ditentukan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <x-button variant="secondary" wire:click="editBooth({{ $booth->id }})">
                                            Edit
                                        </x-button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-gray-500 font-heading">
                                        Belum ada booth yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>

        {{-- Sidebar Forms --}}
        <div class="space-y-6">
            {{-- Form Booth --}}
            <x-card class="space-y-4">
                <h2 class="font-heading font-bold text-lg text-black">
                    {{ $editingBoothId ? 'Edit Booth' : 'Tambah Booth Baru' }}
                </h2>
                
                <form wire:submit="{{ $editingBoothId ? 'updateBooth' : 'createBooth' }}" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nama Booth</label>
                        <input type="text" wire:model="name"
                               class="w-full text-sm border border-gray-300 rounded-xl px-3 py-2 font-sans focus:outline-none focus:border-[#F4782A]">
                        @error('name') <span class="text-[#A63D2F] text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Deskripsi</label>
                        <textarea wire:model="description" rows="2"
                                  class="w-full text-sm border border-gray-300 rounded-xl px-3 py-2 font-sans focus:outline-none focus:border-[#F4782A]"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Pemilik (Penjual)</label>
                        <select wire:model="owner_id"
                                class="w-full text-sm border border-gray-300 rounded-xl px-3 py-2 font-sans focus:outline-none focus:border-[#F4782A]">
                            <option value="">-- Pilih Penjual --</option>
                            @foreach($sellers as $seller)
                                <option value="{{ $seller->id }}">{{ $seller->name }} ({{ $seller->email }})</option>
                            @endforeach
                        </select>
                        @error('owner_id') <span class="text-[#A63D2F] text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-2 pt-2">
                        <x-button variant="primary" type="submit" class="flex-1 justify-center">
                            {{ $editingBoothId ? 'Simpan' : 'Tambah Booth' }}
                        </x-button>
                        @if($editingBoothId)
                            <x-button variant="secondary" type="button" wire:click="cancelEdit">
                                Batal
                            </x-button>
                        @endif
                    </div>
                </form>
            </x-card>

            {{-- Form Buat Akun Penjual Baru --}}
            @if(!$editingBoothId)
                <x-card class="space-y-4">
                    <div>
                        <h2 class="font-heading font-bold text-base text-black">Buat Akun Penjual</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Password default otomatis: "password"</p>
                    </div>

                    <form wire:submit="createSeller" class="space-y-3">
                        <div>
                            <input type="text" wire:model="seller_name" placeholder="Nama Penjual"
                                   class="w-full text-xs border border-gray-300 rounded-xl px-3 py-2 font-sans focus:outline-none focus:border-[#F4782A]">
                            @error('seller_name') <span class="text-[#A63D2F] text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <input type="email" wire:model="seller_email" placeholder="Email Penjual"
                                   class="w-full text-xs border border-gray-300 rounded-xl px-3 py-2 font-sans focus:outline-none focus:border-[#F4782A]">
                            @error('seller_email') <span class="text-[#A63D2F] text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <x-button variant="secondary" type="submit" class="w-full justify-center">
                            + Tambah Penjual
                        </x-button>
                    </form>
                </x-card>
            @endif
        </div>
    </div>
</div>
