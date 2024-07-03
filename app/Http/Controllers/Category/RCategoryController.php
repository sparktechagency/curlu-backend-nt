<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class RCategoryController extends Controller
{

    public function index()
    {
        return Category::paginate(12);
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
            'data' => $category
        ]);

    }

    public function show(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Category retrieved successfully',
            'data' => $category
        ]);
    }

    public function update(Request $request, string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
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
            'data' => $category
        ]);
    }

    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }
        if ($category->category_image){
            removeImage($category->category_image);
        }
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }
}
