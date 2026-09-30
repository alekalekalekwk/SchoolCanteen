<div>
    <div class="max-w-7xl mx-auto p-8">
        {{-- Header Section --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold font-heading text-black">Daftar Order Masuk</h1>
                <p class="text-sm text-gray-600 font-sans mt-1">Kelola status pesanan, pembayaran, dan laporan pelanggan kantin.</p>
            </div>
        </div>

        {{-- Flash message --}}
        @if (session()->has('report_success'))
            <div class="mb-6 p-4 bg-[#E3EFE7] text-[#3B7A57] rounded-xl font-medium flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('report_success') }}</span>
            </div>
        @endif

        {{-- Orders List --}}
        @if($orders->isEmpty())
            <x-card class="text-center py-12">
                <p class="text-gray-500 font-heading text-lg">Belum ada order masuk saat ini.</p>
                <p class="text-xs text-gray-400 mt-1">Pesanan dari siswa akan muncul di sini secara otomatis.</p>
            </x-card>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <x-card class="space-y-4">
                        {{-- Top row: ID, Buyer info, Badges --}}
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="font-heading font-bold text-xl text-black tabular">#{{ $order->id }}</span>
                                <span class="text-xs px-2 py-0.5 rounded bg-gray-100 text-gray-600 font-medium">{{ $order->booth->name }}</span>
                                
                                {{-- Buyer Info --}}
                                <div class="border-l border-gray-200 pl-3">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-black text-sm">{{ $order->buyer->name }}</span>
                                        <span class="text-xs text-gray-500">({{ $order->buyer->nis ?? 'No NIS' }})</span>
                                        @if($order->buyer->credit_score < 50)
                                            <x-badge variant="danger" size="sm">Skor Rendah: {{ $order->buyer->credit_score }}</x-badge>
                                        @else
                                            <span class="text-xs text-gray-500 tabular">Skor: {{ $order->buyer->credit_score }}</span>
                                        @endif
                                    </div>
                                    @if($order->buyer->active_reports_count > 0)
                                        <p class="text-[11px] text-[#A63D2F] font-medium mt-0.5">
                                            {{ $order->buyer->active_reports_count }} laporan aktif tercatat
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Badges: Status & Payment --}}
                            <div class="flex items-center gap-2 flex-wrap">
                                {{-- Order Status Badge --}}
                                @if($order->status === 'selesai')
                                    <x-badge variant="success">Selesai</x-badge>
                                @elseif($order->status === 'siap')
                                    <x-badge variant="warning">Siap Diambil</x-badge>
                                @else
                                    <x-badge variant="warning">Diproses</x-badge>
                                @endif

                                {{-- Payment Status Badge --}}
                                @if($order->payment_status === 'sudah_bayar')
                                    <x-badge variant="success">Sudah Bayar</x-badge>
                                @else
                                    <x-badge variant="warning">Belum Bayar</x-badge>
                                @endif
                            </div>
                        </div>

                        {{-- Middle divider (sage) --}}
                        <div class="border-b border-brand-sage/20"></div>

                        {{-- Order Details: Pickup time & Items --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            {{-- Pickup Time --}}
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-brand-sage flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                                    <circle cx="12" cy="12" r="9" />
                                </svg>
                                <div>
                                    <span class="text-xs text-gray-500 block">Jam Ambil</span>
                                    <span class="font-heading font-bold text-sm text-black tabular">{{ $order->pickup_time }}</span>
                                </div>
                            </div>

                            {{-- Items Ordered --}}
                            <div class="md:col-span-2">
                                <span class="text-xs text-gray-500 block mb-1">Menu Dipesan:</span>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($order->orderItems as $item)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-50 border border-gray-200 text-gray-800">
                                            <span class="font-bold mr-1">{{ $item->qty }}x</span>
                                            {{ $item->menuItem->name ?? 'Menu' }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Action Controls & Report Form --}}
                        <div class="pt-2 flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-t border-gray-100">
                            {{-- Status Cycle & Payment Toggle Buttons --}}
                            <div class="flex items-center gap-3">
                                <x-button variant="primary" wire:click="cycleStatus({{ $order->id }})">
                                    Ubah Status
                                </x-button>
                                <x-button variant="secondary" wire:click="togglePayment({{ $order->id }})">
                                    Toggle Bayar
                                </x-button>
                            </div>

                            {{-- Report Form --}}
                            <div class="flex items-center gap-2 max-w-md w-full">
                                <input type="text" wire:model="reportReasons.{{ $order->id }}" placeholder="Alasan lapor pelanggaran..."
                                    class="text-xs border border-gray-300 rounded-xl px-3 py-2 w-full font-sans focus:outline-none focus:border-[#F4782A]">
                                <x-button variant="danger" wire:click="reportBuyer({{ $order->id }})" class="flex-shrink-0">
                                    Lapor
                                </x-button>
                            </div>
                        </div>

                        {{-- Report Validation Error --}}
                        @error("reason.{$order->id}")
                            <p class="text-right text-xs text-[#A63D2F] font-medium">{{ $message }}</p>
                        @enderror
                    </x-card>
                @endforeach
            </div>
        @endif
    </div>
</div>
