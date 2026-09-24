<?php

namespace App\Livewire\Pembeli;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class OrderHistory extends Component
{
    public function render()
    {
        $orders = Order::where('buyer_id', Auth::id())
            ->with(['booth', 'orderItems.menuItem'])
            ->latest()
            ->get();

        return view('livewire.pembeli.order-history', [
            'activeOrders' => $orders->whereIn('status', ['diproses', 'siap']),
            'historyOrders' => $orders->where('status', 'selesai'),
        ]);
    }

    public function getCreditScoreProperty()
    {
        return Auth::user()->credit_score;
    }
}
