<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Daftar Order Masuk</h2>
        @if (session()->has('report_success'))
            <div class="p-2 bg-green-100 text-green-800 rounded text-sm">
                {{ session('report_success') }}
            </div>
        @endif
    </div>

    @if($orders->isEmpty())
        <p class="text-gray-500">Belum ada order masuk.</p>
    @else
        <div class="overflow-x-auto bg-white rounded-lg shadow border">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID / Booth</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembeli (Skor)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu Dipesan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Ambil</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembayaran</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                #{{ $order->id }} <br>
                                <span class="text-xs font-normal text-gray-500">{{ $order->booth->name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <span class="font-medium">{{ $order->buyer->name }}</span> ({{ $order->buyer->nis ?? '-' }})<br>
                                <span class="text-xs {{ $order->buyer->credit_score < 50 ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                                    Skor: {{ $order->buyer->credit_score }} | Pelanggaran: {{ $order->buyer->active_reports_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <ul class="list-disc list-inside">
                                    @foreach($order->orderItems as $item)
                                        <li>{{ $item->menuItem->name ?? 'Menu' }} ({{ $item->qty }}x)</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $order->pickup_time }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($order->status == 'diproses') bg-yellow-100 text-yellow-800 
                                    @elseif($order->status == 'siap') bg-blue-100 text-blue-800 
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $order->payment_status == 'sudah_bayar' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ str_replace('_', ' ', ucfirst($order->payment_status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex flex-col gap-2">
                                    <div class="flex justify-end gap-2">
                                        <button wire:click="cycleStatus({{ $order->id }})" class="px-3 py-1 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700">
                                            Ubah Status
                                        </button>
                                        <button wire:click="togglePayment({{ $order->id }})" class="px-3 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700">
                                            Toggle Bayar
                                        </button>
                                    </div>
                                    
                                    <div class="flex gap-1 mt-2">
                                        <input type="text" wire:model="reportReasons.{{ $order->id }}" placeholder="Alasan lapor..." class="text-xs border rounded px-2 py-1 w-full">
                                        <button wire:click="reportBuyer({{ $order->id }})" class="px-2 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                            Lapor
                                        </button>
                                    </div>
                                    @error("reason.{$order->id}") <span class="text-red-600 text-[10px] text-right">{{ $message }}</span> @enderror
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>