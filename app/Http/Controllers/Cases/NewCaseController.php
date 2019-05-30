<?php
namespace App\Http\Controllers\Cases;
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
use PDF;
use Validator;
use App\Notifications\CaseNotification;
use App\Notification;

class NewCaseController extends Controller
{
  public function index($case_id) 
  { 
    $participation = Case_participant::where('case_id',$case_id)
                    ->where('user_id',Auth::user()->id)
                    ->get();

    if( $participation->isEmpty() ){
      if(Auth::user()->role_id == 4 || Auth::user()->role_id == 1){

      }else{
        abort(404);
      }
    }

    $case_info = Cases::where('id',$case_id)->get();

    if( !$participation->isEmpty() && $case_info[0]->status == 1 ){
      Case_history::updateOrCreate( ["is_visible"=>1,"status"=>1,"case_id" => $case_id,"action_note" => "Case Read", 'created_by' => Auth::user()->id ] ); 
    }

    if( !$participation->isEmpty() ){
      Case_participant::where('case_id', $case_id)->where('user_id', Auth::user()->id)->update(['is_read' => 1]); 
    }

    return view( 'cases', [ 'case_id' => $case_id,'is_reviewed' => 0 ] );

  }

  public function fetchCase(Request $request) 
  { 
    $is_reviewed = $request->input('is_reviewed');  
    $case_id = $request->input('case_id');  
    $participation = Case_participant::where('case_id',$case_id)
                    ->where('user_id',Auth::user()->id)
                    ->get();

    if( $participation->isEmpty() ){
      if(Auth::user()->role_id == 4 || Auth::user()->role_id == 1){

      }else{
        abort(404);
      }
      
    } 

    $case_info = Cases::where('id',$case_id)->get();
    $participants = Case_participant::leftJoin('users AS b','case_participants.user_id','=','b.id')
    ->where('case_participants.case_id',$case_id)
    ->orderBy('case_participants.ownership')
    ->get();
    return view( 'components.cases.content-case', [ 'case_id' => $case_id,'case_info' => $case_info,'participation'=>$participation,'participants'=>$participants,'is_reviewed'=>$is_reviewed ] );
  }

  public function fetchParticipants($case_id) 
  {    
    $participants = Case_participant::leftJoin('users AS b','case_participants.user_id','=','b.id')->where('case_participants.case_id',$case_id)->select('b.prof_img','b.fname','b.lname','b.title','b.phone_no','b.email');
    return Datatables::of($participants)
    ->addColumn('participants',function($participants){
      return'<div class="media-link cursor-pointer" data-toggle="collapse" data-target="#$participants->id">
            <div class="media-left"><img  src="'.asset('storage/uploads/users/'.$participants->prof_img).'" class="img-circle img-md" alt=""></div>
            <div class="media-body">
              <div class="media-heading text-semibold">
              '.$participants->fname.' '.$participants->lname.'
              </div>
              <span class="text-muted">'.ucwords($participants->title).'</span>
            </div>
          </div>';
    })
    ->rawColumns(['participants'])
    ->make(true);                                                               
  } 

