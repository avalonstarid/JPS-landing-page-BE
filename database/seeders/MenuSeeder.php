<?php

namespace Database\Seeders;

use App\Models\Settings\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(): void
	{
		$menus = [
			[
				// id: 1
				'id' => '9d215665-fadc-4a7d-b0e9-73d8d7dda755',
				'title' => 'Home',
				'icon' => null,
				'to' => '/home',
				'order' => 1,
				'parent_id' => null,
			],
			[
				// id: 2
				'id' => '9d215665-fd76-4d39-ab81-b4d9649e3d17',
				'title' => 'Dashboard',
				'icon' => 'element-11',
				'to' => '/',
				'order' => 1,
//				'parent_id' => 1,
				'parent_id' => '9d215665-fadc-4a7d-b0e9-73d8d7dda755',
			],
			[
				// id: 3
				'id' => '9d215665-ffb3-47bc-9d89-9110fb8c674c',
				'title' => 'User Management',
				'icon' => null,
				'to' => '/user-management',
				'order' => 2,
				'parent_id' => null,
				'permissions' => [
					'user_create', 'user_read', 'user_update', 'user_delete',
					'permission_create', 'permission_read', 'permission_update', 'permission_delete',
					'role_create', 'role_read', 'role_update', 'role_delete',
				],
			],
			[
				// id: 4
				'id' => '9d215666-053f-4058-9215-f5db335b3873',
				'title' => 'Users',
				'icon' => 'users',
				'to' => '/user-management/users',
				'order' => 1,
//				'parent_id' => 3,
				'parent_id' => '9d215665-ffb3-47bc-9d89-9110fb8c674c',
				'permissions' => ['user_create', 'user_read', 'user_update', 'user_delete'],
			],
			[
				// id: 5
				'id' => '9d215666-09ab-4b42-bcd1-053f67b7ca38',
				'title' => 'Permissions',
				'icon' => 'lock-3',
				'to' => '/user-management/permissions',
				'order' => 2,
//				'parent_id' => 3,
				'parent_id' => '9d215665-ffb3-47bc-9d89-9110fb8c674c',
				'permissions' => ['permission_create', 'permission_read', 'permission_update', 'permission_delete'],
			],
			[
				// id: 6
				'id' => '9d215666-0da8-4aa9-b2d7-81e55951237c',
				'title' => 'Roles',
				'icon' => 'lock-3',
				'to' => '/user-management/roles',
				'order' => 3,
//				'parent_id' => 3,
				'parent_id' => '9d215665-ffb3-47bc-9d89-9110fb8c674c',
				'permissions' => ['role_create', 'role_read', 'role_update', 'role_delete'],
			],
			[
				// id: 7
				'id' => '9d215666-1152-4e97-8647-adc605ff61e5',
				'title' => 'Settings',
				'icon' => null,
				'to' => '/settings',
				'order' => 3,
				'parent_id' => null,
				'permissions' => [
					'menu_create', 'menu_read', 'menu_update', 'menu_delete',
					'audit_log_read', 'audit_log_delete',
					'system_log_read', 'system_log_delete',
				],
			],
			[
				// id: 8
				'id' => '9d215666-153d-4a2b-9cac-031f8cfd1975',
				'title' => 'App Version',
				'icon' => 'android',
				'to' => '/settings/app-versions',
				'order' => 1,
//				'parent_id' => 7,
				'parent_id' => '9d215666-1152-4e97-8647-adc605ff61e5',
				'permissions' => [
					'menu_create', 'menu_read', 'menu_update', 'menu_delete',
					'audit_log_read', 'audit_log_delete',
					'system_log_read', 'system_log_delete',
				],
			],
			[
				// id: 9
				'id' => '9d215666-18ce-41a5-90b6-aa5d56caa630',
				'title' => 'Menu Management',
				'icon' => 'abstract-14',
				'to' => '/settings/menu',
				'order' => 2,
//				'parent_id' => 7,
				'parent_id' => '9d215666-1152-4e97-8647-adc605ff61e5',
				'permissions' => ['menu_create', 'menu_read', 'menu_update', 'menu_delete'],
			],
			[
				// id: 10
				'id' => '9d215666-1be9-4366-bbbb-8cd8a59cdea5',
				'title' => 'System',
				'icon' => 'abstract-26',
				'to' => '/settings/system',
				'order' => 3,
//				'parent_id' => 7,
				'parent_id' => '9d215666-1152-4e97-8647-adc605ff61e5',
				'permissions' => ['audit_log_read', 'audit_log_delete', 'system_log_read', 'system_log_delete'],
			],
			[
				// id: 11
				'id' => '9d215666-1f0f-4f86-82e4-2b9fdec06c83',
				'title' => 'Audit Log',
				'to' => '/settings/system/audit-logs',
				'order' => 1,
//				'parent_id' => 10,
				'parent_id' => '9d215666-1be9-4366-bbbb-8cd8a59cdea5',
				'permissions' => ['audit_log_read', 'audit_log_delete'],
			],
			[
				// id: 12
				'id' => '9d215666-21f5-43a4-9dbb-888d2a09d596',
				'title' => 'Performance Monitoring',
				'to' => '/settings/system/performance',
				'order' => 2,
//				'parent_id' => 10,
				'parent_id' => '9d215666-1be9-4366-bbbb-8cd8a59cdea5',
				'permissions' => ['performance_read'],
			],
			[
				// id: 13
				'id' => '9d215666-2505-47ce-97b1-ef0d7dc29ad7',
				'title' => 'System Log',
				'to' => '/settings/system/system-logs',
				'order' => 3,
//				'parent_id' => 10,
				'parent_id' => '9d215666-1be9-4366-bbbb-8cd8a59cdea5',
				'permissions' => ['system_log_read', 'system_log_delete'],
			],
//			[
//				// id: 14
////				'id' => '994828b5-6077-458a-9447-dd4a896182cc',
//				'title' => 'Home Mobile',
//				'icon' => null,
//				'to' => '/home-mobile',
//				'order' => 1,
//				'parent_id' => null,
//				'is_mobile' => true,
//			],
//			[
//				// id: 15
////				'id' => '994828b5-624f-4323-b75a-936a38d18ece',
//				'title' => 'Dashboard',
//				'icon' => 'element-11',
//				'to' => '/mobile/dashboard',
//				'order' => 1,
////				'parent_id' => '994828b5-6077-458a-9447-dd4a896182cc',
//				'is_mobile' => true,
//			],
		];

		foreach ($menus as $item) {
			$menu = Menu::create([
				'id' => $item['id'] ?? null,
				'title' => $item['title'] ?? null,
				'icon' => $item['icon'] ?? null,
				'to' => $item['to'] ?? null,
				'order' => $item['order'] ?? null,
				'active' => true,
				'parent_id' => $item['parent_id'] ?? null,
			]);
			$menu->syncPermissions($item['permissions'] ?? []);
		}
	}
}
