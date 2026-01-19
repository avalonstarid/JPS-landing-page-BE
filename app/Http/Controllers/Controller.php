<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
	use AuthorizesRequests, ValidatesRequests;

	/**
	 * Custom Response
	 *
	 * @param            $message
	 * @param            $data
	 * @param            $errors
	 * @param int|string $status_code
	 *
	 * @return JsonResponse
	 */
	public function response($message, $data = null, $errors = null, int|string $status_code = 200): JsonResponse
	{
		if (!is_int($status_code) || $status_code < 200 || $status_code > 500) {
			$status_code = 400;
		}

		return response()->json([
			'success' => substr($status_code, 0, 1) == 2,
			'message' => $message,
			'data' => $data,
			'errors' => $errors,
		], $status_code);
	}

	/**
	 * Custom Response New
	 *
	 * @param            $message
	 * @param            $data
	 * @param            $errors
	 * @param int|string $status_code
	 *
	 * @return JsonResponse
	 */
	public function responseNew($message, $data = null, $errors = null, int|string $status_code = 200): JsonResponse
	{
		if (!is_int($status_code) || $status_code < 200 || $status_code > 500) {
			$status_code = 400;
		}

		$responseStructure = [
			'success' => str_starts_with((string)$status_code, '2'),
			'message' => $message,
			'errors' => $errors,
		];

		if ($data instanceof ResourceCollection &&
			$data->resource instanceof AbstractPaginator) {

			$paginated = $data->response()->getData(true);

			$responseStructure['data'] = $paginated['data'];

			if (isset($paginated['meta'])) {
				$responseStructure = array_merge($responseStructure, $paginated['meta']);
			}
			if (isset($paginated['links'])) {
				$responseStructure['links'] = $paginated['links'];
			}
		} else {
			$responseStructure['data'] = $data;
		}

		return response()->json($responseStructure, $status_code);
	}
}
