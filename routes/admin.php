<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PermissionController;
use Illuminate\Support\Facades\Route;

Route::name('admin.')->middleware('auth')->group(function() {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::group(['prefix' => 'menu_category', 'as' => 'menu_category.'], function() {
        Route::get('/', [MenuCategoryController::class, 'index'])->name('index')->middleware('permission:menu_category.index');
        Route::get('/create', [MenuCategoryController::class, 'create'])->name('create')->middleware('permission:menu_category.create');
        Route::post('/', [MenuCategoryController::class, 'store'])->name('store')->middleware('permission:menu_category.store');
        Route::get('/edit/{menu_category}', [MenuCategoryController::class, 'edit'])->name('edit')->middleware('permission:menu_category.edit');
        Route::put('/{menu_category}', [MenuCategoryController::class, 'update'])->name('update')->middleware('permission:menu_category.update');
        Route::delete('/{menu_category}', [MenuCategoryController::class, 'destroy'])->name('destroy')->middleware('permission:menu_category.destroy');
        Route::post('/order', [MenuCategoryController::class, 'order'])->name('order')->middleware('permission:menu_category.order');
        Route::get('/restore/{menu_category}', [MenuCategoryController::class, 'restore'])->name('restore')->middleware('permission:menu_category.restore');
    });

    Route::group(['prefix' => 'menu', 'as' => 'menu.'], function() {
        Route::get('/', [MenuController::class, 'index'])->name('index')->middleware('permission:menu.index');
        Route::get('/create', [MenuController::class, 'create'])->name('create')->middleware('permission:menu.create');
        Route::post('/', [MenuController::class, 'store'])->name('store')->middleware('permission:menu.store');
        Route::get('/edit/{menu}', [MenuController::class, 'edit'])->name('edit')->middleware('permission:menu.edit');
        Route::put('/{menu}', [MenuController::class, 'update'])->name('update')->middleware('permission:menu.update');
        Route::delete('/{menu}', [MenuController::class, 'destroy'])->name('destroy')->middleware('permission:menu.destroy');
        Route::post('/order', [MenuController::class, 'order'])->name('order')->middleware('permission:menu.order');
        Route::get('/restore/{menu}', [MenuController::class, 'restore'])->name('restore')->middleware('permission:menu.restore');
    });


    // Route::resource('menu', MenuController::class);
    // Route::post('/menu/order', [MenuController::class, 'order'])->name('menu.order');
    // Route::get('/menu/restore/{menu}', [MenuController::class, 'restore'])->name('menu.restore');
    
    Route::resource('permission', PermissionController::class);
    Route::get('/permission/restore/{permission}', [PermissionController::class, 'restore'])->name('permission.restore');
    
    Route::resource('role', RoleController::class);
    Route::get('/role/restore/{role}', [RoleController::class, 'restore'])->name('role.restore');



    Route::get('/log', [LogController::class, 'index'])->name('log.index');
    Route::get('/log/{log}', [LogController::class, 'show'])->name('log.show');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
});
