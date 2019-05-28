<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Account;
use App\Account_role;
use DB;
use Cache;
use Auth;

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
    						->select('b.account_name','c.role_title','account_roles.billing_rate')
    						->where('account_roles.account_id',$request->account_id)
    						->get();
    return view('components.billing.billing-table',['data' => $data]); 
  }
}
