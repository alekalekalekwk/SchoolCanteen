<?php

namespace App\Livewire\Penjual;

use App\Models\MenuItem;
use App\Models\Booth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class StockManager extends Component
{
    use WithFileUploads;

    public array $stock = [];
    public array $originalStock = [];

    // New Menu form fields
    public $newName = '';
    public $newPrice;
    public $newStock = 0;
    public $newPhoto;

    // Photo replace
    public $newPhotos = [];
    public $editingItem = null;

    public function mount()
    {
        $this->refreshStock();
    }

    public function refreshStock()
    {
        $items = MenuItem::whereHas('booth', fn($q) => $q->where('owner_id', Auth::id()))->get();
        foreach ($items as $item) {
            $this->stock[$item->id] = $item->stock_qty;
            $this->originalStock[$item->id] = $item->stock_qty;
        }
    }

    public function saveStock(int $menuItemId)
    {
        $this->validate([
            "stock.$menuItemId" => 'required|integer|min:0',
        ]);

        $delta = $this->stock[$menuItemId] - ($this->originalStock[$menuItemId] ?? 0);

        if ($delta === 0) {
            return;
        }

        DB::transaction(function () use ($menuItemId, $delta) {
            $menuItem = MenuItem::where('id', $menuItemId)->lockForUpdate()->firstOrFail();
            $this->authorize('update', $menuItem);

            if ($delta > 0) {
                $menuItem->increment('stock_qty', $delta);
            } else {
                $menuItem->decrement('stock_qty', abs($delta));
            }

            $menuItem->refresh();
            $this->stock[$menuItemId] = $menuItem->stock_qty;
            $this->originalStock[$menuItemId] = $menuItem->stock_qty;
        });

        $this->dispatch('notify', message: 'Stok berhasil diperbarui.');
    }

    public function replacePhoto(int $menuItemId)
    {
        $this->validate([
            "newPhotos.$menuItemId" => 'required|image|max:2048',
        ]);

        $item = MenuItem::findOrFail($menuItemId);
        $this->authorize('update', $item);

        if ($item->photo && Storage::disk('public')->exists($item->photo)) {
            Storage::disk('public')->delete($item->photo);
        }

        $path = $this->newPhotos[$menuItemId]->store('menu-photos', 'public');
        $item->update(['photo' => $path]);

        $this->newPhotos[$menuItemId] = null;
        $this->editingItem = null;
        $this->dispatch('notify', message: 'Foto berhasil diganti.');
    }

    public function addMenuItem()
    {
        $this->validate([
            'newName'  => 'required|string|max:255',
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
            'name'     => $this->newName,
            'price'    => $this->newPrice,
            'stock_qty'=> $this->newStock,
            'photo'    => $photoPath,
        ]);

        $this->stock[$item->id] = $item->stock_qty;
        $this->originalStock[$item->id] = $item->stock_qty;
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
