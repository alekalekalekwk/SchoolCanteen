<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold font-heading text-black">Overview Credit Reports</h1>
            <p class="text-sm text-gray-600 font-sans mt-1">Daftar laporan pelanggaran siswa dan pembatalan penalti skor kredit.</p>
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

    <x-card class="p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#FBF3E4]">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Siswa (NIS)</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelapor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Alasan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deduction</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#FBF3E4] bg-white">
                    @forelse ($reports as $report)
                        <tr class="hover:bg-brand-sage/5 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-heading font-bold text-black tabular-nums">
                                #{{ $report->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-black">{{ $report->reportedUser->name }}</div>
                                <div class="text-xs text-gray-500 tabular-nums">{{ $report->reportedUser->nis ?? 'No NIS' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $report->reporter->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-heading font-semibold text-black tabular-nums">
                                {{ $report->order ? '#' . $report->order->id : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="{{ $report->reason }}">
                                {{ $report->reason }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($report->status === 'aktif')
                                    <x-badge variant="danger">aktif</x-badge>
                                @else
                                    <x-badge variant="success">dibatalkan</x-badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[#A63D2F] tabular-nums">
                                -{{ $report->score_deduction }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                @if ($report->status === 'aktif')
                                    <x-button variant="danger" wire:click="cancelReport({{ $report->id }})">
                                        Batalkan Laporan
                                    </x-button>
                                @else
                                    <span class="text-xs text-gray-400 font-medium">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500 font-heading">
                                Belum ada laporan pelanggaran tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>
