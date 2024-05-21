<?php

namespace Abianbiya\Laralag\Seeders;

use Illuminate\Database\Seeder;

class LaralagSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call(UsersTableSeeder::class);
		$this->call(RoleTableSeeder::class);
		$this->call(PermissionTableSeeder::class);
		$this->call(MenuTableSeeder::class);
		$this->call(MenuGroupTableSeeder::class);
		$this->call(ModuleTableSeeder::class);
		$this->call(PermissionRoleTableSeeder::class);
		$this->call(RoleUserTableSeeder::class);
	}
}
