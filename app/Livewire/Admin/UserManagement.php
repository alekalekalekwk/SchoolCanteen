<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class UserManagement extends Component
{
    // Form fields
    public $name = '';
    public $email = '';
    public $nis = '';
    
    // Edit mode
    public $editingUserId = null;
    public $edit_name = '';
    public $edit_nis = '';

    public function render()
    {
        $students = User::where('role', 'pembeli')->orderBy('name')->get();
        return view('livewire.admin.user-management', compact('students'));
    }

    public function createUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nis' => 'nullable|string|unique:users,nis',
        ]);

        try {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'nis' => $this->nis ?: null,
                'password' => Hash::make('password'),
                'role' => 'pembeli',
                'credit_score' => 100,
            ]);

            $this->reset(['name', 'email', 'nis']);
            session()->flash('success', 'Akun siswa berhasil dibuat dengan password default "password".');
        } catch (QueryException $e) {
            $this->addError('nis', 'Terjadi kesalahan basis data (kemungkinan NIS sudah terdaftar).');
        }
    }

    public function editUser($userId)
    {
        $user = User::findOrFail($userId);
        $this->editingUserId = $user->id;
        $this->edit_name = $user->name;
        $this->edit_nis = $user->nis;
    }

    public function cancelEdit()
    {
        $this->reset(['editingUserId', 'edit_name', 'edit_nis']);
    }

    public function updateUser()
    {
        $this->validate([
            'edit_name' => 'required|string|max:255',
            'edit_nis' => 'nullable|string|unique:users,nis,' . $this->editingUserId,
        ]);

        try {
            $user = User::findOrFail($this->editingUserId);
            $user->update([
                'name' => $this->edit_name,
                'nis' => $this->edit_nis ?: null,
            ]);

            $this->cancelEdit();
            session()->flash('success', 'Data siswa berhasil diperbarui.');
        } catch (QueryException $e) {
            $this->addError('edit_nis', 'NIS sudah digunakan oleh siswa lain.');
        }
    }

    public function resetPassword($userId)
    {
        $user = User::findOrFail($userId);
        $user->update([
            'password' => Hash::make('password'),
        ]);

        session()->flash('success', "Password siswa {$user->name} berhasil direset ke 'password'.");
    }
}
