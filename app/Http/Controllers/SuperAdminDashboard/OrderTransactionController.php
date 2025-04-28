<?php

namespace App\Http\Controllers\SuperAdminDashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderTransactionController extends Controller
{
    // public function index(Request $request)
    // {
    //     $transaction = SalonInvoice::with(
    //         'user:id,name,email,address,image',
    //         'salon.user:id,name,email,phone,address,image',
    //         'service'
    //     )
    //         ->select('id', 'user_id', 'salon_id', 'service_id', 'payment_detail_id', 'order_confirmation_date', 'payment', 'status')
    //         ->orderBy("created_at", "desc");

    //     if ($request->filled('salon_name')) {
    //         $transaction->whereHas('user', function ($query) use ($request) {
    //             $query->where('name', 'LIKE', '%' . $request->salon_name . '%');
    //         });
    //     }

    //     if ($request->filled('booking_date')) {
    //         $transaction->whereDate('created_at', $request->booking_date);
    //     }
    //     if ($request->filled('confirmation_date')) {
    //         $transaction->whereDate('order_confirmation_date', $request->confirmation_date);
    //     }
    //     if ($request->filled('status')) {
    //         $transaction->where('status', $request->status);
    //     }
    //     if ($request->filled('service_name')) {
    //         $transaction->whereHas('service', function ($query) use ($request) {
    //             $query->where('service_name', 'LIKE', '%' . $request->service_name . '%');
    //         });
    //     }

    //     $transaction = $transaction->paginate($request->per_page ?? 10);

    //     return response()->json($transaction);
    // }
    public function index(Request $request)
    {

        $orders = Order::with('user', 'salon', 'service');

        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;

            $orders = $orders->whereHas('user', function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            });

            $orders = $orders->orWhereHas('salon', function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            });

            $orders = $orders->orWhereHas('service', function ($query) use ($searchTerm) {
                $query->where('service_name', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->has('status') && $request->status) {
            $status = $request->status;
            $orders = $orders->where('status', $status);
        }

        if ($request->has('booking_date') && $request->booking_date) {
            $booking_date = $request->booking_date;
            $orders = $orders->whereDate('created_at', $booking_date);
        }

        $orders = $orders->paginate($request->per_page ?? 10);

        $orders->getCollection()->transform(function ($order) {
            return [
                'id' => $order->id,
                'user' => [
                    'image' => $order->user->image,
                    'name' => $order->user->name . ' ' . $order->user->last_name,
                    'email' => $order->user->email,
                    'address' => $order->user->address,
                    'phone' => $order->user->phone
                ],
                'salon' => [
                    'image' => $order->salon->image,
                    'name' => $order->salon->name . ' ' . $order->salon->last_name,
                    'email' => $order->salon->email,
                    'address' => $order->salon->address,
                    'phone' => $order->salon->phone
                ],
                'service' => [
                    'name' => $order->service->service_name,
                    'price' => $order->amount,
                ],
                'status' => $order->status,
                'invoice_number' => $order->invoice_number,
                'booking_date' => $order->created_at,
                'confirmation_date' => $order->completed_at,
                'salon_earning' => $order->salon_earning,
            ];
        });

        return response()->json([
            'message' => 'Data retrieved successfully.',
            'data' => $orders,
        ], 200);
    }




}
