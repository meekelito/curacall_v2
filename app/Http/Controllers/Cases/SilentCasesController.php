<?php
namespace App\Http\Controllers\Cases;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cases;
use App\Case_participants;
use DB;
use Cache;
use Auth;

class SilentCasesController extends Controller
{
  public function index() 
  {
  	$cases = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
  						->where('b.user_id',Auth::user()->id)
              ->where('cases.status',"!=",4)
  						->orderBy('cases.status')
  						->orderBy('cases.id','DESC')
  						->select('cases.id','cases.case_id','cases.sender_fullname','cases.status','cases.created_at')
  						->get();


  	$active_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
  									->where('b.user_id',Auth::user()->id)
  									->where('cases.status',1)
  									->select(DB::raw('count(cases.id) as total'))
					  				->get();

		$pending_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
  										->where('b.user_id',Auth::user()->id)
											->where('status',2)
											->select(DB::raw('count(*) as total'))
											->get();
		$closed_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
											->where('b.user_id',Auth::user()->id)
											->where('status',3)
											->select(DB::raw('count(*) as total'))
											->get();	

    return view( 'silent-cases',[ 'cases' => $cases,'active_count' => $active_count[0],'pending_count' => $pending_count[0],'closed_count' => $closed_count[0] ] );
  }
}
