<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\SalonService;
use App\Models\ServiceWishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RCategoryController extends Controller
{

//    public function index(Request $request)
//    {
//        $query = Category::query();
//        if($request->filled('category_name')){
//            $query = $query->where('category_name',$request->category_name);
//        }
//        $category = $query->paginate($request->per_page ?? 10);
//        return response()->json($category);
//    }

    public function index(Request $request)
    {
        $query = Category::query();

        // Check if 'category_name' is provided and not empty
        if ($request->filled('category_name')) {
            // Handle multiple category names
            $categoryNames = explode(',', $request->category_name); // Support comma-separated names if passed as a string
            $query = $query->whereIn('category_name', $categoryNames);
        }

        // Paginate results
        $category = $query->paginate($request->per_page ?? 1);

        return response()->json($category);
    }

    public function store(CategoryRequest $request)
    {
        $category = new Category();
        $category->category_name = $request->category_name;

        if ($request->hasFile('category_image') && $request->file('category_image')->isValid()) {
            $category->category_image = saveImage($request, 'category_image');
        }
        $category->save();

        return response()->json([
            'message' => 'Category added Successfully',
            'data' => $category,
        ]);

    }

    public function show(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Category retrieved successfully',
            'data' => $category,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
            ], 404);
        }

        $category->category_name = $request->category_name ?? $category->category_name;

        if ($request->hasFile('category_image') && $request->file('category_image')->isValid()) {
            if (!empty($category->category_image)) {
                removeImage($category->category_image);
            }
            $category->category_image = saveImage($request, 'category_image');
        }

        $category->save();

        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $category,
        ]);
    }

    public function destroy(string $id)
    {
        $category = Category::find($id);
        $count_service = SalonService::where('category_id', $category->id)->count();

        if ($count_service > 0) {
            return response()->json([
                'message' => 'This category contains some services.',
            ], 400);
        }

        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
            ], 404);
        }
        if ($category->category_image) {
            removeImage($category->category_image);
        }
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully',
        ]);
    }

    // public function categoryWiseServices(Request $request)
    // {
    //     $userLatitude = auth()->user()->latitude;
    //     $userLongitude = auth()->user()->longitude;

    //     $query = Category::with(['salon_services' => function ($q) use ($request, $userLatitude, $userLongitude) {
    //         if ($request->filled('service_name')) {
    //             $q->where('service_name', 'like', '%' . $request->service_name . '%');
    //         }

    //         // Join with the salon and user tables to get the latitude and longitude of the salon's user
    //         $q->join('salons', 'salon_services.salon_id', '=', 'salons.id')
    //             ->join('users', 'salons.user_id', '=', 'users.id')
    //             ->select('salon_services.*', 'users.name', 'users.last_name','users.image', 'users.latitude', 'users.longitude');

    //         // Only apply distance calculation and ordering if user's location is provided
    //         if (!is_null($userLatitude) && !is_null($userLongitude)) {
    //             // Calculate the distance between the authenticated user and each salon service
    //             $q->selectRaw('
    //             ( 6371 * acos( cos( radians(?) ) *
    //               cos( radians(users.latitude) ) *
    //               cos( radians(users.longitude) - radians(?) ) +
    //               sin( radians(?) ) *
    //               sin( radians(users.latitude) ) )
    //             ) AS distance', [$userLatitude, $userLongitude, $userLatitude])
    //                 ->orderBy('distance');
    //         }
    //     }]);

    //     if ($request->filled('category_name')) {
    //         $query->where('category_name', $request->category_name);
    //     }

    //     if ($request->filled('category_id')) {
    //         $query->where('id', $request->category_id);
    //     }

    //     $categories = $query->paginate($request->per_page ?? 10);

    //     // Ensure salon_services is null if no services exist
    //     $categories->getCollection()->transform(function ($category) {
    //         $category->salon_services = $category->salon_services->isNotEmpty() ? $category->salon_services : null;
    //         return $category;
    //     });

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Categories retrieved successfully',
    //         'data' => $categories,
    //     ]);
    // }

    public function categoryWiseServices(Request $request)
    {
        $userLatitude = auth()->user()->latitude;
        $userLongitude = auth()->user()->longitude;
        $userId = Auth::id();

        // Get all service IDs in the user's wishlist
        $wishlistServiceIds = ServiceWishlist::where('user_id', $userId)->pluck('service_id')->toArray();

        $query = Category::with(['salon_services.salon.user:id,name,last_name','salon_services' => function ($q) use ($request, $userLatitude, $userLongitude, $wishlistServiceIds) {
            if ($request->filled('service_name')) {
                $q->where('service_name', 'like', '%' . $request->service_name . '%');
            }

            // Join with salons and users to get additional service data
            $q->join('salons', 'salon_services.salon_id', '=', 'salons.id')
                ->join('users', 'salons.user_id', '=', 'users.id')
                ->select('salon_services.*', 'users.name as user_name', 'users.last_name', 'users.image', 'users.latitude', 'users.longitude');

            // Calculate distance and order by proximity if user's location is available
            if (!is_null($userLatitude) && !is_null($userLongitude)) {
                $q->selectRaw('
                    ( 6371 * acos( cos( radians(?) ) *
                      cos( radians(users.latitude) ) *
                      cos( radians(users.longitude) - radians(?) ) +
                      sin( radians(?) ) *
                      sin( radians(users.latitude) ) )
                    ) AS distance', [$userLatitude, $userLongitude, $userLatitude])
                    ->orderBy('distance');
            }
        }]);

        // Filter by category name or ID if provided
        if ($request->filled('category_name')) {
            $query->where('category_name', $request->category_name);
        }

        if ($request->filled('category_id')) {
            $query->where('id', $request->category_id);
        }

        // Paginate categories
        $categories = $query->paginate($request->per_page ?? 10);

        // Ensure salon_services is null if no services exist
        $categories->getCollection()->transform(function ($category) use ($wishlistServiceIds) {
            $category->salon_services = $category->salon_services->isNotEmpty()
                ? $category->salon_services->map(function ($service) use ($wishlistServiceIds) {
                    $service->in_wishlist = in_array($service->id, $wishlistServiceIds) ? true : false;
                    return $service;
                })
                : null;

            return $category;
        });

        return response()->json([
            'status' => true,
            'message' => 'Categories retrieved successfully',
            'data' => $categories,
        ]);
    }


}
