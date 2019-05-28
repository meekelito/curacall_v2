<?php
namespace App\Http\Controllers\Cases;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cases;
use DB;
use Cache;
use Auth;
use Validator;

class RepositoryCasesController extends Controller
{
  public function index()
  {
    $cases = Cases::with('participants')
              ->where('status',3)
              ->orderBy('cases.id','DESC')
              ->orderBy('cases.is_reviewed','DESC')
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
                  ->where('is_reviewed',0)
									->select(DB::raw('count(id) as total'))
				  				->get();	
 
    return view( 'repository-cases',[ 'cases' => $cases_arr,'closed_count' => $closed_count[0] ] );
  }

  public function reviewCase(Request $request)
  {
    $validator = Validator::make($request->all(), [
        'case_id' => 'required'
    ]);
    if ($validator->fails()) {
      return json_encode(array(
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ));
    }
    $res = Cases::find($request->case_id);
    $res->is_reviewed = 1;
    $res->save();

    if($res){
      return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"Case tagged as reviewed."
      )); 
    }else{
      return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
    }
  }

  public function review_index($case_id) 
  { 
    return view( 'cases', [ 'case_id' => $case_id,'is_reviewed' => 1 ] );
  }

}
