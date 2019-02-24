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

    $cases = Cases::where('status',2)->orderBy('id','desc')->get();
  	$pending_count = Cases::select(DB::raw('count(*) as total'))
											  	->where('status',2)
											  	->get();						  	
    return view( 'pending-cases',[ 'cases' => $cases,'pending_count' => $pending_count[0] ] );
  }
}
