<?php
namespace App\Http\Controllers\Cases;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cases;
use DB;
use Cache;
use Auth;

class ClosedCasesController extends Controller
{
  public function index()
  {
		$cases = Cases::Join('case_participants AS b','cases.case_id','=','b.case_id')
  						->where('b.user_id',Auth::user()->id)
  						->where('cases.status',3)
  						->orderBy('cases.id','DESC')
  						->select('cases.id','cases.case_id','cases.sender_fullname','cases.status','cases.created_at')
  						->get();

		$closed_count = Cases::Join('case_participants AS b','cases.case_id','=','b.case_id')
									->where('b.user_id',Auth::user()->id)
									->where('cases.status',3)
									->select(DB::raw('count(cases.id) as total'))
				  				->get();	

    return view( 'closed-cases',[ 'cases' => $cases,'closed_count' => $closed_count[0] ] );
  }
}
