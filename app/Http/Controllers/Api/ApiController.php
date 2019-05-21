<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Cases;
use App\Case_participant;
use App\Case_history;
use DataTables;
use DB;
use Cache;
use Auth;
use Validator;

class ApiController extends Controller
{
  public function newCase(Request $request)
  {
    $validator = Validator::make($request->all(),[ 
      'case_id' => 'required|unique:cases,case_id', 
      'account_id' => 'required',
      'call_type' => 'required',
      'subcall_type' => 'required',
      'case_message' => 'required',
      'sender_id' => 'required',
      'sender_fullname' => 'required',
      'api_key' => 'required'
    ]); 

    if( $validator->fails() ){
      return json_encode(array( 
        "status"=>0,
        "response"=>"error", 
        "message"=>$validator->errors()
      ));
    }

    $key_status = Keys::where('api_key',$request->api_key)
                ->where('status','active')
                ->get();

    if ( $key_status->isEmpty() ){
      return json_encode(array(
        "status" => 0,
        "response" => "failed", 
        "message" => "Error in connection."
      ));
    }
      

    $res = Cases::create($request->all());

    if($res){
      return json_encode(array(
        "status" => 1,
        "response" => "success", 
        "message" => "Successfully sent."
      ));
    }else{
      return json_encode(array(
        "status" => 0,
        "response" => "failed", 
        "message" => "Error in connection."
      ));
    }
  }

  public function getCases($status = 'all',$user_id)
  {
    if($status=='all'){
      $cases = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
              ->where('b.user_id',$user_id)
              ->where('cases.status','!=',4)
              ->select('cases.id','cases.case_id','cases.account_id','cases.call_type','cases.subcall_type','cases.case_message','cases.status','cases.created_at','cases.updated_at')
              ->get();
    }elseif($status=='active'){
      $cases = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
              ->where('b.user_id',$user_id)
              ->where('cases.status',1)
              ->select('cases.id','cases.case_id','cases.account_id','cases.call_type','cases.subcall_type','cases.case_message','cases.status','cases.created_at','cases.updated_at')
              ->get();
    }elseif($status=='pending'){
      $cases = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
              ->where('b.user_id',$user_id)
              ->where('cases.status',2)
              ->select('cases.id','cases.case_id','cases.account_id','cases.call_type','cases.subcall_type','cases.case_message','cases.status','cases.created_at','cases.updated_at')
              ->get();
    }elseif($status=='closed'){
      $cases = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
              ->where('b.user_id',$user_id)
              ->where('cases.status',3)
              ->select('cases.id','cases.case_id','cases.account_id','cases.call_type','cases.subcall_type','cases.case_message','cases.status','cases.created_at','cases.updated_at')
              ->get();
    }

    
    if($cases->isEmpty()){
      return response()->json([
        'message' => 'No data found.']);
    }
    
    return response()->json($cases);
  }

  public function getCaseSpecific(Request $request)
  {
    $validator = Validator::make($request->all(), [
        'case_id' => 'required',
        'user_id' => 'required'
    ]);

    if ($validator->fails()) {
      return response()->json([
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ]);
    }

    $cases = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
              ->where('cases.id',$request->case_id)
              ->where('b.user_id',$request->user_id)
              ->where('cases.status','!=',4) 
              ->select('cases.id','cases.case_id','cases.account_id','cases.call_type','cases.subcall_type','cases.case_message','cases.status','cases.created_at','cases.updated_at')
              ->get();

    return response()->json($cases);
  }

  public function getParticipants(Request $request)
  {
    $validator = Validator::make($request->all(), [
        'case_id' => 'required'
    ]);

    if ($validator->fails()) {
      return response()->json([
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ]);
    }

    $participants = Case_participant::leftJoin('users AS b','case_participants.user_id','=','b.id')
    ->where('case_participants.case_id',$request->case_id)
    ->select('case_participants.ownership','b.id','b.fname','b.lname','.b.status')
    ->orderBy('case_participants.ownership')
    ->get();

    
    if($participants->isEmpty()){
      return response()->json([
        'message' => 'No data found.']);
    }
    
    return response()->json($participants);
  }

  public function acceptCase(Request $request) 
  { 
    $validator = Validator::make($request->all(), [
        'case_id' => 'required',
        'user_id' => 'required'
    ]);

    if ($validator->fails()) {
      return response()->json([
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ]);
    }

    $res = Cases::find($request->case_id);
    $res->status = 2;
    $res->save();


    $state = Case_participant::leftJoin('users AS b','case_participants.user_id','=','b.id')
    ->where("case_participants.case_id",$request->case_id)
    ->where('case_participants.ownership',3)
    ->select('b.fname','b.lname')
    ->get(); 

    if(!$state->isEmpty()){
      $name = $state[0]->fname.' '.$state[0]->lname;
      return response()->json([
        "status"=>2,
        "response"=>"warning",
        "message"=>"This case is already taken by ".$name
      ]);
    }

    $count = Case_participant::where("case_id",$request->case_id)->get();

    if( $count->count() > 1 ){
      
      $update_res = Case_participant::where('case_id', $request->case_id)
      ->where('ownership', 2 )
      ->update(['ownership' => 5]); 

      $update_res = Case_participant::where('case_id', $request->case_id)
      ->where('user_id', $request->user_id )
      ->update(['ownership' => 3]);
    }

    $res = Case_history::create( ["is_visible"=>1,"status"=>2,"case_id" => $request->case_id,"action_note" => "Case Accepted", 'created_by' => $request->user_id ] ); 

    if($res){
      return response()->json([
        "status"=>1,
        "response"=>"success",
        "message"=>"Case status updated successfully."
      ]);
    }else{
      return response()->json([
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ]);
    }
  }


