<?php

namespace App\Http\Controllers\SuperAdminDashboard\EShop;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShopCategoryRequest;
use App\Models\ShopCategory;
use Illuminate\Http\Request;

class ECategoryController extends Controller
{
    public function index(Request $request)
    {
        return ShopCategory::latest('id')->paginate($request->per_page??10);
    }

    public function store(ShopCategoryRequest $request)
    {
        $category = new ShopCategory();
        $category->category_name = $request->category_name;
        if ($request->hasFile('category_image') && $request->file('category_image')->isValid()) {
            $category->category_image = saveImage($request, 'category_image');
        }

        $category->save();

        return response()->json([
            'message' => 'E-Shop Category added Successfully',
            'data' => $category
        ]);
    }

    public function show(string $id)
    {
        $category = ShopCategory::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'E-Shop Category does not exist'
            ], 404);
        }

        return response()->json([
            'message' => 'E-Shop Category retrieved successfully',
            'data' => $category
        ]);
    }

    public function update(Request $request, string $id)
    {
        $category = ShopCategory::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'E-Shop Category Does Not Exist'
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
            'message' => 'E-Shop Category Updated Successfully',
            'data' => $category
        ]);
    }

    public function destroy(string $id)
    {
        try {
            $category = ShopCategory::find($id);

        if ($category->category_image){
            removeImage($category->category_image);
        }
        if (!$category) {
            return response()->json([
                'message' => 'E-Shop Category Does Not Exist'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'message' => 'E-Shop Category Deleted Successfully'
        ]);
        } catch (\Exception $e) {

        return response()->json([
            'message' => 'E-Shop category not found.'
        ]);}
    }
}
