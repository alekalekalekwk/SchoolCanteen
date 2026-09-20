<?php

namespace App\Livewire\Penjual;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('components.layouts.app')]
class IncomingOrders extends Component
{
    use AuthorizesRequests;

    public array $reportReasons = [];

    public function render()
    {
        $orders = Order::whereHas('booth', fn($q) => $q->where('owner_id', Auth::id()))
            ->with([
                'buyer' => function($q) {
                    $q->withCount(['creditReports as active_reports_count' => fn($q) => $q->where('status', 'aktif')]);
                },
                'orderItems.menuItem',
                'booth'
            ])
            ->orderByDesc('created_at')
            ->get();

        return view('livewire.penjual.incoming-orders', compact('orders'));
    }

    public function cycleStatus(int $orderId)
    {
        $order = Order::findOrFail($orderId);
        $this->authorize('update', $order);

        $order->status = match ($order->status) {
            'diproses' => 'siap',
            'siap' => 'selesai',
            default => $order->status,
        };
        $order->save();
    }

    public function togglePayment(int $orderId)
    {
        $order = Order::findOrFail($orderId);
        $this->authorize('update', $order);

        $order->payment_status = $order->payment_status === 'belum_bayar' ? 'sudah_bayar' : 'belum_bayar';
        $order->save();
    }

    public function reportBuyer(int $orderId)
    {
        $reason = $this->reportReasons[$orderId] ?? '';
        
        $order = Order::findOrFail($orderId);
        $this->authorize('update', $order);
        $this->authorize('create', \App\Models\CreditReport::class);

        if (trim($reason) === '') {
            $this->addError("reason.$orderId", 'Alasan laporan wajib diisi.');
            return;
        }

        DB::transaction(function () use ($order, $reason) {
            \App\Models\CreditReport::create([
                'reported_user_id' => $order->buyer_id,
                'reported_by_id' => Auth::id(),
                'order_id' => $order->id,
                'reason' => $reason,
                'score_deduction' => 10,
                'status' => 'aktif',
            ]);

            $order->buyer->decrement('credit_score', 10);
        });

        $this->reportReasons[$orderId] = '';
        session()->flash('report_success', "Laporan untuk #$orderId terkirim.");
    }
}
