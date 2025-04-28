<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductWishlist;
use App\Models\SalonService;
use App\Models\ServiceWishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{

    public function serviceWishlistIndex(Request $request)
    {
        $userId = Auth::id();
        $wishlistServiceIds = ServiceWishlist::with('service','service.salon.user:id,name,last_name,image')->where('user_id', $userId)->get();

        // $services = SalonService::paginate($request->per_page ?? 10);

        // $services->getCollection()->transform(function ($service) use ($wishlistServiceIds) {
        //     $service->in_wishlist = in_array($service->id, $wishlistServiceIds);
        //     return $service;
        // });

        return response()->json([
            'status' => true,
            'message' => 'Services retrieved successfully.',
            'data' => $wishlistServiceIds,
        ], 200);
    }

    public function serviceWishlistStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }

        $wishlist = ServiceWishlist::where('user_id', Auth::user()->id)
            ->where('service_id', $request->service_id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json([
                'status' => true,
                'message' => 'Service removed from wishlist',
            ], 200);
        }

        try {
            $wishlist = ServiceWishlist::create([
                'user_id' => Auth::user()->id,
                'service_id' => $request->service_id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Service added to wishlist',
                'data' => $wishlist,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while adding to the wishlist.',
            ], 500);
        }
    }


    public function productWishlistIndex(Request $request)
    {
        $userId = Auth::id();
        // $wishlistProductIds = ProductWishlist::where('user_id', $userId)->pluck('product_id')->toArray();
        $wishlistProductIds = ProductWishlist::with('product')->where('user_id', $userId)->get();

        // $products = Product::paginate($request->per_page ?? 10);

        // $products->getCollection()->transform(function ($product) use ($wishlistProductIds) {
        //     $product->in_wishlist = in_array($product->id, $wishlistProductIds);
        //     return $product;
        // });

        return response()->json([
            'status' => true,
            'message' => 'Products retrieved successfully.',
            'data' => $wishlistProductIds,
        ], 200);
    }


    public function productWishlistStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }

        $wishlist = ProductWishlist::where('user_id', Auth::user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json([
                'status' => true,
                'message' => 'Product removed from wishlist',
            ], 200);
        }

        try {
            $wishlist = ProductWishlist::create([
                'user_id' => Auth::user()->id,
                'product_id' => $request->product_id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Product added to wishlist',
                'data' => $wishlist,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while adding to the wishlist.',
            ], 500);
        }
    }

}
