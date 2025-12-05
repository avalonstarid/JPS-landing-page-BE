<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(): void
	{
		$user = User::create([
			'name' => 'Super Admin',
			'email' => 'admin@demo.com',
			'password' => 'demo',
		]);
		$user->assignRole('super-admin');
	}
}
