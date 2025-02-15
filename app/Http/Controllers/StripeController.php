<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentDetail;
use App\Models\Salon;
use App\Models\SalonInvoice;
use App\Models\SalonService;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\Price;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function createStripeConnectedAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }
        $user = User::where('email', $request->email)->first();
        Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
            $account = Account::create([
                'type'         => 'express',
                'country'      => 'US',
                'email'        => $user->email,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers'     => ['requested' => true],
                ],
            ]);
            $customReturnUrl = url("/connected?status=success&email={$user->email}&account_id={$account->id}");
            $accountLink     = AccountLink::create([
                'account'     => $account->id,
                'refresh_url' => url('/vendor/reauth'),
                'return_url'  => $customReturnUrl,
                'type'        => 'account_onboarding',
            ]);

            return response()->json([
                'message'        => 'Stripe Connect account created successfully',
                'onboarding_url' => $accountLink->url,
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function buyServiceIntent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id'     => 'required|numeric',
            'price'          => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }

        $price                 = $request->price;
        $platformFeePercentage = 3; // 3% platform fee
        $totalAmount           = (int) ($price * 100);
        $platformFee           = (int) (($totalAmount * $platformFeePercentage) / 100);

        $service = SalonService::find($request->service_id);
        if (! $service) {
            return response()->json(['status' => false, 'message' => 'Service not found.'], 404);
        }

        $salon = Salon::find($service->salon_id);
        if (! $salon) {
            return response()->json(['status' => false, 'message' => 'Salon not found.'], 404);
        }

        $professionalStripeAccountId = User::find($salon->user_id)->stripe_account_id;
        if (! $professionalStripeAccountId) {
            return response()->json([
                'status'  => false,
                'message' => 'This professional does not have a Stripe account.',
            ], 400);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount'                 => $totalAmount,
                'currency'               => 'usd',
                'payment_method'         => $request->payment_method,
                'transfer_data'          => [
                    'destination' => $professionalStripeAccountId,
                ],
                'application_fee_amount' => $platformFee,
            ]);

            return response()->json([
                'status' => true,
                'data'   => $paymentIntent,
            ]);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function buyServiceSuccess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id'        => 'required|numeric',
            'salon_id'          => 'required|numeric',
            'price'             => 'required',
            'stripe_payment_id' => 'required',
            'schedule_date'     => 'required',
            'schedule_time'     => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }
        $invoice_number = rand(1000000, 90000000);
        $price          = $request->price;
        $curlu_earning  = ($price * 3) / 100;
        $salon_earning  = $price - $curlu_earning;

        $payment_detail = PaymentDetail::create([
            'user_id'           => Auth::user()->id,
            'email'             => Auth::user()->email,
            'amount'            => $price,
            'description'       => 'N/A',
            'invoice_number'    => $invoice_number,
            'paid'              => 1,
            'due_date'          => now(),
            'link'              => 'N/A',
            'stripe_payment_id' => $request->stripe_payment_id,
        ]);
        $order = Order::create([
            'user_id'        => Auth::user()->id,
            'salon_id'       => $request->salon_id,
            'service_id'     => $request->service_id,
            'invoice_number' => $invoice_number,
            'amount'         => $price,
            'completed_at'   => now(),
            'curlu_earning'  => $curlu_earning,
            'salon_earning'  => $salon_earning,
            'status'         => 'completed',
            'description'    => 'N/A',
            'schedule_date'  => $request->schedule_date,
            'schedule_time'  => $request->schedule_time,
        ]);

        SalonInvoice::create([
            'user_id'                 => Auth::user()->id,
            'salon_id'                => $request->salon_id,
            'payment_detail_id'       => $payment_detail->id,
            'service_id'              => $request->service_id,
            'invoice_number'          => $invoice_number,
            'order_confirmation_date' => now(),
            'payment'                 => $price,
            'curlu_earning'           => $curlu_earning,
            'salon_earning'           => $salon_earning,
            'status'                  => 'Upcoming',
            'schedule_date'           => $request->schedule_date,
            'schedule_time'           => $request->schedule_time,
        ]);
        return response()->json([
            'status'  => true,
            'message' => 'Data store successfully.',
            'data'    => $order,
        ]);
    }

    public function generatePaymentLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|numeric',
            'price'      => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }

        $price                 = $request->price;
        $totalAmount           = (int) ($price * 100);
        $platformFeePercentage = 3; // 3% platform fee
        $platformFee           = (int) (($totalAmount * $platformFeePercentage) / 100);

        $service = SalonService::find($request->service_id);
        if (! $service) {
            return response()->json(['status' => false, 'message' => 'Service not found.'], 404);
        }

        $salon = Salon::find($service->salon_id);
        if (! $salon) {
            return response()->json(['status' => false, 'message' => 'Salon not found.'], 404);
        }

        $professional = User::find($salon->user_id);
        if (! $professional || ! $professional->stripe_account_id) {
            return response()->json([
                'status'  => false,
                'message' => 'This professional does not have a Stripe account.',
            ], 400);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items'           => [[
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name' => $service->service_name,
                        ],
                        'unit_amount'  => $totalAmount,
                    ],
                    'quantity'   => 1,
                ]],
                'mode'                 => 'payment',
                'payment_intent_data'  => [
                    'application_fee_amount' => $platformFee,
                    'transfer_data'          => [
                        'destination' => $professional->stripe_account_id,
                    ],
                ],
                // 'success_url'          => url('/payment-success?session_id={CHECKOUT_SESSION_ID}'),
                'success_url'          => url('/payment-success') . '?session_id={CHECKOUT_SESSION_ID}&user_id=' . Auth::user()->id .
                '&user_email=' . Auth::user()->email . '&salon_id=' . $request->salon_id .
                '&service_id=' . $request->service_id . '&price=' . $request->price .
                '&schedule_date=' . $request->schedule_date . '&schedule_time=' . $request->schedule_time,

                'cancel_url'           => url('/payment-failed'),
            ]);
            if (isset($session->id) && $session->id != '') {
                return response()->json([
                    'status'       => true,
                    'payment_link' => $session->url,
                ]);
            } else {
                return response()->json([
                    'status'       => false,
                    'payment_link' => null,
                ]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
