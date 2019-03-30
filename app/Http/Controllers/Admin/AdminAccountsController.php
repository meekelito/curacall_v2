<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Company;
use App\Account;
use App\Account_role;
use App\State;
use App\Role;
use App\User;
use App\Account_group;
use DataTables;
use DB;
use Cache;
use Auth;
use Validator;

class AdminAccountsController extends Controller
{
  public function index()
  {
    return view( 'admin-console-accounts');
  }
  public function fetchAccountGroup() 
  { 
    $account = Account_group::orderBy('id');
    return Datatables::of($account)
    ->addColumn('action', function ($account) {
      return '<a class="btn btn-success btn-xs" title="Update Group Info." onclick="update_group_account_md('.$account->id.')"><i class="icon-pencil4"></i></a>
      <a class="btn btn-danger btn-xs"><i class="icon-bin"></i></a>
      '; 
    })
    ->make(true); 
  }

  public function fetchAccounts() 
  { 
    $account = Account::leftJoin('account_group','accounts.group_id','=','account_group.id')
    
    ->select('accounts.id','accounts.account_id','account_group.group_name','accounts.account_name');
    return Datatables::of($account)
    ->addColumn('action', function ($account) {
      $id = Crypt::encrypt($account->id);
      return '<a class="btn btn-success btn-xs" title="Update Account Info." onclick="update_account_md('."'$id'".')"><i class="icon-pencil4"></i></a>
      <a class="btn btn-danger btn-xs"><i class="icon-bin"></i></a>
      '; 
    })
    ->make(true); 
  }

  public function getModalAddGroupAccount()  
  {  
    $accounts =  Account::where('group_id',null)->get();
    $state =  State::all();
    return view('components.admin-accounts.add-group-account-md',['state' => $state,'accounts'=>$accounts]);
  }

  public function getModalAddAccount()  
  {  
    $group =  Account_group::all();
    $state =  State::all();
    return view('components.admin-accounts.add-account-md',['state' => $state,'group'=>$group]);
  }


  public function getModalUpdateGroupAccount(Request $request)  
  {  
    $data = Account_group::where('id',$request->input('id'))->get();
    return view('components.admin-accounts.update-group-account-md',[ 'data' => $data]);
  }

  public function getModalUpdateAccount(Request $request)  
  {  
    $id = Crypt::decrypt( $request->input('id') );
    $state =  State::all(); 
    $data = Account::where( 'id', $id  )->orderBy('account_name')->get();
    $group =  Account_group::all();


    return view('components.admin-accounts.update-account-md',[ 'data' => $data, 'state' => $state,'group'=>$group ]);
  }

  public function addGroupAccount(Request $request)  
  {  
    $validator = Validator::make($request->all(), [
      'group_name' => 'required|string|unique:account_group,group_name',
      'group_info' => 'required|string'
    ],[
      'group_name.required'=>'Group Name is required.',
      'group_info.required'=>'Group Info. is required.',
    ]);
    if ($validator->fails()) {
      return json_encode(array(
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ));
    }

    $res = Account_group::create( $request->all()+[ 'created_by' => Auth::user()->id ] ); 
    
    if($res){
      return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"Successfully saved. \n Please update the account roles."
      ));
    }else{
      return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
    }
      
  }

  public function addAccount(Request $request)  
  {  
    $validator = Validator::make($request->all(), [
      'account_id' => 'required|number|unique:accounts,account_id',
      'account_name' => 'required|string|unique:accounts,account_name',
      'phone_main' => 'required',
      'account_info' => 'required|string',
      'email' => 'required|email|unique:accounts,email',
      'phone_secondary' => 'required|string',
    ],[
      'account_id.required'=>'Account ID is required.',
      'account_name.required'=>'Account Name is required.',
      'phone_main.required'=>'Main Number is required.',
      'account_info.required' => 'Account Information is required.',
      'email.required' => 'Email is required.',
      'phone_secondary.required' => 'Phone Number is required.',
    ]);

    if ($validator->fails()) {
      return json_encode(array(
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ));
    }

    DB::beginTransaction();
    try{
      $res = Account::create( $request->all()+[ 'created_by' => Auth::user()->id ] ); 
      
      for( $i = 4; $i <= 8; $i++ ){
        Account_role::create([ 'account_id' => $res->id,'role_id' => $i]);
      }
      
      DB::commit();
      return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"Successfully saved. \n Please update the account roles."
      ));
    } catch (Exeption $e){
      DB::rollback();
      return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
    }


  }
  public function updateGroupAccount(Request $request)  
  {  
    $id = $request->input('_id'); 

    $validator = Validator::make($request->all(), [ 
      'group_name' => 'required|string|unique:account_group,group_name,'.$id,
      'group_info' => 'required|string',
    ],[
      'group_name.required'=>'Group Name is required.',
      'group_info.required'=>'Group Info. is required.',
    ]);

    if ($validator->fails()) {
      return json_encode(array(
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ));
    }

    $res = Account_group::find( $id )->update($request->all()+['updated_by' => Auth::user()->id ]);

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

  public function updateAccount(Request $request)  
  {  
    $id = Crypt::decrypt( $request->input('_id') );

    $validator = Validator::make($request->all(), [
      'group_id' => 'required|integer|exists:account_group,id',
      'account_id' => 'required|string|unique:accounts,account_id,'.$id,
      'account_name' => 'required|string|unique:accounts,account_name,'.$id,
      'phone_main' => 'required',
      'account_info' => 'required|string',
      'email' => 'required|email|unique:accounts,email,'.$id,
      'phone_secondary' => 'required|string',
        
    ],[
      'group_id.required'=>'Group is required.',
      'group_id.exists'=>'Invalid Group.',
      'account_id.required'=>'Account ID is required.',
      'account_name.required'=>'Account Name is required.',
      'phone_main.required'=>'Main Number is required.',
      'account_info.required' => 'Account Information is required.',
      'email.required' => 'Email is required.',
      'phone_secondary.required' => 'Phone Number is required.',
    ]);

    if ($validator->fails()) {
      return json_encode(array(
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ));
    }

    $res = Account::find( $id )->update($request->all()+['updated_by' => Auth::user()->id ]);
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


}
