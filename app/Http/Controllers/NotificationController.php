<?php

namespace App\Http\Controllers;

use App\User;
use App\Report;
use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Auth\Authenticatable;

class NotificationController extends Controller
{
    public function getUnread(Authenticatable $user) {
        $unread = Notification::where('owner_id', $user->id)->get();
        
        return response()->json([
            'result' => $unread
        ]);
    }
}