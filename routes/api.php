<?php

use App\Http\Controllers\V1\ContactUsController;
use App\Http\Controllers\V1\Landing\BerandaController;
use App\Http\Controllers\V1\Landing\BeritaController;
use App\Http\Controllers\V1\Landing\BlogController;
use App\Http\Controllers\V1\Landing\FooterController;
use App\Http\Controllers\V1\Landing\HubungiKamiController;
use App\Http\Controllers\V1\Landing\KarirController;
use App\Http\Controllers\V1\Landing\KeberlanjutanController;
use App\Http\Controllers\V1\Landing\LiniBisnisController;
use App\Http\Controllers\V1\Landing\PengumumanController;
use App\Http\Controllers\V1\Landing\ProdukController;
use App\Http\Controllers\V1\Landing\RelasiInvestorController;
use App\Http\Controllers\V1\Landing\TentangPerusahaanController;
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

// Landing Page
Route::get('', [BerandaController::class, 'index']);
Route::get('berita', [BeritaController::class, 'index']);
Route::get('berita-list', [BeritaController::class, 'list']);
Route::get('berita/detail/{slug}', [BeritaController::class, 'detail']);
Route::get('blog', [BlogController::class, 'index']);
Route::get('blog-list', [BlogController::class, 'list']);
Route::get('blog/detail/{slug}', [BlogController::class, 'detail']);
Route::get('footer', [FooterController::class, 'index']);
Route::get('hubungi-kami', [HubungiKamiController::class, 'index']);
Route::get('karir', [KarirController::class, 'index']);
Route::get('karir-list', [KarirController::class, 'list']);
Route::get('karir/detail/{slug}', [KarirController::class, 'detail']);
Route::get('keberlanjutan-laporan-list', [KeberlanjutanController::class, 'laporanList']);
Route::get('keberlanjutan/{slug}', [KeberlanjutanController::class, 'index']);
Route::get('lini-bisnis/{slug}', [LiniBisnisController::class, 'index']);
Route::get('lini-bisnis-list', [LiniBisnisController::class, 'list']);
Route::get('pengumuman', [PengumumanController::class, 'index']);
Route::get('pengumuman-list', [PengumumanController::class, 'list']);
Route::get('pengumuman/detail/{slug}', [PengumumanController::class, 'detail']);
Route::get('produk', [ProdukController::class, 'index']);
Route::get('relasi-investor/laporan-keuangan/list', [RelasiInvestorController::class, 'laporanKeuangan']);
Route::get('relasi-investor/{slug}/list', [RelasiInvestorController::class, 'list']);
Route::get('relasi-investor/{slug}', [RelasiInvestorController::class, 'index']);
Route::get('tentang-perusahaan', [TentangPerusahaanController::class, 'index']);
// End Landing Page

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
