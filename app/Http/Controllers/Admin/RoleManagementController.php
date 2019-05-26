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
use Str;
use App\Account_role;

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
	
     	$roles = Role::all();
     	$permissions = Permission::all();
     	$permission_arr = array();
     	foreach($permissions as $row)
     	{
     		$permission_arr[$row->module][] = array("name"=>$row->name,"description"=>$row->description);
     	}

     	//dd($permission_arr);
    	return view( 'role-management.roles',['roles'=>$roles,'permissions'=> $permission_arr ]);
	}

	public function fetchRoles() 
	{    
	    $role = Role::all();
	    return Datatables::of($role)
	    ->addColumn('action', function ($role) {
	      $id = Crypt::encrypt($role->id);
	      return '<a class="btn btn-success btn-xs" onclick="show_edit_role_modal('."'$id'".')"><i class="icon-pencil4"></i></a>
	      '; 
	    })
	    ->rawColumns(['action'])
	    ->make(true);                                                                                
	} 

	public function fetchPermissions() 
	{    
	    $role = Permission::all();
	    return Datatables::of($role)
	    ->make(true);                                                                                
	} 

	public function fetchClients() 
	{    
	    $accounts = Account::all();
	    return Datatables::of($accounts)
	      ->addColumn('action', function ($account) {
	      $id = Crypt::encrypt($account->id);
	      return '<a class="btn btn-success btn-xs" onclick="show_edit_account_role_modal('."'$id'".','."'$account->account_name'".')"><i class="icon-pencil4"></i></a>
	      '; 
	    })
	    ->make(true);                                                                                
	} 

	public function createrole(Request $request)
	{
		$slug = str_slug($request->role_title);
		$request->merge(['name' =>$slug]);
		$request->validate([
			"name"	=> 'unique:roles,name'
		],
		["name.unique"=>"Role title was already taken"]);

		$role = Role::create(['name'=>$request->name,'role_title'=> $request->role_title,'description'=>$request->description,'is_curacall'=> $request->is_curacall]);

		if($role){
			$role->syncPermissions($request->permissions);

			return json_encode(array("status"=>1,"message"=> "Successfuly saved"));
		}
		else
			return json_encode(array("status"=>0,"message"=>"Oops, Something went wrong."));
	}

	public function editrole(Request $request)
	{
		try {
			$id = Crypt::decrypt( $request->input('role_id') );
			$role = Role::findOrFail($id);
			$permissions = Role::findOrFail($id)->permissions;
			$permission_arr = array();
			foreach($permissions as $row)
			{
				$permission_arr[] = $row["name"];
			}

			return json_encode(array('role'=>$role,'permissions'=>$permission_arr,"update_url"=>route('admin.roles.update',Crypt::encrypt($id))));
		} catch (Exception $e) {
            return "error";
        }
	}

	public function updaterole(Request $request, $id)
	{
		$id = Crypt::decrypt($id);
		$slug = str_slug($request->role_title);
		$request->merge(['name' =>$slug]);
		$request->validate([
			"name"	=> 'unique:roles,name,'.$id
		],
		["name.unique"=>"Role title was already taken"]);

		$role = Role::find($id);
		$role->name = $request->name;
		$role->role_title = $request->role_title;
		$role->description = $request->description;
		$role->is_curacall = $request->is_curacall;
		$role->save();

		if($role){
			$role->syncPermissions($request->permissions);

			return json_encode(array("status"=>1,"message"=> "Role Successfully updated"));
		}
		else
			return json_encode(array("status"=>0,"message"=>"Oops, Something went wrong."));
	}

	public function editAccountRoles(Request $request)
    {
      $account_id = 0;
      try {
          $account_id = Crypt::decrypt($request->account_id);
      } catch (DecryptException $e) {
          $account_id = 0;
      }

      $result = Account_role::select('roles.id','roles.description')->leftJoin('roles','roles.id','=','account_roles.role_id')->where('account_roles.account_id',$account_id)->get();
      
      return json_encode($result);
    }
}
