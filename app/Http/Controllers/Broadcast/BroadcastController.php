<?php
namespace App\Http\Controllers\Broadcast;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Account;
use App\User;
use DataTables;
use DB;
use Cache;
use Auth;

class BroadcastController extends Controller
{

  public function index()
  {
  	$accounts = Account::all();
    return view( 'broadcast',['accounts' => $accounts]);

  }
}
