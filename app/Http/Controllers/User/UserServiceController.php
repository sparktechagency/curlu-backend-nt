<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Salon;
use App\Models\SalonService;
use App\Models\slider;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
class UserServiceController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }
    

    public function homeSlider(Request $request) {
        // $sliders = slider::where('is_slider', 1)
        //             ->orderBy('created_at', 'desc')
        //             ->paginate($request->per_page ?? 10);
        $sliders = slider::paginate($request->per_page ?? 10);

        if($sliders->isEmpty()) {
            return response()->json(['message'=> 'No slider found']);
        }

        return response()->json(['message'=> 'Success','sliders' => $sliders]);
    }

    public function populerService(Request $request) {
        
            $populerService = SalonService::with('category')
                            ->where('service_status', 'active')   
                            ->where('popular', '>', 0)
                            ->orderBy('popular', 'desc')
                            ->paginate($request->per_page ?? 10);
            

            if($populerService->isEmpty()) {
                return response()->json(['message'=> 'No populer service found']);
            }
            return response()->json(['message'=> 'Success','populerService' => $populerService]);
        
    }

    public function caregoryService(Request $request,$id){
        $categoryService = SalonService::with('category')
                            ->where('category_id', $id)
                            ->where('service_status', 'active')
                            ->where('popular', '>', 0)
                            ->orderBy('popular', 'desc') 
                            ->paginate($request->per_page ?? 10);

        if($categoryService->isEmpty()) {
            return response()->json(['message'=> 'No service found']);
        }
        return response()->json(['message'=> 'Success','categoryService' => $categoryService]);
    }
    
    //get discount offers services
    public function serviceOffer(Request $request) {
        $offerService = SalonService::with('salon')
                        ->whereNotNull('discount_price')
                        ->orderBy('discount_price', 'desc')
                        ->paginate($request->per_page ?? 10);

        if($offerService->isEmpty()) {
            return response()->json(['message'=> 'No offers found']);
        }

        $offerService->each(function($service) {
            $service->user = User::where('id', $service->salon->user_id)
                            ->get(['name','last_name','email','phone','image','address','role_type']);
        });
        return response()->json(['message'=> 'Success','offerService' => $offerService]);
    }

    //get e-shop products
    public function eShopProduct(Request $request) {
        // $products = Product::with('shop_category')
        //             ->orderBy('created_at', 'desc')
        //             ->paginate($request->per_page ?? 10);
        $products = Product::orderBy('created_at', 'desc')
                    ->get();

        if($products->isEmpty()) {
            return response()->json(['message'=> 'No products found']);
        }

        return response()->json(['message'=> 'Success','products' => $products]);
    }


    //get nearby professionals services
    public function getNearbyProfessionals(Request $request) {
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

        if (empty($nearbyProfessionals)) {
            return response()->json(['message' => 'No nearby professionals found']);
        }

        return response()->json(['message' => 'Success', 'nearby_professionals' => $nearbyProfessionals]);
    }

    

    public function getNearbyProfessionalsByCategory(Request $request, $id) {
        $user = auth()->user();
        $userLatitude = $request->latitude ?? $user->latitude;
        $userLongitude = $request->longitude ?? $user->longitude;

        if (empty($userLatitude) || empty($userLongitude)) {
            return response()->json(['message' => 'Please update your location to find nearby professionals']);
        }
        $radius = $request->radius ?? 10;

        $nearByServiceByCategory = $this->userService->getNearbyProfessionalsByCategory($userLatitude, $userLongitude, $radius, $id);
        
        if (empty($nearByServiceByCategory)) {
            return response()->json(['message' => 'No nearby professionals found']);
        }

        return response()->json(['message' => 'Success', 'nearby_professionals' => $nearByServiceByCategory]);
    }
    
    
}
