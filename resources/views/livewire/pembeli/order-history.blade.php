<div wire:poll.10s class="max-w-7xl mx-auto p-8 bg-white font-sans">
    {{-- Credit Score Card --}}
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold font-heading text-black">Riwayat &amp; Pesanan Anda</h1>
        <div class="bg-[#F4782A] text-white px-4 py-2 rounded-xl font-heading shadow-sm">
            <span class="block text-xs uppercase tracking-wider text-white/80">Skor Kredit</span>
            <span class="text-2xl font-bold tabular-nums">{{ $this->creditScore }}</span>
        </div>
    </div>

    {{-- Tabs --}}
    @php
        $tabs = [
            'diproses' => 'Diproses',
            'siap'     => 'Siap',
            'selesai'  => 'Selesai',
        ];
    @endphp
    <div class="border-b border-gray-200 mb-6 flex space-x-6">
        @foreach($tabs as $key => $label)
            <button type="button" wire:click="$set('tab', '{{ $key }}')"
                class="pb-2 text-sm font-heading font-medium transition-colors {{ $tab === $key ? 'text-[#F4782A] border-b-2 border-[#F4782A]' : 'text-gray-600 hover:text-[#F4782A]' }}">
                {{ $label }} ({{ $key === 'selesai' ? $historyOrders->count() : $activeOrders->where('status', $key)->count() }})
            </button>
        @endforeach
    </div>

    {{-- Orders list --}}
    @php
        $orders = match($tab) {
            'diproses' => $activeOrders->where('status', 'diproses'),
            'siap'     => $activeOrders->where('status', 'siap'),
            default    => $historyOrders,
        };
    @endphp

    @if($orders->isEmpty())
        <div class="text-center py-12 text-gray-500 font-heading">
            Tidak ada pesanan {{ $tab === 'selesai' ? 'selesai' : 'aktif' }}.
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <x-card class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                    {{-- Left info --}}
                    <div class="flex-1">
                        <div class="flex items-baseline gap-2 mb-1">
                            <h3 class="font-heading text-lg text-black font-bold">{{ $order->booth->name }}</h3>
                            <span class="text-sm text-gray-500 tabular-nums">#{{ $order->id }}</span>
                        </div>
                        <p class="text-sm text-gray-700">
                            <span class="font-medium">Jam Ambil:</span>
                            <span class="text-black font-bold tabular-nums">{{ $order->pickup_time }}</span>
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            @foreach($order->orderItems as $itm)
                                {{ $itm->menuItem->name ?? 'Menu' }} ({{ $itm->qty }}x){{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </p>
                    </div>

                    {{-- Right badges --}}
                    <div class="flex flex-row md:flex-col items-start md:items-end gap-2 flex-shrink-0">
                        @if($order->status === 'selesai')
                            <x-badge variant="success">Selesai</x-badge>
                        @elseif($order->status === 'siap')
                            <x-badge variant="warning">Siap</x-badge>
                        @else
                            <x-badge variant="warning">Diproses</x-badge>
                        @endif

                        @if($order->payment_status === 'sudah_bayar')
                            <x-badge variant="success">Sudah Bayar</x-badge>
                        @else
                            <x-badge variant="warning">Belum Bayar</x-badge>
                        @endif
                    </div>
                </x-card>
            @endforeach
        </div>
    @endif
</div>
