<?php

use App\Models\Order;
use App\Models\PaymentDetail;
use App\Models\SalonInvoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/connected', function (Request $request) {
    $status                  = $request->query('status');
    $email                   = $request->query('email');
    $accountId               = $request->query('account_id');
    $user                    = User::where('email', $email)->first();
    $user->stripe_account_id = $accountId;
    $user->save();
});

Route::get('/payment-success', function (Request $request) {

    if (! $request->has('session_id') || empty($request->session_id)) {
        return response()->json(['status' => false, 'message' => 'Invalid session ID.'], 400);
    }

    if (isset($request->session_id)) {
        $stripe       = new \Stripe\StripeClient(config('stripe.stripe_sk'));
        $response     = $stripe->checkout->sessions->retrieve($request->session_id);
        $order_detail = PaymentDetail::where('stripe_payment_id', $response->payment_intent)->first();
        if (! $order_detail) {
            $invoice_number = rand(1000000, 90000000);
            $price          = $request->price;
            $curlu_earning  = ($price * 3) / 100;
            $salon_earning  = $price - $curlu_earning;

            $user_id       = $request->user_id;
            $user_email    = $request->user_email;
            $salon_id      = $request->salon_id;
            $service_id    = $request->service_id;
            $schedule_date = $request->schedule_date;
            $schedule_time = $request->schedule_time;

            $payment_detail = PaymentDetail::create([
                'user_id'           => $user_id,
                'email'             => $user_email,
                'amount'            => $price,
                'description'       => 'N/A',
                'invoice_number'    => $invoice_number,
                'paid'              => 1,
                'due_date'          => now(),
                'link'              => 'N/A',
                'stripe_payment_id' => $response->payment_intent,
            ]);
            $order = Order::create([
                'user_id'        => $user_id,
                'salon_id'       => $salon_id,
                'service_id'     => $service_id,
                'invoice_number' => $invoice_number,
                'amount'         => $price,
                'completed_at'   => now(),
                'curlu_earning'  => $curlu_earning,
                'salon_earning'  => $salon_earning,
                'status'         => 'completed',
                'description'    => 'N/A',
                'schedule_date'  => $schedule_date,
                'schedule_time'  => $schedule_time,
            ]);

            SalonInvoice::create([
                'user_id'                 => $user_id,
                'salon_id'                => $salon_id,
                'payment_detail_id'       => $payment_detail->id,
                'service_id'              => $service_id,
                'invoice_number'          => $invoice_number,
                'order_confirmation_date' => now(),
                'payment'                 => $price,
                'curlu_earning'           => $curlu_earning,
                'salon_earning'           => $salon_earning,
                'status'                  => 'Upcoming',
                'schedule_date'           => $schedule_date,
                'schedule_time'           => $schedule_time,
            ]);
            // return response()->json([
            //     'status'  => true,
            //     'message' => 'Data store successfully.',
            //     'data'    => $order,
            // ]);
            return view('success');
        } else {
            return view('error');
        }
    }
})->name('payment_success');

Route::get('/payment-failed', function () {
    return 'cancel';
})->name('payment_failed');
