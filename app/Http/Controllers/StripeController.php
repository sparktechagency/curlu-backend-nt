<?php
namespace App\Http\Controllers;

use App\Models\Salon;
use App\Models\SalonService;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\Stripe;
use Stripe\StripeClient;

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

    public function createCheckoutSession(Request $request)
    {
        $stripe               = new StripeClient(config('stripe.stripe_sk'));
        $destinationAccountId = SalonService::where('salon_id', $request->salon_id)->first()->stripe_account_id;

        try {
            $account = $stripe->accounts->retrieve($destinationAccountId);

            if ($account->capabilities->transfers !== 'active') {
                $account = $stripe->accounts->update(
                    $destinationAccountId,
                    [
                        'capabilities' => [
                            'transfers' => 'active',
                        ],
                    ]
                );
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update account capabilities: ' . $e->getMessage()], 500);
        }

        try {
            $checkoutSession = $stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items'           => [[
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name' => $request->service_name,
                        ],
                        'unit_amount'  => $request->price * 100,
                    ],
                    'quantity'   => $request->quantity,
                ]],
                'mode'                 => 'payment',
                'payment_intent_data'  => [
                    'application_fee_amount' => 1000,
                    'transfer_data'          => [
                        'amount'      => 100,
                        'currency'    => 'usd',
                        'destination' => $destinationAccountId,
                    ],
                ],
                'success_url'          => url('/success'),
                'cancel_url'           => url('/cancel'),
            ]);

            return response()->json(['url' => $checkoutSession->url]);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Stripe Checkout session creation failed: ' . $e->getMessage()], 500);
        }
    }

    public function success(Request $request)
    {
        if ($request->has('session_id')) {
            $stripe   = new StripeClient(config('stripe.stripe_sk'));
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

    // =====================================================================================================================
    // =====================================================================================================================

    public function createConnectedAccount(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $stripe = new StripeClient(config('stripe.stripe_sk'));

        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $salon = Salon::where('user_id', $user->id)->first();
        if (! $salon) {
            return response()->json(['error' => 'Salon not found'], 404);
        }
        if ($salon->stripe_account_id) {
            return response()->json([
                'message'    => 'Stripe account already exists',
                'account_id' => $salon->stripe_account_id,
            ]);
        }
        try {
            $account = $stripe->accounts->create([
                'country'      => 'US',
                'email'        => $request->email,
                'capabilities' => [
                    'transfers' => ['requested' => true],
                ],
                'controller'   => [
                    'fees'             => ['payer' => 'application'],
                    'losses'           => ['payments' => 'application'],
                    'stripe_dashboard' => ['type' => 'express'],
                ],
            ]);
            $salon->stripe_account_id = $account->id;
            $salon->save();
            $onboardingLink = $this->create_account_link($account->id);

            return response()->json([
                'account_id'      => $account->id,
                'onboarding_link' => $onboardingLink,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error'   => 'Stripe account creation failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete_account($accountNo)
    {
        try {
            $stripe = new StripeClient(config('stripe.stripe_sk'));
            $stripe->accounts->delete($accountNo, []);
            return response()->json(['message' => 'Account delete successfully']);
        } catch (Exception $e) {
            Log::error('Delete account error: ' . $e->getMessage());
            return response()->json(['message' => 'Account not found'], 500);
        }
    }

    public function create_account_link($accountId)
    {
        $stripe = new StripeClient(config('stripe.stripe_sk'));

        $accountLink = $stripe->accountLinks->create([
            'account'     => $accountId,
            'refresh_url' => url('/dashboard'),
            'return_url'  => url('/dashboard'),
            'type'        => 'account_onboarding',
        ]);

        return response()->json(['url' => $accountLink->url]);
    }
}

// public function createPayment(Request $request)
// {
//     Stripe::setApiKey(env('STRIPE_SECRET'));

//     $paymentIntent = PaymentIntent::create([
//         'amount' => $request->amount * 100, // Amount in cents
//         'currency' => 'usd',
//         'payment_method_types' => ['card'],
//         'transfer_data' => [
//             'destination' => $request->vendor_stripe_account_id,
//         ],
//         'application_fee_amount' => $request->platform_fee * 100, // Platform fee in cents
//     ]);

//     return response()->json(['client_secret' => $paymentIntent->client_secret]);
// }

// public function transferToVendor($vendorAccountId, $amount)
// {
//     Stripe::setApiKey(env('STRIPE_SECRET'));

//     Transfer::create([
//         'amount' => $amount * 100, // Amount in cents
//         'currency' => 'usd',
//         'destination' => $vendorAccountId,
//     ]);

//     return response()->json(['success' => true, 'message' => 'Transfer completed']);
// }

// protected function schedule(Schedule $schedule)
// {
//     $schedule->call(function () {
//         // Get eligible payments from the database
//         $payments = Payment::where('status', 'pending')
//             ->where('created_at', '<=', now()->subDays(7))
//             ->get();

//         foreach ($payments as $payment) {
//             $this->transferToVendor($payment->vendor_account_id, $payment->amount);
//             $payment->status = 'transferred';
//             $payment->save();
//         }
//     })->daily();
// }

// public function createAccountLink(Request $request)
// {
//     Stripe::setApiKey(env('STRIPE_SECRET'));

//     $accountLink = AccountLink::create([
//         'account' => $request->stripe_account_id,
//         'refresh_url' => route('vendor.onboarding.refresh'),
//         'return_url' => route('vendor.onboarding.complete'),
//         'type' => 'account_onboarding',
//     ]);

//     return response()->json(['url' => $accountLink->url]);
// }

// 4. Verify Account Setup Completion
// Stripe provides webhooks to notify you about events, such as when a connected account is fully verified.

// Set up a webhook to listen for account.updated events.
// Check the charges_enabled and payouts_enabled fields to determine if the vendor’s account is ready to accept payments and receive payouts.
// Example Webhook Handling:

// php
// Copy code
// use Illuminate\Http\Request;

// public function handleWebhook(Request $request)
// {
//     $payload = $request->all();

//     if ($payload['type'] == 'account.updated') {
//         $account = $payload['data']['object'];
//         $vendor = Vendor::where('stripe_account_id', $account['id'])->first();

//         if ($account['charges_enabled'] && $account['payouts_enabled']) {
//             $vendor->stripe_account_status = 'active';
//             $vendor->save();
//         }
//     }

//     return response()->json(['success' => true]);
// }
