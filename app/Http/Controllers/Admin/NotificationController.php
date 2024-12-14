<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {

    }

    public function redirect(Notification $Notification)
    {
        $Notification->update(['status' => 2]);
        
        return redirect()->route('admin.ticket.show', $Notification->model->id);
    }
}
