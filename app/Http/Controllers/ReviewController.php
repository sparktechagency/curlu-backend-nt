<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'salon_id' => 'required',
            'service_id' => 'required',
            'order_id' => 'required',
            'rating' => 'required',
            'comment' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }

        $review = Review::create([
            'user_id' => Auth::user()->id,
            'salon_id' => $request->salon_id,
            'service_id' => $request->service_id,
            'salon_invoice_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);
        return response()->json(['message' => 'Review added successfully','data'=>$review]);
    }


    public function index(Request $request){
        $reviews=Review::with('user:id,name,last_name,email,image,address','service:id,service_name,service_description,price,discount_price,service_image')->where('salon_id',Auth::user()->id)->latest('id')->paginate($request->per_page ?? 10);
        return response()->json(['message' => 'Review get successfully','data'=>$reviews]);
    }
}
