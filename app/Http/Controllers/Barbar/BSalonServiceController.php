<?php

namespace App\Http\Controllers\Barbar;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalonServiceRequest;
use App\Models\Salon;
use App\Models\SalonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BSalonServiceController extends Controller
{

    public function index(Request $request)
    {
        // $query = SalonService::with('salon','category');

        // // Search by salon->user->name and address
        // if ($request->filled('user_name')) {
        //     $query->whereHas('salon.user', function ($q) use ($request) {
        //         $q->where('name', 'LIKE', '%' . $request->user_name . '%');
        //     });
        // }

        // if ($request->has('address')) {
        //     $query->whereHas('salon.user', function ($q) use ($request) {
        //         $q->where('address', 'LIKE', '%' . $request->address . '%');
        //     });
        // }

        // // Search by salon_type
        // if ($request->has('salon_type')) {
        //     $query->whereHas('salon', function ($q) use ($request) {
        //         $q->where('salon_type', 'LIKE', '%' . $request->salon_type . '%');
        //     });
        // }

        // // Filter by status
        // if ($request->filled('service_status')) {
        //     if ($request->service_status !== 'all') {
        //         $query->where('service_status', $request->service_status);
        //     }
        // }

        // //Filter by service_name
        // if ($request->filled('service_name')) {
        //     $query->where('service_name', 'LIKE','%'. $request->service_name . '%');
        // }

        // // Filter by price
        // if ($request->has('price_min')) {
        //     $query->where('price', '>=', $request->price_min);
        // }

        // if ($request->has('price_max')) {
        //     $query->where('price', '<=', $request->price_max);
        // }

        // // Paginate the results
        // $services = $query->paginate($request->per_page ?? 10);

        // return response()->json(['message'=> 'Success','services' => $services]);
        $query = SalonService::with('salon.user');

        if (Auth::user()->role_type == 'PROFESSIONAL') {
            $query->where('salon_id', Auth::user()->id);
        }
        elseif (Auth::user()->role_type == 'USER') {
            $query->where('salon_id', $request->salon_id);
        }




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
        $services = $query->paginate();

        return response()->json($services);

    }

    public function store(Request $request)
    {

        $user_id = auth()->user()->id;
        $salon = Salon::where('user_id',$user_id)->first();
        if (empty($salon)){
            return response()->json(['error' => 'salon not found'], 404);
        }
            $service = new SalonService();

            $service->salon_id = $salon->id;
            $service->category_id = $request->category_id;
            $service->service_name = $request->service_name;
            $service->price = $request->price;
            $service->discount_price = $request->discount_price;
            $service->service_status = $request->service_status;
//            $service->schedule_status = $request->schedule_status ?? null;

        if ($request->hasFile('service_image') && $request->file('service_image')->isValid()) {
            $service->service_image = saveImage($request, 'service_image');
        }
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
        $user_id = auth()->user()->id;
        $salon = Salon::where('user_id', $user_id)->first();

        if (empty($salon)) {
            return response()->json(['error' => 'salon not found'], 404);
        }

        $service = SalonService::where('id', $id)->where('salon_id', $salon->id)->first();

        if (empty($service)) {
            return response()->json(['error' => 'service not found'], 404);
        }

        // Update image only if a new file is uploaded
        if ($request->hasFile('service_image') && $request->file('service_image')->isValid()) {
            if (!empty($service->category_image)) {
                removeImage($service->category_image);
            }
            $service->service_image = saveImage($request, 'service_image');
        }
        if ($request->filled('service_name')) {
            $service->service_name = $request->service_name;
        }
        if ($request->filled('price')) {
            $service->price = $request->price;
        }
        if ($request->filled('discount_price')) {
            $service->discount_price = $request->discount_price;
        }
        if ($request->filled('schedule_status')) {
            $service->service_status = $request->service_status;
        }

        $service->save();

        return response()->json(['message' => 'Service updated successfully', 'service' => $service], 200);
    }

    public function destroy(string $id)
    {
        //
    }

    public function serviceStatus(Request $request, $id)
    {
        $service = SalonService::where('id', $id)->first();
        if ($service->service_status == 'active') {
            $status = 'inactive';
        } else {
            $status = 'active';
        }
        $service->service_status = $status;
        $service->save();
        return response()->json(['message' => 'Status updated','data'=>$service], 200);
    }
 public function salonwiseService(Request $request){
    $services = SalonService::query();

    if($request->category_id){
        $services=$services->where('category_id',$request->category_id);
    }
    $services=$services->paginate();
    return response()->json(['data'=>$services]);
 }
}
