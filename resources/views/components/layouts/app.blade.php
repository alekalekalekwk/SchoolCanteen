<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600&family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>{{ config('app.name') }}</title>
</head>
<body class="antialiased bg-white text-black min-h-screen">
    @auth
        <nav class="bg-brand-blue py-4 px-6 shadow-sm">
            <div class="max-w-7xl mx-auto flex justify-between items-center text-white">
                <div class="flex items-center gap-6">
                    <span class="font-bold text-2xl font-heading tracking-tight">Kantin Kita</span>
                    
                    @php
                        $role = auth()->user()->role;
                        $navs = match($role) {
                            'pembeli' => [['route' => 'dashboard', 'label' => 'Booth'], ['route' => 'orders.history', 'label' => 'Pesanan Saya']],
                            'penjual' => [['route' => 'penjual.orders', 'label' => 'Order Masuk'], ['route' => 'penjual.stock', 'label' => 'Kelola Stok']],
                            'admin'   => [['route' => 'admin.dashboard', 'label' => 'Laporan'], ['route' => 'admin.booths', 'label' => 'Kelola Booth'], ['route' => 'admin.users', 'label' => 'Kelola Siswa']],
                            default => [],
                        };
                    @endphp

                    <div class="flex gap-2">
                        @foreach($navs as $nav)
                            <a href="{{ route($nav['route']) }}" wire:navigate 
                               class="px-4 py-2 rounded-lg text-sm font-heading font-bold transition-all
                               {{ request()->routeIs($nav['route']) ? 'bg-[#F4782A] border border-[#FBF3E4] text-white' : 'text-white/85 hover:bg-white/10' }}">
                                {{ $nav['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium border-r border-white/30 pr-4">Halo, <span class="font-bold">{{ auth()->user()->name }}</span></span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm border border-white/30 hover:bg-[#F4782A] px-3 py-1.5 rounded-md font-medium transition-colors">Logout</button>
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
