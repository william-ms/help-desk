<?php

namespace App\Providers;

use App\Observers\LogObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Spatie\Permission\Models\Permission;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
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
        \App\Models\MenuCategory::observe(LogObserver::class);
        \App\Models\Menu::observe(LogObserver::class);
        \Spatie\Permission\Models\Permission::observe(LogObserver::class);
        \Spatie\Permission\Models\Role::observe(LogObserver::class);
    }
}
