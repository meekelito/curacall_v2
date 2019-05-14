<?php
namespace App\Http\Controllers\Account;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Cases;
use App\Case_participant;
use App\Case_history;
use DB;
use Cache;
use Auth;
use Validator;
use App\Notifications\CaseNotification;
use App\Notification;

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

  public function pullCase(Request $request) 
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

    $case_info = Cases::where('id',$request->case_id)->get();
    //check if admin is participant
    $participation = Case_participant::where('case_id',$request->case_id)
                    ->where('user_id',Auth::user()->id)
                    ->get();

    if( $case_info[0]->status == 1 || $case_info[0]->status == 2 ){
      $res = Cases::find($request->case_id);
      $res->status = 2;
      $res->save();

      $update_res = Case_participant::where('case_id', $request->case_id)
      ->update(['ownership' => 4,'is_read' => 0]); 

      if( $participation->isEmpty() ){
        $res = Case_participant::create( ["case_id" => $request->case_id,'user_id' => Auth::user()->id,"ownership"=>2,'is_read' => 1 ] ); 
      }else{
        $update_res = Case_participant::where('case_id', $request->case_id)
        ->where('user_id', Auth::user()->id )
        ->update(['ownership' => 2,'is_read' => 1]);
      }
      
      $res = Case_history::create( ["is_visible"=>1,"status"=>2, "case_id" => $request->case_id,"action_note" => "Case Pulled", 'created_by' => Auth::user()->id ] ); 

    }else{
      $update_res = Case_participant::where('case_id', $request->case_id)
      ->update(['ownership' => 4,'is_read' => 0]); 

      if( $participation->isEmpty() ){
        $res = Case_participant::create( ["case_id" => $request->case_id,'user_id' => Auth::user()->id,"ownership"=>2,'is_read' => 1 ] ); 
      }else{
        $update_res = Case_participant::where('case_id', $request->case_id)
        ->where('user_id', Auth::user()->id )
        ->update(['ownership' => 2,'is_read' => 1]);
      }
      $res = Case_history::create( ["is_visible"=>1, "case_id" => $request->case_id,"action_note" => "Case Pulled", 'created_by' => Auth::user()->id ] ); 
    }

    if($res){
      /** Notify case participants that the case was accepted **/
      $participants = Case_participant::where("case_id",$request->case_id)->where('user_id','!=',Auth::user()->id)->get();

      $message = str_replace("[from_name]",Auth::user()->fname . ' ' . Auth::user()->lname,__('notification.pull_case'));
      $message = str_replace("[case_id]",$request->case_id,$message);
      $arr = array(
          'from_id'     => Auth::user()->id,
          'from_name'   => Auth::user()->fname . ' ' . Auth::user()->lname,
          'from_image'  => Auth::user()->prof_img,
          'case_id'     => $request->case_id,
          'message'     =>    $message,
          'type'        =>  'pull_case',
          'action_url'  => route('case',[$request->case_id])
      );

      foreach($participants as $row)
      {
         $user = User::find($row->user_id);
         $user->notify(new CaseNotification($arr)); // Notify participant
      }
      /** End notifcation **/
      return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"Case pulled successfully."
      ));
    }else{
      return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
    }
   
  }
}

