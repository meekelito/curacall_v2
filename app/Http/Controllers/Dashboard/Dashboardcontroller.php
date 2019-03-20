<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Account;


class DashboardController extends Controller
{
  public function index() 
  {
  	$account = Account::all();
    return view( 'dashboard',['account'=>$account]);
  }

}
