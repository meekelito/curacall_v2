<?php
namespace App\Http\Controllers\Cases;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Room;
use App\Participants;
use App\Messages;
use App\Users;
use DB;
use Cache;
use Auth;

class ActiveCasesController extends Controller
{
  public function index()
  {
  	$cases = array();  
    return view( 'active-cases',[ 'cases' => $cases ] );
  }
}
