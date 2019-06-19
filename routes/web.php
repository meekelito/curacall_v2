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
Route::group(['middleware' => ['nocache']], function () {
   Auth::routes(['verify' => true, 'register' => false]);
    
    Route::get('/', 'Auth\LoginController@showEmailForm' )->name('login'); 
    Route::get('login', 'Auth\LoginController@showEmailForm' )->name('login-email'); 

    Route::post('login/email', 'Auth\LoginController@loginEmail' )->name('login-email'); 
    Route::get('login/password', 'Auth\LoginController@showPasswordForm'); 
    Route::post('login', 'Auth\LoginController@login')->name('login'); 
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
});

Route::group(['middleware' => array('auth','nocache')], function () {
    // Route::post('/notification/chat/get', 'NotificationController@chatget');
    Route::post('/notification/chat/count', 'NotificationController@chatcount');
    //Route::get('/notification/chat', 'NotificationController@chatnotifications');
    //Route::get('/notification/create', 'NotificationController@addnotification');

    Route::post('/notification/get', 'NotificationController@get');
    Route::get('/notification/get', 'NotificationController@get');
    Route::post('/notification/read', 'NotificationController@read');
    Route::post('/notification/count', 'NotificationController@count');

    // Route::post('/notification/reminder/get', 'NotificationController@reminderget');
    Route::post('/notification/reminder/count', 'NotificationController@remindercount');

    Route::post('/notification/all/count', 'NotificationController@countall');

	Route::get('dashboard','Dashboard\DashboardController@index');
    Route::get('dashboard/checkauth','Dashboard\DashboardController@checkuser')->name('checkuser');

    Route::group(['middleware' => array('permission:send-message-to-anyone')], function () {

	Route::get('new-message','Messages\NewMessageController@index');
	Route::post('new-message','Messages\NewMessageController@createMessage');
	Route::post('create-room','Messages\NewMessageController@createRoom');

	Route::post('close-message','Messages\ClosedMessagesController@closeMessage');

    Route::get('messages/room/{id}', 'ChatsController@index')->name('chat.room');
    Route::get('messages/new/{id}', 'ChatsController@createMessage')->name('chat.create');
	Route::get('messages', 'ChatsController@fetchMessages');
	Route::post('messages', 'ChatsController@sendMessage');

    });

	Route::get('all-cases','Cases\AllCasesController@index')->middleware('permission:view-all-cases');
	Route::get('active-cases','Cases\ActiveCasesController@index')->middleware('permission:view-active-cases');
	Route::get('pending-cases','Cases\PendingCasesController@index')->middleware('permission:view-pending-cases');
	Route::get('closed-cases','Cases\ClosedCasesController@index')->middleware('permission:view-closed-cases');
	Route::get('deleted-cases','Cases\DeletedCasesController@index');
    Route::get('silent-cases','Cases\SilentCasesController@index')->middleware('permission:view-silent-cases');


    Route::post('count-case','Cases\NewCaseController@countCase');
    Route::get('pdf-case/{id}','Cases\NewCaseController@pdfCase')->middleware('permission:export-pdf');

    Route::post('fetch-case','Cases\NewCaseController@fetchCase');

    Route::get('cases/case_id/{id}','Cases\NewCaseController@index')->name('case')->middleware('permission:view-all-cases|view-active-cases|view-pending-cases|view-closed-cases|view-silent-cases');

    Route::get('case/notes/{id}','Cases\NewCaseController@fetchNotes');
    Route::get('case/participants/{id}','Cases\NewCaseController@fetchParticipants');
    Route::post('case/new-note', 'Cases\NewCaseController@newNote')->middleware('permission:add-note');

    Route::post('decline-case-md','Cases\NewCaseController@getModalDeclineCase');
    Route::post('decline-case','Cases\NewCaseController@declineCase');

    Route::post('accept-case','Cases\NewCaseController@acceptCase')->middleware('permission:accept-case');
    Route::post('check-case','Cases\NewCaseController@checkCase');

    Route::post('forward-case-md','Cases\NewCaseController@getModalForwardCase');
    Route::post('forward-case', 'Cases\NewCaseController@forwardCase')->middleware('permission:forward-case');

    Route::post('close-case-md','Cases\NewCaseController@getModalCloseCase');
    Route::post('close-case', 'Cases\NewCaseController@closeCase')->middleware('permission:close-case');

    Route::post('reopen-case-md','Cases\NewCaseController@getModalReOpenCase');
    Route::post('reopen-case','Cases\NewCaseController@reopenCase')->middleware('permission:reopen-case');

    Route::post('add-note-md','Cases\NewCaseController@getModalAddNote');
    Route::post('view-note-md','Cases\NewCaseController@getModalViewNote');

	Route::get('contacts','Contacts\ContactsController@index')->middleware('permission:view-contacts');
	Route::get('contacts/fetch-contacts','Contacts\ContactsController@fetchContacts');

    //Reports
    Route::post('report-account','Reports\ReportsController@getReportAccount');
    Route::post('report-oncall','Reports\ReportsController@getReportOncall');
    Route::post('report-oncall/overall-average','Reports\ReportsController@getOverallAverage')->name('report.overall-average');
    Route::post('report-oncall/overall-case-status','Reports\ReportsController@getOverallCaseStatus')->name('report.overall-case-status');
    Route::get('report-oncall/chart/trend','Reports\ReportsController@oncallcharttrend')->name('report.oncall.chart.trend');

    Route::get('report-account/subcalltypes','Reports\ReportsController@getSubcalltypes')->name('reports.subcalltypes');
    Route::get('report-account/chart/overall','Reports\ReportsController@chartoverall')->name('report.chart.overall');
    Route::get('report-account/chart/trend','Reports\ReportsController@charttrend')->name('report.chart.trend');

    Route::post('report-active-case-list','Reports\ReportsController@getReportActiveCase'); 
    Route::post('report-pending-case-list','Reports\ReportsController@getReportPendingCase'); 
    Route::post('report-closed-case-list','Reports\ReportsController@getReportClosedCase');
    Route::post('report-by-calltypes','Reports\ReportsController@getReportByCalltypes');

	//Accounts Settings
	Route::get('user-account-settings','UserAccountSettings\UserAccountSettingsController@index');
	Route::post('user-account-settings/update-user-info','UserAccountSettings\UserAccountSettingsController@updateUser');
	Route::post('user-account-settings/update-user-credentials','UserAccountSettings\UserAccountSettingsController@updateUserCredentials'); 
	//accounts settings END

    Route::group(['middleware' => array('App\Http\Middleware\CuraCallAdminMiddleware')], function () {
        //Admin Console - General Info
        Route::get('admin-console/general','Admin\AdminGeneralController@index')->middleware('permission:manage-curacall-general-info');
        Route::post('admin/general-info','Admin\AdminGeneralController@updateGeneralInfo')->middleware('permission:manage-curacall-general-info');
        //admin console - general info END

        //Admin Console - Roles
        // Route::get('admin-console/roles','Admin\AdminRolesController@index'); 
        // Route::get('admin/admin-roles','Admin\AdminRolesController@fetchAdminRoles'); 
        // Route::get('admin/client-roles','Admin\AdminRolesController@fetchClientRoles'); 
        // Route::post('update-client-role-md','Admin\AdminRolesController@getModalUpdateClientRole');
        // Route::post('admin/update-client-role','Admin\AdminRolesController@updateClientRole'); 
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
        Route::get('admin/admin-account-group','Admin\AdminAccountsController@fetchAccountGroup');  
        Route::get('admin/admin-accounts','Admin\AdminAccountsController@fetchAccounts');  
        //modal add
        Route::post('add-group-account-md','Admin\AdminAccountsController@getModalAddGroupAccount');
        Route::post('add-account-md','Admin\AdminAccountsController@getModalAddAccount');
        //modal update
        Route::post('update-group-account-md','Admin\AdminAccountsController@getModalUpdateGroupAccount');
        Route::post('update-account-md','Admin\AdminAccountsController@getModalUpdateAccount');
        //add account
        Route::post('admin/add-group-account','Admin\AdminAccountsController@addGroupAccount');
        Route::post('admin/add-account','Admin\AdminAccountsController@addAccount');
        //update account
        Route::post('admin/update-group-account','Admin\AdminAccountsController@updateGroupAccount');
        Route::post('admin/update-account','Admin\AdminAccountsController@updateAccount');
        //admin console - accounts END.

        //Admin Console - Accounts
        //index
        Route::get('admin-console/billing','Admin\AdminBillingController@index');
        Route::post('admin-console/update-billing-md','Admin\AdminBillingController@getModallUpdateBilling');
        Route::post('admin-console/update-billing','Admin\AdminBillingController@updateBilling');
        Route::post('account-billing','Admin\AdminBillingController@accountBilling');
        //admin console - accounts END.

        //reports
        Route::get('admin-console/reports','Reports\ReportsController@reportsBilling');
        Route::post('admin-console/reports-billing','Reports\ReportsController@reportsBillingTable');
        //reports end

        Route::get('repository-cases','Cases\RepositoryCasesController@index'); 
        Route::get('review-case/case_id/{id}','Cases\RepositoryCasesController@review_index');

        Route::post('review-case','Cases\RepositoryCasesController@reviewCase');

        // new admin role management
        Route::get('admin/roles','Admin\RoleManagementController@index');
        Route::get('admin/roles/all','Admin\RoleManagementController@fetchRoles')->name('admin.roles.fetch');
        Route::get('admin/roles/curacall','Admin\RoleManagementController@fetchCuracallRoles')->name('admin.roles.curacall');
        Route::get('admin/roles/test','Admin\RoleManagementController@test');
        Route::get('admin/roles/testblade','Admin\RoleManagementController@testblade');
        Route::post('admin/roles/create','Admin\RoleManagementController@createrole')->name('admin.roles.create');
        Route::get('admin/roles/edit','Admin\RoleManagementController@editrole')->name('admin.roles.editrole');
        Route::put('admin/roles/update/{id}','Admin\RoleManagementController@updaterole')->name('admin.roles.update');
        Route::put('admin/account-roles/update/{id}','Admin\RoleManagementController@updateaccountrole')->name('admin.accountroles.update');
        Route::get('admin/permissions/all','Admin\RoleManagementController@fetchPermissions')->name('admin.permissions.fetch');
        Route::get('admin/clients/all','Admin\RoleManagementController@fetchClients')->name('admin.clients.fetch');
        Route::get('admin/account/roles','Admin\RoleManagementController@editAccountRoles')->name('admin.account.roles');  
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

        //Case Management
        Route::get('account/case-management','Account\AccountCaseManagementController@index'); 
        Route::post('account/pull-case','Account\AccountCaseManagementController@pullCase'); 
        //case management END
	}); 

	Route::get('directory','Directory\DirectoryController@index');
	Route::get('broadcast','Broadcast\BroadcastController@index');

	Route::get('archived-cases','Cases\ArchivedCasesController@index');
	Route::get('archived-cases/all','Cases\ArchivedCasesController@fetchArchiveMessages');  
    
    


});
