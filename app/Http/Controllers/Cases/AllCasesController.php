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
  	$cases = Cases::orderBy('status')->orderBy('id','desc')->get();
  	$active_count = Cases::select(DB::raw('count(*) as total'))
											  	->where('status',1)
											  	->get();
		$pending_count = Cases::select(DB::raw('count(*) as total'))
											  	->where('status',2)
											  	->get();
		$closed_count = Cases::select(DB::raw('count(*) as total'))
											  	->where('status',3)
											  	->get();									  	
    return view( 'all-cases',[ 'cases' => $cases,'active_count' => $active_count[0],'pending_count' => $pending_count[0],'closed_count' => $closed_count[0] ] );
  }
}
