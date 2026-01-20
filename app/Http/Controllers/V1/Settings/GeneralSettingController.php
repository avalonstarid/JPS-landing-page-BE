<?php

namespace App\Http\Controllers\V1\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\CompanyProfileRequest;
use App\Http\Requests\Settings\Landing\LandingBerandaRequest;
use App\Http\Requests\Settings\Landing\LandingBeritaRequest;
use App\Http\Requests\Settings\Landing\LandingBlogRequest;
use App\Http\Requests\Settings\Landing\LandingHubungiKamiRequest;
use App\Http\Requests\Settings\Landing\LandingLiniBisnisRequest;
use App\Http\Requests\Settings\Landing\LandingTentangPerusahaanRequest;
use App\Models\Setting;
use App\Traits\ManagesSettings;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;
use Throwable;

#[Group('Settings', 'API Endpoint for Settings.')]
#[Subgroup('General Setting', 'API endpoint for general setting.')]
class GeneralSettingController extends Controller
{
	use ManagesSettings;

	public function show(string $group)
	{
		$data = Cache::rememberForever("settings:$group", function () use ($group) {
			return Setting::where('group', $group)->get();
		})->mapWithKeys(function ($item) {
			if ($item->type === 'image') {
				return [$item->key => $item->getFirstMediaUrl($item->key, 'thumb')];
			}

			return [$item->key => $item->value];
		});

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $data,
		);
	}

	/**
	 * Update Setting Company Profile
	 *
	 * @return JsonResponse
	 *
	 * @throws Throwable
	 */
	public function companyProfile(CompanyProfileRequest $request)
	{
		try {
			$this->authorize('update_company', Setting::class);

			$validated = $request->validated();

			$updatedData = $this->updateSettings($validated, 'company');

			Cache::forget('settings:company');

			return $this->response(
				message: 'Berhasil menyimpan data.',
				data: $updatedData,
			);
		} catch (Exception $e) {
			return $this->response(
				message: $e->getMessage(),
				status_code: $e->getCode(),
			);
		}
	}

	/**
	 * Update Landing Page Beranda
	 *
	 * @param LandingBerandaRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function landingBeranda(LandingBerandaRequest $request)
	{
		try {
			$this->authorize('update_landing', Setting::class);

			$validated = $request->validated();

			$updatedData = $this->updateSettings($validated, 'landing_beranda');

			Cache::forget('settings:landing_beranda');

			return $this->response(
				message: 'Berhasil menyimpan data.',
				data: $updatedData,
			);
		} catch (Exception $e) {
			return $this->response(
				message: $e->getMessage(),
				status_code: $e->getCode(),
			);
		}
	}

	/**
	 * Update Landing Page Berita
	 *
	 * @param LandingBeritaRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function landingBerita(LandingBeritaRequest $request)
	{
		try {
			$this->authorize('update_landing', Setting::class);

			$validated = $request->validated();

			$updatedData = $this->updateSettings($validated, 'landing_berita');

			Cache::forget('settings:landing_berita');

			return $this->response(
				message: 'Berhasil menyimpan data.',
				data: $updatedData,
			);
		} catch (Exception $e) {
			return $this->response(
				message: $e->getMessage(),
				status_code: $e->getCode(),
			);
		}
	}

	/**
	 * Update Landing Page Blog
	 *
	 * @param LandingBlogRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function landingBlog(LandingBlogRequest $request)
	{
		try {
			$this->authorize('update_landing', Setting::class);

			$validated = $request->validated();

			$updatedData = $this->updateSettings($validated, 'landing_blog');

			Cache::forget('settings:landing_blog');

			return $this->response(
				message: 'Berhasil menyimpan data.',
				data: $updatedData,
			);
		} catch (Exception $e) {
			return $this->response(
				message: $e->getMessage(),
				status_code: $e->getCode(),
			);
		}
	}

	/**
	 * Update Landing Page Hubungi Kami
	 *
	 * @param LandingHubungiKamiRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function landingHubungiKami(LandingHubungiKamiRequest $request)
	{
		try {
			$this->authorize('update_landing', Setting::class);

			$validated = $request->validated();

			$updatedData = $this->updateSettings($validated, 'landing_hubungi_kami');

			Cache::forget('settings:landing_hubungi_kami');

			return $this->response(
				message: 'Berhasil menyimpan data.',
				data: $updatedData,
			);
		} catch (Exception $e) {
			return $this->response(
				message: $e->getMessage(),
				status_code: $e->getCode(),
			);
		}
	}

	/**
	 * Update Landing Page Lini Bisnis
	 *
	 * @param LandingLiniBisnisRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function landingLiniBisnis(LandingLiniBisnisRequest $request)
	{
		try {
			$this->authorize('update_landing', Setting::class);

			$validated = $request->validated();

			$updatedData = $this->updateSettings($validated, 'landing_lini_bisnis');

			Cache::forget('settings:landing_lini_bisnis');

			return $this->response(
				message: 'Berhasil menyimpan data.',
				data: $updatedData,
			);
		} catch (Exception $e) {
			return $this->response(
				message: $e->getMessage(),
				status_code: $e->getCode(),
			);
		}
	}

	/**
	 * Update Landing Page Tentang Perusahaan
	 *
	 * @param LandingTentangPerusahaanRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function landingTentangPerusahaan(LandingTentangPerusahaanRequest $request)
	{
		try {
			$this->authorize('update_landing', Setting::class);

			$validated = $request->validated();

			$updatedData = $this->updateSettings($validated, 'landing_tentang_perusahaan');

			Cache::forget('settings:landing_tentang_perusahaan');

			return $this->response(
				message: 'Berhasil menyimpan data.',
				data: $updatedData,
			);
		} catch (Exception $e) {
			return $this->response(
				message: $e->getMessage(),
				status_code: $e->getCode(),
			);
		}
	}
}
