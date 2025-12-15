<?php

use App\Http\Controllers\V1\Master\EnumController;
use App\Http\Controllers\V1\Master\EnumTypeController;
use App\Http\Controllers\V1\Master\ProductController;
use App\Http\Controllers\V1\Master\StandardController;
use App\Http\Controllers\V1\Master\VisionMissionController;
use Illuminate\Support\Facades\Route;

Route::controller(EnumController::class)->group(function () {
	Route::delete('enums/bulk-destroy', 'bulkDestroy');
	Route::apiResource('enums', EnumController::class);
});

Route::controller(EnumTypeController::class)->group(function () {
	Route::delete('enum-types/bulk-destroy', 'bulkDestroy');
	Route::apiResource('enum-types', EnumTypeController::class);
});

Route::controller(ProductController::class)->group(function () {
	Route::delete('products/bulk-destroy', 'bulkDestroy');
	Route::apiResource('products', ProductController::class);
});

Route::controller(StandardController::class)->group(function () {
	Route::delete('standards/bulk-destroy', 'bulkDestroy');
	Route::apiResource('standards', StandardController::class);
});

Route::controller(VisionMissionController::class)->group(function () {
	Route::delete('vision-missions/bulk-destroy', 'bulkDestroy');
	Route::apiResource('vision-missions', VisionMissionController::class);
});
