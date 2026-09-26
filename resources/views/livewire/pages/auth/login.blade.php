<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $user = \Illuminate\Support\Facades\Auth::user();
        $redirectTo = match ($user->role) {
            'penjual' => route('penjual.orders', absolute: false),
            'admin'   => route('admin.dashboard', absolute: false),
            default   => route('dashboard', absolute: false),
        };
        $this->redirect($redirectTo, navigate: true);
    }
}; ?>
<div class="relative min-h-screen flex items-center justify-center font-['Space_Grotesk']" style="background-image: url('{{ asset('images/wikrama.jpeg') }}'); background-size: cover; background-position: center;">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="relative w-full max-w-md p-12 bg-[#F4782A] rounded-[20px] border-[3px] border-black">
        <h2 class="text-4xl font-bold text-white mb-4">Masuk ke Kantin Kita</h2>
        <p class="text-xl font-bold text-white mb-8">Gunakan akun yang sudah dibuatkan admin</p>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login" class="space-y-6">
            <!-- Email -->
            <div>
                <label for="email" class="block text-2xl font-bold text-white mb-2">Email</label>
                <input wire:model="form.email" id="email" type="email" required autocomplete="username"
                       class="w-full h-12 px-4 bg-white rounded-[10px] border-2 border-black text-xl text-black/60"
                       placeholder="nama@kantin.com" />
                <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-white" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-2xl font-bold text-white mb-2">Password</label>
                <input wire:model="form.password" id="password" type="password" required autocomplete="current-password"
                       class="w-full h-12 px-4 bg-white rounded-[10px] border-2 border-black text-xl text-black/60"
                       placeholder="********" />
                <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-white" />
            </div>

            <!-- Submit button -->
            <button type="submit" class="w-full h-16 mt-6 bg-[#3C8DB3] rounded-2xl flex items-center justify-center text-3xl font-bold text-white">Masuk</button>
        </form>
    </div>
</div>
