<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Company;
use App\State;
use DB;
use Cache;
use Auth;

class AdminGeneralController extends Controller
{
  public function index()
  {
    $data =  Company::all();
    $state =  State::all();
    return view( 'admin-console-general',['data' => $data,'state' => $state]);
  }

  public function updateGeneralInfo(Request $request)
  {
  	$res = Company::find(1)->update($request->all());
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
