<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Role;
use App\Account;
use App\Account_role;
use DataTables;
use DB;
use Cache;
use Auth;

class AdminRolesController extends Controller
{
  public function index()
  {
    $accounts = Account::all();
    return view( 'admin-console-roles',[ 'accounts' => $accounts ]);
  }

  public function fetchAdminRoles() 
  {    
    $role = Role::where('id','<=',3);
    return Datatables::of($role)
    ->addColumn('action', function ($role) {
      $id = Crypt::encrypt($role->id);
      return '<a class="btn btn-success btn-xs" onclick="admin_role_md('."'$id'".')"><i class="icon-pencil4"></i></a>
      '; 
    })->rawColumns(['action'])
    ->make(true);                                                                                
  } 

  public function fetchClientRoles() 
  {    
    $role = Role::where('id','>',3);
    return Datatables::of($role)
    ->addColumn('action', function ($role) {
      $id = Crypt::encrypt($role->id);
      return '<a class="btn btn-success btn-xs" onclick="client_role_md('."'$id'".')"><i class="icon-pencil4"></i></a>
      '; 
    })->rawColumns(['action'])
    ->make(true);                                                                                
  } 

  public function getModalUpdateClientRole(Request $request) 
  {  
    $role_id = Crypt::decrypt( $request->input('id') );
    $account_id = Crypt::decrypt( $request->input('account') );

    $account = Account::where('id',$account_id)->get();

    $role = Role::leftJoin('account_roles as b','roles.id','=','b.role_id' )
            ->where( 'b.account_id', $account_id )
            ->where('b.role_id', $role_id )
            ->get(); 
    return view('components.admin-roles.update-client-role-md',[ 'data' => $role, 'account'=>$account ]);
  }

  public function updateClientRole(Request $request) 
  {  
    $id = Crypt::decrypt( $request->input('id') );
    $res = Account_role::find($id);

    if($request->has('msg_acaregiver')){
      $res->msg_time = $request->input('msg_time');
      $res->msg_acaregiver = 1;
      $res->msg_caregiver = 0;
      $res->msg_nursing = 0;
      $res->msg_management = 0;
      $res->msg_account_admin = 0;
      $res->msg_all = 0;
      $res->save();
    }else{
      $res->msg_acaregiver = $request->input('msg_acaregiver', 0);
      $res->msg_caregiver = $request->input('msg_caregiver', 0);
      $res->msg_nursing = $request->input('msg_nursing', 0);
      $res->msg_management = $request->input('msg_management', 0);
      $res->msg_account_admin = $request->input('msg_account_admin', 0);
      $res->msg_all = $request->input('msg_all', 0);
      $res->save();
    }

    

    if($res){
      return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"Successfully updated."
      ));
    }else{
      return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
    }
  } 

}
