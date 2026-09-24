<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>{{ config('app.name') }}</title>
</head>
<body class="antialiased">
    @auth
        <nav class="bg-white border-b border-gray-200 py-3 px-6">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-6">
                    <span class="font-bold text-lg text-indigo-600">Kantin</span>
                    
                    @if(auth()->user()->role === 'pembeli')
                        <a href="{{ route('dashboard') }}" wire:navigate class="text-gray-600 hover:text-indigo-600 text-sm">Booth</a>
                        <a href="{{ route('orders.history') }}" wire:navigate class="text-gray-600 hover:text-indigo-600 text-sm">Pesanan Saya</a>
                    @elseif(auth()->user()->role === 'penjual')
                        <a href="{{ route('penjual.orders') }}" wire:navigate class="text-gray-600 hover:text-indigo-600 text-sm">Order Masuk</a>
                        <a href="{{ route('penjual.stock') }}" wire:navigate class="text-gray-600 hover:text-indigo-600 text-sm">Kelola Stok</a>
                    @elseif(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" wire:navigate class="text-gray-600 hover:text-indigo-600 text-sm">Laporan</a>
                        <a href="{{ route('admin.booths') }}" wire:navigate class="text-gray-600 hover:text-indigo-600 text-sm">Kelola Booth</a>
                        <a href="{{ route('admin.users') }}" wire:navigate class="text-gray-600 hover:text-indigo-600 text-sm">Kelola Siswa</a>
                    @endif
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:underline">Logout</button>
                    </form>
                </div>
            </div>
        </nav>
    @endauth
    
    {{ $slot }}
</body>
</html>