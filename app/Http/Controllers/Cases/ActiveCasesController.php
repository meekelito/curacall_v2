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
    $cases_in = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
              ->where('b.user_id',3)
              ->where('b.is_silent',0) 
              ->where('cases.status',1)
              ->orderBy('cases.id','DESC')
              ->select('cases.id')
              ->get();


    $cases = Cases::with('participants')
              ->whereIn('id',$cases_in)
              ->orderBy('cases.id','DESC')
              ->get();

    $cases_arr = array();
    foreach($cases as $case)
    {
      $participants_arr = array();
      foreach($case->participants as $participant)
      {
        array_push($participants_arr, 
          array(
            'ownership'=>$participant->ownership,
            'fname'=>$participant->user->fname,
            'lname'=>$participant->user->lname,
          )
        );
      }

      $cases_arr[] = array(
        "id"=>$case->id,
        "case_id" => $case->case_id,
        "call_type" => $case->call_type,
        "subcall_type"=> $case->subcall_type,
        "case_message" => $case->case_message,
        "status" => $case->status,
        "created_at" => $case->created_at,
        "participants"=> $participants_arr
      );
      
    }

    // return response()->json(["result"=>$cases_arr]);

    // $cases = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
  		// 				->where('b.user_id',Auth::user()->id)
    //           ->where('b.is_silent',0) 
  		// 				->where('cases.status',1)
  		// 				->orderBy('cases.id','DESC')
  		// 				->select('cases.id','cases.case_id','cases.sender_fullname','cases.status','cases.created_at')
  		// 				->get(); 

		$active_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
									->where('b.user_id',Auth::user()->id)
                  ->where('b.is_silent',0) 
									->where('cases.status',1)
									->select(DB::raw('count(cases.id) as total'))
				  				->get();	
 
		

    return view( 'active-cases',[ 'cases' => $cases_arr,'active_count' => $active_count[0] ] );
  }
}
