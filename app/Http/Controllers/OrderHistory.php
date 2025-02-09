<?php
namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Salon;
use App\Models\SalonInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderHistory extends Controller
{
    public function orderHistory(Request $request)
    {
        $per_page       = $request->per_page ?? 10;
        $salon_invoices = SalonInvoice::with('salon.user:id,name,last_name,address', 'service', 'user:id,name,last_name,address');
        if (Auth::user()->role_type == 'PROFESSIONAL') {
            $salon          = Salon::where('user_id', Auth::user()->id)->first();
            $salon_invoices = $salon_invoices->where('salon_id', $salon->id);
        }
        $salon_invoices = $salon_invoices->latest('id')->paginate($per_page);
        foreach ($salon_invoices as $invoice) {
            $invoice->review = Review::where('salon_invoice_id', $invoice->id)
                ->where('user_id', $invoice->user_id)
                ->first();
        }
        return response()->json([
            'status'  => true,
            'message' => 'History retrieve successfully.',
            'data'    => $salon_invoices,
        ]);
    }

    public function orderHistorysingle($id)
    {
        $salon_invoice         = SalonInvoice::with('salon.user:id,name,last_name,address', 'service', 'user:id,name,last_name,address')->where('id', $id)->first();
        $salon_invoice->review = Review::where('salon_invoice_id', $salon_invoice->id)
            ->where('user_id', $salon_invoice->user_id)
            ->first();
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
