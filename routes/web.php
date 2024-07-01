<?php

use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\StripeWebhookController;
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

////
//Route::get('/home', [PaymentController::class, 'index'])->name('home');
//Route::post('/payment-request', [PaymentController::class, 'storePaymentRequest'])->name('payment-request');
//Route::get('/payment/{slug}', [PaymentController::class, 'paymentCheckout'])->name('payment-checkout');
//Route::get('/payment/success/{session_id}', [PaymentController::class, 'paymentSuccess'])->name('payment-success');
//Route::post('stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
