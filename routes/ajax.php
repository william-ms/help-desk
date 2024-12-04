<?php

use App\Http\Controllers\Ajax\SettingController;
use App\Http\Controllers\Ajax\TicketController;
use App\Http\Controllers\Ajax\TicketResponseController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/ajax', 'as' => 'ajax.', 'middleware' => ['auth']], function () {

    Route::post('/setting/theme', [SettingController::class, 'set_theme'])->name('setting.theme');

    Route::post('/ticket', [TicketController::class, 'get_automatic_response'])->name('ticket.get_automatic_response');
    Route::post('/ticket_response', [TicketResponseController::class, 'store'])->name('ticket_response.store');
});
