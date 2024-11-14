<?php

namespace App\Http\Controllers;

use App\Models\Salon;
use App\Models\SalonService;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class StripeController extends Controller
{
    public function connectAccount(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $stripe = new StripeClient(config('stripe.stripe_sk'));
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $salon = Salon::where('user_id', $user->id)->first();
        if (!$salon) {
            return response()->json(['error' => 'Salon not found'], 404);
        }
        if ($salon->stripe_account_id) {
            return response()->json([
                'message' => 'Stripe account already exists',
                'account_id' => $salon->stripe_account_id,
            ]);
        }
        try {
            $account = $stripe->accounts->create([
                'type' => 'express',
                'country' => 'US',
                // 'email' => $request->email,
            ]);

            $salon->stripe_account_id = $account->id;
            $salon->save();

            $onboardingLink = $this->getOnboardingLink($account->id);

            return response()->json([
                'account_id' => $account->id,
                'onboarding_link' => $onboardingLink,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Stripe account creation failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getOnboardingLink($accountId)
    {
        $stripe = new StripeClient(config('stripe.stripe_sk'));

        $accountLink = $stripe->accountLinks->create([
            'account' => $accountId,
            'refresh_url' => url('/dashboard'),
            'return_url' => url('/dashboard'),
            'type' => 'account_onboarding',
        ]);

        return response()->json(['url' => $accountLink->url]);
    }

    public function createCheckoutSession(Request $request)
    {
        $stripe = new StripeClient(config('stripe.stripe_sk'));
    $destinationAccountId = SalonService::where('salon_id', $request->salon_id)->first()->stripe_account_id;

        try {
            $account = $stripe->accounts->retrieve($destinationAccountId);

            if ($account->capabilities->transfers !== 'active') {
                $account = $stripe->accounts->update(
                    $destinationAccountId,
                    [
                        'capabilities' => [
                            'transfers' => 'active',
                        ]
                    ]
                );
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update account capabilities: ' . $e->getMessage()], 500);
        }

        try {
            $checkoutSession = $stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $request->service_name,
                                                ],
                        'unit_amount' => $request->price * 100,
                    ],
                    'quantity' => $request->quantity,
                ]],
                'mode' => 'payment',
                'payment_intent_data' => [
                    'application_fee_amount' => 1000,
                    'transfer_data' => [
                        'amount'=>100,
                        'currency'=>'usd',
                        'destination' => $destinationAccountId,
                    ],
                ],
                'success_url' => url('/success'),
                'cancel_url' => url('/cancel'),
            ]);

            return response()->json(['url' => $checkoutSession->url]);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Stripe Checkout session creation failed: ' . $e->getMessage()], 500);
        }
    }

    public function success(Request $request)
    {
        if ($request->has('session_id')) {
            $stripe = new StripeClient(config('stripe.stripe_sk'));
            $response = $stripe->checkout->sessions->retrieve($request->session_id);

            // // Save payment details
            // $payment = new Payment();
            // $payment->payment_id = $response->id;
            // $payment->product_name = session()->get('product_name');
            // $payment->quantity = session()->get('quantity');
            // $payment->amount = session()->get('price');
            // $payment->currency = $response->currency;
            // $payment->customer_name = $response->customer_details->name;
            // $payment->customer_email = $response->customer_details->email;
            // $payment->payment_status = $response->status;
            // $payment->payment_method = "Stripe";
            // $payment->save();

            // Clear session data
            session()->forget('product_name');
            session()->forget('quantity');
            session()->forget('price');

            return response()->json(['message' => 'Payment is successful.']);
        } else {
            return redirect()->route('payment-cancel');
        }
    }

    public function cancel()
    {
        return response()->json(['message' => 'Payment is canceled.']);
    }
}
