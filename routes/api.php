<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Barbar\BSalonServiceController;
use App\Http\Controllers\Barbar\HomeController;
use App\Http\Controllers\Category\RCategoryController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Porfessional\ManageSchedulController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\SuperAdminDashboard\ChatController;
use App\Http\Controllers\SuperAdminDashboard\DashboardController;
use App\Http\Controllers\SuperAdminDashboard\EShop\ECategoryController;
use App\Http\Controllers\SuperAdminDashboard\Eshop\ProductController;
use App\Http\Controllers\SuperAdminDashboard\FaqController;
use App\Http\Controllers\SuperAdminDashboard\ManageAdminController;
use App\Http\Controllers\SuperAdminDashboard\ManageHaircutOfferController;
use App\Http\Controllers\SuperAdminDashboard\NotificationController;
use App\Http\Controllers\SuperAdminDashboard\OrderTransactionController;
use App\Http\Controllers\SuperAdminDashboard\SalonController;
use App\Http\Controllers\SuperAdminDashboard\Slider\SliderController;
use App\Http\Controllers\SuperAdminDashboard\UserController;
use App\Http\Controllers\TermsConditionController;
use App\Http\Controllers\USalonController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\UserServiceController;
use App\Http\Controllers\User\WishlistController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([['middleware' => 'auth:api']], function ($router) {
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
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);

    Route::get('/logout', [AuthController::class, 'logout']);
});

//dashboard
Route::middleware(['admin', 'auth:api'])->group(function () {

    // dashboard api
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // user api
    Route::get('/user-details', [UserController::class, 'userDetails']);
    Route::put('/user-status/{id}', [UserController::class, 'userStatus']);

    // salon api
    Route::get('/all-salon', [SalonController::class, 'allSalon']);
    Route::post('/add-salon', [SalonController::class, 'addSalon']);
    Route::put('/salon-status/{id}', [SalonController::class, 'salonStatus']);

    // category api
    Route::resource('/categories', RCategoryController::class)->except('edit', 'create', 'index');

    // shop-category api
    Route::resource('/shop-category', ECategoryController::class)->except('edit', 'create', 'index');

    // products api
    Route::resource('/products', ProductController::class)->except('edit', 'create');

    // admins api
    Route::resource('/admins', ManageAdminController::class)->except('edit', 'create');

    // Route::resource('/sliders', SliderController::class)->except('edit','create');
    Route::resource('/sliders', SliderController::class)->except('index');

    Route::resource('/faqs', FaqController::class)->except('edit', 'create', 'index');

    // salon invoice api
    Route::get('/salon_invoice', [SalonController::class, 'salon_invoice']);

    // notification
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notification/markread/{id}', [NotificationController::class, 'markRead']);

    // feedback api
    Route::get('/feedback', [FeedbackController::class, 'index']);
    // order_transaction api
    Route::get('/order_transaction', [OrderTransactionController::class, 'index']);
    // manage haircut offer api
    Route::get('/manage-haircut', [ManageHaircutOfferController::class, 'index']);

    //chat
    Route::get('/search-user', [ChatController::class, 'searchUser']);
    Route::post('/send-message', [ChatController::class, 'sendMessage']);
    Route::get('/get-message', [ChatController::class, 'getMessage']);
    Route::get('/chat-list', [ChatController::class, 'chatList']);
});

Route::middleware(['professional', 'auth:api'])->group(function () {
    // salon service
    Route::resource('/salon-services', BSalonServiceController::class)->except('edit', 'create');
    Route::put('/service-status/{id}', [BSalonServiceController::class, 'serviceStatus']);

    Route::get('/show-products', [HomeController::class, 'showProducts']);
    Route::get('/logout', [AuthController::class, 'logout']);

    //schedule time for salon
    Route::resource('/salon-services', BSalonServiceController::class)->except('edit', 'create');
    Route::post('/schedules', [ManageSchedulController::class, 'storeSchedule']);
    Route::get('/schedules', [ManageSchedulController::class, 'salonScheduleTime']);
//    Route::put('/schedules/{id}', [ManageSchedulController::class,'updateSchedule']);
//    Route::delete('/schedules/{id}', [ManageSchedulController::class,'deleteSchedule']);

    //upcoming booking
    Route::get('/upcoming-booking', [ManageSchedulController::class, 'upcomingBooking']);


    Route::get('review',[ReviewController::class,'index']);

});

