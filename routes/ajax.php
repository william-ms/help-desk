<?php

use App\Http\Controllers\Ajax\SettingController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/ajax', 'as' => 'ajax.', 'middleware' => ['auth']], function () {

    Route::post('/setting/theme', [SettingController::class, 'set_theme'])->name('setting.theme');
});
