<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Cases;
use App\Account;
use App\Api_keys; 
use App\Case_participant;
use App\Case_history;
use DB;
use Cache;
use Auth;
use Validator;
use Carbon\Carbon;
use App\Notifications\ReminderNotification;
use App\Notifications\CaseNotification;
use App\Case_repository;
use App\ApiCron;
use App\Call_type;
use App\Calltype_notification;

class ApiController extends Controller
{
  public function newCase(Request $request)
  {
    $validator = Validator::make($request->all(),[ 
      'case_id' => 'bail|required|unique:cases,case_id', 
      'account_id' => 'bail|required|exists:accounts,account_id',
      'call_type' => 'bail|required',
      'subcall_type' => 'bail|required',
      'case_message' => 'bail|required',
      'recipients' => 'required|array',
      'recipients.*'=> 'distinct|exists:users,id',
      'recipients.*'=> 'distinct|exists:users,id',
      'recipients.*'=> 'distinct|exists:users,id',
    ],[
      'account_id.exists' => 'The account ID invalid ',
      'recipient.distinct'=>'Recipient must contain unique Curacall ID.',
      'recipient.*.exists'=>'Recipient does not exist.',
    ]);

    if( $validator->fails() ){
      return response()->json([ 
        "status"=> 400,
        "response"=>"bad request", 
        "message"=>$validator->errors()
      ]);
    }
    
    DB::beginTransaction();
    try{
      $res = Account::where('account_id', $request->account_id)->firstOrFail();
      $request->merge(array('account_id' => $res->id));
      $case = Cases::create($request->all());

      $now = Carbon::now()->toDateTimeString();
      $participants = array();

      $message = str_replace("[case_id]",$request->case_id,__('notification.new_case'));
      $arr = array(
          'case_id'     => $request->case_id,
          'message'     =>    $message,
          'type'        =>  'new_case',
          'action_url'  => route('case',[$case->id])
      );

      foreach ($request->recipients as $recipient) {
        $participants[] = array(
          'case_id'=>$case->id,
          'user_id'=>$recipient,
          'ownership'=>1,
          'is_silent'=>0,
          'created_at'=>$now,
          'updated_at'=>$now
        );

       $user = User::find($recipient);
       $user->notify(new CaseNotification($arr)); // Notify participant
          
      }

      Case_participant::insert($participants);
      
      DB::commit();
      return response()->json([
        "status" => 200,
        "response" => "success", 
        "message" => "Successfully sent."
      ]);
    } catch (Exeption $e){
      DB::rollback();
      return response()->json([
        "status" => 500,
        "response" => "Internal Server Error", 
        "message" => "An internal server error occurred while processing the request."
      ]);
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
      ->update(['ownership' => 2]);
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

      return ["read"=> $read,"accepted" =>$accepted,"closed"=>$closed];
  }



  public function reminderNotification(Request $request)
  {
        $validator = Validator::make($request->all(),[ 
          'notifiable_id' => 'required',
          'case_id'  => 'required'
        ]); 

        if( $validator->fails() ){
            return json_encode(array( 
              "status"=>0,
              "response"=>"error", 
              "message"=>$validator->errors()->first()
            ));
        }

        $user = User::findOrFail($request->notifiable_id);
        $message = str_replace("[case_id]",$request->case_id,__('notification.reminder'));
        $arr = array(
            'case_id' => $request->case_id,
            'message' =>    $message,
            'type'    => 'reminder',
            'action_url'    => route('case',[$request->case_id])
        );
        $user->notify(new ReminderNotification($arr));

        if($user)
          return response()->json([
              "status" => 1,
              "response" => "success", 
              "message" => "Successfully sent."
          ]);
  }


  public function sendCaseOncall(Request $request)
  {
    $validator = Validator::make($request->all(),[ 
      'questionnaire_id' => 'bail|required|unique:cases,case_id',
      'account_id' => 'bail|required|exists:accounts,account_id',
      'caller_information' => 'required',
      'caller_information.caller_id' => 'nullable|string',
      'caller_information.caller_first_name' => 'required|string',
      'caller_information.caller_last_name' => 'required|string',
      'caller_information.caller_email_address' => 'nullable|email',
      'caller_information.caller_type' => 'required|string',
      'caller_information.caller_type_details' => 'nullable|string',
      'caller_information.caller_details' => 'nullable|string',
      'caller_information.caller_alternative_telephone_number' => 'nullable|string',
      'caller_information.extension_direct_line' => 'nullable|string|in:Direct Line,Extension',
      'caller_information.caller_confirmed_patient_telephone' => 'required|boolean',
      'caller_information.confirmed_caller_first_name' => 'required|boolean',
      'caller_information.confirmed_caller_last_name' => 'required|boolean',
      'caller_information.caller_calling_from' => 'nullable|string',
      'caller_information.confirmed_telephone_number' => 'required|boolean',
      'caller_information.mobile_number' => 'nullable|string',
      'caller_information.caller_telephone_extension' => 'nullable|string',
      'caller_information.caller_telephone_number' => 'nullable|string',
      'caller_information.caller_patient_telephone' => 'nullable|string',
      'caller_information.telephone_number' => 'nullable|string',
      'caller_information.caller_relationship_with_field_worker' => 'nullable|string',

      'call_information' => 'required',
      'call_information.call_typology' => 'required|in:Normal Call,Collect Call,Public Payphone Call',
      'call_information.number_of_calls' => 'required|in:1st Time,2nd Time,3rd Time,4th Time,5th Time +',
      'call_information.call_type' => 'required|string|in:Clocking Out/Checkin-Check-out,Complaints,Contact Request,Medical,Office,Referral or Agency Contract,Scheduling,Shift Cancelation,Other',
      'call_information.call_subtype' => 'required|string',
      'call_information.confirmation' => 'required|boolean',
      'call_information.call_language' => 'required|string',
      'call_information.call_reason' => 'nullable|string',
      'call_information.reason_call_subtype' => 'nullable|string',
      'call_information.contacted_translation_company' => 'required|boolean',
      'call_information.pin_number' => 'required_if:call_information.contacted_translation_company,==,Yes',
      'call_information.full_message' => 'required|string',
      'call_information.direct_deposit_or_receive_a_check' => 'nullable|boolean',
      'call_information.lab_doctor_notification' => 'required|boolean',
      'call_information.hospital_related' => 'nullable|boolean',
      'call_information.medical_emergency' => 'nullable|boolean',
      'call_information.time_of_call' => 'required|string|in:After Hours/Holiday Hours,During Hours',
      'call_information.name_of_compliance_officer' => 'nullable|string',
      'call_information.name_of_insurance_company' => 'nullable|string',
      'call_information.name_of_oncall_staff' => 'nullable|string',
      'call_information.name_of_the_home_health_agency' => 'nullable|string',
      'call_information.hospital_id' => 'nullable|string',
      'call_information.name_of_the_hospital' => 'nullable|string',
      'call_information.name_of_the_lab_company' => 'nullable|string',
      'call_information.new_or_existing_employee' => 'nullable|in:Existing Employee,New Employee',
      'call_information.new_referral_or_previous_patient' => 'nullable|in:New Referral,Previous Patient',
      'call_information.new_existing_patient' => 'nullable|in:Existing Patient,New Patient',
      'call_information.caller_position_interested_in' => 'nullable|string',
      'call_information.reason_for_being_late' => 'nullable|string',
      'call_information.reason_of_the_cancelation' => 'nullable|string',
      'call_information.relation_to_patient' => 'nullable|string',
      'call_information.services_requested' => 'nullable|in:Home Health Aide(HHA),Hospice,Private Duty,Rehabilitation,Visiting Nursing Service(VNS),Other',
      'call_information.services_requested_details' => 'nullable|string',
      'call_information.start_end_time_of_the_shift' => 'nullable|string|in:Does Not know,Not Applicable,Yes',
      'call_information.team' => 'nullable|string|in:NJ,NJHO',
      'call_information.was_timesheet_submitted' => 'nullable|boolean',
      'call_information.type_of_results' => 'nullable|in:Abnormal,Stat,Critical,Normal',
      'call_information.violence' => 'nullable|boolean',
      'call_information.call_handled_by_initials' => 'required|string',
      'call_information.created_by' => 'required|string',
      'call_information.created_on' => 'required|string',
      'call_information.message_content' => 'nullable|string',
      'call_information.message_ticket' => 'nullable|string',
      'call_information.read' => 'nullable|boolean',
      'call_information.read_by' => 'nullable|string',
      'call_information.read_on' => 'nullable|string',
      'call_information.date_and_time' => 'nullable|string',
      'call_information.call_outside_escalation_hours' => 'nullable|boolean',
      'call_information.call_outside_escalation_interval' => 'nullable|boolean',
      'call_information.compare_date' => 'nullable|string',
      'call_information.start_compare_date' => 'nullable|string',
      'call_information.actions_taken' => 'nullable|string',

      'caregiver_information' => 'required',
      'caregiver_information.caregiver_type' => 'required|in:Certified Nursing Assistant (CNA),Coordinator,Doctor (MD),Home Health Aide (HHA),Nurse Registered (RN) Licensed (LPN) Practitioner (NP),Personal Care Aide (PCA),Physical Therapist (PT) Pharmacist',
      'caregiver_information.pin_code' => 'nullable|string',
      'caregiver_information.employee_first_name' => 'nullable|string',
      'caregiver_information.employee_last_name' => 'nullable|string',
      'caregiver_information.information_confirmed' => 'nullable|boolean',
      'caregiver_information.provided_caregiver_first_name' => 'nullable|in:Does not Know,Refuse to provide,Yes',
      'caregiver_information.provided_caregiver_last_name' => 'nullable|in:Does not Know,Refuse to provide,Yes',
      'caregiver_information.confirmed_caregiver_first_name' => 'nullable|boolean',
      'caregiver_information.confirmed_caregiver_last_name' => 'nullable|boolean',
      'caregiver_information.absent_or_late' => 'nullable|in:Absent,Late',
      'caregiver_information.caregiver_time_and_attendance_pin_code' => 'nullable|string',
      'caregiver_information.doesnt_know_how_late_will_be_to_shift' => 'nullable|boolean',
      'caregiver_information.doesnt_know_pin_code' => 'nullable|boolean',
      'caregiver_information.how_late_will_you_be_to_your_shift' => 'nullable|integer',
      'caregiver_information.employee_date_time_of_shift_start' => 'nullable|string',
      'caregiver_information.employee_date_time_of_shift_end' => 'nullable|string',
      'patient_information' => 'required',
      'patient_information.patient_date_and_time_of_first_visit' => 'nullable|string',
      'patient_information.patient_first_name' => 'nullable|string',
      'patient_information.patient_last_name' => 'nullable|string',
      'patient_information.confirmed_patient_first_name' => 'nullable|boolean',
      'patient_information.confirmed_patient_last_name' => 'nullable|boolean',
      'patient_information.provided_patient_first_name' => 'nullable|in:Does not Know,Refuse to provide,Yes',
      'patient_information.provided_patient_last_name' => 'nullable|in:Does not Know,Refuse to provide,Yes',
      'patient_information.patient_telephone_number' => 'nullable|string',
      'patient_information.confirmed_patient_telephone' => 'nullable|boolean',
      'patient_information.patient_telephone_number_confirmation' => 'nullable|in:Does not have,Refuse to provide,Yes',

      'oncall_personnel' => 'required',
      'oncall_personnel.oncall_staff' => 'required',
      'oncall_personnel.oncall_staff.*.dochalo_ID' => 'required',
      'oncall_personnel.silent_listener.*.dochalo_ID' => 'required_with:oncall_personnel.silent_listener', 
    ]);

 
    if( $validator->fails() ){
      return response()->json([ 
        "status"=> 400,
        "response"=>"bad request", 
        "message"=>$validator->errors()
      ]);
    }

    $calltype = $request->call_information['call_type']; // use for cron

    $res = Account::where('account_id', $request->account_id)->firstOrFail();
  

    $array_holder = array(
      "case_id"=>$request->questionnaire_id,
      "account_id"=>$res->id,
      "subcall_type"=>$request->call_information['call_subtype']
    );

    foreach ($request->call_information as $key => $value) {
      $array_holder[$key] = $value;
    }
    foreach ($request->caller_information as $key => $value) {
      $array_holder[$key] = $value;
    }
    foreach ($request->caregiver_information as $key => $value) {
      $array_holder[$key] = $value;
    }
    foreach ($request->patient_information as $key => $value) {
      $array_holder[$key] = $value;
    }
 

    
    
    

    DB::beginTransaction();
    try{
      $case = Cases::create($array_holder);

      $now = Carbon::now()->toDateTimeString();
      $oncall_personnel = array(); //list of oncall personnel
      $reminder_participants = array();
      foreach ($request->oncall_personnel['oncall_staff'] as $participant) {
        if(isset($participant['dochalo_ID'])){
          $str2 = substr($participant['dochalo_ID'], 2);
          $curacall_id = ltrim($str2, '0');
          $oncall_personnel[] = array(
            'case_id'=>$case->id,
            'user_id'=>$curacall_id,
            'oncall_personnel' => 'oncall',
            'created_at'=>$now,
            'updated_at'=>$now
          );

          $reminder_participants[] = $curacall_id;
        }
      }

      // dd(count($request->oncall_personnel['silent_listener']));
      // if(count($request->oncall_personnel['backup_1'])>=1){
      //   foreach ($request->oncall_personnel['backup_1'] as $participant) {
      //     $str2 = substr($participant['dochalo_ID'], 2);
      //     $curacall_id = ltrim($str2, '0');
      //     $oncall_personnel[] = array(
      //       'case_id'=>$case->id,
      //       'user_id'=>$curacall_id,
      //       'oncall_personnel' => 'backup_1',
      //       'created_at'=>$now,
      //       'updated_at'=>$now
      //     );
      //   }
      // }

      // if(count($request->oncall_personnel['backup_2'])>=1){
      //   foreach ($request->oncall_personnel['backup_2'] as $participant) {
      //     $str2 = substr($participant['dochalo_ID'], 2);
      //     $curacall_id = ltrim($str2, '0');
      //     $oncall_personnel[] = array(
      //       'case_id'=>$case->id,
      //       'user_id'=>$curacall_id,
      //       'oncall_personnel' => 'backup_2',
      //       'created_at'=>$now,
      //       'updated_at'=>$now
      //     );
      //   }
      // }

      // if(count($request->oncall_personnel['silent_listener'])>=1){
      //   foreach ($request->oncall_personnel['silent_listener'] as $participant) {
      //     $str2 = substr($participant['dochalo_ID'], 2);
      //     $curacall_id = ltrim($str2, '0');
      //     $oncall_personnel[] = array(
      //       'case_id'=>$case->id,
      //       'user_id'=>$curacall_id,
      //       'oncall_personnel' => 'silent_listener',
      //       'created_at'=>$now,
      //       'updated_at'=>$now
      //     );
      //   }
      // }

      //return var_dump($oncall_personnel);
      Case_participant::insert($oncall_personnel);

     

      $request->merge(array(
        'call_information'=>json_encode($request->call_information),
        'caller_information'=>json_encode($request->caller_information),
        'caregiver_information'=>json_encode($request->caregiver_information),
        'patient_information'=>json_encode($request->patient_information),
        'oncall_personnel'=>json_encode($request->oncall_personnel)
      ));

      Case_repository::create($request->all());

      DB::commit();

       /* Notification */
      $message = str_replace("[case_id]",$case->id,__('notification.new_case'));
      $arr = array(
          'case_id'     => $case->id,
          'message'     =>    $message,
          'type'        =>  'new_case',
          'action_url'  => route('case',[$case->id])
      );

      foreach ($oncall_personnel as $row) {
       $user = User::find($row['user_id']);
       $user->notify(new CaseNotification($arr)); // Notify participant
      }
      /* END Notification */
      
      /* Reminder Cron */
      $cron = new ApiCron;
      $interval_minutes = 15;//default interval if calltype not in database
      $calltype1 = Call_type::where('name',$calltype)->first();
      if($calltype1)
       $interval_minutes = $calltype1->calltype_notification()->first()->interval_minutes ?? $interval;

      $cron->remind($case->id,$interval_minutes,$reminder_participants);


      /* End Reminder Cron */
      return response()->json([
        "status" => 200,
        "response" => "success", 
        "message" => "Successfully sent."
      ]);
    } catch (Exeption $e){
      DB::rollback();
      return response()->json([
        "status" => 500,
        "response" => "Internal Server Error", 
        "message" => "An internal server error occurred while processing the request."
      ]);
    } 

  }

  public function sendNotification(Request $request)
  {
    $participants = array(4,5);
    $case_id = 68;
    /** Notification message template **/
    $message = str_replace("[from_name]","Curacall User",__('notification.forward_case'));
    $message = str_replace("[case_id]",$case_id,$message);
    $arr = array(
        'case_id'     => $questionnaire_id,
        //'message'     => $message,
        'type'        => 'forward_case',
        //'forward_to'  => $forwarded_recipients,
        'action_url'  => route('case',[$case_id])
    );
    /** END Notification message template **/

    /** Sending Notifcation part **/


    $participants_count = count($participants);
    // Notify all participants of the case except you
    foreach($participants as $participant)
    {
        $user = User::find($participant);

        $str_recipients = "test";
        if(count($request->recipient) > 1){
          $str_recipients = "test" . " and " . $participants_count;
          $str_recipients .= ($participants_count == 1) ? " Other" : " Others";
        }
        
         $arr['forward_to'] = $forwarded_recipients;
         $arr['message'] = $message . $str_recipients;
         //$user->notify(new CaseNotification($arr)); // Notify participant
         Notification::notify_user($arr,$user);
    }
    /** End Sending Notification part **/
  }

  public function addOnCallBackUp(Request $request)
  {
    $validator = Validator::make($request->all(),[ 
      'questionnaire_id' => 'required|exists:cases,case_id',
      'account_id' => '|exists:accounts,account_id',
      'oncall_type' => 'required|in:backup_1,backup_2,silent_listener',
      'oncall_personnel' => 'required',
      'oncall_personnel.oncall_staff' => 'required',
      'oncall_personnel.oncall_staff.*.dochalo_ID' => 'required',
      'oncall_personnel.silent_listener.*.dochalo_ID' => 'required_with:oncall_personnel.silent_listener',
    ],[ 
      'questionnaire_id.exists'=>'Questionnaire ID does not exist.',
      'account_id.exists'=>'Account ID does not exist.',
      'oncall_type.required' => 'OnCall type is required.',
      'oncall_type.oncall_staff.required' => 'OnCall type is required.',
      'oncall_personnel.oncall_staff.*.dochalo_ID.required' => 'Dochalo ID is required.',
      'oncall_personnel.silent_listener.*.dochalo_ID.required_with' => 'Dochalo ID field is required when oncall personnel silent listener is present',
    ]);
 
    if( $validator->fails() ){
      return response()->json([ 
        "status"=> 400,
        "response"=>"bad request", 
        "message"=>$validator->errors()
      ]);
    }
    $oncall_personnel = array();
    $now = Carbon::now()->toDateTimeString();

    DB::beginTransaction();
    try{

      $case = Cases::where('case_id',$request->questionnaire_id)->select('id')->get();
      $participants_id = Case_participant::where('case_id',$case[0]->id)->select('user_id')->get();

      $participants = array();
      $existing_participants = array();
      foreach ($participants_id as $row) {
        $participants[] = $row->user_id;
      }

      $pass = true;
      foreach ($request->oncall_personnel['oncall_staff'] as $participant) {
        if(isset($participant['dochalo_ID']) ){
          $str2 = substr($participant['dochalo_ID'], 2);
          $curacall_id = ltrim($str2, '0');
          if (!in_array($curacall_id, $participants)){
            $oncall_personnel[] = array(
              'case_id'=>$case[0]->id,
              'user_id'=>$curacall_id,
              'oncall_personnel' => $request->oncall_type,
              'created_at'=>$now,
              'updated_at'=>$now
            );
          }else{
            $existing_participants[] = $participant['dochalo_ID'];
            $pass = false;
          }
        }
      }
      if($pass == false){
        return response()->json([
          "status" => 200,
          "response" => "success", 
          "message" => "This OnCall(s) is already a participant of this Case ". implode(', ', $existing_participants)
        ]);
      }

      $res = Case_participant::insert($oncall_personnel);
      
      DB::commit();

       /* Notification */
      $message = str_replace("[case_id]",$case[0]->id,__('notification.new_case'));
      $arr = array(
          'case_id'     => $case[0]->id,
          'message'     =>    $message,
          'type'        =>  'new_case',
          'action_url'  => route('case',[$case[0]->id])
      );

      foreach ($oncall_personnel as $row) {
       $user = User::find($row['user_id']);
       $user->notify(new CaseNotification($arr)); // Notify participant
      }
      /* END Notification */

      return response()->json([
        "status" => 200,
        "response" => "success", 
        "message" => "Successfully sent."
      ]);
    } catch (Exeption $e){
      DB::rollback();
      return response()->json([
        "status" => 500,
        "response" => "Internal Server Error", 
        "message" => "An internal server error occurred while processing the request."
      ]);
    } 

  }


  public function forwardCase(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'questionnaire_id' => 'required|exists:cases,case_id',
      'account_id' => 'bail|required|exists:accounts,account_id',
      'user_id' => 'required|exists:users',
      'oncall_personnel' => 'required',
      'oncall_personnel.oncall_staff' => 'required',
      'oncall_personnel.oncall_staff.*.dochalo_ID' => 'required'
    ],[ 
      'questionnaire_id.exists'=>'Questionnaire ID does not exist.',
      'account_id.exists'=>'Account ID does not exist.',
      'oncall_type.required' => 'OnCall type is required.',
      'oncall_type.oncall_staff.required' => 'OnCall type is required.',
      'oncall_personnel.oncall_staff.*.dochalo_ID.required' => 'Dochalo ID is required.',
      'oncall_personnel.silent_listener.*.dochalo_ID.required_with' => 'Dochalo ID field is required when oncall personnel silent listener is present',
    ]);

