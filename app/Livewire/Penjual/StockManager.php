<?php

namespace App\Livewire\Penjual;

use App\Models\MenuItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class StockManager extends Component
{
    public array $stock = [];

    public function mount()
    {
        $items = MenuItem::whereHas('booth', fn($q) => $q->where('owner_id', Auth::id()))->get();
        foreach ($items as $item) {
            $this->stock[$item->id] = $item->stock_qty;
        }
    }

    public function saveStock(int $menuItemId)
    {
        $menuItem = MenuItem::findOrFail($menuItemId);
        $this->authorize('update', $menuItem);

        $this->validate([
            "stock.$menuItemId" => 'required|integer|min:0',
        ]);

        $menuItem->stock_qty = $this->stock[$menuItemId];
        $menuItem->save();
        $this->dispatch('notify', message: 'Stok berhasil diperbarui.');
    }

    public function render()
    {
        $items = MenuItem::whereHas('booth', fn($q) => $q->where('owner_id', Auth::id()))->with('booth')->get();
        return view('livewire.penjual.stock-manager', [
            'items' => $items,
        ]);
    }
}
