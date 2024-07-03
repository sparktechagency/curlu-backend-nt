<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Barbar\BSalonServiceController;
use App\Http\Controllers\Category\RCategoryController;
use App\Http\Controllers\SuperAdminDashboard\EShop\ECategoryController;
use App\Http\Controllers\SuperAdminDashboard\Eshop\ProductController;
use App\Http\Controllers\SuperAdminDashboard\ManageAdminController;
use App\Http\Controllers\SuperAdminDashboard\SalonController;
use App\Http\Controllers\SuperAdminDashboard\SalonServiceController;
use App\Http\Controllers\SuperAdminDashboard\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([
    ['middleware' => 'auth:api']
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/email-verified', [AuthController::class, 'emailVerified']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/profile', [AuthController::class, 'loggedUserData']);
    Route::post('/forget-pass', [AuthController::class, 'forgetPassword']);
    Route::post('/verified-checker', [AuthController::class, 'emailVerifiedForResetPass']);
    Route::post('/reset-pass', [AuthController::class, 'resetPassword']);
    Route::post('/update-pass', [AuthController::class, 'updatePassword']);
    Route::put('/profile/edit/{id}', [AuthController::class, 'editProfile']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
});

//dashboard
Route::middleware(['admin', 'auth:api'])->group(function (){
    Route::get('/user-details', [UserController::class,'userDetails']);
    Route::get('/all-salon', [SalonController::class,'allSalon']);
    Route::get('/add-salon', [SalonController::class,'addSalon']);

    // category
    Route::resource('/categories', RCategoryController::class)->except('edit','create');

    Route::resource('/shop-category', ECategoryController::class)->except('edit','create');

    Route::resource('/products',ProductController::class)->except('edit','create');


});

Route::middleware(['professional', 'auth:api'])->group(function (){
    Route::resource('/salon-services',BSalonServiceController::class)->except('edit','create');
});

Route::middleware(['super.admin', 'auth:api'])->group(function (){
    Route::resource('/admins', ManageAdminController::class)->except('edit','create');
});

//R&D individual barcode generate for each task

//Route::middleware(['auth:api'])->group(function (){
//    Route::get('/home', [PaymentController::class, 'index'])->name('home');
//    Route::post('/payment-request', [PaymentController::class, 'storePaymentRequest'])->name('payment-request');
//    Route::get('/payment/{slug}', [PaymentController::class, 'paymentCheckout'])->name('payment-checkout');
//    Route::get('/payment/success/{session_id}', [PaymentController::class, 'paymentSuccess'])->name('payment-success');
//    Route::post('stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
//});



