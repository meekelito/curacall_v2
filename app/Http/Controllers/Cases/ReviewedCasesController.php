<?php
namespace App\Http\Controllers\Cases;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cases;
use DB;
use Cache;
use Auth;

class ReviewedCasesController extends Controller
{
  public function index()
  {
    $cases = Cases::with('participants')
              ->where('status',3)
              ->where('is_reviewed',1)
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
             
		$closed_count = Cases::where('status',3)
                  ->where('is_reviewed',1)
									->select(DB::raw('count(cases.id) as total'))
				  				->get();	
 
    return view( 'reviewed-cases',[ 'cases' => $cases_arr,'closed_count' => $closed_count[0] ] );
  }
}
