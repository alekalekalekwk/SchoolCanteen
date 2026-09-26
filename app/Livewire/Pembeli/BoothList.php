<?php

namespace App\Livewire\Pembeli;

use App\Models\Booth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class BoothList extends Component
{
    public function badgeData(int $count): array
    {
        if ($count > 10) {
            return ['bg' => 'bg-[#E3EFE7]', 'text' => 'text-[#3B7A57]'];
        }
        if ($count > 4) {
            return ['bg' => 'bg-[#F5EAD8]', 'text' => 'text-[#A9761F]'];
        }
        return ['bg' => 'bg-[#F5E3DE]', 'text' => 'text-[#A63D2F]'];
    }

    public function render()
    {
        return view('livewire.pembeli.booth-list', [
            'booths' => Booth::withCount(['menuItems as available_menus_count' => function ($query) {
                $query->where('stock_qty', '>', 0);
            }])->get(),
        ]);
    }
}
