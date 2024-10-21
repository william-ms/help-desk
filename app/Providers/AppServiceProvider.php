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

        view()->composer(['admin.base'], function($view) {

            $User = auth()->user();
//
            if ($User->hasRole(1)) {
                $CategoriesAndMenusForSidebar = MenuCategory::with(['menus' => function ($query) {
                    $query->orderBy('order');
                }])->whereHas('menus')->orderBy('order')->get();
            } else {
                $menus = $User->permissions->merge($User->roles()->first()->permissions)->filter(function ($item) {
                    if (str_contains($item->name, '.index')) {
                        $item->name = str_replace('.index', '', $item->name);
                        return true;
                    }
    
                    return false;
                })->pluck('name');
            
                $menus->push('dashboard');

                $CategoriesAndMenusForSidebar = MenuCategory::with(['menus' => function ($query) use ($menus) {
                    $query->whereIn('route', $menus)->orderBy('order');
                }])->orderBy('order')->get();
            }

            //Cria uma sessão com as configurações do usuário para serem utilizados na view base
            if(!session()->has('UserSettings')) {
                $UserSettings = [];

                foreach ($User->settings as $Setting) {
                    $UserSettings[$Setting->key] = $Setting->value;
                }

                session()->put('UserSettings', $UserSettings);
            }

            $view->with([
                'CategoriesAndMenusForSidebar' => $CategoriesAndMenusForSidebar,
                'UserSettings' => session()->get('UserSettings'),
            ]);
        });
    }
}
