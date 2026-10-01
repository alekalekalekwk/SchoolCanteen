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

<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-white">
    {{-- Left column: Branding with solid brand-blue & clean inline SVG canteen illustrations --}}
    <div class="relative min-h-[320px] lg:min-h-screen flex items-center justify-center p-8 lg:p-12 overflow-hidden bg-brand-blue">
        {{-- SVG Decorative Line Art Kantin (Full Cover, Absolute, slice to prevent shrinkage on wide/mobile aspect ratio) --}}
        <svg class="absolute inset-0 w-full h-full pointer-events-none"
             viewBox="0 0 600 800"
             preserveAspectRatio="xMidYMid slice"
             fill="none"
             xmlns="http://www.w3.org/2000/svg">
            {{-- Piring & Sendok Garpu (Kanan Atas) --}}
            <g stroke="#FBF3E4" stroke-opacity="0.2" stroke-width="2">
                <circle cx="500" cy="140" r="70" />
                <circle cx="500" cy="140" r="50" stroke-dasharray="6 6" />
                <path d="M470 90 L530 190" stroke-linecap="round" />
                <path d="M530 90 L470 190" stroke-linecap="round" />
            </g>

            {{-- Icon 1: Tabler Bowl-Chopsticks (Kiri Bawah) --}}
            <g transform="translate(60, 600) scale(6.5)" stroke="#FBF3E4" stroke-opacity="0.22" stroke-width="0.38" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 11h16a1 1 0 0 1 1 1v.5c0 1.5 -2.517 5.573 -4 6.5v1a1 1 0 0 1 -1 1h-8a1 1 0 0 1 -1 -1v-1c-1.687 -1.054 -4 -5 -4 -6.5v-.5a1 1 0 0 1 1 -1" />
                <path d="M19 7l-14 1" />
                <path d="M19 2l-14 3" />
            </g>

            {{-- Icon 2: Tabler Salad (Kanan Bawah) --}}
            <g transform="translate(440, 620) scale(6)" stroke="#FBF3E4" stroke-opacity="0.2" stroke-width="0.35" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 11h16a1 1 0 0 1 1 1v.5c0 1.5 -2.517 5.573 -4 6.5v1a1 1 0 0 1 -1 1h-8a1 1 0 0 1 -1 -1v-1c-1.687 -1.054 -4 -5 -4 -6.5v-.5a1 1 0 0 1 1 -1" />
                <path d="M18.5 11c.351 -1.017 .426 -2.236 .5 -3.714v-1.286h-2.256c-2.83 0 -4.616 .804 -5.64 2.076" />
                <path d="M5.255 11.008a12.204 12.204 0 0 1 -.255 -2.008v-1h1.755c.98 0 1.801 .124 2.479 .35" />
                <path d="M8 8l1 -4l4 2.5" />
                <path d="M13 11v-.5a2.5 2.5 0 1 0 -5 0v.5" />
            </g>

            {{-- Gelembung & Bintang Aksen Halus --}}
            <circle cx="80" cy="180" r="14" stroke="#F4782A" stroke-opacity="0.2" stroke-width="2" />
            <circle cx="520" cy="400" r="8" stroke="#FBF3E4" stroke-opacity="0.15" stroke-width="2" />
            <path d="M120 380 Q120 395 135 395 Q120 395 120 410 Q120 395 120 380 Z" fill="#F4782A" fill-opacity="0.2" />
        </svg>

        {{-- Branding Text Content (Sharp & High Contrast) --}}
        <div class="relative z-10 max-w-md text-center lg:text-left text-white space-y-4">
            <span class="inline-block px-3 py-1 rounded-full bg-white/15 text-white text-xs font-sans tracking-wide">
                Akun dibuatkan oleh Admin Sekolah
            </span>
            <h1 class="text-4xl lg:text-5xl font-bold font-heading tracking-tight text-white">
                Kantin Kita
            </h1>
            <p class="text-base lg:text-lg text-white/90 font-sans leading-relaxed">
                Sistem pre-order makanan dan minuman kantin sekolah. Pesan lebih awal, kurangi antrean istirahat.
            </p>
        </div>
    </div>

    {{-- Right column: Standard white Card login form --}}
    <div class="flex items-center justify-center p-6 sm:p-10 lg:p-16 bg-[#FAFAFA]">
        <div class="w-full max-w-md">
            <x-card class="p-8 sm:p-10 bg-white">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold font-heading text-black">
                        Masuk ke Akun
                    </h2>
                    <p class="text-sm text-gray-500 font-sans mt-1">
                        Masukkan email dan password yang diberikan pengelola kantin.
                    </p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form wire:submit="login" class="space-y-5">
                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5 font-sans">
                            Email
                        </label>
                        <input wire:model="form.email"
                               id="email"
                               type="email"
                               required
                               autofocus
                               autocomplete="username"
                               placeholder="nama@kantin.com"
                               class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-sans text-black focus:outline-none focus:border-[#F4782A] transition-colors" />
                        <x-input-error :messages="$errors->get('form.email')" class="mt-1 text-xs text-[#A63D2F]" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5 font-sans">
                            Password
                        </label>
                        <input wire:model="form.password"
                               id="password"
                               type="password"
                               required
                               autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-sans text-black focus:outline-none focus:border-[#F4782A] transition-colors" />
                        <x-input-error :messages="$errors->get('form.password')" class="mt-1 text-xs text-[#A63D2F]" />
                    </div>

                    {{-- Submit button --}}
                    <div class="pt-2">
                        <x-button variant="primary" type="submit" class="w-full justify-center py-3 text-base">
                            Masuk Sekarang
                        </x-button>
                    </div>

                    {{-- Forgot password link --}}
                    @if (Route::has('password.request'))
                        <div class="text-center pt-2">
                            <a href="{{ route('password.request') }}"
                               wire:navigate
                               class="text-xs text-gray-500 hover:text-[#F4782A] font-sans transition-colors">
                                Lupa password?
                            </a>
                        </div>
                    @endif
                </form>
            </x-card>
        </div>
    </div>
</div>
