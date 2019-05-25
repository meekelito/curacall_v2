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
		  $role->givePermissionTo(
               [
                    //Dashboard
                    'view-account-reports',
                    'view-oncall-reports',
                    // Cases
                    'view-all-cases',                  
                    'view-active-cases', 
                    'view-pending-cases',
                    'view-closed-cases', 
                    'view-silent-cases',
                    'forward-case',
                    'accept-case',
                    'close-case', 
                    'pull-case', 
                    'add-note',
                    'export-pdf',
                    'add-case-participant',  
                     // Messages
                    'never-send-message-unless-message-received', 
                    'send-message-to-caregiver',            
                    'send-message-to-nursing',       
                    'send-message-to-coordinator',
                    'send-message-to-management',  
                    'send-message-to-account-admin',   
                    'send-message-to-anyone',  
                    // Contacts
                    'view-contacts',                
                    // Settings
                    'profile-setting',           
                    'security-login',                     
                    'message-setting',                    
                    'notification-setting',               
                    // Generate Information
                    'edit-company-information',         
                    'edit-support-contact-information',  
                    //Case Management
                    'manage-case',                      
                    //Billing
                    'view-billing-report',              
                    //Curacall Admin Console
                    'manage-general-info',              
                    'manage-roles',                      
                    'manage-users',                     
                    'manage-accounts',                  
                    'manage-billing'
               ]
            );

		// curacall maangement role's permission
        $role = Role::findByName('curacall-management');
		$role->givePermissionTo(
               [
                    //Dashboard
                    'view-account-reports',
                    'view-oncall-reports',
                    // Cases
                    'view-all-cases',                  
                    'view-active-cases', 
                    'view-pending-cases',
                    'view-closed-cases', 
                    'view-silent-cases',
                    'forward-case',
                    'accept-case',
                    'close-case', 
                    'pull-case', 
                    'add-note',
                    'export-pdf',
                    'add-case-participant',  
                     // Messages
                    'never-send-message-unless-message-received', 
                    'send-message-to-caregiver',            
                    'send-message-to-nursing',       
                    'send-message-to-coordinator',
                    'send-message-to-management',  
                    'send-message-to-account-admin',   
                    'send-message-to-anyone',  
                    // Contacts
                    'view-contacts',                
                    // Settings
                    'profile-setting',           
                    'security-login',                     
                    'message-setting',                    
                    'notification-setting',               
                    // Generate Information
                    'edit-company-information',         
                    'edit-support-contact-information',  
                    //Case Management
                    'manage-case',                      
                    //Billing
                    'view-billing-report',              
               ]
            );

		// curacall user role's permission
        $role = Role::findByName('curacall-user');
		  $role->givePermissionTo(
               [
                    //Dashboard
                    'view-account-reports',
                    'view-oncall-reports',
                    // Cases
                    'view-all-cases',                  
                    'view-active-cases', 
                    'view-pending-cases',
                    'view-closed-cases', 
                    'view-silent-cases',
                    'forward-case',
                    'accept-case',
                    'close-case', 
                    'pull-case', 
                    'add-note',
                    'export-pdf',
                    'add-case-participant',  
                     // Messages
                    'never-send-message-unless-message-received', 
                    'send-message-to-caregiver',            
                    'send-message-to-nursing',       
                    'send-message-to-coordinator',
                    'send-message-to-management',  
                    'send-message-to-account-admin',   
                    'send-message-to-anyone',  
                    // Contacts
                    'view-contacts',                
                    // Settings
                    'profile-setting',           
                    'security-login',                     
                    'message-setting',                    
                    'notification-setting'
               ]
            );

        // account admin role's permission
        $role = Role::findByName('account-admin');
        $role->givePermissionTo(
               [
                    //Dashboard
                    'view-account-reports',
                    'view-oncall-reports',
                    // Cases
                    'view-all-cases',                  
                    'view-active-cases', 
                    'view-pending-cases',
                    'view-closed-cases', 
                    'view-silent-cases',
                    'forward-case',
                    'accept-case',
                    'close-case', 
                    'pull-case', 
                    'add-note',
                    'export-pdf',
                    'add-case-participant',  
                     // Messages
                    'never-send-message-unless-message-received', 
                    'send-message-to-caregiver',            
                    'send-message-to-nursing',       
                    'send-message-to-coordinator',
                    'send-message-to-management',  
                    'send-message-to-account-admin',   
                    'send-message-to-anyone',  
                    // Contacts
                    'view-contacts',                
                    // Settings
                    'profile-setting',           
                    'security-login',                     
                    'message-setting',                    
                    'notification-setting',               
                    // Generate Information
                    'edit-company-information',         
                    'edit-support-contact-information',  
                    //Case Management
                    'manage-case',                      
                    //Billing
                    'view-billing-report',              
               ]
            );

        // agency management role's permission
        $role = Role::findByName('agency-management');
        $role->givePermissionTo(
           [
                //Dashboard
                'view-oncall-reports',
                // Cases
                'view-all-cases',                  
                'view-active-cases', 
                'view-pending-cases',
                'view-closed-cases', 
                'view-silent-cases',
                'forward-case',
                'accept-case',
                'close-case', 
                'pull-case', 
                'add-note',
                'export-pdf',
                'add-case-participant',  
                 // Messages
                'never-send-message-unless-message-received', 
                'send-message-to-caregiver',            
                'send-message-to-nursing',       
                'send-message-to-coordinator',
                'send-message-to-management',  
                'send-message-to-account-admin',   
                'send-message-to-anyone',  
                // Contacts
                'view-contacts',                
                // Settings
                'profile-setting',           
                'security-login',                     
                'message-setting',                    
                'notification-setting',               
                // Generate Information
                'edit-company-information',         
                'edit-support-contact-information',  
                //Case Management
                'manage-case',                      
                //Billing
                'view-billing-report',
           ]
        );

        // agency nursing role's permission
        $role = Role::findByName('agency-nursing');
         $role->givePermissionTo(
               [
                    //Dashboard
                    'view-oncall-reports',
                    // Cases
                    'view-all-cases',                  
                    'view-active-cases', 
                    'view-pending-cases',
                    'view-closed-cases', 
                    'view-silent-cases',
                    'forward-case',
                    'accept-case',
                    'close-case', 
                    'pull-case', 
                    'add-note',
                    'export-pdf',
                    'add-case-participant',  
                     // Messages
                    'never-send-message-unless-message-received', 
                    'send-message-to-caregiver',            
                    'send-message-to-nursing',       
                    'send-message-to-coordinator',
                    'send-message-to-management',  
                    'send-message-to-account-admin',   
                    'send-message-to-anyone',  
                    // Contacts
                    'view-contacts',                
                    // Settings
                    'profile-setting',           
                    'security-login',                     
                    'message-setting',                    
                    'notification-setting'
               ]
            );

        // agency coordinator role's permission
        $role = Role::findByName('agency-coordinator');
        $role->givePermissionTo(
               [
                    //Dashboard
                    'view-oncall-reports',
                    // Cases
                    'view-all-cases',                  
                    'view-active-cases', 
                    'view-pending-cases',
                    'view-closed-cases', 
                    'view-silent-cases',
                    'forward-case',
                    'accept-case',
                    'close-case', 
                    'pull-case', 
                    'add-note',
                    'export-pdf',
                    'add-case-participant',  
                     // Messages
                    'never-send-message-unless-message-received', 
                    'send-message-to-caregiver',            
                    'send-message-to-nursing',       
                    'send-message-to-coordinator',
                    'send-message-to-management',  
                    'send-message-to-account-admin',   
                    'send-message-to-anyone',  
                    // Contacts
                    'view-contacts',                
                    // Settings
                    'profile-setting',           
                    'security-login',                     
                    'message-setting',                    
                    'notification-setting'
               ]
            );

        // agency caregiver role's permission
        $role = Role::findByName('agency-caregiver');
        $role->givePermissionTo(
               [
                     // Messages
                    'never-send-message-unless-message-received', 
                    'send-message-to-caregiver',            
                    'send-message-to-nursing',       
                    'send-message-to-coordinator',
                    'send-message-to-management',  
                    'send-message-to-account-admin',   
                    'send-message-to-anyone',  
                    // Contacts
                    'view-contacts',                
                    // Settings
                    'profile-setting',           
                    'security-login',                     
                    'message-setting',                    
                    'notification-setting'
               ]
            );

    }
}
