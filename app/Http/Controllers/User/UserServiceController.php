<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Salon;
use App\Models\SalonService;
use App\Models\slider;
use App\Models\User;
use Illuminate\Http\Request;




class UserServiceController extends Controller
{

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
        
            $populerService = SalonService::with('salon')
                            ->where('popular', '>', 0)
                            ->orderBy('popular', 'desc')
                            ->pasinate($request->per_page ?? 10); ;
            

            if($populerService->isEmpty()) {
                return response()->json(['message'=> 'No populer service found']);
            }
               
            return response()->json(['message'=> 'Success','populerService' => $populerService]);
        
    }

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

    
    
}
