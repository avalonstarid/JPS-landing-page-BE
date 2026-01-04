<?php

use App\Http\Controllers\V1\Settings\AppVersionController;
use App\Http\Controllers\V1\Settings\BusinessLineController;
use App\Http\Controllers\V1\Settings\GeneralSettingController;
use App\Http\Controllers\V1\Settings\MenuController;
use App\Http\Controllers\V1\Settings\System\AuditLogController;
use Illuminate\Support\Facades\Route;

Route::apiResource('app-versions', AppVersionController::class)->only(['index', 'store']);

Route::controller(BusinessLineController::class)->group(function () {
	Route::delete('business-lines/bulk-destroy', 'bulkDestroy');
	Route::apiResource('business-lines', BusinessLineController::class);
});

Route::controller(GeneralSettingController::class)->prefix('general-settings')->group(function () {
	Route::post('company-profile', 'companyProfile');
});

Route::controller(MenuController::class)->group(function () {
	Route::delete('menu/bulk-destroy', 'bulkDestroy');
	Route::get('menu/get-menu', 'getMenu');
	Route::apiResource('menu', MenuController::class);
});

Route::prefix('system')->group(function () {
	Route::controller(AuditLogController::class)->group(function () {
		Route::delete('audit-logs/bulk-destroy', 'bulkDestroy');
		Route::apiResource('audit-logs', AuditLogController::class)->only(['index', 'show', 'destroy']);
	});
});
