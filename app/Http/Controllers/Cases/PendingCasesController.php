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
	// $cases = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
  // 						->where('b.user_id',Auth::user()->id)
  // 						->where('cases.status',2)
  // 						->orderBy('cases.id','DESC')
  // 						->select('cases.id','cases.case_id','cases.sender_fullname','cases.status','cases.created_at')
  // 						->get();

	// $pending_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
	// 							->where('b.user_id',Auth::user()->id)
	// 							->where('cases.status',2)
	// 							->select(DB::raw('count(cases.id) as total'))
	// 		  				->get();				  	
  //   return view( 'pending-cases',[ 'cases' => $cases,'pending_count' => $pending_count[0] ] ); 

    $cases_in = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
              ->where('b.user_id',Auth::user()->id)
              ->where('b.is_silent',0) 
              ->where('cases.status',2)
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
            'is_read'=>$participant->is_read,
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

    $pending_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                  ->where('b.user_id',Auth::user()->id)
                  ->where('b.is_silent',0) 
                  ->where('cases.status',2)
                  ->select(DB::raw('count(cases.id) as total'))
                  ->get();  
 
    

    return view( 'pending-cases',[ 'cases' => $cases_arr,'pending_count' => $pending_count[0] ] );
  }
}
