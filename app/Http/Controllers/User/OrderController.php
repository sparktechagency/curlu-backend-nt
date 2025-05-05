<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Salon;
use App\Models\SalonInvoice;
use App\Models\SalonService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function getUserOrers()
    {
        $orders = Order::with(['service.salon.user'])->where('user_id', auth()->id())->get();

        $orders->transform(function ($order) {
            return [
                'order_id'     => $order->id,
                'user_id'      => $order->user_id,
                'salon_id'     => $order->service->salon->id,
                'salon_name'   => $order->service->salon->user->name . ' ' . $order->service->salon->user->last_name,
                'order_number' => $order->order_number,
                'total_amount' => $order->total_amount,
                'status'       => $order->status,
                'booking_time' => Carbon::parse($order->completed_at)->format('d M, Y - h:i a'),
                'services'     => [
                    'service_id'    => $order->service->id,
                    'service_name'  => $order->service->service_name,
                    'service_image' => $order->service->image,
                ],

            ];
        });
        if ($orders->isEmpty()) {
            return response()->json(['message' => 'No orders found']);
        }
        return response()->json(['message' => 'Success', 'orders' => $orders]);
    }

    /**
     * Place an order for a service.
     */
    public function placeOrder(Request $request, $serviceId)
    {
        // dd($request->all());

        try {
            if (! empty($serviceId)) {

                $previousOrder = Order::where('user_id', auth()->id())->where('service_id', $serviceId)->where('status', 'pending')->get();
                if (count($previousOrder) > 0) {
                    return response()->json(['message' => 'You already have a pending order for this service']);
                }
                $service             = SalonService::find($serviceId);
                $order               = new Order();
                $order->user_id      = auth()->id();
                $order->salon_id     = $service->salon_id;
                $order->service_id   = $service->id;
                $order->order_number = 'ORD-' . strtoupper(uniqid());
                $lastOrder           = Order::orderBy('created_at', 'desc')->first();
                if ($lastOrder) {
                    $lastOrderNumber     = intval(substr($lastOrder->order_number, 4));
                    $order->order_number = 'ORD-' . strtoupper(str_pad($lastOrderNumber + 1, 8, '0', STR_PAD_LEFT));
                } else {
                    $order->order_number = 'ORD-00000001';
                }
                $order->total_amount = $service->discount_price != null ? ($service->price - $service->discount_price) : $service->price;
                $order->completed_at = $request->complated_at;
                $order->save();
            } else {
                return response()->json(['message' => 'Service not found']);
            }
            return response()->json(['message' => 'Order placed successfully']);
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return response()->json(['message' => 'This Service not available']);
            }
            return response()->json(['message' => 'Failed to place order', 'error_code' => $e->getCode()]);
        }
    }

    public function cancelOrder($id)
    {
        try {
            $orderCancel = Order::findOrFail($id);
            if ($orderCancel->status == 'pending') {
                $orderCancel->update(['status' => 'cancelled']);
                return response()->json(['message' => 'Order cancelled successfully']);
            } else {
                if ($orderCancel->status == 'cancelled') {
                    return response()->json(['message' => 'Order already cancelled']);
                } else {
                    return response()->json(['message' => 'Order cannot be cancelled']);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong', 'error_code' => $e->getCode()]);
        }
    }

    public function totalOrderAmount()
    {
        $orderTotalAmount = Order::where('user_id', auth()->id())->where('status', 'pending')->sum('total_amount');
        if ($orderTotalAmount == 0) {
            return response()->json(['message' => 'No pending orders']);
        }
        return response()->json(['message' => 'Success', 'orderTotalAmount' => $orderTotalAmount]);
    }

    public function orderRequest(Request $request)
    {

        $curlu_earning = 2;
        $order         = Order::create([
            'user_id'        => Auth::user()->id,
            'salon_id'       => $request->salon_id ?? null,
            'service_id'     => $request->salon_id ?? null,
            'amount'         => $request->amount,
            'status'         => $request->status ?? 'pending',
            'invoice_number' => $request->invoice_number,
            'description'    => $request->description,
            'curlu_earning'  => $curlu_earning,
            'salon_earning'  => $request->amount - $curlu_earning,
            'schedule_time'  => $request->schedule_time,
            'schedule_date'  => $request->schedule_date,
        ]);
        return response()->json(['message' => 'Success', 'data' => $order]);
    }

    public function orderHistory(Request $request)
    {
        if (Auth::user()->role_type == 'USER') {
            $orders = Order::with([
                'user',
                'service',
                'service.salon',
                'service.salon.user',
            ])->where('user_id', auth()->id())->latest('id')->paginate($request->per_page ?? 10);
            if ($orders->isEmpty()) {
                return response()->json([
                    'message' => 'No orders found.',
                    'data'    => [],
                ], 404);
            }

            return response()->json([
                'message' => 'Orders retrieved successfully.',
                'data'    => $orders,

            ], 200);
        }

        return response()->json([
            'message' => 'Unauthorized access.',
            'data'    => [],
        ], 403);
    }

    public function myEarning()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        $salon_id=Salon::where('user_id',Auth::user()->id)->first()->id;
        $weeklyEarning = Order::where('salon_id', $salon_id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->sum('salon_earning');

        $totalEarning = Order::where('salon_id', $salon_id)->sum('salon_earning');

          $orders = Order::with('user:id,name,last_name')
            ->where('salon_id',$salon_id)
            ->latest('id')
            ->get();

        $earningDetails = $orders->map(function ($order) {
            return [
                'user_name'      => $order->user->name . ' ' . $order->user->last_name,
                'invoice_number' => $order->invoice_number,
                'schedule_date'  => $order->schedule_date,
                'schedule_time'  => $order->schedule_time,
                'amount'         => $order->amount,
            ];
        });

        // Prepare response data
        $earning = [
            'weekly_earning'  => $weeklyEarning,
            'total_earning'   => $totalEarning,
            'earning_details' => $earningDetails,
        ];

        return response()->json([
            'message' => 'Earnings retrieved successfully.',
            'data'    => $earning,
        ], 200);
    }

    public function orderReschedule(Request $request, $id)
    {
        $order         = Order::findOrFail($id);
        $salon_invoice = SalonInvoice::findOrFail($id);
        $order->update([
            'schedule_date' => $request->schedule_date,
            'schedule_time' => $request->schedule_time,
        ]);
        $salon_invoice->update([
            'schedule_date' => $request->schedule_date,
            'schedule_time' => $request->schedule_time,
        ]);
        return response()->json([
            'status'=>true,
            'message'=>'Reshedule successfully complete',
            'data'=>$order,
        ]);
    }
}
