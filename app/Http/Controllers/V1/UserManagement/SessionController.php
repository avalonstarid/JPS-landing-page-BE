<?php

namespace App\Http\Controllers\V1\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\SessionModel;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group("User Management", "API Endpoint for User Management.")]
#[Subgroup("Session", "API endpoint for session management.")]
class SessionController extends Controller
{
	/**
	 * Get Data
	 *
	 * @return JsonResponse
	 */
	public function index(): JsonResponse
	{
		$data = SessionModel::whereNot('id', session()->getId())
			->orderBy('last_activity', 'desc')
			->get();

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $data,
		);
	}

	/**
	 * Get Current Session
	 *
	 * @return JsonResponse
	 */
	public function current()
	{
		$data = SessionModel::where('id', session()->getId())->firstOrFail();

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $data,
		);
	}

	/**
	 * Delete Data
	 *
	 * @param $id
	 *
	 * @return JsonResponse
	 */
	public function destroy($id)
	{
		$session = SessionModel::where('id', $id)->firstOrFail();
		$session->delete();

		return $this->response(
			message: 'Berhasil menghapus sesi.',
		);
	}

	/**
	 * Delete Other Session
	 *
	 * @return JsonResponse
	 */
	public function destroyOther()
	{
		$session = SessionModel::whereNot('id', session()->getId());
		$session->delete();

		return $this->response(
			message: 'Berhasil menghapus sesi lainnya.',
		);
	}
}
