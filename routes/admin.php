<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartamentController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\TicketResponseController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::name('admin.')->middleware('auth')->group(function() {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::group(['prefix' => 'menu_category', 'as' => 'menu_category.'], function() {
        Route::get('/', [MenuCategoryController::class, 'index'])->name('index')->can('menu_category.index');
        Route::get('/create', [MenuCategoryController::class, 'create'])->name('create')->can('menu_category.create');
        Route::post('/', [MenuCategoryController::class, 'store'])->name('store')->can('menu_category.create');
        Route::get('/{menu_category}/edit', [MenuCategoryController::class, 'edit'])->name('edit')->can('menu_category.edit');
        Route::put('/{menu_category}', [MenuCategoryController::class, 'update'])->name('update')->can('menu_category.edit');
        Route::delete('/{menu_category}', [MenuCategoryController::class, 'destroy'])->name('destroy')->can('menu_category.destroy');
        Route::post('/order', [MenuCategoryController::class, 'order'])->name('order')->can('menu_category.order');
        Route::get('/{menu_category}/restore', [MenuCategoryController::class, 'restore'])->name('restore')->can('menu_category.restore');
    });

    Route::group(['prefix' => 'menu', 'as' => 'menu.'], function() {
        Route::get('/', [MenuController::class, 'index'])->name('index')->can('menu.index');
        Route::get('/create', [MenuController::class, 'create'])->name('create')->can('menu.create');
        Route::post('/', [MenuController::class, 'store'])->name('store')->can('menu.create');
        Route::get('/{menu}/edit', [MenuController::class, 'edit'])->name('edit')->can('menu.edit');
        Route::put('/{menu}', [MenuController::class, 'update'])->name('update')->can('menu.edit');
        Route::delete('/{menu}', [MenuController::class, 'destroy'])->name('destroy')->can('menu.destroy');
        Route::post('/order', [MenuController::class, 'order'])->name('order')->can('menu.order');
        Route::get('/{menu}/restore', [MenuController::class, 'restore'])->name('restore')->can('menu.restore');
    });

    Route::group(['prefix' => 'permission', 'as' => 'permission.'], function() {
        Route::get('/', [PermissionController::class, 'index'])->name('index')->can('permission.index');
        Route::get('/create', [PermissionController::class, 'create'])->name('create')->can('permission.create');
        Route::post('/', [PermissionController::class, 'store'])->name('store')->can('permission.create');
        Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('edit')->can('permission.edit');
        Route::put('/{permission}', [PermissionController::class, 'update'])->name('update')->can('permission.edit');
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy')->can('permission.destroy');
    });

    Route::group(['prefix' => 'role', 'as' => 'role.'], function() {
        Route::get('/', [RoleController::class, 'index'])->name('index')->can('role.index');
        Route::get('/create', [RoleController::class, 'create'])->name('create')->can('role.create');
        Route::post('/', [RoleController::class, 'store'])->name('store')->can('role.create');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit')->can('role.edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update')->can('role.edit');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy')->can('role.destroy');
    });

    Route::group(['prefix' => 'user', 'as' => 'user.'], function() {
        Route::get('/', [UserController::class, 'index'])->name('index')->can('user.index');
        Route::get('/create', [UserController::class, 'create'])->name('create')->can('user.create');
        Route::post('/', [UserController::class, 'store'])->name('store')->can('user.create');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit')->can('user.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update')->can('user.edit');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy')->can('user.destroy');
        Route::get('/{user}/restore', [UserController::class, 'restore'])->name('restore')->can('user.restore');
    });

    Route::group(['prefix' => 'company', 'as' => 'company.'], function() {
        Route::get('/', [CompanyController::class, 'index'])->name('index')->can('company.index');
        Route::get('/create', [CompanyController::class, 'create'])->name('create')->can('company.create');
        Route::post('/', [CompanyController::class, 'store'])->name('store')->can('company.create');
        Route::get('/{company}/edit', [CompanyController::class, 'edit'])->name('edit')->can('company.edit');
        Route::put('/{company}', [CompanyController::class, 'update'])->name('update')->can('company.edit');
        Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('destroy')->can('company.destroy');
        Route::get('/{company}/restore', [CompanyController::class, 'restore'])->name('restore')->can('company.restore');
    });

    Route::group(['prefix' => 'departament', 'as' => 'departament.'], function() {
        Route::get('/', [DepartamentController::class, 'index'])->name('index')->can('departament.index');
        Route::get('/create', [DepartamentController::class, 'create'])->name('create')->can('departament.create');
        Route::post('/', [DepartamentController::class, 'store'])->name('store')->can('departament.create');
        Route::get('/{departament}/edit', [DepartamentController::class, 'edit'])->name('edit')->can('departament.edit');
        Route::put('/{departament}', [DepartamentController::class, 'update'])->name('update')->can('departament.edit');
        Route::delete('/{departament}', [DepartamentController::class, 'destroy'])->name('destroy')->can('departament.destroy');
        Route::get('/{departament}/restore', [DepartamentController::class, 'restore'])->name('restore')->can('departament.restore');
    });

    Route::group(['prefix' => 'category', 'as' => 'category.'], function() {
        Route::get('/', [CategoryController::class, 'index'])->name('index')->can('category.index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create')->can('category.create');
        Route::post('/', [CategoryController::class, 'store'])->name('store')->can('category.create');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit')->can('category.edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update')->can('category.edit');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy')->can('category.destroy');
        Route::get('/{category}/restore', [CategoryController::class, 'restore'])->name('restore')->can('category.restore');
    });

    Route::group(['prefix' => 'subcategory', 'as' => 'subcategory.'], function() {
        Route::get('/', [SubcategoryController::class, 'index'])->name('index')->can('subcategory.index');
        Route::get('/create', [SubcategoryController::class, 'create'])->name('create')->can('subcategory.create');
        Route::post('/', [SubcategoryController::class, 'store'])->name('store')->can('subcategory.create');
        Route::get('/{subcategory}/edit', [SubcategoryController::class, 'edit'])->name('edit')->can('subcategory.edit');
        Route::put('/{subcategory}', [SubcategoryController::class, 'update'])->name('update')->can('subcategory.edit');
        Route::delete('/{subcategory}', [SubcategoryController::class, 'destroy'])->name('destroy')->can('subcategory.destroy');
        Route::get('/{subcategory}/restore', [SubcategoryController::class, 'restore'])->name('restore')->can('subcategory.restore');
    });

    Route::group(['prefix' => 'ticket', 'as' => 'ticket.'], function() {
        Route::get('/', [TicketController::class, 'index'])->name('index')->can('ticket.index');
        Route::get('/create', [TicketController::class, 'create'])->name('create')->can('ticket.create');
        Route::post('/', [TicketController::class, 'store'])->name('store')->can('ticket.create');
        Route::put('/{ticket}', [TicketController::class, 'update'])->name('update')->can('ticket.edit');
        Route::get('/{ticket}', [TicketController::class, 'show'])->name('show')->can('ticket.show')->middleware(['checkTicketAccess']);
    });

    Route::group(['prefix' => 'notification', 'as' => 'notification.'], function() {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/{notification}/redirect', [NotificationController::class, 'redirect'])->name('redirect');
    });

    Route::get('/log', [LogController::class, 'index'])->name('log.index')->can('log.index');
    Route::get('/log/{log}', [LogController::class, 'show'])->name('log.show')->can('log.show');

    Route::get('/profile/{tab}', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/{tab}', [ProfileController::class, 'update'])->name('profile.update');
});
