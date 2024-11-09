<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $feedbacks = Feedback::with(
            'user:id,name,email,address,phone,image',
            'salon',
            'payment_detail:id,user_id,invoice_number,created_at'
        );
        if ($request->filled('date')) {
            $feedbacks = $feedbacks->whereDate('created_at', $request->date);
        }

        if ($request->filled('salon_name')) {
            $feedbacks = $feedbacks->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->salon_name . '%');
            });
        }
        $feedbacks = $feedbacks->select('id','user_id','salon_id','payment_detail_id','comments','review','created_at');
        $feedbacks=$feedbacks->paginate(10);
        $rating_sum=$feedbacks->sum('review');
        return $rating_sum;
        return response()->json(['message' => 'success', 'data' => $feedbacks], 200);
    }


}
