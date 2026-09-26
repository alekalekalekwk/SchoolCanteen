<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600&family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>{{ config('app.name') }}</title>
</head>
<body class="antialiased bg-gray-50">
    @auth
        <nav class="bg-brand-blue py-4 px-6 shadow-sm">
            <div class="max-w-7xl mx-auto flex justify-between items-center text-white">
                <div class="flex items-center space-x-8">
                    <div class="flex items-center space-x-2">
                        <span class="font-bold text-2xl font-heading tracking-tight text-white">Kantin</span>
                    </div>
                    
                    <div class="flex space-x-6">
                        @if(auth()->user()->role === 'pembeli')
                            <a href="{{ route('dashboard') }}" wire:navigate class="{{ request()->routeIs('dashboard') ? 'font-semibold text-brand-orange' : 'text-white hover:text-brand-cream' }} text-sm transition-colors">Booth</a>
                            <a href="{{ route('orders.history') }}" wire:navigate class="{{ request()->routeIs('orders.history') ? 'font-semibold text-brand-orange' : 'text-white hover:text-brand-cream' }} text-sm transition-colors">Pesanan Saya</a>
                        @elseif(auth()->user()->role === 'penjual')
                            <a href="{{ route('penjual.orders') }}" wire:navigate class="{{ request()->routeIs('penjual.orders') ? 'font-semibold text-brand-orange' : 'text-white hover:text-brand-cream' }} text-sm transition-colors">Order Masuk</a>
                            <a href="{{ route('penjual.stock') }}" wire:navigate class="{{ request()->routeIs('penjual.stock') ? 'font-semibold text-brand-orange' : 'text-white hover:text-brand-cream' }} text-sm transition-colors">Kelola Stok</a>
                        @elseif(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" wire:navigate class="{{ request()->routeIs('admin.dashboard') ? 'font-semibold text-brand-orange' : 'text-white hover:text-brand-cream' }} text-sm transition-colors">Laporan</a>
                            <a href="{{ route('admin.booths') }}" wire:navigate class="{{ request()->routeIs('admin.booths') ? 'font-semibold text-brand-orange' : 'text-white hover:text-brand-cream' }} text-sm transition-colors">Kelola Booth</a>
                            <a href="{{ route('admin.users') }}" wire:navigate class="{{ request()->routeIs('admin.users') ? 'font-semibold text-brand-orange' : 'text-white hover:text-brand-cream' }} text-sm transition-colors">Kelola Siswa</a>
                        @endif
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="text-sm font-medium border-r border-white/30 pr-4">
                        Halo, <span class="font-bold">{{ auth()->user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm bg-white/10 hover:bg-brand-orange hover:text-white px-3 py-1.5 rounded-md font-medium transition-colors">Logout</button>
                    </form>
                </div>
            </div>
        </nav>
    @endauth
    
    <main class="max-w-7xl mx-auto my-8 p-8">
        {{ $slot }}
    </main>
</body>
</html>