<?php
namespace App\Http\Controllers\Cases;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Cases;
use App\Case_participant;
use App\Case_history;
use App\Note;
use DataTables;
use DB;
use Cache;
use Auth;
use Validator;

class NewCaseController2 extends Controller
{
  public function index($case_id) 
  {	
    $participation = Case_participant::where('case_id',$case_id)
                    ->where('user_id',Auth::user()->id)
                    ->get();

    if( $participation->isEmpty() ){
      return redirect('all-cases');
    }

  	$case_info = Cases::where('id',$case_id)->get();

    $participants = Case_participant::leftJoin('users AS b','case_participants.user_id','=','b.id')
    ->where('case_participants.case_id',$case_id)
    ->orderBy('case_participants.ownership')
    ->get();

  	if( $case_info[0]->status == 1 ){
      $res =  Case_history::updateOrCreate( ["status"=>1,"case_id" => $case_id,"action_note" => "Case Read", 'created_by' => Auth::user()->id ] ); 
    }

    return view( 'cases', [ 'case_id' => $case_id,'case_info' => $case_info,'participation'=>$participation,'participants'=>$participants ] );
  }

  public function fetchNotes($case_id) 
  {    
    $note = Case_history::leftJoin('users AS b','case_history.created_by','=','b.id')
    				->where('case_history.case_id',$case_id)
            ->where('case_history.status',null)
    				->orderBy('case_history.id','DESC')
    				->select('case_history.id','case_history.note','case_history.created_at','b.prof_img','b.lname','b.fname');

    return Datatables::of($note)
    ->addColumn('note',function($note){
      $petsa = "";
      $content = "";
      if( date('Y-m-d') == date('Y-m-d', strtotime($note->created_at))){
        $petsa = date_format($note->created_at,"h:i a");
      }else{
        $petsa = date_format($note->created_at,"M d") ;
      }
      if( strlen($note->note) >= 50){
        $content = substr($note->note, 0, 50).'...';
      }else{
        $content = $note->note;
      }

      return '<div class="media-left">
      <img src="'.asset('storage/uploads/users/'.$note->prof_img).'" class="img-circle img-xs" alt="">
      </div>
      <div class="media-body" style="width: 100%;">
        <a onclick="view_note('.$note->id.')">
        '.$note->fname.' '.$note->lname.'
        <span class="media-annotation pull-right">
        '.$petsa.'
        </span>
        </a>
        <span class="display-block text-muted">'.$content.'</span>
      </div>';
    })
    ->rawColumns(['note'])
    ->make(true);                                                               
  } 
  //accept case
  public function acceptCase(Request $request) 
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
    $res->status = 2;
    $res->save();

    $state = Case_participant::leftJoin('users AS b','case_participants.user_id','=','b.id')
    ->where("case_participants.case_id",$request->case_id)
    ->where('case_participants.ownership',3)
    ->select('b.fname','b.lname')
    ->get(); 

    if(!$state->isEmpty()){
      $name = $state[0]->fname.' '.$state[0]->lname;
      return json_encode(array(
        "status"=>2,
        "response"=>"success",
        "message"=>"This case is already taken by ".$name
      ));
    }
    $count = Case_participant::where("case_id",$request->case_id)->get();

    if( $count->count() > 1 ){
      
      $update_res = Case_participant::where('case_id', $request->case_id)
      ->where('ownership', 2 )
      ->update(['ownership' => 5]); 

      $update_res = Case_participant::where('case_id', $request->case_id)
      ->where('user_id', Auth::user()->id )
      ->update(['ownership' => 3]);
    }

    $res = Case_history::create( ["status"=>2,"case_id" => $request->case_id,"action_note" => "Case Accepted", 'created_by' => Auth::user()->id ] ); 

