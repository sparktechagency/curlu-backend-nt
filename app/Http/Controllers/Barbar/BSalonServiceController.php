<?php
namespace App\Http\Controllers\Barbar;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Salon;
use App\Models\SalonScheduleTime;
use App\Models\SalonService;
use App\Models\User;
use App\Services\FileUploadService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BSalonServiceController extends Controller
{

    protected $fileuploadService;
    private $filePath = 'adminAsset/service_image/';
    public function __construct(FileUploadService $file_upload_service)
    {
        $this->fileuploadService = $file_upload_service;
    }
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
            $salon = Salon::where('user_id', Auth::user()->id)->first();
            $query->where('salon_id', $salon->id);
        } elseif (Auth::user()->role_type == 'USER') {
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
        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

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
        $services = $query->latest('id')->paginate($request->per_page ?? 10);

        return response()->json($services);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (empty($user->stripe_account_id)) {
            return response()->json(['error' => 'To receive payments, you need to connect your account first.'], 404);
        }
        $user_id = auth()->user()->id;
        $salon   = Salon::where('user_id', $user_id)->first();
        if (empty($salon)) {
            return response()->json(['error' => 'salon not found'], 404);
        }
        $service = new SalonService();

        $service->salon_id       = $salon->id;
        $service->category_id    = $request->category_id;
        $service->service_name   = $request->service_name;
        $service->price          = $request->price;
        $service->discount_price = $request->discount_price;
        $service->service_status = $request->service_status;
//            $service->schedule_status = $request->schedule_status ?? null;

        if ($request->hasFile('service_image') && $request->file('service_image')->isValid()) {
            $service->service_image = $this->fileuploadService->setPath($this->filePath)->saveOptimizedImage($request->file('service_image'), 40, 1320, null, true);
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
        $salon   = Salon::where('user_id', $user_id)->first();

        if (empty($salon)) {
            return response()->json(['error' => 'salon not found'], 404);
        }

        $service = SalonService::where('id', $id)->where('salon_id', $salon->id)->first();

        if (empty($service)) {
            return response()->json(['error' => 'service not found'], 404);
        }

        // Update image only if a new file is uploaded
        if ($request->hasFile('service_image') && $request->file('service_image')->isValid()) {
            if (! empty($service->service_image)) {
                removeImage($service->service_image);
            }
            $service->service_image = $this->fileuploadService->setPath($this->filePath)->saveOptimizedImage($request->file('service_image'), 40, 1320, null, true);
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
        try {
            $service = SalonService::findOrFail($id);
            DB::table('service_wishlists')->where('service_id', $id)->delete();
            $service->delete();
            return response()->json([
                'status'  => true,
                'message' => 'Service delete successfully.',
                'data'    => $service,
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
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
        return response()->json(['message' => 'Status updated', 'data' => $service], 200);
    }
    public function salonwiseService(Request $request)
    {
        // $services = SalonService::query();

        // if ($request->category_id) {
        //     $services = $services->where('category_id', $request->category_id);
        // }
        // $services = $services->paginate();
        // return response()->json(['data' => $services]);
        $per_page = $request->per_page;
        $salons   = User::where('role_type', 'PROFESSIONAL');
        if ($request->search) {
            $salons = $salons->where('name', 'LIKE', '%' . $request->search . '%')->orWhere('last_name', 'LIKE', '%' . $request->search . '%');
        }
        $salons = $salons->paginate($per_page ?? 10);
        $salons->getCollection()->transform(function ($salon) {
            $salonScheduleTime = SalonScheduleTime::where('salon_id', $salon->id)->first();
            if ($salonScheduleTime) {
                $salonScheduleTime->schedule     = json_decode($salonScheduleTime->schedule, true);
                $salonScheduleTime->booking_time = json_decode($salonScheduleTime->booking_time, true);
            }
            $ratingSum   = Review::where('salon_id', $salon->id)->sum('rating');
            $ratingCount = Review::where('salon_id', $salon->id)->count();

            $rating = $ratingCount > 0 ? round(min($ratingSum / $ratingCount, 5), 1) : 0;
            return [
                'id'        => $salon->id,
                'name'      => $salon->name,
                'last_name' => $salon->last_name,
                'image'     => $salon->image,
                'address'   => $salon->address,
                'rating'    => $rating,
                'schedule'  => $salonScheduleTime,
            ];
        });
        return response()->json([
            'status'  => true,
            'message' => 'salon retrieve successfully',
            'data'    => $salons,
        ]);
    }

    public function serviceDetails($id)
    {
        $service = SalonService::with('salon', 'salon.user')->findOrFail($id);
        return response()->json(['data' => $service]);
    }
}
