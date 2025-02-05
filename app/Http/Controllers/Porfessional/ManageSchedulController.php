<?php
namespace App\Http\Controllers\Porfessional;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SalonScheduleTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ManageSchedulController extends Controller
{
    public function salonScheduleTime(Request $request)
    {

        if (Auth::user()->role_type == 'USER') {
            $salon_id          = $request->salon_id;
            $salonScheduleTime = SalonScheduleTime::where('salon_id', $salon_id)
                ->get();
        } else {
            $salonScheduleTime = SalonScheduleTime::where('salon_id', auth()->user()->id)
                ->get();
        }

        if ($salonScheduleTime->isEmpty()) {
            return response()->json(['message' => 'No salon schedule time found']);
        }

        $salonScheduleTime->transform(function ($scheduleTime) {
            $schedule                   = json_decode($scheduleTime->schedule);
            $bookingTime                = json_decode($scheduleTime->booking_time);
            $scheduleTime->schedule     = $schedule;
            $scheduleTime->booking_time = $bookingTime;
            return $scheduleTime;
        });

        return response()->json(['data' => $salonScheduleTime]);
    }

    public function storeSchedule(Request $request)
    {
        // return $request;
        $validated = Validator::make($request->all(), [
            'schedule' => 'required|json',
            'capacity' => 'required|integer',
        ]);
        if ($validated->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validated->errors()]);
        }
        $salon_id     = auth()->user()->id;
        $scheduleTime = SalonScheduleTime::where('salon_id', $salon_id)->first();
        if ($scheduleTime) {
            $scheduleTime->schedule     = $request->schedule ?? $scheduleTime->schedule;
            $scheduleTime->booking_time = $request->booking_time ?? $scheduleTime->booking_time;
            $scheduleTime->salon_id     = auth()->user()->id;
            $scheduleTime->capacity     = $request->capacity ?? $scheduleTime->capacity;
            $scheduleTime->save();

            return response()->json(['message' => 'Schedule time updated successfully', 'data' => $scheduleTime]);
        }
        $scheduleTime               = new SalonScheduleTime();
        $scheduleTime->schedule     = $request->schedule;
        $scheduleTime->booking_time = $request->booking_time;
        $scheduleTime->salon_id     = $salon_id;
        $scheduleTime->capacity     = $request->capacity;
        $scheduleTime->save();

        return response()->json(['message' => 'Schedule time added successfully', 'data' => $scheduleTime]);
    }

    public function updateSchedule(Request $request, $id)
    {

        try {
            $updateSchedul = SalonScheduleTime::findOrfail($id);
            // dd($request->all());
            if ($updateSchedul) {
                $validated = Validator::make($request->all(), [
                    'schedule' => 'required|json',
                    'capacity' => 'required|integer',
                ]);

                if ($validated->fails()) {
                    return response()->json(['message' => 'Validation failed', 'errors' => $validated->errors()]);
                }

                $updateSchedul->schedule = $request->schedule;
                $updateSchedul->capacity = $request->capacity;
                $updateSchedul->save();

                return response()->json(['message' => 'Schedule time updated successfully', 'schedule' => $updateSchedul]);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong']);
        }
    }

    public function deleteSchedule($id)
    {

        try {
            $deleteSchedule = SalonScheduleTime::findOrfail($id);
            $deleteSchedule->delete();
            return response()->json(['message' => 'Schedule time deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong']);
        }
    }

    public function upcomingBooking(Request $request)
    {

        $date = $request->date ?? now()->toDateString();

        $upcomingBooking = Order::with('user:id,name,last_name')
            ->where('salon_id', Auth::user()->id)
            ->where('schedule_date', $date)
            ->select('id', 'user_id', 'invoice_number', 'schedule_date', 'schedule_time')
            ->orderByRaw("STR_TO_DATE(schedule_time, '%H:%i:%s') ASC")
            ->get();

        if ($upcomingBooking->isEmpty()) {
            return response()->json([
                'message' => 'No bookings found for the selected date.',
                'data'    => [],
            ], 200);
        }

        $data = $upcomingBooking->map(function ($booking) {
            return [
                'user_name'      => $booking->user->name . ' ' . $booking->user->last_name,
                'invoice_number' => $booking->invoice_number,
                'schedule_time'  => \Carbon\Carbon::createFromFormat('H:i:s', trim($booking->schedule_time))
                    ->format('h:i a'),
                'schedule_date'  => $booking->schedule_date,
            ];
        });

        return response()->json([
            'message' => 'Success',
            'data'    => $data,
        ], 200);
    }

    public function schedule(Request $request)
    {
        $data = [
            'date'     => $request->date,
            'time'     => $request->time,
            'salon_id' => $request->salon_id,
        ];
        return response()->json([
            'status'  => true,
            'message' => 'Data retrieve successfully',
            'data'    => $data,
        ]);
    }

}
