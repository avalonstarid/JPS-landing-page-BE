<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\BusinessLineController;
use App\Http\Controllers\V1\ContactUsController;
use App\Http\Controllers\V1\FaqController;
use App\Http\Controllers\V1\HistoricalTimelineController;
use App\Http\Controllers\V1\Investor\DocumentInvsController;
use App\Http\Controllers\V1\Investor\FinancialReportController;
use App\Http\Controllers\V1\JobApplicationController;
use App\Http\Controllers\V1\JobPostingController;
use App\Http\Controllers\V1\Keberlanjutan\TinjauanController;
use App\Http\Controllers\V1\MediaController;
use App\Http\Controllers\V1\NotificationController;
use App\Http\Controllers\V1\PostController;
use App\Http\Controllers\V1\TestimonialController;
use Illuminate\Support\Facades\Route;
use MeShaon\RequestAnalytics\Http\Controllers\Api\AnalyticsApiController;

Route::middleware(['auth:web,sanctum', 'optimizeImages'])->group(function () {
	Route::controller(AnalyticsApiController::class)->prefix('analytics')->group(function () {
		Route::get('overview', 'overview');
		Route::get('page-views', 'pageViews');
		Route::get('visitors', 'visitors');
	});

	Route::controller(BusinessLineController::class)->group(function () {
		Route::delete('business-lines/bulk-destroy', 'bulkDestroy');
		Route::apiResource('business-lines', BusinessLineController::class);
	});

	Route::controller(ContactUsController::class)->group(function () {
		Route::delete('contact-us/bulk-destroy', 'bulkDestroy');
		Route::apiResource('contact-us', ContactUsController::class)->except(['store'])->parameters([
			'contact-us' => 'contact_us',
		]);
	});

	Route::controller(FaqController::class)->group(function () {
		Route::delete('faq/bulk-destroy', 'bulkDestroy');
		Route::apiResource('faq', FaqController::class);
	});

	Route::controller(HistoricalTimelineController::class)->group(function () {
		Route::delete('historical-timelines/bulk-destroy', 'bulkDestroy');
		Route::apiResource('historical-timelines', HistoricalTimelineController::class);
	});

	Route::prefix('investor')->group(function () {
		Route::controller(DocumentInvsController::class)->prefix('document/{category:slug}')->group(function () {
			Route::delete('bulk-destroy', 'bulkDestroy');
			Route::get('', 'index');
			Route::post('', 'store');
			Route::get('{document}', 'show');
			Route::put('{document}', 'update');
			Route::delete('{document}', 'destroy');
		});

		Route::controller(FinancialReportController::class)->group(function () {
			Route::delete('financial-reports/bulk-destroy', 'bulkDestroy');
			Route::apiResource('financial-reports', FinancialReportController::class);
		});
	});

	Route::controller(JobApplicationController::class)->group(function () {
		Route::delete('job-applications/bulk-destroy', 'bulkDestroy');
		Route::apiResource('job-applications', JobApplicationController::class)->except(['update']);
	});

	Route::controller(JobPostingController::class)->group(function () {
		Route::delete('job-postings/bulk-destroy', 'bulkDestroy');
		Route::apiResource('job-postings', JobPostingController::class);
	});

	Route::controller(TinjauanController::class)->group(function () {
		Route::post('keberlanjutan/tinjauan', 'store');
		Route::get('keberlanjutan/tinjauan', 'show');
	});

	Route::prefix('master')->group(__DIR__ . '/api/v1/master.php');

	Route::controller(MediaController::class)->prefix('media')->group(function () {
		Route::delete('{media}', 'destroy');
		Route::post('', 'upload');
	});

	Route::controller(NotificationController::class)->prefix('notifications')->group(function () {
		Route::put('mark-as-read/{id}', 'markAsRead');
		Route::post('read-all', 'readAll');
		Route::get('unread-count', 'unreadCount');
		Route::get('', 'index');
	});

	Route::controller(PostController::class)->group(function () {
		Route::delete('posts/bulk-destroy', 'bulkDestroy');
		Route::apiResource('posts', PostController::class);
	});

	Route::prefix('settings')->group(__DIR__ . '/api/v1/settings.php');

	Route::controller(TestimonialController::class)->group(function () {
		Route::delete('testimonials/bulk-destroy', 'bulkDestroy');
		Route::apiResource('testimonials', TestimonialController::class);
	});

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
