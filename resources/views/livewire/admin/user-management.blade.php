<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Kelola Siswa (Pembeli)</h2>
        <div class="flex gap-4">
             <a href="{{ route('admin.dashboard') }}" wire:navigate class="text-sm text-blue-600 hover:underline">← Laporan</a>
             <a href="{{ route('admin.booths') }}" wire:navigate class="text-sm text-blue-600 hover:underline">Kelola Booth →</a>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- List Siswa -->
        <div class="lg:col-span-2 overflow-x-auto bg-white rounded-lg shadow border">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama / Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Skor</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($students as $student)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                <div class="text-xs text-gray-500">{{ $student->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $student->nis ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $student->credit_score < 70 ? 'text-red-600' : 'text-gray-700' }}">
                                {{ $student->credit_score }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="editUser({{ $student->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                <button wire:click="resetPassword({{ $student->id }})" 
                                        wire:confirm="Reset password siswa ini ke 'password'?"
                                        class="text-amber-600 hover:text-amber-900 text-xs">Reset Pass</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Forms Side -->
        <div class="space-y-6">
            @if($editingUserId)
                <!-- Form Edit -->
                <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100 shadow-sm">
                    <h3 class="font-bold text-indigo-800 mb-4">Edit Siswa</h3>
                    <form wire:submit="updateUser">
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-indigo-700 uppercase">Nama</label>
                            <input type="text" wire:model="edit_name" class="w-full border rounded px-3 py-2 text-sm mt-1 focus:ring-indigo-500">
                            @error('edit_name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-indigo-700 uppercase">NIS</label>
                            <input type="text" wire:model="edit_nis" class="w-full border rounded px-3 py-2 text-sm mt-1 focus:ring-indigo-500">
                            @error('edit_nis') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700">Update</button>
                            <button type="button" wire:click="cancelEdit" class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-300">Batal</button>
                        </div>
                    </form>
                </div>
            @else
                <!-- Form Tambah -->
                <div class="bg-white p-4 rounded-lg border shadow-sm">
                    <h3 class="font-bold text-gray-800 mb-4">Tambah Siswa Baru</h3>
                    <form wire:submit="createUser">
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 uppercase">Nama Lengkap</label>
                            <input type="text" wire:model="name" class="w-full border rounded px-3 py-2 text-sm mt-1">
                            @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 uppercase">Email</label>
                            <input type="email" wire:model="email" class="w-full border rounded px-3 py-2 text-sm mt-1">
                            @error('email') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 uppercase">NIS (Opsional)</label>
                            <input type="text" wire:model="nis" class="w-full border rounded px-3 py-2 text-sm mt-1">
                            @error('nis') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Simpan Akun</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>