    if ($validator->fails()) {
      return response()->json([ 
        "status"=> 400,
        "response"=>"bad request", 
        "message"=>$validator->errors()
      ]);
      
    }

    $oncall_personnel = array(); //list of oncall personnel
    foreach ($request->oncall_personnel['oncall_staff'] as $participant) {
      if(isset($participant['dochalo_ID'])){
        $str2 = substr($participant['dochalo_ID'], 2);
        $curacall_id = ltrim($str2, '0');
        $oncall_personnel[] = array(
          'case_id'=>$case->id,
          'user_id'=>$curacall_id,
          'oncall_personnel' => 'oncall',
          'created_at'=>$now,
          'updated_at'=>$now
        );
      }
    }

    $user_info = User::find($request->user_id);


    

    //checking if the user is still the owner of the case
    $participation = Case_participant::where('case_id',$request->questionnaire_id)
                    ->where('user_id',$request->user_id)
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
    $participants_id = Case_participant::select('user_id')->where("case_id",$request->questionnaire_id)->where('user_id','!=',$request->user_id)->get();// jeric's update, exclude self for notification purposes
    $participants = array();
    //$update = array();
    //$insert = array();
     $forwarded_recipients = array(); // list of forwarded users
    foreach ($participants_id as $row) {
      $participants[] = $row->user_id;
    }

