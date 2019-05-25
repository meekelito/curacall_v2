<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// curacall admin role's permission
        $role = Role::findByName('curacall-admin');
		$role->givePermissionTo(Permission::all());

		// curacall maangement role's permission
        $role = Role::findByName('curacall-management');
		$role->givePermissionTo(Permission::all());

		// curacall maangement role's permission
        $role = Role::findByName('curacall-user');
		$role->givePermissionTo(Permission::all());

    }
}
