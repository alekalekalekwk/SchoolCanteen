<div wire:poll.10s class="p-6 max-w-4xl mx-auto">
    <!-- Credit Score Section -->
    <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4 mb-6 flex justify-between items-center">
        <div>
            <h3 class="text-sm font-semibold text-indigo-800 uppercase tracking-wider">Skor Kredit Anda</h3>
            <p class="text-xs text-indigo-600">Jaga skor tetap tinggi untuk terus menggunakan fasilitas pre-order.</p>
        </div>
        <div class="text-2xl font-bold text-indigo-700">
            {{ $this->creditScore }}
        </div>
    </div>

    <!-- Active Orders Section -->
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Pesanan Aktif</h2>
        @if($activeOrders->isEmpty())
            <div class="bg-white border rounded-lg p-4 text-center text-gray-500">
                Belum ada pesanan aktif
            </div>
        @else
            <div class="space-y-4">
                @foreach($activeOrders as $order)
                    <div class="bg-white border rounded-lg p-4 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-gray-900">{{ $order->booth->name }}</span>
                                <span class="text-xs text-gray-500">#{{ $order->id }}</span>
                            </div>
                            <div class="text-sm text-gray-600 mb-2">
                                Jam Ambil: <span class="font-semibold text-gray-800">{{ $order->pickup_time }}</span>
                            </div>
                            <ul class="text-xs text-gray-500 list-disc list-inside">
                                @foreach($order->orderItems as $item)
                                    <li>{{ $item->menuItem->name ?? 'Menu' }} ({{ $item->qty }}x)</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="flex flex-col items-start md:items-end gap-2">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                {{ $order->status === 'diproses' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                            <span class="text-xs text-gray-500">
                                Pembayaran: {{ str_replace('_', ' ', ucfirst($order->payment_status)) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- History Orders Section -->
    <div>
        <h2 class="text-lg font-bold text-gray-700 mb-3">Riwayat Pesanan</h2>
        @if($historyOrders->isEmpty())
            <div class="text-sm text-gray-500 italic">
                Belum ada riwayat pesanan yang selesai.
            </div>
        @else
            <div class="space-y-3 opacity-75">
                @foreach($historyOrders as $order)
                    <div class="bg-gray-50 border rounded-lg p-3 flex justify-between items-center text-sm">
                        <div>
                            <span class="font-semibold text-gray-800">{{ $order->booth->name }}</span>
                            <span class="text-xs text-gray-500 ml-2">#{{ $order->id }}</span>
                            <div class="text-xs text-gray-500">
                                @foreach($order->orderItems as $item)
                                    {{ $item->menuItem->name ?? 'Menu' }} ({{ $item->qty }}x){{ !$loop->last ? ',' : '' }}
                                @endforeach
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded-full font-medium">Selesai</span>
                            <div class="text-xs text-gray-400 mt-1">{{ $order->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>