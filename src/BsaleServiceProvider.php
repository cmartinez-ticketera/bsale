<?php

namespace ticketeradigital\bsale;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ticketeradigital\bsale\Providers\EventServiceProvider;

class BsaleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/config/bsale.php' => config_path('bsale.php'),
        ]);
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }

    protected function routeConfiguration()
    {
        return [
            'prefix' => config('bsale.routePrefix'),
            'middleware' => config('bsale.routeMiddleware'),
        ];
    }

    public function register(): void
    {
        $this->app->bind(Bsale::class, function () {
            return new Bsale();
        });
        $this->app->register(EventServiceProvider::class);
    }
}
