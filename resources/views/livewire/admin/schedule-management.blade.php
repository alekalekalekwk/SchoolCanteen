<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold font-heading text-black">Jadwal Jam Ambil</h1>
            <p class="text-sm text-gray-600 font-sans mt-1">Atur jadwal istirahat siswa dan mode darurat.</p>
        </div>
    </div>

    {{-- Toggle Override Panel --}}
    <x-card class="flex items-center justify-between p-6 {{ $setting->override_active ? 'bg-[#F5E3DE] border-[#A63D2F]' : '' }}">
        <div class="flex items-center gap-4">
            @if($setting->override_active)
                <x-badge variant="danger">DARURAT: Jadwal Override Sedang AKTIF</x-badge>
                <p class="text-sm font-bold text-[#A63D2F]">Semua jadwal otomatis Senin & Reguler diabaikan.</p>
            @else
                <x-badge variant="neutral">Status Normal (Jadwal Senin/Reguler Aktif)</x-badge>
            @endif
        </div>
        <x-button 
            variant="{{ $setting->override_active ? 'secondary' : 'danger' }}" 
            wire:click="toggleOverride"
        >
            {{ $setting->override_active ? 'Matikan Override / Kembali Normal' : 'Aktifkan Override Darurat' }}
        </x-button>
    </x-card>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @foreach(['senin' => 'Jadwal Senin', 'reguler' => 'Jadwal Reguler', 'override' => 'Jadwal Override'] as $slotType => $label)
            <x-card class="space-y-4 {{ $slotType == 'override' ? 'border-[#F4782A]' : '' }}">
                <div class="flex justify-between items-center">
                    <h2 class="font-bold text-lg font-heading">{{ $label }}</h2>
                    <x-badge variant="neutral">
                        {{ $slotType == 'senin' ? 'Berlaku tiap Senin' : ($slotType == 'reguler' ? 'Berlaku Sel-Jum' : 'Cadangan Darurat') }}
                    </x-badge>
                </div>

                <div class="space-y-2">
                    @forelse($$slotType as $slot)
                        <div class="flex justify-between p-3 border-b border-[#FBF3E4] text-sm">
                            <span class="font-bold font-heading">{{ $slot->label }}</span>
                            <div class="flex gap-3">
                                <button wire:click="edit({{ $slot->id }})" class="text-blue-600 hover:text-blue-800">Edit</button>
                                <button wire:click="delete({{ $slot->id }})" class="text-red-600 hover:text-red-800">Hapus</button>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 italic">Belum ada slot.</p>
                    @endforelse
                </div>

                <button wire:click="$set('type', '{{ $slotType }}')" class="w-full text-sm font-bold text-[#3C8DB3] hover:text-[#F4782A]">+ Tambah Slot</button>
            </x-card>
        @endforeach
    </div>

    {{-- Edit Form --}}
    @if($type)
        <x-card class="max-w-xl">
            <h3 class="font-bold font-heading mb-4">{{ $editingId ? 'Edit Slot' : 'Tambah Slot Baru' }} ({{ strtoupper($type) }})</h3>
            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase mb-1">Mulai</label>
                        <input type="time" wire:model="start_time" class="w-full border-gray-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase mb-1">Selesai</label>
                        <input type="time" wire:model="end_time" class="w-full border-gray-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase mb-1">Urutan</label>
                        <input type="number" wire:model="sort_order" class="w-full border-gray-300 rounded-lg text-sm">
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <x-button variant="primary" type="submit">Simpan</x-button>
                    <x-button variant="secondary" type="button" wire:click="cancelEdit">Batal</x-button>
                </div>
            </form>
            @error('start_time') <p class="text-red-600 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
        </x-card>
    @endif
</div>
