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

class PendingCasesController extends Controller
{
  public function index()
  {
  	$cases = Cases::where('status','pending')->get();
    return view( 'pending-cases',[ 'cases' => $cases ] );
  }
}
