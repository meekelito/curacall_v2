<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Account;
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
}
