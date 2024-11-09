<?php

namespace App\Http\Controllers\SuperAdminDashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SalonInvoice;

class OrderTransactionController extends Controller
{
   public function index(){
    $transaction=SalonInvoice::with(
        'user:id,name,email,address,image',
        'salon.user:id,name,email,phone,address,image',
        'service'
        )->orderBy("created_at","desc")->select('id','user_id','salon_id','service_id','payment_detail_id','order_confirmation_date','payment')->paginate();
    return response()->json(['data'=>$transaction]);
   }
}
