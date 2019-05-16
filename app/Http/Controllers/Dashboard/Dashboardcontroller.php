<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Account;
use App\User;
use App\Cases;
use DB;
use Auth;

class DashboardController extends Controller
{
  public function index() 
  {
  	$account = Account::all();
  	$users = User::all();
    return view( 'dashboard',['account'=>$account,'users'=>$users]);
  }

  public function checkuser(Request $request)
  {

      if(Auth::check())
        if(Auth::user()->id == $request->id)
            return "1";
        else
          return "0";
      else
        return "0";
  }

}
