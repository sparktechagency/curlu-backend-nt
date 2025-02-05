<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\Payment\PaymentController;

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

////
//Route::get('/home', [PaymentController::class, 'index'])->name('home');
//Route::post('/payment-request', [PaymentController::class, 'storePaymentRequest'])->name('payment-request');
//Route::get('/payment/{slug}', [PaymentController::class, 'paymentCheckout'])->name('payment-checkout');
//Route::get('/payment/success/{session_id}', [PaymentController::class, 'paymentSuccess'])->name('payment-success');
//Route::post('stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);



Route::get('/connected', function (Request $request) {
    $status                  = $request->query('status');
    $email                   = $request->query('email');
    $accountId               = $request->query('account_id');
    $user                    = User::where('email', $email)->first();
    $user->stripe_account_id = $accountId;
    $user->save();
});
