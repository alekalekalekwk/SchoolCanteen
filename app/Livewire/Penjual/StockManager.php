<?php

namespace App\Livewire\Penjual;

use App\Models\MenuItem;
use App\Models\Booth;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class StockManager extends Component
{
    use WithFileUploads;

    public array $stock = [];

    // New Menu form fields
    public $newName = '';
    public $newPrice;
    public $newStock = 0;
    public $newPhoto;

    public function mount()
    {
        $this->refreshStock();
    }

    public function refreshStock()
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

    public function addMenuItem()
    {
        $this->validate([
            'newName' => 'required|string|max:255',
            'newPrice' => 'required|integer|min:0',
            'newStock' => 'required|integer|min:0',
            'newPhoto' => 'nullable|image|max:2048',
        ]);

        $booth = Booth::where('owner_id', Auth::id())->first();
        abort_if(!$booth, 403, 'Anda belum memiliki booth untuk menambah menu.');

        $photoPath = null;
        if ($this->newPhoto) {
            $photoPath = $this->newPhoto->store('menu-photos', 'public');
        }

        $item = MenuItem::create([
            'booth_id' => $booth->id,
            'name' => $this->newName,
            'price' => $this->newPrice,
            'stock_qty' => $this->newStock,
            'photo' => $photoPath,
        ]);

        $this->stock[$item->id] = $item->stock_qty;
        $this->reset(['newName', 'newPrice', 'newStock', 'newPhoto']);
        session()->flash('message', 'Menu baru berhasil ditambahkan.');
    }

    public function render()
    {
        $items = MenuItem::whereHas('booth', fn($q) => $q->where('owner_id', Auth::id()))->with('booth')->get();
        return view('livewire.penjual.stock-manager', [
            'items' => $items,
        ]);
    }
}
