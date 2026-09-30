<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold font-heading text-black">Kelola Siswa (Pembeli)</h1>
            <p class="text-sm text-gray-600 font-sans mt-1">Daftar akun siswa, status skor kredit, dan manajemen akun.</p>
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
        {{-- List Siswa --}}
        <div class="lg:col-span-2">
            <x-card class="p-0 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#FBF3E4]">
                        <thead class="bg-gray-50/80">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama / Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NIS</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Skor Kredit</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#FBF3E4] bg-white">
                            @forelse($students as $student)
                                <tr class="hover:bg-brand-sage/5 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-black">{{ $student->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $student->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 tabular-nums">
                                        {{ $student->nis ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($student->credit_score < 50)
                                            <x-badge variant="danger" size="sm">Skor Rendah: {{ $student->credit_score }}</x-badge>
                                        @else
                                            <span class="tabular-nums font-semibold text-gray-700">{{ $student->credit_score }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-2">
                                        <x-button variant="secondary" wire:click="editUser({{ $student->id }})">
                                            Edit
                                        </x-button>
                                        <x-button variant="danger" wire:click="resetPassword({{ $student->id }})"
                                                  wire:confirm="Reset password siswa ini ke 'password'?">
                                            Reset
                                        </x-button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500 font-heading">
                                        Belum ada siswa yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>

        {{-- Sidebar Form --}}
        <div>
            @if($editingUserId)
                {{-- Form Edit Siswa --}}
                <x-card class="space-y-4">
                    <h2 class="font-heading font-bold text-lg text-black">Edit Siswa</h2>
                    <form wire:submit="updateUser" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nama</label>
                            <input type="text" wire:model="edit_name"
                                   class="w-full text-sm border border-gray-300 rounded-xl px-3 py-2 font-sans focus:outline-none focus:border-[#F4782A]">
                            @error('edit_name') <span class="text-[#A63D2F] text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">NIS</label>
                            <input type="text" wire:model="edit_nis"
                                   class="w-full text-sm border border-gray-300 rounded-xl px-3 py-2 font-sans focus:outline-none focus:border-[#F4782A]">
                            @error('edit_nis') <span class="text-[#A63D2F] text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex gap-2 pt-2">
                            <x-button variant="primary" type="submit" class="flex-1 justify-center">
                                Update
                            </x-button>
                            <x-button variant="secondary" type="button" wire:click="cancelEdit">
                                Batal
                            </x-button>
                        </div>
                    </form>
                </x-card>
            @else
                {{-- Form Tambah Siswa --}}
                <x-card class="space-y-4">
                    <div>
                        <h2 class="font-heading font-bold text-lg text-black">Tambah Siswa Baru</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Password default otomatis: "password"</p>
                    </div>

                    <form wire:submit="createUser" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                            <input type="text" wire:model="name"
                                   class="w-full text-sm border border-gray-300 rounded-xl px-3 py-2 font-sans focus:outline-none focus:border-[#F4782A]">
                            @error('name') <span class="text-[#A63D2F] text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Email</label>
                            <input type="email" wire:model="email"
                                   class="w-full text-sm border border-gray-300 rounded-xl px-3 py-2 font-sans focus:outline-none focus:border-[#F4782A]">
                            @error('email') <span class="text-[#A63D2F] text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">NIS (Opsional)</label>
                            <input type="text" wire:model="nis"
                                   class="w-full text-sm border border-gray-300 rounded-xl px-3 py-2 font-sans focus:outline-none focus:border-[#F4782A]">
                            @error('nis') <span class="text-[#A63D2F] text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <x-button variant="primary" type="submit" class="w-full justify-center">
                            Simpan Akun
                        </x-button>
                    </form>
                </x-card>
            @endif
        </div>
    </div>
</div>
