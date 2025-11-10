<?php

namespace App\Providers;

use App\Facades\Cart;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Foundation\AliasLoader;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFacades();
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
     * Register cart as a singleton.
     *
     * @return void
     */
    protected function registerFacades(): void
    {
        $loader = AliasLoader::getInstance();

        $loader->alias('cart', Cart::class);

        $this->app->singleton('cart', \App\Helpers\Cart::class);
    }
}
