<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Overview Credit Reports</h2>

    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-lg shadow border">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Siswa (NIS)</th>
                    <th class="px-4 py-2 text-left">Pelapor</th>
                    <th class="px-4 py-2 text-left">Order</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Pengurangan Skor</th>
                    <th class="px-4 py-2 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($reports as $report)
                    <tr>
                        <td class="px-4 py-2">{{ $report->id }}</td>
                        <td class="px-4 py-2">{{ $report->reportedUser->name }} ({{ $report->reportedUser->nis }})</td>
                        <td class="px-4 py-2">{{ $report->reporter->name }}</td>
                        <td class="px-4 py-2">#{{ $report->order->id }}</td>
                        <td class="px-4 py-2">{{ $report->status }}</td>
                        <td class="px-4 py-2">-{{ $report->score_deduction }}</td>
                        <td class="px-4 py-2 text-right">
                            @if ($report->status === 'aktif')
                                <button wire:click="cancelReport({{ $report->id }})"
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                    Batalkan Laporan
                                </button>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>