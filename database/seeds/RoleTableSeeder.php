<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
              ['name'   => 'curacall-admin', 		    'role_title' => 'CuraCall Admin',   'description'  =>  'Curacall Admin', 'is_curacall' => 1],
              ['name'   => 'curacall-management', 	'role_title' => 'CuraCall Management',	'description'  =>  'Curacall Management', 'is_curacall' => 1],
              ['name'   => 'curacall-user',       	'role_title' => 'CuraCall User',	'description'  =>  'Curacall User', 'is_curacall' => 1],
              ['name'   => 'account-admin',       	'role_title' => 'Account Admin',	'description'  =>  'Account Admin', 'is_curacall' => 0],
              ['name'   => 'agency-management',   	'role_title' => 'Agency Management',	'description'  =>  'Agency Management', 'is_curacall' => 0],
              ['name'   => 'agency-nursing',      	'role_title' => 'Agency Nursing',	'description'  =>  'Agency Nursing', 'is_curacall' => 0],
              ['name'   => 'agency-coordinator',  	'role_title' => 'Agency Coordinators',	'description'  =>  'Agency Coordinator', 'is_curacall' => 0],
              ['name'   => 'agency-caregiver',    	'role_title' => 'Agency Caregivers',	'description'  =>  'Agency Caregiver', 'is_curacall' => 0]
          ];


        foreach ($roles as $role) {
             Role::create(['name'=>$role['name'],'role_title'=> $role['role_title'],'description'=>$role['description'],'is_curacall'=> $role['is_curacall'],'is_default'=> 1]);
        }
    }
}
