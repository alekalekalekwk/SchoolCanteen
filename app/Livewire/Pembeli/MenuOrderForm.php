<?php

namespace App\Livewire\Pembeli;

use App\Models\Booth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
class MenuOrderForm extends Component
{
    public Booth $booth;
    public $pickup_time = '';
    public array $quantities = [];

    public function mount(Booth $booth)
    {
        $this->booth = $booth->load('menuItems');
        foreach ($this->booth->menuItems as $item) {
            $this->quantities[$item->id] = 0;
        }
    }

    public function getTimeSlotsProperty()
    {
        return [
            '09:40:00' => '09:40 - 10:00',
            '10:10:00' => '10:10 - 10:30',
            '11:20:00' => '11.20 - 13.00',
        ];
    }

    public function placeOrder()
    {
        $this->validate([
            'pickup_time' => 'required',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:0',
        ]);

        // Filter items with qty > 0
        $orderedItems = array_filter($this->quantities, fn($qty) => $qty > 0);

        if (empty($orderedItems)) {
            $this->addError('quantities', 'Pilih minimal 1 menu untuk dipesan.');
            return;
        }

        try {
            DB::transaction(function () use ($orderedItems) {
                // Re-fetch booth menu items with lock for concurrency and stock validation
                foreach ($orderedItems as $itemId => $qty) {
                    $menuItem = MenuItem::where('id', $itemId)->lockForUpdate()->first();

                    if (!$menuItem) {
                        throw new \Exception("Menu tidak ditemukan.");
                    }

                    if ($menuItem->stock_qty < $qty) {
                        throw new \Exception("Stok untuk menu '{$menuItem->name}' tidak mencukupi.");
                    }
                }

                // Create Order
                $order = Order::create([
                    'buyer_id' => Auth::id(),
                    'booth_id' => $this->booth->id,
                    'pickup_time' => $this->pickup_time,
                    'status' => 'diproses',
                    'payment_status' => 'belum_bayar',
                ]);

                // Create Order Items and decrease stock
                foreach ($orderedItems as $itemId => $qty) {
                    $menuItem = MenuItem::find($itemId);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_item_id' => $menuItem->id,
                        'qty' => $qty,
                        'price_at_order' => $menuItem->price,
                    ]);

                    $menuItem->decrement('stock_qty', $qty);
                }
            });

            session()->flash('success', 'Pesanan berhasil dibuat! Silakan ambil pada jam yang ditentukan.');
            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            $this->addError('order', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pembeli.menu-order-form', [
            'timeSlots' => $this->getTimeSlotsProperty(),
        ]);
    }
}
