<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Apis\TinyMCEController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [DashboardController::class, 'index']);

    Route::post('tinymce/add/image/', [TinyMCEController::class, 'add_images'])->name('tinymce.add.images');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/ajax.php';
