<?php
namespace App\Http\Controllers\Cases;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cases;
use App\Case_participants;
use DB;
use Cache;
use Auth;

class AllCasesController extends Controller
{
  public function index() 
  {
  	// $cases_in = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
  	// 					->where('b.user_id',Auth::user()->id)
   //            ->where('cases.status',"!=",4)
  	// 					->orderBy('cases.status')
  	// 					->orderBy('cases.id','DESC')
  	// 					->select('cases.id','cases.case_id','cases.sender_fullname','cases.status','cases.created_at','cases.updated_at')
  	// 					->get();

    $cases_in = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
             ->where('b.user_id',Auth::user()->id)

             
             ->select('cases.id')
             ->get();

    $cases = Cases::with('participants')
              ->whereIn('id',$cases_in)
              ->orderBy('cases.status')
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
        "updated_at" => $case->updated_at,
        "participants"=> $participants_arr
      );
      
    }


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

 
                     

    return view( 'all-cases',[ 'cases' => $cases_arr,'active_count' => $active_count[0],'pending_count' => $pending_count[0],'closed_count' => $closed_count[0] ] );
  }
}
