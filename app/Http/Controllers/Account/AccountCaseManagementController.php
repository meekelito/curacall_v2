<?php
namespace App\Http\Controllers\Account;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cases;
use App\Case_participants;
use DB;
use Cache;
use Auth;

class AccountCaseManagementController extends Controller
{
  public function index() 
  {
    $cases = Cases::with('participants')
              ->where('account_id',Auth::user()->account_id)
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
            'user_id'=>$participant->user_id,
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
        "updated_at" => $case->updated_at,
        "participants"=> $participants_arr
      );
      
    }

    $active_count = Cases::where('cases.account_id',Auth::user()->account_id)
                    ->where('cases.status',1)
                    ->select(DB::raw('count(cases.id) as total'))
                    ->get();

    $pending_count = Cases::where('cases.account_id',Auth::user()->account_id)
                      ->where('status',2)
                      ->select(DB::raw('count(*) as total'))
                      ->get();
    $closed_count = Cases::where('cases.account_id',Auth::user()->account_id)
                      ->where('status',3)
                      ->select(DB::raw('count(*) as total'))
                      ->get();  
               

    return view( 'account-case-management',[ 'cases' => $cases_arr,'active_count' => $active_count[0],'pending_count' => $pending_count[0],'closed_count' => $closed_count[0] ] );
  }
}

