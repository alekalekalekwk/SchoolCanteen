<?php

namespace App\Livewire\Pembeli;

use App\Models\Booth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class BoothList extends Component
{
    public function render()
    {
        return view('livewire.pembeli.booth-list', [
            'booths' => Booth::withCount(['menuItems as available_menus_count' => function ($query) {
                $query->where('stock_qty', '>', 0);
            }])->get(),
        ]);
    }
}
