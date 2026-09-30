<?php

use App\Livewire\Admin\ReportOverview;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\BoothManagement;
use App\Livewire\Admin\ScheduleManagement;
use App\Livewire\Penjual\IncomingOrders;
use App\Livewire\Penjual\StockManager;
use App\Livewire\Pembeli\BoothList;
use App\Livewire\Pembeli\MenuOrderForm;
use App\Livewire\Pembeli\OrderHistory;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', ReportOverview::class)->name('admin.dashboard');
    Route::get('/admin/users', UserManagement::class)->name('admin.users');
    Route::get('/admin/booths', BoothManagement::class)->name('admin.booths');
    Route::get('/admin/schedules', ScheduleManagement::class)->name('admin.schedules');
});

Route::middleware(['auth', 'role:pembeli'])->group(function () {
    Route::get('/dashboard', BoothList::class)->name('dashboard');
    Route::get('/booth/{booth}', MenuOrderForm::class)->name('booth.show');
    Route::get('/pesanan-saya', OrderHistory::class)->name('orders.history');
});

Route::middleware(['auth', 'role:penjual'])->group(function () {
    Route::get('/penjual/orders', IncomingOrders::class)->name('penjual.orders');
    Route::get('/penjual/stock', StockManager::class)->name('penjual.stock');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
