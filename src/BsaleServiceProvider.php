<?php

namespace ticketeradigital\bsale;

use Illuminate\Support\ServiceProvider;

class BsaleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/config/bsale.php' => config_path('bsale.php'),
        ]);
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    public function register(): void
    {
        $this->app->bind(Bsale::class, function () {
            return new Bsale();
        });
    }
}
