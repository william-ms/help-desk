<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function check_new_notification(Request $request, Notification $Notification)
    {
        $notification = view('ajax.notification.new_notification', [
            'Notification' => $Notification, 
        ])->render();

        return response()->json([
            'notification' => $notification,
        ]);
    }
}
