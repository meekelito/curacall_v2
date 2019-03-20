<?php
namespace App\Http\Controllers\Cases;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cases;
use DB;
use Cache;
use Auth;

class PendingCasesController extends Controller
{
  public function index()
  {
		$cases = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
  						->where('b.user_id',Auth::user()->id)
  						->where('cases.status',2)
  						->orderBy('cases.id','DESC')
  						->select('cases.id','cases.case_id','cases.sender_fullname','cases.status','cases.created_at')
  						->get();

		$pending_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
									->where('b.user_id',Auth::user()->id)
									->where('cases.status',2)
									->select(DB::raw('count(cases.id) as total'))
				  				->get();				  	
    return view( 'pending-cases',[ 'cases' => $cases,'pending_count' => $pending_count[0] ] );
  }
}
