<?php

use App\Http\Controllers\V1\Master\BankController;
use App\Http\Controllers\V1\Master\CategoryController;
use App\Http\Controllers\V1\Master\EnumController;
use App\Http\Controllers\V1\Master\EnumTypeController;
use Illuminate\Support\Facades\Route;

Route::controller(BankController::class)->group(function () {
	Route::delete('bank/bulk-destroy', 'bulkDestroy');
	Route::apiResource('bank', BankController::class);
});

Route::controller(CategoryController::class)->group(function () {
	Route::delete('categories/bulk-destroy', 'bulkDestroy');
	Route::apiResource('categories', CategoryController::class);
});

Route::controller(EnumController::class)->group(function () {
	Route::delete('enums/bulk-destroy', 'bulkDestroy');
	Route::apiResource('enums', EnumController::class);
});

Route::controller(EnumTypeController::class)->group(function () {
	Route::delete('enum-types/bulk-destroy', 'bulkDestroy');
	Route::apiResource('enum-types', EnumTypeController::class);
});
