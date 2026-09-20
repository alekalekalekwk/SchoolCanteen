<?php

namespace App\Livewire\Admin;

use App\Models\Booth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class BoothManagement extends Component
{
    // Booth form
    public $name = '';
    public $description = '';
    public $owner_id = '';

    // New Seller form
    public $seller_name = '';
    public $seller_email = '';

    // Edit mode
    public $editingBoothId = null;

    public function render()
    {
        return view('livewire.admin.booth-management', [
            'booths' => Booth::with('owner')->orderBy('name')->get(),
            'sellers' => User::where('role', 'penjual')->orderBy('name')->get(),
        ]);
    }

    public function createBooth()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'owner_id' => 'required|exists:users,id',
        ]);

        Booth::create([
            'name' => $this->name,
            'description' => $this->description,
            'owner_id' => $this->owner_id,
        ]);

        $this->reset(['name', 'description', 'owner_id']);
        session()->flash('success', 'Booth baru berhasil didaftarkan.');
    }

    public function createSeller()
    {
        $this->validate([
            'seller_name' => 'required|string|max:255',
            'seller_email' => 'required|email|unique:users,email',
        ]);

        $user = User::create([
            'name' => $this->seller_name,
            'email' => $this->seller_email,
            'password' => Hash::make('password'),
            'role' => 'penjual',
        ]);

        $this->owner_id = $user->id;
        $this->reset(['seller_name', 'seller_email']);
        session()->flash('success', 'Akun penjual baru berhasil dibuat.');
    }

    public function editBooth($boothId)
    {
        $booth = Booth::findOrFail($boothId);
        $this->editingBoothId = $booth->id;
        $this->name = $booth->name;
        $this->description = $booth->description;
        $this->owner_id = $booth->owner_id;
    }

    public function updateBooth()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'owner_id' => 'required|exists:users,id',
        ]);

        $booth = Booth::findOrFail($this->editingBoothId);
        $booth->update([
            'name' => $this->name,
            'description' => $this->description,
            'owner_id' => $this->owner_id,
        ]);

        $this->cancelEdit();
        session()->flash('success', 'Data booth berhasil diperbarui.');
    }

    public function cancelEdit()
    {
        $this->reset(['editingBoothId', 'name', 'description', 'owner_id']);
    }
}
