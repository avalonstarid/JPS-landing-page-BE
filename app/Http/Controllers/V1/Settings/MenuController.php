<?php

namespace App\Http\Controllers\V1\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\MenuRequest;
use App\Models\Settings\Menu;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Subgroup;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

#[Group("Settings", "API Endpoint for Settings.")]
#[Subgroup("Menu", "API endpoint for menu management.")]
class MenuController extends Controller
{
	/**
	 * Get Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[QueryParam('filter[search]', required: false, example: 'user', enum: ['search'])]
	#[QueryParam('include', required: false, example: 'parent', enum: ['parent'])]
	#[QueryParam('rows', 'int', required: false, example: 10)]
	#[QueryParam('sort', required: false, example: 'name', enum: ['order', 'title'])]
	public function index(Request $request): JsonResponse
	{
//		$this->authorize('viewAny', Menu::class);

		$query = QueryBuilder::for(
			subject: Menu::class,
		)->allowedSorts(
			sorts: ['order', 'title'],
		)->allowedFilters(
			filters: [
				AllowedFilter::callback('search', function (Builder $q, $value) {
					$q->whereAny(['title', 'to', 'icon'], 'LIKE', '%' . $value . '%');
				}),
			],
		)->allowedIncludes(
			includes: ['parent'],
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
	 * Insert Data
	 *
	 * @param MenuRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function store(MenuRequest $request): JsonResponse
	{
		try {
			$this->authorize('create', Menu::class);

			DB::beginTransaction();

			$data = Menu::create($request->safe()->except(['permissions', 'roles']));

			// Sync Roles & Permission
			$data->syncRoles($request->safe()->array('roles'));
			$data->syncPermissions($request->safe()->array('permissions'));

			// Disable Logging
			activity()->disableLogging();

			$sameOrder = Menu::where([
				'parent_id' => $data->parent_id,
				'order' => $data->order,
			])->first();

			if ($sameOrder) {
				$orderNew = Menu::where([
					['id', '!=', $data->id],
					['parent_id', '=', $data->parent_id],
					['order', '>=', $data->order],
				])->get();

				foreach ($orderNew as $item) {
					$item->order = $item->order + 1;
					$item->save();
				}
			}

			DB::commit();

			return $this->response(
				message: 'Berhasil menambah data.',
				data: $data,
				status_code: 201,
			);
		} catch (Exception $e) {
			DB::rollBack();

			return $this->response(
				message: $e->getMessage(),
				status_code: $e->getCode(),
			);
		}
	}

	/**
	 * Get Detail Data
	 *
	 * @param Menu $menu
	 *
	 * @return JsonResponse
	 */
	public function show(Menu $menu): JsonResponse
	{
//		$this->authorize('view', $menu);

		$menu = $menu->load('permissions:id', 'roles:id');

		$permissions = $menu->permissions->pluck('id');
		$roles = $menu->roles->pluck('id');
		$menu = $menu->toArray();
		$menu['permissions'] = $permissions;
		$menu['roles'] = $roles;

		return $this->response(
			message: 'Berhasil mengambil data.',
			data: $menu,
		);
	}

