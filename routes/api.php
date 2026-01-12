<?php

use App\Http\Controllers\V1\ContactUsController;
use App\Http\Controllers\V1\LandingController;
use App\Models\AppVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(LandingController::class)->group(function () {
	Route::get('', 'index');
});

Route::get('app-version', function () {
	$data = AppVersion::first();

	return response()->json([
		'success' => true,
		'code' => $data?->code,
		'name' => $data?->name,
		'url' => $data?->url,
	]);
});

Route::post('contact-us', [ContactUsController::class, 'store']);

Route::get('redirect-login', function () {
	return redirect(config('app.frontend_url') . '/auth/sign-in');
})->name('login');

Route::prefix('v1')->group(__DIR__ . '/api-v1.php');

Route::get('download/{path}', function (Request $request, string $path) {
	if (!$request->hasValidSignature()) {
		return redirect('/404');
	}

	return Storage::disk('download')->download($path);
})->name('files.download')->signatureParameters();