  public function fetchNotes($case_id) 
  {    
    $note = Case_history::leftJoin('users AS b','case_history.created_by','=','b.id')
            ->where('case_history.case_id',$case_id)
            ->where('case_history.is_visible',1)
            ->orderBy('case_history.id','DESC')
            ->select('case_history.id','case_history.action_note','case_history.note','case_history.created_at','b.prof_img','b.lname','b.fname');
    return Datatables::of($note)
    ->addColumn('note',function($note){
      $petsa = date_format($note->created_at,"M d,Y  h:i a");
      $content = "";
      if( strlen($note->note) >= 50){
        $content = substr($note->note, 0, 50).'...';
      }else{
        $content = $note->note;
      }
      if($note->note==null){
        $content = $note->action_note;
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
    
    $state = Case_participant::leftJoin('users AS b','case_participants.user_id','=','b.id')
    ->where("case_participants.case_id",$request->case_id)
    ->where('case_participants.ownership',2)
    ->select('b.fname','b.lname')
    ->get(); 
    
    if(!$state->isEmpty()){
      $name = $state[0]->fname.' '.$state[0]->lname;
      return json_encode(array(
        "status"=>2,
        "response"=>"warning",
        "message"=>"This case is already taken by ".$name
      ));
    }

    $res = Cases::find($request->case_id);
    $res->status = 2;
    $res->save();

      
    $update_res = Case_participant::where('case_id', $request->case_id)
    ->update(['ownership' => 4,'is_read' => 1]); 
    $update_res = Case_participant::where('case_id', $request->case_id)
    ->where('user_id', Auth::user()->id )
    ->update(['ownership' => 2]);

    $res = Case_history::create( ["is_visible"=>1,"status"=>2,"case_id" => $request->case_id,"action_note" => "Case Accepted", 'created_by' => Auth::user()->id ] ); 
    if($res){
       /** Notify case participants that the case was accepted **/
          $participants = Case_participant::where("case_id",$request->case_id)->where('user_id','!=',Auth::user()->id)->get();
 
          $message = str_replace("[from_name]",Auth::user()->fname . ' ' . Auth::user()->lname,__('notification.accept_case'));
          $message = str_replace("[case_id]",$request->case_id,$message);
          $arr = array(
              'from_id'     => Auth::user()->id,
              'from_name'   => Auth::user()->fname . ' ' . Auth::user()->lname,
              'from_image'  => Auth::user()->prof_img,
              'case_id'     => $request->case_id,
              'message'     =>    $message,
              'type'        =>  'accept_case',
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
    $case_id = $request->case_id;      
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
    
    $res = Case_history::create( ["is_visible"=>1,"status"=>4,"case_id" => $request->case_id,"action_note" => "Case Declined", 'created_by' => Auth::user()->id ] ); 
    
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
    $case_id = $request->case_id; 
    if( Auth::user()->is_curacall ){
      $users = User::where('id','!=',Auth::user()->id)
                ->where('status','active')
                ->orderBy('fname')
                ->get();
    }else{
      $users = User::where('id','!=',Auth::user()->id)
                ->where('status','active')
                ->where('account_id',Auth::user()->account_id)
                ->orderBy('fname')
                ->get();
    }
                 
    return view('components.cases.forward-case-md',[ 'users'=>$users,'case_id' => $case_id ]); 
  }
  
  public function getModalCloseCase(Request $request) 
  {              
    $case_id = $request->case_id;
    return view('components.cases.close-case-md',['case_id' => $case_id]); 
  }
  
  public function getModalReOpenCase(Request $request) 
  {              
    $case_id = $request->case_id;
    return view('components.cases.reopen-case-md',['case_id' => $case_id]); 
  }
  public function getModalAddNote(Request $request) 
  {              
    $case_id = $request->case_id;
    return view('components.cases.add-note-md',['case_id' => $case_id]); 
  }
  public function getModalViewNote(Request $request) 
  {            
    $note = Case_history::leftJoin('users AS b','case_history.created_by','=','b.id')
            ->where('case_history.id',$request->input('id'))
            ->select('case_history.id','case_history.action_note','case_history.note','case_history.created_at','b.prof_img','b.lname','b.fname')
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
    $res = Case_history::create( $request->all()+[ "is_visible"=>1,'created_by' => Auth::user()->id ] ); 
    if($res){

       $participants = Case_participant::where('case_id',$request->case_id)->where('user_id','!=',Auth::user()->id)->get();
        $message = str_replace("[from_name]",Auth::user()->fname . ' ' . Auth::user()->lname,__('notification.new_note'));
        $message = str_replace("[case_id]",$request->case_id,$message);
        $arr = array(
            'from_id'     => Auth::user()->id,
            'from_name'   => Auth::user()->fname . ' ' . Auth::user()->lname,
            'from_image'  => Auth::user()->prof_img,
            'case_id'     => $request->case_id,
            'message'     =>    $message,
            'type'        =>  'added_note',
            'action_url'  => route('case',[$request->case_id])
        );


      foreach($participants as $participant)
      {
          $participant->user->notify(new CaseNotification($arr));
      }

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
    
    $res = Cases::find($request->case_id);
    $res->status = 3;
    $res->save();
    
    //add here update all participants if the participant ownership is still 5 and not 2
    $res = Case_history::create( $request->all()+["status" => 3,"is_visible" => 1,"action_note" => "Case Closed", 'created_by' => Auth::user()->id ] ); 
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

    $res = Cases::find($request->case_id);
    $res->status = 2;
    $res->save(); 

    $res = Case_history::create( $request->all()+["is_visible" => 1,"status" => 2,"action_note" => "Case Re-Opened", 'created_by' => Auth::user()->id ] ); 

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
    ->where('case_participants.ownership',2)
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
    $active_count = 0;
    $pending_count = 0;
    $closed_count = 0;
    $silent_count = 0;

    if(auth()->user()->can('view-active-cases')){
          $active_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                    ->where('b.user_id',Auth::user()->id)
                    ->where('b.is_silent',0) 
                    ->where('cases.status',1)
                    ->select(DB::raw('count(cases.id) as total'))
                    ->first()->total;
    }

    if(auth()->user()->can('view-pending-cases')){
          $pending_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                      ->where('b.user_id',Auth::user()->id)
                      ->where('status',2)
                      ->select(DB::raw('count(*) as total'))
                      ->first()->total;
    }

    if(auth()->user()->can('view-closed-cases')){
          $closed_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                      ->where('b.user_id',Auth::user()->id)
                      ->where('status',3)
                      ->select(DB::raw('count(*) as total'))
                      ->first()->total;  
    }

    if(auth()->user()->can('view-silent-cases')){
          $silent_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                    ->where('b.user_id',Auth::user()->id)
                    ->where('b.is_silent',1) 
                    ->where('cases.status',1)
                    ->select(DB::raw('count(cases.id) as total'))
                    ->first()->total;
    }

    return json_encode(array(
      "status"=>1,
      "all_count"=>$active_count + $pending_count + $closed_count + $silent_count,
      "active_count"=>$active_count,
      "pending_count"=>$pending_count,
      "closed_count"=>$closed_count,
      "silent_count"=>$silent_count
    ));
  }
  public function forwardCase(Request $request)
  {
    $validator = Validator::make($request->all(), [
        'case_id' => 'required',
        'note' => 'required',
        'recipient' => 'required'
    ]);
    if ($validator->fails()) {
      return json_encode(array(
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ));
    }

    //checking if the user is still the owner of the case
    $participation = Case_participant::where('case_id',$request->case_id)
                    ->where('user_id',Auth::user()->id)
                    ->get();

    if( $participation[0]->ownership == 2 || $participation[0]->ownership == 5 ){

    }else{
      return json_encode(array(
        "status"=>2,
        "response"=>"warning",
        "message"=>"Error while updating please refresh the page."
      ));
    }

    
    // compare the participants and recipients if existing update the ownership if not insert to participants 
    // $participants_id = Case_participant::where("case_id",$request->case_id)
    // ->select('user_id')
    // ->get();
    $participants_id = Case_participant::select('user_id')->where("case_id",$request->case_id)->where('user_id','!=',Auth::user()->id)->get();// jeric's update, exclude self for notification purposes
    $participants = array();
    //$update = array();
    //$insert = array();
     $forwarded_recipients = array(); // list of forwarded users
    foreach ($participants_id as $row) {
      $participants[] = $row->user_id;
    }

    /** Notification message template **/
      $message = str_replace("[from_name]",Auth::user()->fname . ' ' . Auth::user()->lname,__('notification.forward_case'));
      $message = str_replace("[case_id]",$request->case_id,$message);
      $arr = array(
          'case_id'     => $request->case_id,
          //'message'     => $message,
          'type'        => 'forward_case',
          //'forward_to'  => $forwarded_recipients,
          'action_url'  => route('case',[$request->case_id])
      );
    /** END Notification message template **/

   
    // update all participants ownership state to Forwarded
    foreach ($request->recipient as $row) {
      if (in_array($row, $participants)){ 
        Case_participant::where('case_id', $request->case_id)
        ->where('user_id', $row )
        ->update(['ownership' => 1, 'is_read' => 0]); 
      }else{ 
        Case_participant::create( ["case_id" => $request->case_id,"user_id" => $row, 'ownership' => 1, 'is_read' => 0 ] ); 
        $user = User::find($row);

        $arr['forward_to'] = $user->fname . ' ' . $user->lname;
        $arr['message'] = $message . "you";
        Notification::notify_user($arr,$user);
      } 

      /** create recipients list for notification **/
      $user = User::find($row);
      $user_info = array(  
                        "id"    =>  $user->id,
                        "name"  =>  $user->fname . ' ' . $user->lname
                      );

      array_push($forwarded_recipients,$user_info); // add notifiable user info into array
      /** end create recipients list for notification **/

      Case_history::create( $request->all()+["is_visible" => 1,"status" => 2,"action_note" => "Case Forwarded","sent_to"=>$row, 'created_by' => Auth::user()->id ] ); 
    }



    /** Sending Notifcation part **/
     $other_participant_count = count($request->recipient) - 1;
    // Notify all participants of the case except you
    foreach($participants as $row)
    {
         $user = User::find($row);
        
         if(in_array($user->id,$request->recipient))
         {
            $str_recipients = "You";
            if(count($request->recipient) > 1){
              $str_recipients = "You and " . $other_participant_count;
              $str_recipients .= ($other_participant_count == 1) ? " Other" : " Others";
            }
         }else{
              $str_recipients = $forwarded_recipients[0]['name'];
              if(count($request->recipient) > 1){
                $str_recipients = $forwarded_recipients[0]['name'] . " and " . $other_participant_count;
                $str_recipients .= ($other_participant_count == 1) ? " Other" : " Others";
              }
         }
         $arr['forward_to'] = $forwarded_recipients;
         $arr['message'] = $message . $str_recipients;
         //$user->notify(new CaseNotification($arr)); // Notify participant
         Notification::notify_user($arr,$user);
    }
    /** End Sending Notification part **/

    $res=Case_participant::where('case_id', $request->case_id)
    ->where('user_id', Auth::user()->id  )
    ->update(['ownership' => 5]); 
   
  
    if($res){
      return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"Case successfully forwarded."
      ));
    }else{
      return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
    }
    
    // $participants_id = array();
    // $recipient = $request->recipient;
    // foreach ($count as $row) {
    //   $participants_id[] = $row->user_id;
    // }
    // $result = array_diff($recipient,$participants_id);
    // return json_encode(array(
    //   "status"=>1,
    //   "response"=>"error",
    //   "message"=> "update: ".$update."\n insert: ".$insert
    // ));
    // return json_encode(array(
    //   "status"=>1,
    //   "response"=>"error",
    //   "message"=> count($request->recipient)
    // ));
  }

  public function pdfCase($case)
  {
    $pdf = PDF::loadView('components.pdf.case',array('case' => $case))->setPaper('legal', 'portrait');
    return $pdf->stream();  
  }
}