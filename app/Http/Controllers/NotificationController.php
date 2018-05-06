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

    public function getUnreadCount(Authenticatable $user) {
        $unreadCount = Notification::where('owner_id', $user->id)
                                   ->where('is_read', 0)->count();
        
        return response()->json([
            'result' => $unreadCount
        ]);
    }

    public function read(Authenticatable $user, $notifId) {
        $notif = Notification::where('owner_id', $user->id)
                             ->where('id', $notifId)->first();
        $notif->is_read = true;
        $notif->save();
        
        return response()->json([
            'result' => $notif
        ]);
    }
}