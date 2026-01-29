<?php

namespace App\Http\Controllers\V1\Landing;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Spatie\Analytics\Facades\Analytics;
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

			// Analytics
			$now = Carbon::now();
			$periodToday = Period::create($now->copy()->startOfDay(), $now->copy()->endOfDay());
			$periodMonth = Period::create($now->copy()->startOfMonth(), $now->copy()->endOfMonth());
			$periodYear = Period::create($now->copy()->startOfYear(), $now->copy()->endOfYear());

			return [
				// Company
				'company' => $company,

				// Visitor
				'visitor' => [
					'total' => Analytics::fetchTotalVisitorsAndPageViews(Period::years(3))->sum('screenPageViews'),
					'today' => Analytics::fetchTotalVisitorsAndPageViews($periodToday)->sum('screenPageViews'),
					'month' => Analytics::fetchTotalVisitorsAndPageViews($periodMonth)->sum('screenPageViews'),
					'year' => Analytics::fetchTotalVisitorsAndPageViews($periodYear)->sum('screenPageViews'),
				],
			];
		});

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $data,
		);
	}
}
