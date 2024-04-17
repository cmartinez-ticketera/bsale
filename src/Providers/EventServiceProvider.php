<?php

namespace ticketeradigital\bsale\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use ticketeradigital\bsale\Events\ResourceUpdated;
use ticketeradigital\bsale\Listeners\UpdateResource;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ResourceUpdated::class => [
            UpdateResource::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
