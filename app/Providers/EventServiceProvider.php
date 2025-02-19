<?php

namespace App\Providers;

use App\Observers\LogObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
        \App\Models\Permission::observe(LogObserver::class);
        \App\Models\Role::observe(LogObserver::class);
        \App\Models\User::observe(LogObserver::class);
        \App\Models\Company::observe(LogObserver::class);
        \App\Models\Departament::observe(LogObserver::class);
        \App\Models\Category::observe(LogObserver::class);
        \App\Models\Subcategory::observe(LogObserver::class);
    }
}
