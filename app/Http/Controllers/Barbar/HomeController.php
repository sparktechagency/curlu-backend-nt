<?php

namespace App\Http\Controllers\Barbar;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function showProducts(Request $request)
    {
        $query = Product::with('shop_category');

        if($request->filled('shop_category_id')){
            $query->where('shop_category_id', $request->input('shop_category_id'));
        }
        if($request->filled('eshop_category_name')){
            $query->whereHas('shop_category', function ($query) use ($request) {
                $query->where('category_name', $request->input('eshop_category_name'));
            });
        }

        $products = $query->paginate(10);
        return response()->json($products);
    }
}
