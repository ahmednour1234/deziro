<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('notification.add.after', 'App\Listeners\PushNotification@afterNotificationAdded');

        Event::listen('order.shipped.after', 'App\Listeners\PushNotification@afterShippedOrder');

        Event::listen('order.delivered.after', 'App\Listeners\PushNotification@afterDeliveredOrder');

        Event::listen('order.canceled.after', 'App\Listeners\PushNotification@afterCanceledOrder');


    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
