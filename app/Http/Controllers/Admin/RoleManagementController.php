<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Account;
use Auth;

class RoleManagementController extends Controller
{
	public function test()
	{
		if(Auth::user()->hasPermissionTo('view-account-reports')) // Auth::user = (3) michael plang ang naay role sa db, model_has_role
			// view account reports block
			return "yes";
		else
			return "no";
	}

	public function testblade()
	{
		return view('testpermission');
	}


    public function index()
	{
	    $accounts = Account::all();
     	$roles = Role::all();
     	$permissions = Permission::all();
     	$permission_arr = array();
     	foreach($permissions as $row)
     	{
     		$permission_arr[$row->module][] = array("name"=>$row->name,"description"=>$row->description);
     	}

     	//dd($permission_arr);
    	return view( 'role-management.roles',[ 'accounts' => $accounts,'roles'=>$roles,'permissions'=> $permission_arr ]);
	}

	public function fetchRoles() 
	{    
	    $role = Role::all();
	    return Datatables::of($role)
	    ->addColumn('action', function ($role) {
	      $id = Crypt::encrypt($role->id);
	      return '<a class="btn btn-success btn-xs" onclick="admin_role_md('."'$id'".')"><i class="icon-pencil4"></i></a>
	      '; 
	    })
	    ->rawColumns(['action'])
	    ->make(true);                                                                                
	} 
}