Route::middleware(['super.admin', 'auth:api'])->group(function () {
//    Route::resource('/admins', ManageAdminController::class)->except('edit','create');
});

//R&D individual barcode generate for each task

// Route::middleware(['auth:api'])->group(function (){
//    Route::get('/home', [PaymentController::class, 'index'])->name('home');
//    Route::post('/payment-request', [PaymentController::class, 'storePaymentRequest'])->name('payment-request');
//    Route::get('/payment/{slug}', [PaymentController::class, 'paymentCheckout'])->name('payment-checkout');
//    Route::get('/payment/success/{session_id}', [PaymentController::class, 'paymentSuccess'])->name('payment-success');
//    Route::post('stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
Route::post('create-connect-account', [StripeController::class, 'connectAccount'])->name('connectAccount');
Route::get('getOnboardingLink/{account_id}', [StripeController::class, 'getOnboardingLink'])->name('getOnboardingLink');
Route::post('payment-request', [StripeController::class, 'createCheckoutSession'])->name('payment-request');
Route::get('success', [StripeController::class, 'success'])->name('payment-success');
Route::get('cancel', [StripeController::class, 'cancel'])->name('payment-cancel');

// testing connected account
Route::post('create_account', [StripeController::class, 'createConnectedAccount'])->name('create_account');
Route::post('create_account_link/{accountNo}', [StripeController::class, 'create_account_link'])->name('create_account_link');
Route::delete('delete_account/{accountNo}', [StripeController::class, 'delete_account'])->name('delete_account');

// });

Route::resource('/about-us', AboutUsController::class)->except('edit', 'create');
Route::resource('/terms-condition', TermsConditionController::class)->except('edit', 'create');
Route::resource('/privacy-policy', PrivacyPolicyController::class)->except('edit', 'create');
Route::resource('/faqs', FaqController::class)->only('index');

Route::middleware(['admin.professional.user', 'auth:api'])->group(function () {
    Route::resource('/shop-category', ECategoryController::class)->only('index');
    Route::resource('/categories', RCategoryController::class)->only('index');
});

//USER role route
Route::middleware(['user', 'auth:api'])->group(function () {

    Route::get('/salon-de   tails/{id}', [USalonController::class, 'salonDetails']);
    // Route::get('/slider', [UserServiceController::class,'homeSlider']);
    // Route::resource('/sliders', SliderController::class)->only('index');
    // Route::resource('/sliders', SliderController::class)->only('index')->middleware('admin');
    Route::get('/populer-service', [UserServiceController::class, 'populerService']);
    Route::get('/cat-service/{id}', [UserServiceController::class, 'caregoryService']);
    Route::get('/offer-service', [UserServiceController::class, 'serviceOffer']);
    Route::get('/e-shop', [UserServiceController::class, 'eShopProduct']);
    Route::resource('/shop-category', ECategoryController::class)->only( 'index');

    Route::get('/category-wise-services', [RCategoryController::class, 'categoryWiseServices']);

    Route::get('/profession-services/{id}', [UserServiceController::class, 'findServiceByProfessional']);

    Route::get('/nearby-professionals', [UserServiceController::class, 'getNearbyProfessionals']);
    Route::get('/nearby-catServices/{id}', [UserServiceController::class, 'getNearbyProfessionalsByCategory']);

    Route::post('/service-wishlist', [WishlistController::class, 'serviceWishlistStore']);
    Route::get('/service-wishlist', [WishlistController::class, 'serviceWishlistIndex']);
    Route::post('/product-wishlist', [WishlistController::class, 'productWishlistStore']);
    Route::get('/product-wishlist', [WishlistController::class, 'productWishlistIndex']);

    //order
    Route::get('/place-order', [OrderController::class, 'getUserOrers']);
    Route::post('/place-order/{serviceId}', [OrderController::class, 'placeOrder']);
    Route::put('/order-cancel/{id}', [OrderController::class, 'cancelOrder']);
    Route::get('total-order-amount', [OrderController::class, 'totalOrderAmount']);

    Route::get('usalon-services',[BSalonServiceController::class,'salonwiseService']);


    Route::post('review',[ReviewController::class,'store']);

});

Route::resource('/sliders', SliderController::class)->only('index');
