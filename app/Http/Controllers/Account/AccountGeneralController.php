<?php
namespace App\Http\Controllers\Account;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Account;
use App\State;
use DB;
use Cache;
use Auth;

class AccountGeneralController extends Controller
{
  public function index()
  {
    $data =  Account::where( 'id', Auth::user()->account_id )->get();
    $state =  State::all();
    return view( 'account-general-information',['data' => $data,'state' => $state]);
  }

  public function updateGeneralInfo(Request $request)
  {
  	$res = Account::find( Auth::user()->account_id )->update($request->all());
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
