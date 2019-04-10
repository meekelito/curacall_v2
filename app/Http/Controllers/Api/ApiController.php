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
    ->select('b.id','b.fname','b.lname','.b.status')
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



}
