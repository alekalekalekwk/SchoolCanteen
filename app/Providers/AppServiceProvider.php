<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\MenuItem;
use App\Policies\OrderPolicy;
use App\Policies\MenuItemPolicy;
use App\Models\Booth;
use App\Models\CreditReport;
use App\Policies\BoothPolicy;
use App\Policies\CreditReportPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(MenuItem::class, MenuItemPolicy::class);
        Gate::policy(Booth::class, BoothPolicy::class);
        Gate::policy(CreditReport::class, CreditReportPolicy::class);
    }
}
