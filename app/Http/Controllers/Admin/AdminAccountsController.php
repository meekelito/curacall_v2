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

  public function fetchAccounts() 
  { 
    $account = Account::all();
    return Datatables::of($account)
    ->addColumn('action', function ($account) {
      $id = Crypt::encrypt($account->id);
      return '<a class="btn btn-success btn-xs" title="Update Account Info." onclick="update_account_md('."'$id'".')"><i class="icon-pencil4"></i></a>
     
      <a class="btn btn-danger btn-xs"><i class="icon-bin"></i></a>
      '; 
    })
    ->make(true); 
  }

  public function getModalAddAccount()  
  {  
    $state =  State::all();
    return view('components.admin-accounts.add-account-md',['state' => $state]);
  }

  public function getModalUpdateAccount(Request $request)  
  {  
    $id = Crypt::decrypt( $request->input('id') );
    $state =  State::all(); 
    $data = Account::where( 'id', $id  )->get();
    return view('components.admin-accounts.update-account-md',[ 'data' => $data, 'state' => $state ]);
  }

  public function addAccount(Request $request)  
  {  
    $validator = Validator::make($request->all(), [
        'account_name' => 'required|string|unique:accounts,account_name',
        'phone_main' => 'required',
        'account_info' => 'required|string',
        'email' => 'required|email|unique:accounts,email',
        'phone_secondary' => 'required|string',
    ],[
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

  public function updateAccount(Request $request)  
  {  
    $id = Crypt::decrypt( $request->input('_id') );

    $validator = Validator::make($request->all(), [
        'account_name' => 'required|string|unique:accounts,account_name,'.$id,
        'phone_main' => 'required',
        'account_info' => 'required|string',
        'email' => 'required|email|unique:accounts,email,'.$id,
        'phone_secondary' => 'required|string',
        
    ],[
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
