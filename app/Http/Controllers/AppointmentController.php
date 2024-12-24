<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function nextAppointment(Request $request)
    {
        $now = now();
        $order = Order::with('service:id,service_name','salon:id,name,last_name,address')->where('user_id', auth()->id())
            ->where(function ($query) use ($now) {
                $query->where('schedule_date', '>', $now->toDateString())
                    ->orWhere(function ($q) use ($now) {
                        $q->where('schedule_date', '=', $now->toDateString())
                          ->where('schedule_time', '>', $now->toTimeString());
                    });
            })
            ->orderBy('schedule_date', 'asc')
            ->orderBy('schedule_time', 'asc')
            ->select('id','service_id','salon_id','schedule_date','schedule_time')
            ->first();

        if (!$order) {
            return response()->json([
                'message' => 'No upcoming appointments found.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'message' => 'Next appointment retrieved successfully.',
            'data' => $order,
        ], 200);
    }


}
