<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Company;
use App\Account;
use App\State;
use App\Role;
use App\User;
use DataTables;
use Validator;
use DB;
use Cache;
use Auth;
use Hash;


class AdminUsersController extends Controller
{

  public function __construct()
  { 
    $this->middleware('sanitize');
  }

  public function index()
  {
    $data =  Company::all();
    $state =  State::all();
    return view( 'admin-console-users',['data' => $data,'state' => $state]);
  }

  public function getModalAdminUserNew() 
  {  
    $roles =  Role::where( 'is_curacall', 1 )->get();
    return view('components.admin-users.admin-user-new-md',[ 'roles' => $roles ]);
  }

  public function getModalClientUserNew() 
  {  
    $roles =  Role::where( 'is_curacall', 0 )->get();
    $accounts =  Account::all(); 
    return view('components.admin-users.client-user-new-md',[ 'roles' => $roles, 'accounts'=> $accounts, 'type' => "Client" ]);
  }

  public function getModalAdminUserUpdate(Request $request)  
  {  
    $id = Crypt::decrypt($request->input('id'));
    $roles = Role::where( 'is_curacall', 1 )->get();
    $data = User::where( 'id', $id )->get();
    if($data){
      return view('components.admin-users.admin-user-update-md',['data' => $data,'roles'=>$roles]);
    }else{
      return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
    }
  }

  public function getModalClientUserUpdate(Request $request)  
  {  
    $id = Crypt::decrypt($request->input('id'));
    $roles = Role::where( 'is_curacall', 0 )->get();
    $accounts = Account::all();
    $data = User::where( 'id', $id )->get();
    if($data){
      return view('components.admin-users.client-user-update-md',['data' => $data,'roles'=>$roles,'accounts'=>$accounts]);
    }else{
      return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
    }
  }

  public function fetchAdminUsers() 
  {   
    $users = User::leftJoin( 'roles as b','users.role_id','=','b.id' )
            ->where( 'users.is_curacall', 1 )
            ->select('users.id','users.fname','users.lname','users.email','users.status','users.prof_img','b.role_title');

    return Datatables::of($users)
    ->editColumn('img',function($users){
      if( file_exists('storage/uploads/users/'.$users->prof_img) ){
        return '<img src="'.asset('storage/uploads/users/'.$users->prof_img).'" class="img-circle img-md">'; 
      }else{
        return '<img src="'.asset('storage/uploads/users/default.png').'" class="img-circle img-md">'; 
      }
    }) 
    ->editColumn('curacall_id',function($users){
      return 'CC'.str_pad($users->id, 6,'0',STR_PAD_LEFT);
    }) 
    ->editColumn('status',function($users){
      if($users->status == "active"){
        return '<span class="label label-success">'.$users->status.'</span>';
      }elseif($users->status == "inactive"){
        return '<span class="label label-default">'.$users->status.'</span>';
      }elseif(($users->status == "pending")){
        return '<span class="label label-info">'.$users->status.'</span>';
      }else{
        return '<span class="label label-default">'.$users->status.'</span>';
      }
    })
    ->addColumn('action', function ($users) {
      $id = Crypt::encrypt($users->id);
      return '<ul class="icons-list">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="icon-menu9"></i>
          </a> 
          <ul class="dropdown-menu dropdown-menu-right">
            <li><a onclick="get_admin_user_md('."'$id'".')"><i class="icon-pencil4"></i> Update info.</a></li>
            <li><a onclick="get_status_md('."'$id'".')"><i class="icon-user-block"></i> Update status</a></li>
            <li><a onclick="reset_password('."'$id'".')"><i class="icon-reset"></i> Reset password</a></li>
          </ul>
        </li>
      </ul>'; 
    })->rawColumns(['img','curacall_id','status','action'])
    ->make(true);                                                                              
  } 

