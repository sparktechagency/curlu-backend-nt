<?php
namespace App\Http\Controllers;

use App\Models\Salon;
use App\Models\SalonInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderHistory extends Controller
{
    public function orderHistory(Request $request)
    {
        $per_page      = $request->per_page ?? 10;
        $salon_invoice = SalonInvoice::with('salon.user:id,name,last_name,address', 'service', 'user:id,name,last_name,address');
        if (Auth::user()->role_type == 'USER') {
            $salon_invoice = $salon_invoice->where('user_id', Auth::user()->id);
        }
        if (Auth::user()->role_type == 'PROFESSIONAL') {
            $salon         = Salon::where('user_id', Auth::user()->id)->first();
            $salon_invoice = $salon_invoice->where('salon_id', $salon->id);
        }
        $salon_invoice = $salon_invoice->latest('id')->paginate($per_page);
        return response()->json([
            'status'  => true,
            'message' => 'History retrieve successfully.',
            'data'    => $salon_invoice,
        ]);
    }

    public function orderHistorysingle($id)
    {
        $salon_invoice = SalonInvoice::with('salon.user:id,name,last_name,address', 'service', 'user:id,name,last_name,address')->where('id', $id)->first();
        return response()->json([
            'status'  => true,
            'message' => 'Data retrieve successfully.',
            'data'    => $salon_invoice,
        ]);
    }

    public function qrScan($id)
    {
        $salon_invoice         = SalonInvoice::where('invoice_number', $id)->first();
        $salon_invoice->status = 'Past';
        $salon_invoice->save();
        return response()->json([
            'status'  => true,
            'message' => 'Data retrieve successfully.',
            'data'    => $salon_invoice,
        ]);
    }
}
