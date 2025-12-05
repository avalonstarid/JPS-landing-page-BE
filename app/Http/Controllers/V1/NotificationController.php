<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Spatie\QueryBuilder\QueryBuilder;

#[Group("Notification", "API Endpoint for notification Management.")]
class NotificationController extends Controller
{
	/**
	 * Get Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[QueryParam('page', 'int', required: false, example: 1)]
	#[QueryParam('rows', 'int', required: false, example: 10)]
	public function index(Request $request): JsonResponse
	{
		$query = QueryBuilder::for(
			subject: auth()->user()->notifications(),
		)->allowedSorts(
			sorts: ['created_at'],
		);

		$request->merge([
			'page' => $request->input('page', 1),
		]);

		$data = $query->fastPaginate(perPage: $request->input('rows', 10))->withQueryString();

		return response()->json(array_merge([
			'success' => true,
			'message' => 'Berhasil mengambil data.',
		], $data->toArray()));
	}

	/**
	 * Get Unread Count
	 *
	 * @return JsonResponse
	 */
	public function unreadCount()
	{
		return $this->response(
			message: 'Berhasil mengambil data.',
			data: auth()->user()->unreadNotifications()->count(),
		);
	}

	/**
	 * Read All
	 *
	 * @return JsonResponse
	 */
	public function readAll()
	{
		auth()->user()->unreadNotifications->markAsRead();

		return $this->response(
			message: 'Semua notifikasi berhasil di mark as read.',
		);
	}

	/**
	 * Mark As Read
	 *
	 * @param $id
	 *
	 * @return JsonResponse
	 */
	public function markAsRead($id)
	{
		$notification = auth()->user()->notifications()->findOrFail($id);
		$notification->markAsRead();

		return $this->response(
			message: 'Notifikasi berhasil di mark as read.',
		);
	}
}
