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
            if (auth()->user()->hasRole(1)) {
                $CategoriesAndMenusForSidebar = MenuCategory::with(['menus' => function ($query) {
                    $query->orderBy('order');
                }])->whereHas('menus')->orderBy('order')->get();
            } else {
                $permissions = auth()->user()->permissions->filter(function ($item) {
                    if (str_contains($item->name, '.index')) {
                        $item->name = str_replace('.index', '', $item->name);
                        return true;
                    }
    
                    return false;
                })->pluck('name');
            
                $permissions->push('dashboard');

                $CategoriesAndMenusForSidebar = MenuCategory::with(['menus' => function ($query) use ($permissions) {
                    $query->whereIn('route', $permissions)->orderBy('order');
                }])->orderBy('order')->get();
            }
            
            $view->with([
                'CategoriesAndMenusForSidebar' => $CategoriesAndMenusForSidebar,
            ]);
        });
    }
}
