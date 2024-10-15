<?php

use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartamentController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::name('admin.')->middleware('auth')->group(function() {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::group(['prefix' => 'menu_category', 'as' => 'menu_category.'], function() {
        Route::get('/', [MenuCategoryController::class, 'index'])->name('index')->can('menu_category.index');
        Route::get('/create', [MenuCategoryController::class, 'create'])->name('create')->can('menu_category.create');
        Route::post('/', [MenuCategoryController::class, 'store'])->name('store')->can('menu_category.create');
        Route::get('/edit/{menu_category}', [MenuCategoryController::class, 'edit'])->name('edit')->can('menu_category.edit');
        Route::put('/{menu_category}', [MenuCategoryController::class, 'update'])->name('update')->can('menu_category.edit');
        Route::delete('/{menu_category}', [MenuCategoryController::class, 'destroy'])->name('destroy')->can('menu_category.destroy');
        Route::post('/order', [MenuCategoryController::class, 'order'])->name('order')->can('menu_category.order');
        Route::get('/restore/{menu_category}', [MenuCategoryController::class, 'restore'])->name('restore')->can('menu_category.restore');
    });

    Route::group(['prefix' => 'menu', 'as' => 'menu.'], function() {
        Route::get('/', [MenuController::class, 'index'])->name('index')->can('menu.index');
        Route::get('/create', [MenuController::class, 'create'])->name('create')->can('menu.create');
        Route::post('/', [MenuController::class, 'store'])->name('store')->can('menu.create');
        Route::get('/edit/{menu}', [MenuController::class, 'edit'])->name('edit')->can('menu.edit');
        Route::put('/{menu}', [MenuController::class, 'update'])->name('update')->can('menu.edit');
        Route::delete('/{menu}', [MenuController::class, 'destroy'])->name('destroy')->can('menu.destroy');
        Route::post('/order', [MenuController::class, 'order'])->name('order')->can('menu.order');
        Route::get('/restore/{menu}', [MenuController::class, 'restore'])->name('restore')->can('menu.restore');
    });

    Route::group(['prefix' => 'permission', 'as' => 'permission.'], function() {
        Route::get('/', [PermissionController::class, 'index'])->name('index')->can('permission.index');
        Route::get('/create', [PermissionController::class, 'create'])->name('create')->can('permission.create');
        Route::post('/', [PermissionController::class, 'store'])->name('store')->can('permission.create');
        Route::get('/edit/{permission}', [PermissionController::class, 'edit'])->name('edit')->can('permission.edit');
        Route::put('/{permission}', [PermissionController::class, 'update'])->name('update')->can('permission.edit');
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy')->can('permission.destroy');
    });

    Route::group(['prefix' => 'role', 'as' => 'role.'], function() {
        Route::get('/', [RoleController::class, 'index'])->name('index')->can('role.index');
        Route::get('/create', [RoleController::class, 'create'])->name('create')->can('role.create');
        Route::post('/', [RoleController::class, 'store'])->name('store')->can('role.create');
        Route::get('/edit/{role}', [RoleController::class, 'edit'])->name('edit')->can('role.edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update')->can('role.edit');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy')->can('role.destroy');
    });

    Route::group(['prefix' => 'user', 'as' => 'user.'], function() {
        Route::get('/', [UserController::class, 'index'])->name('index')->can('user.index');
        Route::get('/create', [UserController::class, 'create'])->name('create')->can('user.create');
        Route::post('/', [UserController::class, 'store'])->name('store')->can('user.create');
        Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit')->can('user.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update')->can('user.edit');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy')->can('user.destroy');
        Route::get('/restore/{user}', [UserController::class, 'restore'])->name('restore')->can('user.restore');
    });

    Route::group(['prefix' => 'company', 'as' => 'company.'], function() {
        Route::get('/', [CompanyController::class, 'index'])->name('index')->can('company.index');
        Route::get('/create', [CompanyController::class, 'create'])->name('create')->can('company.create');
        Route::post('/', [CompanyController::class, 'store'])->name('store')->can('company.create');
        Route::get('/edit/{company}', [CompanyController::class, 'edit'])->name('edit')->can('company.edit');
        Route::put('/{company}', [CompanyController::class, 'update'])->name('update')->can('company.edit');
        Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('destroy')->can('company.destroy');
        Route::get('/restore/{company}', [CompanyController::class, 'restore'])->name('restore')->can('company.restore');
    });

    Route::group(['prefix' => 'departament', 'as' => 'departament.'], function() {
        Route::get('/', [DepartamentController::class, 'index'])->name('index')->can('departament.index');
        Route::get('/create', [DepartamentController::class, 'create'])->name('create')->can('departament.create');
        Route::post('/', [DepartamentController::class, 'store'])->name('store')->can('departament.create');
        Route::get('/edit/{departament}', [DepartamentController::class, 'edit'])->name('edit')->can('departament.edit');
        Route::put('/{departament}', [DepartamentController::class, 'update'])->name('update')->can('departament.edit');
        Route::delete('/{departament}', [DepartamentController::class, 'destroy'])->name('destroy')->can('departament.destroy');
        Route::get('/restore/{departament}', [DepartamentController::class, 'restore'])->name('restore')->can('departament.restore');
    });

    Route::get('/log', [LogController::class, 'index'])->name('log.index')->can('log.index');;
    Route::get('/log/{log}', [LogController::class, 'show'])->name('log.show')->can('log.show');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
});
