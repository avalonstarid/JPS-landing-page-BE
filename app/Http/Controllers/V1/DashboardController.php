<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

class DashboardController extends Controller
{
	/**
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function mostVisited(Request $request)
	{
		$startDate = Carbon::parse($request->query('start_date', Carbon::now()->startOfMonth()))->startOfDay();
		$endDate = Carbon::parse($request->query('end_date', Carbon::now()->endOfMonth()))->endOfDay();

		$data = Analytics::fetchMostVisitedPages(Period::create($startDate, $endDate), 5);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $data,
		);
	}

	/**
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function topBrowsers(Request $request)
	{
		$startDate = Carbon::parse($request->query('start_date', Carbon::now()->startOfMonth()))->startOfDay();
		$endDate = Carbon::parse($request->query('end_date', Carbon::now()->endOfMonth()))->endOfDay();

		$data = Analytics::fetchTopBrowsers(Period::create($startDate, $endDate), 5);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $data,
		);
	}

	/**
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function topCountries(Request $request)
	{
		$startDate = Carbon::parse($request->query('start_date', Carbon::now()->startOfMonth()))->startOfDay();
		$endDate = Carbon::parse($request->query('end_date', Carbon::now()->endOfMonth()))->endOfDay();

		$data = Analytics::fetchTopCountries(Period::create($startDate, $endDate), 5);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $data,
		);
	}

	/**
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function topOperatingSystems(Request $request)
	{
		$startDate = Carbon::parse($request->query('start_date', Carbon::now()->startOfMonth()))->startOfDay();
		$endDate = Carbon::parse($request->query('end_date', Carbon::now()->endOfMonth()))->endOfDay();

		$data = Analytics::fetchTopOperatingSystems(Period::create($startDate, $endDate), 5);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $data,
		);
	}

	/**
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function topReferrers(Request $request)
	{
		$startDate = Carbon::parse($request->query('start_date', Carbon::now()->startOfMonth()))->startOfDay();
		$endDate = Carbon::parse($request->query('end_date', Carbon::now()->endOfMonth()))->endOfDay();

		$data = Analytics::fetchTopReferrers(Period::create($startDate, $endDate), 5);

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $data,
		);
	}

	/**
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function totalVisitor(Request $request)
	{
		$startDate = Carbon::parse($request->query('start_date', Carbon::now()->startOfMonth()))->startOfDay();
		$endDate = Carbon::parse($request->query('end_date', Carbon::now()->endOfMonth()))->endOfDay();

		$data = Analytics::fetchTotalVisitorsAndPageViews(Period::create($startDate, $endDate));

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $data,
		);
	}
}
