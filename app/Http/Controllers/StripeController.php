<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentDetail;
use App\Models\PlatformFee;
use App\Models\Salon;
use App\Models\SalonInvoice;
use App\Models\SalonService;
use App\Models\User;
use App\Notifications\NewOrder;
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
                'country'      => 'GB',
                'email'        => $user->email,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers'     => ['requested' => true],
                ],
            ]);

            $returnUrl = url("/api/stripe/onboarding/callback?email={$user->email}&account_id={$account->id}");
            $refreshUrl = url("/api/stripe/onboarding/callback?email={$user->email}&account_id={$account->id}");

            $accountLink     = AccountLink::create([
                'account'     => $account->id,
                'refresh_url' => $refreshUrl,
                'return_url'  => $returnUrl,
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

  public function stripeOnboardingCallback(Request $request)
    {
        $email = $request->query('email');
        $tempAccountId = $request->query('account_id');

        if (!$email || !$tempAccountId) {
            //  Log::error('status' => false, 'message' => 'Missing email or temp_account ID.');
            // return response()->json(['status' => false, 'message' => 'Missing email or temp_account ID.'], 400);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            // return response()->json(['status' => false, 'message' => 'User not found.'], 404);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $account = Account::retrieve($tempAccountId);

            if ($account->charges_enabled && $account->payouts_enabled) {

                $user->stripe_account_id = $tempAccountId;
                $user->save();

                // return response()->json([
                //     'status' => true,
                //     'message' => 'Onboarding complete & verified.',
                // ]);
            } else {
                // return response()->json([
                //     'status' => false,
                //     'message' => 'Onboarding not yet completed.',
                // ]);
            }
        } catch (Exception $e) {
            Log::error('Stripe retrieve failed: ' . $e->getMessage());
            // return response()->json([
            //     'status' => false,
            //     'message' => 'Something went wrong while checking Stripe account.',
            //     'error' => $e->getMessage(),
            // ], 500);
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

        $platformFeePercentage = PlatformFee::first()->curlu_earning;
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
        $platformFeePercentage = PlatformFee::first()->curlu_earning;
        $curlu_earning  = ($price * $platformFeePercentage) / 100;
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
            'status'         => 'pending',
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
        $notification_data = [
            'buyer_name' => Auth::user()->name . ' ' . Auth::user()->last_name,
            'address'    => Auth::user()->address,
        ];
        $salon_id     = Salon::where('id', $request->salon_id)->pluck('user_id')->first();
        $professional = User::find($salon_id);
        if ($professional) {
            $professional->notify(new NewOrder($notification_data));
        }
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
        $platformFeePercentage = PlatformFee::first()->curlu_earning;
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
