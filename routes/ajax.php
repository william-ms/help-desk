<?php

use App\Http\Controllers\Ajax\NotificationController;
use App\Http\Controllers\Ajax\SettingController;
use App\Http\Controllers\Ajax\TicketController;
use App\Http\Controllers\Ajax\TicketResponseController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/ajax', 'as' => 'ajax.', 'middleware' => ['auth']], function () {

    Route::post('/setting/theme', [SettingController::class, 'set_theme'])->name('setting.theme');

    Route::post('/ticket', [TicketController::class, 'get_automatic_response'])->name('ticket.get_automatic_response');
    Route::post('/ticket_response', [TicketResponseController::class, 'store'])->name('ticket_response.store');
    Route::post('/ticket_response/{ticket_response}/check_new_response', [TicketResponseController::class, 'check_new_response'])->name('ticket_response.check_new_response');
    
    Route::post('/notification/{notification}/check_new_notification', [NotificationController::class, 'check_new_notification'])->name('notification.check_new_notification');
});
