<?php

namespace App\Providers;

use App\Models\MenuCategory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
        Gate::before(function ($user, $ability) {
            if ($user->hasRole(1)) {
                return true;
            }
        });

        view()->composer(['admin.menu'], function($view) {
            $CategoriesAndMenusForSidebar = MenuCategory::with(['menus' => function($query){
                $query->orderBy('order');
            }])->whereHas('menus')->orderBy('order')->get();

            $view->with([
                'CategoriesAndMenusForSidebar' => $CategoriesAndMenusForSidebar,
            ]);
        });
    }
}
