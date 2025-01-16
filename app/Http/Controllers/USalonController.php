<?php
namespace App\Http\Controllers;

use App\Models\Salon;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\ServiceWishlist;
use App\Models\SalonScheduleTime;
use Illuminate\Support\Facades\Auth;

class USalonController extends Controller
{
    public function salonDetails(string $id, Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $salon_details = Salon::with([
            'user:id,name,last_name,image,address',
            'salon_schedule_time',
            'salon_services' => function ($query) use ($perPage) {
                $query->paginate($perPage);
            },
        ])->where('id', $id)->first();
        $salon_services = $salon_details->salon_services()->paginate($perPage);
        $salon_services->getCollection()->transform(function ($service) {
            $isInWishlist = false;

            if (Auth::check()) {
                $isInWishlist = ServiceWishlist::where('service_id', $service->id)
                    ->where('user_id', Auth::id())
                    ->exists();
            }
            $service->wishlist = $isInWishlist;
            return $service;
        });
        $salonScheduleTime = SalonScheduleTime::where('salon_id', $salon_details->id)->first();

        if ($salonScheduleTime) {
            $salonScheduleTime->schedule     = json_decode($salonScheduleTime->schedule, true);
            $salonScheduleTime->booking_time = json_decode($salonScheduleTime->booking_time, true);
        }
        $ratingSum   = Review::where('salon_id', $salon_details->id)->sum('rating');
        $ratingCount = Review::where('salon_id', $salon_details->id)->count();

        $rating = $ratingCount > 0 ? min($ratingSum / $ratingCount, 5) : 0;


        $review=Review::with('service:id,service_name')->where('salon_id',$id)->get();
        $response = [
            'cover_image'    => $salon_details->cover_image,
            'image'          => $salon_details->user->image,
            'address'        => $salon_details->user->address,
            'name'           => $salon_details->user->name,
            'last_name'      => $salon_details->user->last_name,
            'rating'         => $rating,
            'profile'        => [
                'id'                => $salon_details->id,
                'user_id'           => $salon_details->user_id,
                'experience'        => $salon_details->experience,
                'salon_type'        => $salon_details->salon_type,
                'salon_description' => $salon_details->salon_description,
                'id_card'           => $salon_details->id_card,
                'iban_number'       => $salon_details->iban_number,
                'kbis'              => $salon_details->kbis,
                'created_at'        => $salon_details->created_at,
                'updated_at'        => $salon_details->updated_at,
                'total_seats'       => $salon_details->user->schedule->capacity ?? null,
            ],
            'schedule'       => $salonScheduleTime,
            'salon_services' => $salon_services,
            'review'=>$review,
        ];

        return response()->json($response);
    }

}

// public function salonScheduleTimeById(Request $request,$id)
// {
//     $salonScheduleTime = SalonScheduleTime::where('salon_id', $id)
//         ->get();

//     if ($salonScheduleTime->isEmpty()) {
//         return response()->json(['message' => 'No salon schedule time found']);
//     }

//     $salonScheduleTime->transform(function ($scheduleTime) {
//         $schedule = json_decode($scheduleTime->schedule);
//         $bookingTime = json_decode($scheduleTime->booking_time);
//         $scheduleTime->schedule = $schedule;
//         $scheduleTime->booking_time = $bookingTime;
//         return $scheduleTime;
//     });

//     return response()->json(['data' => $salonScheduleTime]);
// }
