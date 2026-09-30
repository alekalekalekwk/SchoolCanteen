<?php

namespace App\Livewire\Pembeli;

use App\Models\Booth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TimeSlot;
use App\Models\ScheduleSetting;
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

    public function getTotalPriceProperty()
    {
        $total = 0;
        foreach ($this->quantities as $id => $qty) {
            if ($qty > 0) {
                $item = $this->booth->menuItems->find($id);
                if ($item) $total += ($item->price * $qty);
            }
        }
        return $total;
    }

    public function mount(Booth $booth)
    {
        $this->booth = $booth->load('menuItems');
        foreach ($this->booth->menuItems as $item) {
            $this->quantities[$item->id] = 0;
        }
    }

    public function getTimeSlotsProperty()
    {
        $isOverride = ScheduleSetting::value('override_active') ?? false;
        $type = $isOverride ? 'override' : (\Carbon\Carbon::now()->isMonday() ? 'senin' : 'reguler');

        $slots = TimeSlot::where('type', $type)
            ->orderBy('sort_order')
            ->orderBy('start_time')
            ->get();

        $currentTime = \Carbon\Carbon::now()->format('H:i:s');

        $result = [];
        foreach ($slots as $slot) {
            if ($slot->start_time > $currentTime) {
                $result[$slot->start_time] = $slot->label;
            }
        }
        return $result;    }

    public function placeOrder()
    {
        $this->validate([
            'pickup_time' => 'required',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:0',
        ]);

        if ($this->pickup_time <= \Carbon\Carbon::now()->format('H:i:s')) {
            $this->addError('pickup_time', 'Jam yang dipilih sudah lewat, silakan pilih ulang.');
            return;
        }

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
