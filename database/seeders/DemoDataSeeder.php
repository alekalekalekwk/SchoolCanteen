<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Booth;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        User::create([
            'name' => 'Admin Kantin',
            'email' => 'admin@kantin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. 3 Penjual with 1 booth and 3-5 menu items each
        $penjualData = [
            ['name' => 'Bu Siti', 'email' => 'siti@kantin.com', 'booth' => 'Warung Bu Siti', 'menus' => [
                ['name' => 'Mie Tek Tek', 'price' => 12000, 'stock_qty' => 30],
                ['name' => 'Es Teh Manis', 'price' => 3000, 'stock_qty' => 50],
                ['name' => 'Bakso Kuah', 'price' => 15000, 'stock_qty' => 25],
            ]],
            ['name' => 'Pak Joko', 'email' => 'joko@kantin.com', 'booth' => 'Pempek Pak Joko', 'menus' => [
                ['name' => 'Pempek Kapal Selam', 'price' => 10000, 'stock_qty' => 20],
                ['name' => 'Pempek Lenjer', 'price' => 8000, 'stock_qty' => 30],
                ['name' => 'Es Jeruk', 'price' => 4000, 'stock_qty' => 40],
                ['name' => 'Tekwan', 'price' => 12000, 'stock_qty' => 15],
            ]],
            ['name' => 'Mbak Rini', 'email' => 'rini@kantin.com', 'booth' => 'Aneka Juice & Snack', 'menus' => [
                ['name' => 'Juice Alpukat', 'price' => 8000, 'stock_qty' => 20],
                ['name' => 'Roti Bakar Coklat', 'price' => 7000, 'stock_qty' => 25],
                ['name' => 'Pisang Goreng', 'price' => 5000, 'stock_qty' => 35],
                ['name' => 'Es Buah', 'price' => 7000, 'stock_qty' => 30],
                ['name' => 'Juice Mangga', 'price' => 9000, 'stock_qty' => 20],
            ]],
        ];

        foreach ($penjualData as $p) {
            $user = User::create([
                'name' => $p['name'],
                'email' => $p['email'],
                'password' => Hash::make('password'),
                'role' => 'penjual',
            ]);

            $booth = Booth::create([
                'owner_id' => $user->id,
                'name' => $p['booth'],
                'description' => 'Booth kuliner ' . $p['name'],
            ]);

            foreach ($p['menus'] as $menu) {
                MenuItem::create([
                    'booth_id' => $booth->id,
                    'name' => $menu['name'],
                    'price' => $menu['price'],
                    'stock_qty' => $menu['stock_qty'],
                ]);
            }
        }

        // 3. 20 Pembeli (siswa) with nis and credit_score = 100
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name' => 'Siswa ' . $i,
                'email' => 'siswa' . $i . '@kantin.com',
                'password' => Hash::make('password'),
                'role' => 'pembeli',
                'nis' => '100' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'credit_score' => 100,
            ]);
        }
    }
}
