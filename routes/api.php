<?php

use App\Http\Controllers\Company\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {

    // Auth routes
    Route::group(['middleware' => ['throttle:30,1']], function () {
        // Maximum 30 request in 1 min
        Route::post('/login', [UserAuthController::class, 'login'])->name('user.login');
        Route::post('/signup', [UserAuthController::class, 'signup'])->name('user.signup');
        Route::post('/forget-password', [UserAuthController::class, 'forgetPassword']);
        Route::post('/reset-password', [UserAuthController::class, 'resetPassword']);
    });

    Route::group(['middleware' => ['jwt.verify']], function () {
        Route::post('logout', [UserAuthController::class, 'logout']);
        Route::post('refresh-token', [UserAuthController::class, 'refresh'])->name('refresh.token');
        Route::get('user', [UserAuthController::class, 'me'])->name('user.me');
        Route::put('user/password/update/{id}', [UserAuthController::class, 'changePassword'])->name('user.update.password');
        Route::put('user/update/{id}', [UserAuthController::class, 'updateUser'])->name('user.update');
    });
});
