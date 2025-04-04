<?php

use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\UserController;
use App\Http\Controllers\Api\Auth\SocialLoginController;
use App\Http\Controllers\Api\Frontend\HomeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest:api'], function ($router) {
    //register
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('/verify-email', [RegisterController::class, 'VerifyEmail']);
    Route::post('/resend-otp', [RegisterController::class, 'ResendOtp']);
    Route::post('/verify-otp', [RegisterController::class, 'VerifyEmail']);
    //login
    Route::post('login', [LoginController::class, 'login'])->name('login');
    //forgot password
    Route::post('/forget-password', [ResetPasswordController::class, 'forgotPassword']);
    Route::post('/otp-token', [ResetPasswordController::class, 'MakeOtpToken']);
    Route::post('/reset-password', [ResetPasswordController::class, 'ResetPassword']);
    //social login
    Route::post('/social-login', [SocialLoginController::class, 'SocialLogin']);

    //page
    Route::get('/page/home', [HomeController::class, 'index']);
});

Route::group(['middleware' => 'auth:api | otp'], function ($router) {
    Route::get('/refresh-token', [LoginController::class, 'refreshToken']);
    Route::post('/logout', [LogoutController::class, 'logout']);
    Route::get('/me', [UserController::class, 'me']);
    Route::get('/account/switch', [UserController::class, 'accountSwitch']);
    Route::post('/update-profile', [UserController::class, 'updateProfile']);
    Route::post('/update-avatar', [UserController::class, 'updateAvatar']);
    Route::delete('/delete-profile', [UserController::class, 'deleteProfile']);
});

/* // Firebase Token Module
Route::get("firebase/test", [FirebaseTokenController::class, "test"]);
Route::post("firebase/token/add", [FirebaseTokenController::class, "store"]);
Route::post("firebase/token/get", [FirebaseTokenController::class, "getToken"]);
Route::post("firebase/token/delete", [FirebaseTokenController::class, "deleteToken"]);

//Notification
Route::controller(NotificationController::class)->prefix('notify')->group(function () {
    Route::get('/', 'index');
    Route::get('status/read/all', 'readAll');
    Route::get('status/read/{id}', 'readSingle');
}); */


Route::prefix('cms')->name('cms.')->group(function () {
    Route::get('home', [HomeController::class, 'index'])->name('home');
});