<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
              ['name'  => 'never-send-message-unless-message-received', 'description'  =>  'Can never send a message unless a message was received'],
              ['name'  => 'send-message-to-caregiver',                  'description'  =>  'Can send a message to caregiver'],
              ['name'  => 'send-message-to-nursing',                    'description'  =>  'Can send a message to nursing'],
              ['name'  => 'send-message-to-coordinator',                'description'  =>  'Can send a message to coordinator'],
              ['name'  => 'send-message-to-management',                 'description'  =>  'Can send a message to management'],
              ['name'  => 'send-message-to-account-admin',              'description'  =>  'Can send a message to account admin'],
              ['name'  => 'send-message-to-anyone',                     'description'  =>  'Can send a message to anyone'],
          ];


        foreach ($permissions as $permission) {
             Permission::create(['name'=>$permission['name'],'description'=>$permission['description']]);
        }
    }
}
