<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Feedback;
use App\Models\Product;
use App\Models\Salon;
use App\Models\SalonScheduleTime;
use App\Models\SalonService;
use App\Models\slider;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Pagination\LengthAwarePaginator;

use ParagonIE\Sodium\Core\Curve25519\Fe;

class UserServiceController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function homeSlider(Request $request)
    {
        // $sliders = slider::where('is_slider', 1)
        //             ->orderBy('created_at', 'desc')
        //             ->paginate($request->per_page ?? 10);
        $sliders = slider::paginate($request->per_page ?? 10);

        if ($sliders->isEmpty()) {
            return response()->json(['message' => 'No slider found']);
        }

        return response()->json(['message' => 'Success', 'sliders' => $sliders]);
    }

    public function populerService(Request $request)
    {

        $populerService = SalonService::with('category')
            ->where('service_status', 'active')
            ->where('popular', '>', 0)
            ->orderBy('popular', 'desc')
            ->paginate($request->per_page ?? 10);


        if ($populerService->isEmpty()) {
            return response()->json(['message' => 'No populer service found']);
        }
        return response()->json(['message' => 'Success', 'populerService' => $populerService]);
    }


    public function caregoryService(Request $request, $id)
    {
        $categoryService = SalonService::with('category')
            ->where('category_id', $id)
            ->where('service_status', 'active')
            // ->where('popular', '>', 0)
            ->orderBy('popular', 'desc')
            ->paginate($request->per_page ?? 10);

        if ($categoryService->isEmpty()) {
            return response()->json(['message' => 'No service found']);
        }
        return response()->json(['message' => 'Success', 'categoryService' => $categoryService]);
    }

    //get discount offers services
    public function serviceOffer(Request $request)
    {
        $offerService = SalonService::with('salon')
            ->whereNotNull('discount_price')
            ->orderBy('discount_price', 'desc')
            ->paginate($request->per_page ?? 10);

        if ($offerService->isEmpty()) {
            return response()->json(['message' => 'No offers found']);
        }

        $offerService->each(function ($service) {
            $service->user = User::where('id', $service->salon->user_id)
                ->get(['name', 'last_name', 'email', 'phone', 'image', 'address', 'role_type']);
        });
        return response()->json(['message' => 'Success', 'offerService' => $offerService]);
    }

    //get e-shop products
    public function eShopProduct(Request $request)
    {
        // $products = Product::with('shop_category')
        //             ->orderBy('created_at', 'desc')
        //             ->paginate($request->per_page ?? 10);
        $products = Product::orderBy('created_at', 'desc')
            ->get();

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found']);
        }

        return response()->json(['message' => 'Success', 'products' => $products]);
    }


    //get nearby professionals services
    public function getNearbyProfessionals(Request $request)
    {
        $user = auth()->user();

        $userLatitude = $request->latitude ?? $user->latitude;
        $userLongitude = $request->longitude ?? $user->longitude;

        if (empty($userLatitude) || empty($userLongitude)) {
            return response()->json(['message' => 'Please update your location to find nearby professionals']);
        }
        $radius = $request->radius ?? 10;

        $nearbyProfessionals = $this->userService->getNearbyProfessionals($userLatitude, $userLongitude, $radius);

        foreach ($nearbyProfessionals as $professional) {
            $professional->distance = $this->userService->distanceService->getDistance(
                $userLatitude,
                $userLongitude,
                $professional->latitude,
                $professional->longitude
            );
        }

        $nearbyProfessionals = collect($nearbyProfessionals)->transform(function ($professional) {
            $schedule = SalonScheduleTime::where('salon_id', $professional->salon->id)
                        ->get()->transform(function ($item) {
                            return is_string($item->schedule) ? json_decode($item->schedule, true) : $item->schedule;
                        });
            $reviews = Feedback::where('salon_id', $professional->salon->id)
                    ->avg('review');
            return [
                'user_id' => $professional->id,
                'salon_id' => $professional->salon->id,
                'name' => $professional->name,
                'last_name' => $professional->last_name,
                'address' => $professional->address,
                'distance' => $professional->distance,
                'image' => $professional->image,
                'cover_image' => $professional->cover_image,
                'salon_type' => $professional->salon->salon_type,
                'rating' => number_format($reviews, 1) ?? 0,
                'schedule_time' => $schedule,
                
            ];
        });

        if (empty($nearbyProfessionals)) {
            return response()->json(['message' => 'No nearby professionals found']);
        }

        return response()->json(['message' => 'Success', 'nearby_professionals' => $nearbyProfessionals]);
    }



    public function getNearbyProfessionalsByCategory(Request $request, $id)
    {
        $user = auth()->user();
        $userLatitude = $request->latitude ?? $user->latitude;
        $userLongitude = $request->longitude ?? $user->longitude;

        if (empty($userLatitude) || empty($userLongitude)) {
            return response()->json(['message' => 'Please update your location to find nearby professionals']);
        }
        $radius = $request->radius ?? 10;
        $perPage = $request->per_page ?? 10;
        $searchTerm = $request->search_term;

        $nearByServiceByCategory = $this->userService->getNearbyProfessionalsByCategory($userLatitude, $userLongitude, $radius, $id, $searchTerm, $perPage);

        if (empty($nearByServiceByCategory)) {
            return response()->json(['message' => 'No nearby professionals found']);
        }

        return response()->json(['message' => 'Success', 'nearbyProfessionalServices' => $nearByServiceByCategory]);
    }



    public function findServiceByProfessional(Request $request, $id)
    {

        try {
            $salon_user = Salon::with('user')->where('id', $id)->get();

            $salon_user = collect($salon_user)->map(function ($salon) use ($request) {
                $service = SalonService::where('salon_id', $salon->id)->paginate($request->per_page ?? 10);
                $schedule = SalonScheduleTime::where('salon_id', $salon->id)->get();
                $schedule = $schedule->map(function ($item) {
                    return is_string($item->schedule) ? json_decode($item->schedule, true) : $item->schedule;
                });
                return [
                    'salon_user' => [
                        'salon_id' => $salon->id,
                        'salon_user_id' => $salon->user->id,
                        'name' => $salon->user->name,
                        'last_name' => $salon->user->last_name,
                        'email' => $salon->user->email,
                        'phone' => $salon->user->phone,
                        'image' => $salon->user->image,
                        'cover_image' => $salon->user->cover_image,
                        'address' => $salon->user->address,
                        'salon_type' => $salon->salon_type,
                        'descriotion' => $salon->salon_description,
                        'experience' => $salon->experience,
                        'schedule_time' => $schedule,
                    ],
                    'services' => $service,
                ];
            });
            return response()->json([
                'message' => 'Success',
                'salon_services' => $salon_user,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong ' . $e->getMessage()]);
        }
    }
}
