<?php

namespace App\Http\Controllers\V1\Landing;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Spatie\Analytics\Period;

class FooterController extends Controller
{
	public function index()
	{
		$data = Cache::remember('landing:footer', 3600, function () {
			$company = Cache::rememberForever('settings:company', function () {
				return Setting::where('group', 'company')->get();
			})->mapWithKeys(function ($item) {
				if ($item->type === 'image') {
					return [$item->key => $item->getFirstMediaUrl($item->key, 'thumb')];
				}

				return [$item->key => $item->value];
			});

			return [
				// Company
				'company' => $company,

				// Visitor
				'visitor' => [
					'total' => 0,
					'today' => 0,
					'month' => 0,
					'year' => 0,
				],
			];
		});

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $data,
		);
	}
}
