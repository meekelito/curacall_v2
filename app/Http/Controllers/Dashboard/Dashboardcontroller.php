<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Account;
use App\User;


class DashboardController extends Controller
{
  public function index() 
  {
  	$account = Account::all();
  	$users = User::all();
    return view( 'dashboard',['account'=>$account,'users'=>$users]);
  }

}
