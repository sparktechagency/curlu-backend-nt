<?php

namespace App\Http\Controllers\SuperAdminDashboard;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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

    public function allMark(Request $request)
    {
        try {
            $notifications = Auth::user()->unreadNotifications;

            if ($notifications->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No unread notifications found.',
                ], 404);
            }

            $notifications->markAsRead();

            return response()->json([
                'status' => true,
                'message' => 'All notifications marked as read successfully.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while marking notifications.',
            ], 500);
        }
    }

}
