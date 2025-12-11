<?php

use App\Http\Controllers\V1\Master\EnumController;
use App\Http\Controllers\V1\Master\EnumTypeController;
use App\Http\Controllers\V1\Master\ProductController;
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