    if($res){
      return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"Case status updated successfully."
      ));
    }else{
      return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
    }
  }

  //decline case
  public function getModalDeclineCase(Request $request) 
  {  
    $case_id = $request->input('case_id');      
    return view('components.cases.decline-case-md',[ 'case_id'=>$case_id ]); 
  }

  public function declineCase(Request $request)
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


    $count = Case_participant::where("case_id",$request->case_id)->get();

    if( $count->count() == 1 ){
      $res1 = Cases::find($request->case_id);
      $res1->status = 4;
      $res1->save();
    }

    $update_res = Case_participant::where('case_id', $request->case_id)
      ->where('user_id', Auth::user()->id )
      ->update(['ownership' => 4]); 

    // if( $count->count() == 1 ){
    //   $res1 = Cases::find($request->case_id);
    //   $res1->status = 4;
    //   $res1->save();
    // }else{
    //   $update_res = Case_participant::where('case_id', $request->case_id)
    //   ->where('ownership', 2 )
    //   ->update(['ownership' => 5]); 

    //   $update_res = Case_participant::where('case_id', $request->case_id)
    //   ->where('user_id', Auth::user()->id )
    //   ->update(['ownership' => 3]);  
    // }
    
    $res = Case_history::create( ["status"=>4,"case_id" => $request->case_id,"action_note" => "Case Declined", 'created_by' => Auth::user()->id ] ); 

    

    if($res){
      return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"Case status updated successfully."
      ));
    }else{
      return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
    }
  }


  public function getModalForwardCase(Request $request) 
  {				
    $case_id = $request->input('case_id');	
  	$users = User::where('id','!=',Auth::user()->id)
                ->where('status','active')
                ->orderBy('fname') 
                ->get();  	
    return view('components.cases.forward-case-md',[ 'users'=>$users,'case_id' => $case_id ]); 
  }
  
  public function getModalCloseCase(Request $request) 
  {							 
    $case_id = $request->input('case_id');
    return view('components.cases.close-case-md',['case_id' => $case_id]); 
  }
  
  public function getModalReOpenCase(Request $request) 
  {              
    $case_id = $request->input('case_id');
    return view('components.cases.reopen-case-md',['case_id' => $case_id]); 
  }

  public function getModalAddNote(Request $request) 
  {							 
  	$case_id = $request->input('case_id');
    return view('components.cases.add-note-md',['case_id' => $case_id]); 
  }

  public function getModalViewNote(Request $request) 
  {            
    $note = Case_history::leftJoin('users AS b','case_history.created_by','=','b.id')
            ->where('case_history.id',$request->input('id'))
            ->select('case_history.id','case_history.note','case_history.created_at','b.prof_img','b.lname','b.fname')
            ->get();

    return view('components.cases.view-note-md',['note' => $note]); 
  }

  public function newNote(Request $request)
  {
  	$validator = Validator::make($request->all(), [
        'case_id' => 'required',
        'note' => 'required',
    ]);

    if ($validator->fails()) {
      return json_encode(array(
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ));
    }

    if( $request->case_form == "close" ){
    	$res = Cases::find($request->case_id);
	    $res->status = 3;
	    $res->save();
    }

    if( $request->case_form == "reopen" ){
      $res = Cases::find($request->case_id);
      $res->status = 2;
      $res->save();
    }

  	$res = Case_history::create( $request->all()+[ 'created_by' => Auth::user()->id ] ); 

  	if($res){
  		return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"Note successfully added."
      ));
  	}else{
  		return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
  	}
  }

  public function closeCase(Request $request)
  {
    $validator = Validator::make($request->all(), [
        'case_id' => 'required',
        'note' => 'required',
    ]);

    if ($validator->fails()) {
      return json_encode(array(
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ));
    }

    if( $request->case_form == "close" ){
      $res = Cases::find($request->case_id);
      $res->status = 3;
      $res->save();
    }

    $res = Case_history::create( $request->all()+["status" => 3,"action_note" => "Case Closed", 'created_by' => Auth::user()->id ] ); 

    if($res){
      return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"Case successfully Closed."
      ));
    }else{
      return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
    }
  }

  public function forwardCase(Request $request)
  {

      return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"Note successfully added.".$request->recipient
      ));

  }

  public function reopenCase(Request $request)
  {
    $validator = Validator::make($request->all(), [
        'case_id' => 'required',
        'note' => 'required',
    ]);

    if ($validator->fails()) {
      return json_encode(array(
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ));
    }

    if( $request->case_form == "close" ){
      $res = Cases::find($request->case_id);
      $res->status = 2;
      $res->save();
    }

    $res = Case_history::create( $request->all()+["status" => 2,"action_note" => "Case Re-Opened", 'created_by' => Auth::user()->id ] ); 

    if($res){
      return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"Case successfully re-opened."
      ));
    }else{
      return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
    }
  }

  public function checkCase(Request $request)
  {
     $state = Case_participant::leftJoin('users AS b','case_participants.user_id','=','b.id')
    ->where("case_participants.case_id",$request->case_id)
    ->where('case_participants.ownership',3)
    ->select('b.fname','b.lname')
    ->get(); 

    if(!$state->isEmpty()){
      $name = $state[0]->fname.' '.$state[0]->lname;
      return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"This case is already taken by ".$name
      ));
    }else{
      return json_encode(array(
        "status"=>0,
        "response"=>"success",
        "message"=>"Case is still available."
      ));
    }
  }

  public function countCase(Request $request)
  {

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

    return json_encode(array(
        "status"=>1,
        "all_count"=>$active_count[0]->total+$pending_count[0]->total+$closed_count[0]->total,
        "active_count"=>$active_count[0]->total,
        "pending_count"=>$pending_count[0]->total,
        "closed_count"=>$closed_count[0]->total
      ));
  }

}
