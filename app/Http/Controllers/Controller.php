<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
	use AuthorizesRequests, ValidatesRequests;

	/**
	 * Custom Response
	 *
	 * @param            $message
	 * @param null       $data
	 * @param null       $errors
	 * @param int|string $status_code
	 *
	 * @return JsonResponse
	 */
	public function response($message, $data = null, $errors = null, int|string $status_code = 200): JsonResponse
	{
		if ($status_code < 200 || $status_code > 500 || !is_int($status_code)) {
			$status_code = 400;
		}

		return response()->json([
			'success' => substr($status_code, 0, 1) == 2,
			'message' => $message,
			'data' => $data,
			'errors' => $errors,
		], $status_code);
	}
}
