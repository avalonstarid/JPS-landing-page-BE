<?php

namespace App\Http\Controllers\V1\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\AppVersionRequest;
use App\Models\AppVersion;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;
use Throwable;

#[Group("Settings", "API Endpoint for Settings.")]
#[Subgroup("App Version", "API endpoint for app version.")]
class AppVersionController extends Controller
{
	/**
	 * Get Data
	 *
	 * @return JsonResponse
	 */
	public function index(): JsonResponse
	{
		$data = AppVersion::first();

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $data,
		);
	}


	/**
	 * Insert Data
	 *
	 * @param AppVersionRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(AppVersionRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', AppVersion::class);

			DB::beginTransaction();

			$data = AppVersion::updateOrCreate(['id' => 1], $request->validated());

			DB::commit();

			return $this->response(
				message: 'Setting App Version Berhasil.',
				data: $data,
			);
		} catch (Exception $e) {
			DB::rollBack();

			return $this->response(
				message: $e->getMessage(),
				status_code: $e->getCode(),
			);
		}
	}
}
