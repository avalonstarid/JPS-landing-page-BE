<?php

use App\Http\Controllers\V1\UserManagement\PermissionController;
use App\Http\Controllers\V1\UserManagement\RoleController;
use App\Http\Controllers\V1\UserManagement\SessionController;
use App\Http\Controllers\V1\UserManagement\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(PermissionController::class)->group(function () {
	Route::delete('permissions/bulk-destroy', 'bulkDestroy');
	Route::apiResource('permissions', PermissionController::class);
});

Route::controller(RoleController::class)->group(function () {
	Route::delete('roles/bulk-destroy', 'bulkDestroy');
	Route::apiResource('roles', RoleController::class);
});

Route::controller(SessionController::class)->group(function () {
	Route::get('sessions/current', 'current');
	Route::delete('sessions/logout-other', 'destroyOther');
	Route::delete('sessions/{id}', 'destroy');
	Route::get('sessions', 'index');
});

Route::controller(UserController::class)->group(function () {
	Route::delete('users/bulk-destroy', 'bulkDestroy');
	Route::get('users/export', 'export');
	Route::apiResource('users', UserController::class);
});