  public function getCaseCount(Request $request)
  {

    $active_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                    ->where('b.user_id',$request->user_id)
                    ->where('cases.status',1)
                    ->select(DB::raw('count(cases.id) as total'))
                    ->get();

    $pending_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                      ->where('b.user_id',$request->user_id)
                      ->where('status',2)
                      ->select(DB::raw('count(*) as total'))
                      ->get();
    $closed_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                      ->where('b.user_id',$request->user_id)
                      ->where('status',3)
                      ->select(DB::raw('count(*) as total'))
                      ->get();  

    return json_encode(array(
        "status"=>1,
        "all_count"=>$active_count[0]->total+$pending_count[0]->total+$closed_count[0]->total,
        "active_count"=>$active_count[0]->total,
        "pending_count"=>$pending_count[0]->total,
        "closed_count"=>$closed_count[0]->total
      ));

    return response()->json([
      "status"=>1,
      "all_count"=>$active_count[0]->total+$pending_count[0]->total+$closed_count[0]->total,
      "active_count"=>$active_count[0]->total,
      "pending_count"=>$pending_count[0]->total,
      "closed_count"=>$closed_count[0]->total
    ]);
  }

  public function closeCase(Request $request)
  {
    // return response()->json([
    //   "status"=>0,
    //   "response"=>"failed", 
    //   "message"=>"Error in connection."
    // ]);

    $validator = Validator::make($request->all(), [
        'case_id' => 'required',
        'user_id' => 'required',
        'note' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json([
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ]);
    }


    $res = Cases::find($request->case_id);
    $res->status = 3;
    $res->save();
  

    $res2 = Case_history::create( $request->all()+["status" => 3,"action_note" => "Case Closed", 'created_by' => $request->user_id ] ); 

    if($res2){
      return response()->json([
        "status"=>1,
        "response"=>"success",
        "message"=>"Case successfully Closed."
      ]);
    }else{
      return response()->json([
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ]);
    }
  }

  public function testCasex(Request $request)
  {
    $cases = Cases::with("participants")->get();


    return response()->json([ 
      "message"=>$cases
    ]);

  }

  public function testCase()
  {
    // $cases = Cases::with('participants')->where('status',1)->get();
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

    return response()->json(["result"=>$cases_arr]);
  }

  public function testCasexx(Request $request)
  {
    $cases_in = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
              ->where('b.user_id',3)
              ->where('b.is_silent',0) 
              ->where('cases.status',1)
              ->orderBy('cases.id','DESC')
              ->select('cases.id')
              ->get();

    $cases = Cases::leftJoin('case_participants AS b','cases.id','=','b.case_id')
              ->leftJoin('users AS c','b.user_id','c.id')
    
              ->where('cases.status',1)
              ->orderBy('cases.id','DESC')
              ->select('cases.id','cases.case_id','cases.sender_fullname','cases.status','cases.created_at','c.fname')
              ->get();


    return response()->json([ 
      "message"=>$cases_in
    ]);

  }

  function getAverageTime($status,$from,$to,$action_note = '', $user_id = 'all')
  {
    /** Function to get the average time base on case_history table **/
      $action_note_condition = '';
      if($action_note != '')
        $action_note_condition = " AND action_note = '$action_note'";

      if($user_id == 'all')
        $case_participants = "SELECT case_id FROM case_participants WHERE  ownership != 4";
      else
        $case_participants = "SELECT case_id FROM case_participants WHERE  ownership != 4 AND user_id = ".$user_id;

      $data = DB::select("SELECT a.*,b.created_at as date_created,TIMESTAMPDIFF(MINUTE,b.created_at,a.created_at) as time_diff FROM case_history a 

       LEFT JOIN cases b ON a.case_id = b.id 
       WHERE a.status = ?
       AND a.case_id IN(".$case_participants.")
       AND (a.created_at BETWEEN ? AND ?) 
       GROUP BY a.case_id",[$status,$from,$to]);

      $totaltime = 0;

      if($data){
        foreach($data as $row){
                $timestamp = $row->time_diff;
                $totaltime += $timestamp;
        }

        $average_time = ($totaltime/count($data));

        return $this->convertToHumanTime($average_time);
      }else
      return "0";
  }

  function convertToHumanTime($minutes,$precision = 'first') {

    $d = floor ($minutes / 1440);
    $h = floor (($minutes - $d * 1440) / 60);
    $m = floor($minutes - ($d * 1440) - ($h * 60));
    
    $days = $d > 1 ? 'days' : 'day';
    $hours = $h > 1 ? 'hours' : 'hour';
    $minutes = $m > 1 ? 'minutes' : 'minute';

    $display = array();
    if($d > 0 )
      array_push($display, "{$d} $days");
    if($h > 0 )
      array_push($display, "{$h}  $hours");
    if($m > 0)
      array_push($display, "{$m} $minutes");

    if($precision == 'first')
      return $display[0];
    else if($precision == 'last')
      return $display[count($display)-1];
    else
      return implode(' ',$display);
}

  public function getReportAverageTime(Request $request)
  {
    $validator = Validator::make($request->all(),[ 
      'user_id' => 'required',
      'from'  => 'required|date',
      'to'    =>  'required|date'
    ]); 

    if( $validator->fails() ){
      return json_encode(array( 
        "status"=>0,
        "response"=>"error", 
        "message"=>$validator->errors()
      ));
    }



      $read =  $this->getAverageTime(1,$request->from,$request->to,'Case Read',$request->user_id);

      $accepted = $this->getAverageTime(2,$request->from,$request->to,$request->user_id);
  
      $closed = $this->getAverageTime(3,$request->from,$request->to,$request->user_id);

      return json_encode(array("read"=> $read,"accepted" =>$accepted,"closed"=>$closed));
  }

}
