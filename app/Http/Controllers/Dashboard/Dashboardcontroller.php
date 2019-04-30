<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Account;
use App\User;
use App\Cases;
use DB;
class DashboardController extends Controller
{
  public function index() 
  {
  	$account = Account::all();
  	$users = User::all();
    return view( 'dashboard',['account'=>$account,'users'=>$users]);
  }

  public function casescount(Request $request)
  {
	$r1 = explode("-", $request->range);
    $date=date_create($r1[0]);
    $from = date_format($date,"Y-m-d H:i:s");
    $date=date_create($r1[1]);
    $to = date_format($date,"Y-m-d H:i:s");

  	if($request->account_id == "all")
  	{
		 $cases = Cases::leftJoin('accounts as b','b.id','=','cases.account_id')->whereBetween('cases.created_at',[$from,$to])
  					->select(DB::raw('count(0) as value'),'b.account_name as name')->groupBy('cases.account_id')
  					->calltype($request->call_type)
  					->subcalltype($request->subcall_type)
  					->get();

  		  	return json_encode($cases);
  	}else
  	{
  		$cases = Cases::whereBetween('cases.created_at',[$from,$to])
  					->account($request->account_id)
  					->select('cases.call_type as name',DB::raw('count(0) as value'))->groupBy('cases.call_type')
  					->calltype($request->call_type)
  					->subcalltype($request->subcall_type)
  					->get();

  		return json_encode($cases);
  	}
 


  }

}
