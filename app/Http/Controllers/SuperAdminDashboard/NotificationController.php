<?php

namespace App\Http\Controllers\SuperAdminDashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        $currentUser = auth()->user()->id;
        $notifications = DB::table('notifications')->where('notifiable_id', $currentUser)->select('id', 'data', 'read_at', 'created_at')->get();
        $notifications = $notifications->map(function ($notification) {
            $notification->data = json_decode($notification->data);
            return $notification;
        });
        $total_unread = DB::table('notifications')->where('notifiable_id', $currentUser)->whereNull('read_at')->count();
        return response()->json([
            'unread_notification' => $total_unread,
            'notification' => $notifications,
        ]);
    }

    public function markRead($id)
    {
        $notificaiton=auth()->user()->notifications->where('id', $id)->markAsRead();
        return response()->json(['message' => 'Marked as read'], 200);
    }
    
}
