<?php

namespace App\Http\Controllers\SuperAdminDashboard;

use App\Http\Controllers\Controller;
use App\Models\SalonInvoice;
use Illuminate\Http\Request;

class OrderTransactionController extends Controller
{
    public function index(Request $request)
    {
        $transaction = SalonInvoice::with(
            'user:id,name,email,address,image',
            'salon.user:id,name,email,phone,address,image',
            'service'
        )
            ->select('id', 'user_id', 'salon_id', 'service_id', 'payment_detail_id', 'order_confirmation_date', 'payment', 'status')
            ->orderBy("created_at", "desc");

        if ($request->filled('salon_name')) {
            $transaction->whereHas('user', function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->salon_name . '%');
            });
        }

        if ($request->filled('booking_date')) {
            $transaction->whereDate('created_at', $request->booking_date);
        }
        if ($request->filled('confirmation_date')) {
            $transaction->whereDate('order_confirmation_date', $request->confirmation_date);
        }
        if ($request->filled('status')) {
            $transaction->where('status', $request->status);
        }
        if ($request->filled('service_name')) {
            $transaction->whereHas('service', function ($query) use ($request) {
                $query->where('service_name', 'LIKE', '%' . $request->service_name . '%');
            });
        }

        $transaction = $transaction->paginate($request->per_page ?? 10);

        return response()->json($transaction);
    }

}
