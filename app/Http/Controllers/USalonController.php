<?php

namespace App\Http\Controllers;

use App\Models\Salon;
use Illuminate\Http\Request;

class USalonController extends Controller
{
    public function salonDetails(string $id, Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $salon_details = Salon::with([
            'user:id,name,last_name,image,address',
            // 'user.schedule',
            'salon_services' => function ($query) use ($perPage) {
                $query->paginate($perPage);
            }
        ])->where('id', $id)->first();

        $salon_services = $salon_details->salon_services()->paginate($perPage);

        $response = [
            'cover_image' => $salon_details->cover_image,
            'image' => $salon_details->user->image,
            'address' => $salon_details->user->address,
            'name' => $salon_details->user->name,
            'last_name' => $salon_details->user->last_name,
            'rating'=> null,
            'profile' => [
                'id' => $salon_details->id,
                'user_id' => $salon_details->user_id,
                'experience' => $salon_details->experience,
                'salon_type' => $salon_details->salon_type,
                'salon_description' => $salon_details->salon_description,
                'id_card' => $salon_details->id_card,
                'iban_number' => $salon_details->iban_number,
                'kbis' => $salon_details->kbis,
                'created_at' => $salon_details->created_at,
                'updated_at' => $salon_details->updated_at,
                'total_seats' => $salon_details->user->schedule->capacity ?? null,
            ],
            // 'schedule' => $salon_details->user->schedule->schedule,

            'salon_services' => $salon_services,
        ];

        return response()->json($response);
    }
}
