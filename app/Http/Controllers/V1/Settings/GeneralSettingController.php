<?php

namespace App\Http\Controllers\V1\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\CompanyProfileRequest;
use App\Models\Setting;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class GeneralSettingController extends Controller
{
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

			DB::beginTransaction();

			$validated = $request->validated();

			foreach ($validated as $key => $value) {
				Setting::updateOrCreate(
					[
						'group' => 'company',
						'key' => $key,
					],
					[
						'value' => $value,
						'type' => $key == 'company_social' ? 'json' : 'string',
					],
				);
			}

			cache()->forget('site_settings');

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah company profile.',
				data: Setting::where('group', 'company')->get(),
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
