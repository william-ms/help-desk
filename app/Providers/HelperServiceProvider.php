<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

require_once app_path('Helpers/log.php');
require_once app_path('Helpers/macros.php');

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
