<?php
namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    // public function index(Request $request)
    // {
    //     $feedbacks = Feedback::with(
    //         'user:id,name,email,address,phone,image',
    //         'salon',
    //         'payment_detail:id,user_id,invoice_number,created_at'
    //     );

    //     if ($request->filled('date')) {
    //         $feedbacks = $feedbacks->whereDate('created_at', $request->date);
    //     }

    //     if ($request->filled('salon_name')) {
    //         $feedbacks = $feedbacks->whereHas('user', function ($q) use ($request) {
    //             $q->where('name', 'LIKE', '%' . $request->salon_name . '%');
    //         });
    //     }

    //     $feedbacks = $feedbacks->select('id', 'user_id', 'salon_id', 'payment_detail_id', 'comments', 'review', 'created_at')->paginate(10);

    //     return response()->json([
    //         'message' => 'success',
    //         'data' => $feedbacks,
    //     ], 200);
    // }
    public function index(Request $request)
    {
        $reviews = Review::with('user:id,name,last_name,email,image,address,phone', 'salon.user:id,name,last_name,email,image,address,phone')->latest('id');

        if ($request->filled('date')) {
            $reviews = $reviews->whereDate('created_at', $request->date);
        }

        if ($request->filled('salon_name')) {
            $salonName = $request->salon_name;
            $reviews   = $reviews->whereHas('salon.user', function ($q) use ($salonName) {
                $q->where('name', 'LIKE', "%" . $salonName . "%")
                    ->orWhere('last_name', 'LIKE', "%" . $salonName . "%");
            });
        }

        $reviews = $reviews->paginate($request->per_page ?? 10);
        return response()->json([
            'message' => 'success',
            'data'    => $reviews,
        ], 200);
    }

}
