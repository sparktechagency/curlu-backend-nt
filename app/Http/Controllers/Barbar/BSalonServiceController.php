<?php

namespace App\Http\Controllers\Barbar;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalonServiceRequest;
use App\Models\Salon;
use App\Models\SalonService;
use Illuminate\Http\Request;

class BSalonServiceController extends Controller
{

    public function index(Request $request)
    {
        $query = SalonService::query();

        // Search by salon->user->name and address
        if ($request->filled('user_name')) {
            $query->whereHas('salon.user', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->user_name . '%');
            });
        }

        if ($request->has('address')) {
            $query->whereHas('salon.user', function ($q) use ($request) {
                $q->where('address', 'LIKE', '%' . $request->address . '%');
            });
        }

        // Search by salon_type
        if ($request->has('salon_type')) {
            $query->whereHas('salon', function ($q) use ($request) {
                $q->where('salon_type', 'LIKE', '%' . $request->salon_type . '%');
            });
        }

        // Filter by service_status
        if ($request->has('service_status')) {
            $query->where('service_status', $request->service_status);
        }

        // Filter by price
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Paginate the results
        $services = $query->paginate($request->per_page ?? 10);

        return response()->json(['message'=> 'Success','services' => $services]);

    }

    public function store(Request $request)
    {
        $user_id = auth()->user()->id;
        $salon = Salon::where('user_id',$user_id)->first();

        if (empty($salon)){
            return response()->json(['error' => 'salon not found'], 404);
        }
            $service = new SalonService();
            if ($request->hasFile('service_image') && $request->file('service_image')->isValid()) {
                $service->service_image = saveImage($request, 'service_image');
            }
            $service->salon_id = $salon->id;
            $service->category_id = $request->category_id;
            $service->service_name = $request->service_name;
            $service->price = $request->price;
            $service->discount_price = $request->discount_price;
            $service->save();
            return response()->json(['message' => 'Service created successfully', 'service' => $service], 201);
    }


    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
