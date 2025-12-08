<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\NotificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:web,sanctum', 'optimizeImages'])->group(function () {
	Route::prefix('master')->group(__DIR__ . '/api/v1/master.php');

	Route::controller(NotificationController::class)->prefix('notifications')->group(function () {
		Route::put('mark-as-read/{id}', 'markAsRead');
		Route::post('read-all', 'readAll');
		Route::get('unread-count', 'unreadCount');
		Route::get('', 'index');
	});

	Route::prefix('settings')->group(__DIR__ . '/api/v1/settings.php');

	Route::prefix('user-management')->group(__DIR__ . '/api/v1/user-management.php');
});

Route::controller(AuthController::class)->prefix('auth')->group(function () {
	Route::post('login/{type}', 'login');
	Route::post('register', 'register');
	Route::post('forgot-password', 'forgotPassword');
	Route::post('forgot-password/verify', 'verifyToken');
	Route::post('reset-password', 'resetPassword');
	Route::post('request-otp', 'reqOtp');
	Route::post('verify-otp/{type}', 'verifyOtp');

	Route::middleware('auth:sanctum')->group(function () {
		Route::post('logout/{type}', 'logout');
		Route::get('fetch/{type}', 'fetch');
		Route::middleware('optimizeImages')->post('update_avatar', 'updateAvatar');
		Route::put('update-profile', 'updateProfile');
		Route::put('update-password', 'updatePassword');
	});
});
