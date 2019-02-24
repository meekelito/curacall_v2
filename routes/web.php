<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Auth\LoginController@showEmailForm' )->name('login'); 
Route::get('login', 'Auth\LoginController@showEmailForm' )->name('login-email'); 

Route::post('login/email', 'Auth\LoginController@loginEmail' )->name('login-email'); 
Route::get('login/password', 'Auth\LoginController@showPasswordForm'); 
Route::post('login', 'Auth\LoginController@login')->name('login'); 
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware' => array('auth')], function () {
	Route::get('dashboard','Dashboard\DashboardController@index');
	Route::get('new-message','Messages\NewMessageController@index');
	Route::post('new-message','Messages\NewMessageController@createMessage');
	Route::post('create-room','Messages\NewMessageController@createRoom');

	Route::post('close-message','Messages\ClosedMessagesController@closeMessage');

	Route::get('messages/room/{id}', 'ChatsController@index');
	Route::get('messages/new/{id}', 'ChatsController@createMessage');
	Route::get('messages', 'ChatsController@fetchMessages');
	Route::post('messages', 'ChatsController@sendMessage');

	Route::get('all-cases','Cases\AllCasesController@index'); 
	Route::get('active-cases','Cases\ActiveCasesController@index');
	Route::get('pending-cases','Cases\PendingCasesController@index');
	Route::get('closed-cases','Cases\ClosedCasesController@index');
	Route::get('deleted-cases','Cases\DeletedCasesController@index');

    Route::get('case/{id}','Cases\NewCaseController@index');

	
	Route::get('contacts','Contacts\ContactsController@index');
	Route::get('contacts/fetch-contacts','Contacts\ContactsController@fetchContacts');

	//Accounts Settings
	Route::get('user-account-settings','UserAccountSettings\UserAccountSettingsController@index');
	Route::post('user-account-settings/update-user-info','UserAccountSettings\UserAccountSettingsController@updateUser');
	Route::post('user-account-settings/update-user-credentials','UserAccountSettings\UserAccountSettingsController@updateUserCredentials'); 
	//accounts settings END

    Route::group(['middleware' => array('App\Http\Middleware\CuraCallAdminMiddleware')], function () {
        //Admin Console - General Info
        Route::get('admin-console/general','Admin\AdminGeneralController@index');
        Route::post('admin/general-info','Admin\AdminGeneralController@updateGeneralInfo'); 
        //admin console - general info END

        //Admin Console - Roles
        Route::get('admin-console/roles','Admin\AdminRolesController@index'); 
        Route::get('admin/admin-roles','Admin\AdminRolesController@fetchAdminRoles'); 
        Route::get('admin/client-roles','Admin\AdminRolesController@fetchClientRoles'); 
        Route::post('update-client-role-md','Admin\AdminRolesController@getModalUpdateClientRole');
        Route::post('admin/update-client-role','Admin\AdminRolesController@updateClientRole'); 
        //admin console - roles END

        //Admin Console - Users
        //index
        Route::get('admin-console/users','Admin\AdminUsersController@index');
        //datatables
        Route::get('admin/admin-users','Admin\AdminUsersController@fetchAdminUsers');  
        Route::post('admin/client-users','Admin\AdminUsersController@fetchClientUsers');  
        //modal add
        Route::post('admin-user-new-md','Admin\AdminUsersController@getModalAdminUserNew');
        Route::post('client-user-new-md','Admin\AdminUsersController@getModalClientUserNew');
        //modal update
        Route::post('admin-user-update-md','Admin\AdminUsersController@getModalAdminUserUpdate');
        Route::post('client-user-update-md','Admin\AdminUsersController@getModalClientUserUpdate');
        Route::post('update-status-md','Admin\AdminUsersController@getModalupdateStatus');
        //add user
        Route::post('admin/add-admin-user','Admin\AdminUsersController@addAdminUser');
        Route::post('admin/add-client-user','Admin\AdminUsersController@addClientUser');
        //update user
        Route::post('admin/update-admin-user','Admin\AdminUsersController@updateAdminUser');
        Route::post('admin/update-client-user','Admin\AdminUsersController@updateClientUser');
        Route::post('admin/update-status','Admin\AdminUsersController@updateStatus');
        Route::post('admin/reset-password','Admin\AdminUsersController@resetPassword');
        //admin console - users END 

        //Admin Console - Accounts
        //index
        Route::get('admin-console/accounts','Admin\AdminAccountsController@index');
        //datatables
        Route::get('admin/admin-accounts','Admin\AdminAccountsController@fetchAccounts');  
        //modal add
        Route::post('add-account-md','Admin\AdminAccountsController@getModalAddAccount');
        //modal update
        Route::post('update-account-md','Admin\AdminAccountsController@getModalUpdateAccount');
        //add account
        Route::post('admin/add-account','Admin\AdminAccountsController@addAccount');
        //update account
        Route::post('admin/update-account','Admin\AdminAccountsController@updateAccount');
        //admin console - accounts END.
    });
    
    Route::group(['middleware' => array('App\Http\Middleware\AccountAdminMiddleware')], function () {
		//Account General Info
		Route::get('account/general-info','Account\AccountGeneralController@index');
		Route::post('account/general-info','Account\AccountGeneralController@updateGeneralInfo'); 
		//account general info END

		//Account Role
		Route::get('account/roles','Account\AccountRolesController@index'); 
		Route::get('account/table-roles','Account\AccountRolesController@fetchRoles'); 
		Route::post('update-role-md','Account\AccountRolesController@getModalUpdateRole');
		Route::post('account/update-role','Account\AccountRolesController@updateRole'); 
		//account general info END
	}); 

	Route::get('directory','Directory\DirectoryController@index');
	Route::get('broadcast','Broadcast\BroadcastController@index');

	Route::get('archived-messages','Messages\ArchivedMessagesController@index');
	Route::get('archived-messages/all','Messages\ArchivedMessagesController@fetchArchiveMessages');  
    
    


});
