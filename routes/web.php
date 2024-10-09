<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [DashboardController::class, 'index']);
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