    /** Notification message template **/
      $message = str_replace("[from_name]",$user_info->fname . ' ' . $user_info->lname,__('notification.forward_case'));
      $message = str_replace("[case_id]",$request->questionnaire_id,$message);
      $arr = array(
          'case_id'     => $request->questionnaire_id,
          //'message'     => $message,
          'type'        => 'forward_case',
          //'forward_to'  => $forwarded_recipients,
          'action_url'  => route('case',[$request->questionnaire_id])
      );
    /** END Notification message template **/

   
    // update all participants ownership state to Forwarded
    foreach ($request->recipient as $row) {
      if (in_array($row, $participants)){ 
        Case_participant::where('case_id', $request->questionnaire_id)
        ->where('user_id', $row )
        ->update(['ownership' => 1, 'is_read' => 0]); 
      }else{ 
        Case_participant::create( ["case_id" => $request->questionnaire_id,"user_id" => $row, 'ownership' => 1, 'is_read' => 0 ] ); 
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

      Case_history::create( $request->all()+["is_visible" => 1,"status" => 2,"action_note" => "Case Forwarded","sent_to"=>$row, 'created_by' => $request->user_id ] ); 
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

    $res=Case_participant::where('case_id', $request->questionnaire_id)
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
    
  }

  public function testcron()
  {
    $cron = new ApiCron;
    // $result = $cron->read("123");
    //$result = $cron->remind("31",2,array(4));
    //$result = $cron->login();
    //return json_encode($result);
      $interval = 15;
      $calltype = Call_type::where('name',"Medical")->first();
      if($calltype)
       $interval = $calltype->calltype_notification()->first()->interval_minutes ?? $interval;

      return $interval;
  }

}
