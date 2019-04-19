<?php
namespace App\Http\Controllers\Cases;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cases;
use DB;
use Cache;
use Auth;

class ActiveCasesController extends Controller
{
  public function index()
  {
    $cases = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
  						->where('b.user_id',Auth::user()->id)
              ->where('b.is_silent',0) 
  						->where('cases.status',1)
  						->orderBy('cases.id','DESC')
  						->select('cases.id','cases.case_id','cases.sender_fullname','cases.status','cases.created_at')
  						->get();

		$active_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
									->where('b.user_id',Auth::user()->id)
                  ->where('b.is_silent',0) 
									->where('cases.status',1)
									->select(DB::raw('count(cases.id) as total'))
				  				->get();		
				  							  	
    return view( 'active-cases',[ 'cases' => $cases,'active_count' => $active_count[0] ] );
  }
}
