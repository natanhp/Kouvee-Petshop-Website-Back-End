<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\ProductMinReached::class => [
            \App\Listeners\SendRestockNotification::class,
        ],
        \App\Events\GroomingFinishNotif::class => [
            \App\Listeners\SendSMSNotif::class,
        ],
    ];
}
