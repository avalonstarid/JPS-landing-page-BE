<?php

namespace Database\Seeders;

use App\Models\UserManagement\Permission;
use App\Models\UserManagement\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(): void
	{
		$roles = ['super-admin', 'user'];

		foreach ($roles as $role) {
			Role::create([
				'label' => Str::headline($role),
				'name' => $role,
//				'guard_name' => 'api',
			]);
		}

		$permissions = [
			'user_create', 'user_read', 'user_update', 'user_delete',
			'menu_create', 'menu_read', 'menu_update', 'menu_delete',
			'permission_create', 'permission_read', 'permission_update', 'permission_delete',
			'role_create', 'role_read', 'role_update', 'role_delete',
			'audit_log_read', 'audit_log_delete',
			'system_log_read', 'system_log_delete',
			'performance_read',
		];
		foreach ($permissions as $item) {
			Permission::create([
				'label' => Str::headline($item),
				'name' => $item,
//				'guard_name' => 'api',
			]);
		}
	}
}
