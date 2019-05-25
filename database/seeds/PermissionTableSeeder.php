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
              // Dashboard
              ['name'  => 'view-account-reports',               'description'  =>  'Can View Dashboard account reports', 'module' => 'dashboard'],
              ['name'  => 'view-oncall-reports',                'description'  =>  'Can View Dashboard on-call reports', 'module' => 'dashboard'],
              // Cases
              ['name'  => 'view-all-cases',                     'description'  =>  'Can View All cases', 'module' => 'cases'],
              ['name'  => 'view-active-cases',                  'description'  =>  'Can View Active cases', 'module' => 'cases'],
              ['name'  => 'view-pending-cases',                 'description'  =>  'Can View Pending cases', 'module' => 'cases'],
              ['name'  => 'view-closed-cases',                  'description'  =>  'Can View Closed cases', 'module' => 'cases'],
              ['name'  => 'view-silent-cases',                  'description'  =>  'Can View Silent cases', 'module' => 'cases'],
              ['name'  => 'forward-case',                       'description'  =>  'Forward case', 'module' => 'cases'],
              ['name'  => 'accept-case',                        'description'  =>  'Accept case', 'module' => 'cases'],
              ['name'  => 'close-case',                         'description'  =>  'Forward case', 'module' => 'cases'],
              ['name'  => 'pull-case',                          'description'  =>  'Pull case', 'module' => 'cases'],
              ['name'  => 'add-note',                           'description'  =>  'Add note', 'module' => 'cases'],
              ['name'  => 'export-pdf',                         'description'  =>  'Export PDF', 'module' => 'cases'],
              ['name'  => 'add-case-participant',               'description'  =>  'Add case participant', 'module' => 'cases'],
              // Messages
              ['name'  => 'never-send-message-unless-message-received', 'description'  =>  'Can never send a message unless a message was received', 'module' => 'messages'],
              ['name'  => 'send-message-to-caregiver',                  'description'  =>  'Can send a message to caregiver', 'module' => 'messages'],
              ['name'  => 'send-message-to-nursing',                    'description'  =>  'Can send a message to nursing', 'module' => 'messages'],
              ['name'  => 'send-message-to-coordinator',                'description'  =>  'Can send a message to coordinator', 'module' => 'messages'],
              ['name'  => 'send-message-to-management',                 'description'  =>  'Can send a message to management', 'module' => 'messages'],
              ['name'  => 'send-message-to-account-admin',              'description'  =>  'Can send a message to account admin', 'module' => 'messages'],
              ['name'  => 'send-message-to-anyone',                     'description'  =>  'Can send a message to anyone', 'module' => 'messages'],
              // Contacts
              ['name'  => 'view-contacts',                      'description'  =>  'View Contacts', 'module' => 'contacts'],
              // Settings
              ['name'  => 'profile-setting',                    'description'  =>  'Profile Settings', 'module' => 'settings'],
              ['name'  => 'security-login',                     'description'  =>  'Security and Login', 'module' => 'settings'],
              ['name'  => 'message-setting',                    'description'  =>  'Message Settings', 'module' => 'settings'],
              ['name'  => 'notification-setting',               'description'  =>  'Notification Settings', 'module' => 'settings'],
              // Generate Information
              ['name'  => 'edit-company-information',           'description'  =>  'Edit Company Information', 'module' => 'general information'],
              ['name'  => 'edit-support-contact-information',   'description'  =>  'Edit Support Contact Information', 'module' => 'general information'],
              //Billing
              ['name'  => 'view-billing-report',                'description'  =>  'View Billing Report', 'module' => 'billing'],
              //Curacall Admin Console
              ['name'  => 'case-repository',                    'description'  =>  'Case Repository', 'module' => 'admin console'],
              ['name'  => 'archived-closed-case',               'description'  =>  'Archive Closed Case', 'module' => 'admin console'],
              ['name'  => 'manage-curacall-general-info',       'description'  =>  'Manage Curacall General Information', 'module' => 'admin console'],
              ['name'  => 'manage-roles',                       'description'  =>  'Manage Roles', 'module' => 'admin console'],
              ['name'  => 'manage-users',                       'description'  =>  'Manage Users', 'module' => 'admin console'],
              ['name'  => 'manage-accounts',                    'description'  =>  'Manage Accounts', 'module' => 'admin console'],
              ['name'  => 'manage-billing',                     'description'  =>  'Manage Billing', 'module' => 'admin console'],
              //Account Admin Console
              ['name'  => 'manage-account-general-info',        'description'  =>  'Manage Account General Information', 'module' => 'account console'],
              ['name'  => 'case-management',                    'description'  =>  'Case Management', 'module' => 'account console'],
          ];

        Permission::truncate();
        foreach ($permissions as $permission) {
             Permission::create(['name'=>$permission['name'],'description'=>$permission['description'],'module'=>$permission['module']]);
        }
    }
}
