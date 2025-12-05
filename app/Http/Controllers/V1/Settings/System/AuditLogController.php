<?php

namespace App\Http\Controllers\V1\Settings\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Subgroup;
use Spatie\Activitylog\Models\Activity;
use Spatie\QueryBuilder\QueryBuilder;

#[Group("Settings", "API Endpoint for Settings.")]
#[Subgroup("Audit Log", "API endpoint for audit log data.")]
class AuditLogController extends Controller
{
	/**
	 * Get Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[QueryParam('include', required: false, example: '', enum: ['causer', 'subject'])]
	#[QueryParam('rows', 'int', required: false, example: 10)]
	#[QueryParam('sort', required: false, example: 'created_at', enum: ['created_at'])]
	public function index(Request $request): JsonResponse
	{
		$query = QueryBuilder::for(
			subject: Activity::class,
		)->allowedSorts(
			sorts: ['created_at'],
		)->allowedIncludes(
			includes: [
				'causer',
				'subject',
			],
		);

		if ($request->input('all', '') == 1) {
			return $this->response(
				message: 'Berhasil mengambil data.',
				data: $query->get(),
			);
		} else {
			$request->merge([
				'page' => $request->input('page', 1),
			]);

			$data = $query->fastPaginate($request->input('rows', 10))->withQueryString();

			return response()->json(array_merge([
				'success' => true,
				'message' => 'Berhasil mengambil data.',
			], $data->toArray()));
		}
	}

	/**
	 * Get Detail Data
	 *
	 * @param Activity $auditLog
	 *
	 * @return JsonResponse
	 */
	public function show(Activity $auditLog): JsonResponse
	{
		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $auditLog->load([
				'causer',
			]),
		);
	}

	/**
	 * Delete Data
	 *
	 * @param Activity $auditLog
	 *
	 * @return JsonResponse
	 */
	public function destroy(Activity $auditLog): JsonResponse
	{
		$auditLog->delete();

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}

	/**
	 * Bulk Delete Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[BodyParam("data", "object[]", "List of id", example: [['id' => 1]])]
	public function bulkDestroy(Request $request): JsonResponse
	{
		$ids = collect($request->data)->pluck(value: 'id');

		Activity::whereIn('id', $ids)->delete();

		return $this->response(
			message: 'Berhasil menghapus data.',
		);
	}
}
