<?php
namespace App\Http\Controllers\Cases;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Room;
use App\Participants;
use App\Messages;
use App\Users;
use App\Cases;
use DB;
use Cache;
use Auth;

class AllCasesController extends Controller
{
  public function index()
  {
  	$cases = Cases::orderBy('id','desc')->get();
    return view( 'all-cases',[ 'cases' => $cases ] );
  }
}
