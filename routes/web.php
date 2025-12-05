<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('download/{path}', function (Request $request, string $path) {
	if (!$request->hasValidSignature()) {
		return redirect('/404');
	}

	return Storage::disk('download')->download($path);
})->name('files.download')->signatureParameters();