  public function fetchClientUsers() 
  {   
    $users = User::leftJoin('accounts as b','users.account_id','=','b.id')
            ->leftJoin( 'roles as c','users.role_id','=','c.id' )
            ->where('users.is_curacall', 0 )
            ->select('users.id','users.fname','users.lname','users.email','users.status','users.prof_img','b.account_name','c.role_title');

    return Datatables::of($users)
    ->editColumn('img',function($users){
      if( file_exists('storage/uploads/users/'.$users->prof_img) ){
        return '<img src="'.asset('storage/uploads/users/'.$users->prof_img).'" class="img-circle img-md">'; 
      }else{
        return '<img src="'.asset('storage/uploads/users/default.png').'" class="img-circle img-md">'; 
      }
    }) 
    ->editColumn('curacall_id',function($users){
      return 'CC'.str_pad($users->id, 6,'0',STR_PAD_LEFT);
    }) 
    ->editColumn('status',function($users){
      if($users->status == "active"){
        return '<span class="label label-success">'.$users->status.'</span>';
      }elseif($users->status == "inactive"){
        return '<span class="label label-default">'.$users->status.'</span>';
      }elseif(($users->status == "pending")){
        return '<span class="label label-info">'.$users->status.'</span>';
      }else{
        return '<span class="label label-default">'.$users->status.'</span>';
      }
    })
    ->addColumn('action', function ($users) {
      $id = Crypt::encrypt($users->id);
      return '<ul class="icons-list">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="icon-menu9"></i>
          </a> 
          <ul class="dropdown-menu dropdown-menu-right">
            <li><a onclick="get_client_user_md('."'$id'".')"><i class="icon-pencil4"></i> Update info.</a></li>
            <li><a onclick="get_status_md('."'$id'".')"><i class="icon-user-block"></i> Update status</a></li>
            <li><a onclick="reset_password('."'$id'".')"><i class="icon-reset"></i> Reset password</a></li>
          </ul>
        </li>
      </ul>'; 
    })->rawColumns(['img','curacall_id','status','action'])
    ->make(true);                                                                            
  } 

