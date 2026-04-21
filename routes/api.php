<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\ResetPasswordController;
use App\Http\Controllers\Api\V1\Auth\SocialLoginController;
use App\Http\Controllers\Api\V1\Auth\UserController;
use App\Http\Controllers\Api\V1\ChatController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\FirebaseTokenController;
use App\Http\Controllers\Api\V1\Frontend\CategoryController;
use App\Http\Controllers\Api\V1\Frontend\FaqController;
use App\Http\Controllers\Api\V1\Frontend\HomeController;
use App\Http\Controllers\Api\V1\Frontend\ImageController;
use App\Http\Controllers\Api\V1\Frontend\PageController;
use App\Http\Controllers\Api\V1\Frontend\PostController;
use App\Http\Controllers\Api\V1\Frontend\SettingsController;
use App\Http\Controllers\Api\V1\Frontend\SocialLinksController;
use App\Http\Controllers\Api\V1\Frontend\SubcategoryController;
use App\Http\Controllers\Api\V1\Frontend\SubscriberController;
use App\Http\Controllers\Api\V1\MCPController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\PrayerTimesController;
use App\Http\Controllers\Api\V2\Gateway\PaymentStatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::prefix('v1')->name('v1')->group(function () {

    Route::get('/page/home', [HomeController::class, 'index']);

    Route::get('/category', [categoryController::class, 'index']);
    Route::get('/subcategory', [SubcategoryController::class, 'index']);

    Route::get('/social/links', [SocialLinksController::class, 'index']);
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::get('/faq', [FaqController::class, 'index']);

    Route::post('subscriber/store', [SubscriberController::class, 'store'])->name('api.subscriber.store');

    /*
    # Post
    */
    Route::middleware(['auth:api'])->controller(PostController::class)->prefix('auth/post')->group(function () {
        Route::get('/', 'index');
        Route::post('/store', 'store');
        Route::get('/show/{id}', 'show');
        Route::post('/update/{id}', 'update');
        Route::delete('/delete/{id}', 'destroy');
    });

    Route::get('/posts', [PostController::class, 'posts']);
    Route::get('/post/show/{post_id}', [PostController::class, 'post']);

    Route::middleware(['auth:api'])->controller(ImageController::class)->prefix('auth/post/image')->group(function () {
        Route::get('/', 'index');
        Route::post('/store', 'store');
        Route::get('/delete/{id}', 'destroy');
    });

    Route::get('dynamic/page', [PageController::class, 'index']);
    Route::get('dynamic/page/show/{slug}', [PageController::class, 'show']);

    /*
    # Auth Route
    */

    Route::group(['middleware' => 'guest:api'], function ($router) {
        // register
        Route::post('register', [RegisterController::class, 'register']);
        Route::post('/verify-email', [RegisterController::class, 'VerifyEmail']);
        Route::post('/resend-otp', [RegisterController::class, 'ResendOtp']);
        Route::post('/verify-otp', [RegisterController::class, 'VerifyEmail']);
        // login
        Route::post('login', [LoginController::class, 'login'])->name('api.login');
        // forgot password
        Route::post('/forget-password', [ResetPasswordController::class, 'forgotPassword']);
        Route::post('/otp-token', [ResetPasswordController::class, 'MakeOtpToken']);
        Route::post('/reset-password', [ResetPasswordController::class, 'ResetPassword']);
        // social login
        Route::post('/social-login', [SocialLoginController::class, 'SocialLogin']);
    });

    Route::group(['middleware' => ['auth:api', 'api-otp']], function ($router) {
        Route::get('/refresh-token', [LoginController::class, 'refreshToken']);
        Route::post('/logout', [LogoutController::class, 'logout']);
        Route::get('/me', [UserController::class, 'me']);
        Route::get('/account/switch', [UserController::class, 'accountSwitch']);
        Route::post('/update-profile', [UserController::class, 'updateProfile']);
        Route::post('/update-avatar', [UserController::class, 'updateAvatar']);
        Route::delete('/delete-profile', [UserController::class, 'destroy']);
    });

    /*
    # Firebase Notification Route
    */

    Route::middleware(['auth:api'])->controller(FirebaseTokenController::class)->prefix('firebase')->group(function () {
        Route::get('test', 'test');
        Route::post('token/add', 'store');
        Route::post('token/get', 'getToken');
        Route::post('token/delete', 'deleteToken');
    });

    /*
    # In App Notification Route
    */

    Route::middleware(['auth:api'])->controller(NotificationController::class)->prefix('notify')->group(function () {
        Route::get('test', 'test');
        Route::get('/', 'index');
        Route::get('read/{id?}', 'read');
    });

    /*
    # Chat Route
    */

    Route::middleware(['auth:api'])->controller(ChatController::class)->prefix('auth/chat')->group(function () {
        Route::get('/list', 'list');
        Route::post('/send/{receiver_id}', 'send');
        Route::get('/conversation/{receiver_id}', 'conversation');
        Route::get('/room/{receiver_id}', 'room');
        Route::get('/search', 'search');
        Route::get('/seen/all/{receiver_id}', 'seenAll');
        Route::get('/seen/single/{chat_id}', 'seenSingle');
    });

    /*
    # CMS
    */

    Route::prefix('cms')->name('cms.')->group(function () {
        Route::get('home', [HomeController::class, 'index'])->name('home');
    });

    /*
    # prayer time
    # http:://127.0.0.1:8000/api/prayer-times?date=2025-12-25&lat=23.7018&lng=90.3742&timezone=6&method=1
    # http:://127.0.0.1:8000/api/prayer-times/today?lat=23.7018&lng=90.3742&timezone=6&method=1
    */
    Route::prefix('prayer-times')->group(function () {
        Route::get('/', [PrayerTimesController::class, 'index']);
        Route::get('/today', [PrayerTimesController::class, 'today']);
        Route::get('/methods', [PrayerTimesController::class, 'methods']);
    });

    Route::post('contact/store', [ContactController::class, 'store'])->name('contact.store');


    /*
    # test code
    */
    Route::get('/users', [UserController::class, 'users']);

    Route::get('telegram/messages', function () {

        $token = config('services.telegram.token');
        $chatId = config('services.telegram.channel');

        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        Http::post($url, [
            'chat_id' => $chatId,
            'text' => "hello from laravel 123",
            'parse_mode' => 'HTML'
        ]);
    });

    Route::get('/user-by-name', function (Request $request) {
        $name = $request->input('name');

        return \App\Models\User::where('name', 'LIKE', "%$name%")
            ->select('name', 'email')
            ->get();
    });

    Route::get('/user-email', function (Request $request) {

        $name = $request->input('name');

        $users = \App\Models\User::where('name', 'LIKE', "%$name%")
            ->select('name', 'email')
            ->get();

        // Gemini API call
        $response = Http::post('https://generativelanguage.googleapis.com/v1/models/gemini-1.5-pro:generateContent?key=gen-lang-client-0194670477', [
            "contents" => [
                [
                    "parts" => [
                        [
                            "text" => "User data: " . $users->toJson() . ". 
                            Give a clean response with name and email."
                        ]
                    ]
                ]
            ]
        ]);

        return $response->json();
    });
    
});

Route::prefix('v2')->name('v2')->group(function () {
    Route::get('/payment/success', [PaymentStatusController::class, 'success']);
    Route::get('/payment/cancel', [PaymentStatusController::class, 'cancel']);
});
