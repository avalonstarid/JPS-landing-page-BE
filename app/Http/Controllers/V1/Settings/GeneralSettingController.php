<?php

namespace App\Http\Controllers\V1\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\CompanyProfileRequest;
use App\Models\Setting;
use App\Traits\ManagesSettings;
use Exception;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;
use Throwable;

#[Group("Settings", "API Endpoint for Settings.")]
#[Subgroup("General Setting", "API endpoint for general setting.")]
class GeneralSettingController extends Controller
{
	use ManagesSettings;

	/**
	 * Update Setting Company Profile
	 *
	 * @param CompanyProfileRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function companyProfile(CompanyProfileRequest $request)
	{
		try {
			$this->authorize('update_company', Setting::class);

			$validated = $request->validated();

			$updatedData = $this->updateSettings($validated, 'company');

			return $this->response(
				message: 'Berhasil mengubah company profile.',
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
