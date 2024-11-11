<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{

    public function getUserOrers()
    {
        $orders = Order::with(['service'])->where('user_id', auth()->id())->get();
        if($orders->isEmpty()){
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
            if(!empty($serviceId) ){

                $previousOrder = Order::where('user_id', auth()->id())->where('service_id', $serviceId)->where('status', 'pending')->get();
                if(count($previousOrder) > 0){
                    return response()->json(['message' => 'You already have a pending order for this service']);
                }
                $order = new Order();
                $order->user_id = auth()->id();
                $order->service_id = $serviceId;
                $order->order_number = 'ORD-'.strtoupper(uniqid());
                $lastOrder = Order::orderBy('created_at', 'desc')->first();
                if ($lastOrder) {
                    $lastOrderNumber = intval(substr($lastOrder->order_number, 4));
                    $order->order_number = 'ORD-' . strtoupper(str_pad($lastOrderNumber + 1, 8, '0', STR_PAD_LEFT));
                } else {
                    $order->order_number = 'ORD-00000001';
                }
                $order->total_amount = $request->total_amount;
                $order->completed_at = $request->complated_at;
                $order->save();
            }else{
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
}