	/**
	 * Update Data
	 *
	 * @param MenuRequest $request
	 * @param Menu        $menu
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(MenuRequest $request, Menu $menu): JsonResponse
	{
		try {
			$this->authorize('update', $menu);

			DB::beginTransaction();

			$oldMenu = Menu::find($menu->id);
			$sameOrder = Menu::where([
				'parent_id' => $menu->parent_id,
				'order' => $menu->order,
			])->first();

			$menu->update($request->safe()->except(['permissions', 'roles']));

			// Sync Roles & Permission
			$menu->syncRoles($request->safe()->array('roles'));
			$menu->syncPermissions($request->safe()->array('permissions'));

			// Disable Logging
			activity()->disableLogging();

			if ($sameOrder) {
				if ($oldMenu) {
					$orderOld = Menu::where([
						['id', '!=', $menu->id],
						['parent_id', '=', $oldMenu->parent_id],
						['order', '>', $oldMenu->order],
					])->get();
					foreach ($orderOld as $item) {
						if ($item->order > 1) {
							$item->order = $item->order - 1;
							$item->save();
						}
					}
				}

				$orderNew = Menu::where([
					['id', '!=', $menu->id],
					['parent_id', '=', $menu->parent_id],
					['order', '>=', $menu->order],
				])->get();
				foreach ($orderNew as $item) {
					$item->order = $item->order + 1;
					$item->save();
				}
			}

			DB::commit();

			return $this->response(
				message: 'Berhasil mengubah data.',
				data: $menu,
			);
		} catch (Exception $e) {
			DB::rollBack();

			return $this->response(
				message: $e->getMessage(),
				status_code: $e->getCode(),
			);
		}
	}

	/**
	 * Delete Data
	 *
	 * @param Menu $menu
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function destroy(Menu $menu): JsonResponse
	{
		try {
			$this->authorize('delete', $menu);

			DB::beginTransaction();

			$menu->delete();

			$order = Menu::where([
				['id', '!=', $menu->id],
				['parent_id', '=', $menu->parent_id],
				['order', '>', $menu->order],
			])->get();
			foreach ($order as $item) {
				if ($item->order > 1) {
					$item->order = $item->order - 1;
					$item->save();
				}
			}

			DB::commit();

			return $this->response(
				message: 'Berhasil menghapus data.',
			);
		} catch (Exception $e) {
			DB::rollBack();

			return $this->response(
				message: $e->getMessage(),
				status_code: $e->getCode(),
			);
		}
	}

	/**
	 * Bulk Delete Data
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	#[BodyParam("data", "object[]", "List of id", example: [['id' => 1]])]
	public function bulkDestroy(Request $request): JsonResponse
	{
		$this->authorize('bulkDelete', Menu::class);

		try {
			DB::beginTransaction();

			foreach ($request->data as $id) {
				$menu = Menu::where('id', $id)->firstOrFail();
				$menu->delete();

				$order = Menu::where([
					['id', '!=', $menu->id],
					['parent_id', '=', $menu->parent_id],
					['order', '>', $menu->order],
				])->get();

				foreach ($order as $item2) {
					if ($item2->order > 1) {
						$item2->order = $item2->order - 1;
						$item2->save();
					}
				}
			}

			DB::commit();

			return $this->response(
				message: 'Data berhasil dihapus.',
			);
		} catch (Exception $e) {
			DB::rollBack();

			return $this->response(
				message: 'Data gagal dihapus. ' . $e->getMessage(),
				status_code: 500,
			);
		}
	}

	/**
	 * Get Dynamic Menu
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function getMenu(Request $request): JsonResponse
	{
		$userRoles = auth()->user()->getRoleNames()->toArray();
		$userPermissions = auth()->user()->getAllPermissions()->pluck('name')->toArray();
		$menu = $this->getMenuQuery($request, $userRoles, $userPermissions);

		return $this->response(
			message: 'Berhasil mengambil menu.',
			data: $menu,
		);
	}

	/**
	 * Query Show Dynamic Menu
	 *
	 * @param      $request
	 * @param      $userRoles
	 * @param      $userPermissions
	 * @param null $model
	 *
	 * @return array
	 */
	private function getMenuQuery($request, $userRoles, $userPermissions, $model = null): array
	{
		if ($model == null) {
			$model = Menu::with([
				'permissions:name',
				'roles:name',
				'childrenActive',
				'childrenActive.roles:name',
				'childrenActive.permissions:name',
			])
				->whereNull('parent_id')
				->where('active', true)
				->orderBy('order')->get();
		}

		$data = [];
		$index = 0;
		foreach ($model as $item) {
			// Check Roles or Permissions Menu
			if (!in_array('super-admin', $userRoles) && (count($item->roles) > 0 || count($item->permissions) > 0)) {
				$hasRoles = false;
				$hasPermissions = false;

				if (count($item->roles) > 0) {
					if (array_intersect($userRoles, $item->roles->pluck('name')->toArray())) {
						$hasRoles = true;
					}
				}

				if (count($item->permissions) > 0) {
					if (array_intersect($userPermissions, $item->permissions->pluck('name')->toArray())) {
						$hasPermissions = true;
					}
				}

				if (!$hasRoles && !$hasPermissions) {
					continue;
				}
			}

			$data[$index] = [
				'title' => $item->title,
				'icon' => $item->icon,
				'to' => $item->to,
			];
			if (count($item->roles) > 0) {
				$data[$index]['roles'] = $item->roles->pluck('name')->toArray();
			}
			if (count($item->permissions) > 0) {
				$data[$index]['permissions'] = $item->permissions->pluck('name')->toArray();
			}
			if (count($item->childrenActive) > 0) {
				$data[$index]['children'] =
					$this->getMenuQuery($request, $userRoles, $userPermissions, $item->childrenActive);
			}

			$index++;
		}

		return $data;
	}

	/**
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function searchMenu(Request $request): JsonResponse
	{
		$data = Menu::query();

		$data->whereNotNull('parent_id');
		$data->where([
			'active' => true,
		]);
		$data->where('label', 'like', '%' . $request->search . '%');

		return $this->response(
			message: 'Berhasil mengambil menu.',
			data: $data->get(),
		);
	}
}
