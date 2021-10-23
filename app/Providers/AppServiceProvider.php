<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Contracts\ProductWarehouse;
use App\Services\ProductWarehouseService;
use Illuminate\Pagination\Paginator;
use App\Models\Order;
use App\Observers\OrderObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        ProductWarehouse::class => ProductWarehouseService::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [ProductWarehouseService::class];
    }
}
