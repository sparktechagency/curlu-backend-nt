<?php

namespace App\Http\Controllers\SuperAdminDashboard;

use App\Http\Controllers\Controller;
use App\Models\SalonService;
use Illuminate\Http\Request;

class ManageHaircutOfferController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
        $salonServices = SalonService::whereHas('salon.user', function ($query) use ($searchTerm) {
            $query->where('name', 'LIKE', '%' . $searchTerm . '%')->orWhere('address', 'LIKE', '%' . $searchTerm . '%');
        })->with([
            'salon' => function ($query) {
                $query->select('id', 'user_id')->with([
                    'user' => function ($userQuery) {
                        $userQuery->select('id', 'name', 'image', 'address');
                    },
                ]);
            },
        ])->select('id', 'salon_id', 'service_name', 'price', 'discount_price', 'service_status', 'created_at')->paginate();
        return response()->json($salonServices);
    }
}
