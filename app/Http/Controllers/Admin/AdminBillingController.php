<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Account;
use App\Account_role;
use DB;
use Cache;
use Auth;
use Validator;

class AdminBillingController extends Controller
{
  public function index()
  {
    $accounts =  Account::all();
    return view( 'admin-console-billing',['accounts'=>$accounts]);
  }

  public function accountBilling(Request $request)
  {
    $data =  Account_role::leftJoin('accounts AS b','account_roles.account_id','b.id')
    						->leftJoin('roles AS c','account_roles.role_id','c.id')
    						->select('b.account_name','c.role_title','account_roles.billing_rate','account_roles.id')
    						->where('account_roles.account_id',$request->account_id)
    						->get();
    return view('components.billing.billing-table',['data' => $data]); 
  }

  public function getModallUpdateBilling(Request $request)
  {
    $account_role = Account_role::find($request->account_role);
    return view('components.billing.update-billing-md',['account_role'=> $account_role]); 
  }
  public function updateBilling(Request $request)
  {
    $validator = Validator::make($request->all(), [ 
      'billing_rate' => 'required'
    ]);

    $res = Account_role::find( $request->account_role_id )->update($request->all());
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
