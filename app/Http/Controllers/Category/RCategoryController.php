<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\SalonService;
use Illuminate\Http\Request;

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
        $category = $query->paginate($request->per_page ?? 10);

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
}