  public function addAdminUser(Request $request)  
  {  
    $role_id = Crypt::decrypt($request->role_id);
    $request->merge([
      'role_id' => $role_id
    ]);

    $validator = Validator::make($request->all(), [
        'fname' => 'required|regex:/^[\pL\s\-]+$/u',
        'lname' => 'required|regex:/^[\pL\s\-]+$/u',
        'email' => 'required|email|unique:users,email',
        'prof_suffix' => 'nullable|string',
        'title' => 'nullable|string',
        'mobile_no' => 'nullable|string',
        'phone_no' => 'nullable|string',
        'role_id' => 'required|in:1,2,3',
    ],[
      'fname.required'=>'First name is required.',
      'lname.required'=>'Last name is required.',
      'fname.regex' => 'The first name may only contain letters and spaces.',
      'lname.regex' => 'The last name may only contain letters and spaces.',
      'role_id.in' => 'The role is invalid.',
    ]);

    if ($validator->fails()) {
      return json_encode(array(
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ));
    }

    $password = Hash::make('password');
    $res = User::create( $request->all()+['is_curacall' => 1 ]+['password' => $password ]+['created_by' => Auth::user()->id ] ); 

    if($res){ 
      return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"Successfully saved."
      ));
    }else{
      return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
    }
  }

  public function addClientUser(Request $request)  
  {  
    $role_id = Crypt::decrypt($request->role_id);
    $account_id = Crypt::decrypt($request->account_id);

    $request->merge([ 
      'role_id' => $role_id,
      'account_id' => $account_id
    ]);

    $validator = Validator::make($request->all(), [
        'fname' => 'required|regex:/^[\pL\s\-]+$/u',
        'lname' => 'required|regex:/^[\pL\s\-]+$/u',
        'email' => 'required|email|unique:users,email',
        'prof_suffix' => 'nullable|string',
        'title' => 'nullable|string',
        'mobile_no' => 'nullable|string',
        'phone_no' => 'nullable|string',
        'role_id' => 'required|in:4,5,6,7,8',
        'account_id' => 'required',
    ],[
      'fname.required'=>'First name is required.',
      'lname.required'=>'Last name is required.',
      'fname.regex' => 'The first name may only contain letters and spaces.',
      'lname.regex' => 'The last name may only contain letters and spaces.',
      'role_id.in' => 'The role is invalid.',
      'account_id.required' => 'Account is required.',
    ]);

    if ($validator->fails()) {
      return json_encode(array(
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ));
    }

    $password = Hash::make('password');
    $res = User::create( $request->all()+['password' => $password ]+['created_by' => Auth::user()->id ] ); 


    if($res){ 
      return json_encode(array(
        "status"=>1,
        "type" => $request->_type,
        "response"=>"success",
        "message"=>"Successfully saved."
      ));
    }else{
      return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
    }
  }

  
  public function updateAdminUser(Request $request)  
  {  
    $role_id = Crypt::decrypt($request->role_id);
    $id = Crypt::decrypt($request->id);

    $request->merge([
      'role_id' => $role_id
    ]);

    $validator = Validator::make($request->all(), [
        'fname' => 'required|regex:/^[\pL\s\-]+$/u',
        'lname' => 'required|regex:/^[\pL\s\-]+$/u',
        'email' => 'required|email|unique:users,email,'.$id,
        'prof_suffix' => 'nullable|string',
        'title' => 'nullable|string',
        'mobile_no' => 'nullable|string',
        'phone_no' => 'nullable|string',
        'role_id' => 'required|in:1,2,3',
    ],[
      'fname.required'=>'First name is required.',
      'lname.required'=>'Last name is required.',
      'fname.regex' => 'The first name may only contain letters and spaces.',
      'lname.regex' => 'The last name may only contain letters and spaces.',
      'role_id.in' => 'The role is invalid.',
    ]);

    if ($validator->fails()) {
      return json_encode(array(
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ));
    }


    $res = User::find( $id )->update($request->all()+['updated_by' => Auth::user()->id ]);
    if($res){ 
      return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"Successfully saved."
      ));
    }else{
      return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
    }
  }

  public function updateClientUser(Request $request)  
  {  
    $role_id = Crypt::decrypt($request->role_id);
    $account_id = Crypt::decrypt($request->account_id);
    $id = Crypt::decrypt($request->id);

    $request->merge([ 
      'role_id' => $role_id,
      'account_id' => $account_id
    ]);

    $validator = Validator::make($request->all(), [
        'fname' => 'required|regex:/^[\pL\s\-]+$/u',
        'lname' => 'required|regex:/^[\pL\s\-]+$/u',
        'email' => 'required|email|unique:users,email,'.$id,
        'prof_suffix' => 'nullable|string',
        'title' => 'nullable|string',
        'mobile_no' => 'nullable|string',
        'phone_no' => 'nullable|string',
        'role_id' => 'required|in:4,5,6,7,8',
        'account_id' => 'required',
    ],[
      'fname.required'=>'First name is required.',
      'lname.required'=>'Last name is required.',
      'fname.regex' => 'The first name may only contain letters and spaces.',
      'lname.regex' => 'The last name may only contain letters and spaces.',
      'role_id.in' => 'The role is invalid.',
      'account_id.required' => 'Account is required.',
    ]);

    if ($validator->fails()) {
      return json_encode(array(
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ));
    }


    $res = User::find( $id )->update($request->all()+['updated_by' => Auth::user()->id ]);
    if($res){ 
      return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"Successfully saved."
      ));
    }else{
      return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
    }
  }


  public function getModalUpdateStatus(Request $request)  
  {  
    $id = Crypt::decrypt($request->input('id'));
    $data = User::where( 'id', $id )->get();
    return view('components.admin-users.status-update-md',['data' => $data]);
  }

  public function updateStatus(Request $request)  
  { 
    $id = Crypt::decrypt($request->_id);

    $validator = Validator::make($request->all(), [
      'status' => 'required|in:active,deactivated,pending',
    ],[
      'status.in' => 'The status is invalid.',
    ]);

    if ($validator->fails()) {
      return json_encode(array(
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ));
    } 

    $res = User::find( $id )->update($request->all()+['updated_by' => Auth::user()->id ]);
    if($res){ 
      return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"Successfully saved."
      ));
    }else{
      return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
    }
  }
  
  public function resetPassword(Request $request)  
  { 
    $id = Crypt::decrypt($request->id);
    $pass = $this->randomPassword();

    $res = User::find(Auth::user()->id);
    $res->password = Hash::make($pass);
    $res->updated_by = Auth::user()->id;
    $res->save();

    if($res){ 
      return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>$pass
      ));
    }else{
      return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
    }
  } 

  public function randomPassword() {
    $alphabet = "0123456789";
    $pass = array();
    $alphaLength = strlen($alphabet)-1;
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
  }

}
