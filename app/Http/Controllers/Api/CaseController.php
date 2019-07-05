<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MobCase;
use App\MobNote;
use App\Case_history;
use App\Case_participant;
use App\User;
use App\Cases;
use App\Notification;
use Validator;

class CaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = $request->input('user_id');
        $status = $request->input('status');
        $offset = $request->has('offset') ? $request->input('offset') : 0;
        $limit = $request->has('limit') ? $request->input('limit') : 20;

        if ($status) {
            if ($status != '4') {

                $cases = MobCase::Join('case_participants AS b','cases.id','=','b.case_id')
                ->where('b.user_id', $user_id)
                ->where('cases.status', $status)
                ->select('cases.id','cases.case_id', 'cases.call_type', 'cases.subcall_type', 'cases.case_message', 'cases.sender_id', 'cases.participant_no', 'cases.account_id','cases.sender_fullname','cases.status','cases.created_at','b.ownership')
                ->offset($offset)
                ->limit($limit)
                ->orderBy('cases.status','ASC')
                ->orderBy('b.is_read','ASC')
                ->orderBy('b.ownership','DESC')
                ->get();
            } else {
                $cases = MobCase::Join('case_participants AS b','cases.id','=','b.case_id')
                ->where('b.user_id',$user_id)
                ->where('b.is_silent',1) 
                ->where(function($q) {
                    $q->where('cases.status',1)
                    ->orWhere('cases.status',2);
                })
                ->select('cases.id','cases.case_id', 'cases.call_type', 'cases.subcall_type', 'cases.case_message', 'cases.sender_id', 'cases.participant_no', 'cases.account_id','cases.sender_fullname','cases.status','cases.created_at','b.ownership')
                ->offset($offset)
                ->limit($limit)
                ->orderBy('cases.status','ASC')
                ->orderBy('b.is_read','ASC')
                ->orderBy('b.ownership','DESC')
                ->get();
            }
        }
        else {
            $cases = MobCase::Join('case_participants AS b','cases.id','=','b.case_id')
            ->where('b.user_id', $user_id)
            ->where('cases.status', '!=', 4)
            ->select('cases.id','cases.case_id', 'cases.call_type', 'cases.subcall_type', 'cases.case_message', 'cases.sender_id', 'cases.participant_no', 'cases.account_id','cases.sender_fullname','cases.status','cases.created_at','b.ownership')
            ->offset($offset)
            ->limit($limit)
            ->orderBy('cases.status','ASC')
            ->orderBy('b.is_read','ASC')
            ->orderBy('b.ownership','DESC')
            ->get();
        }

        $formatted = [];
        foreach($cases as $case) {
            $formattedCase = $this->formatCases($case, $user_id);
            $formatted[] = $formattedCase;
        }

        $counter = $this->caseCounter($user_id);
        $resp = [
            'result'=>'success',
            'user_id'=>$user_id,
            'status'=>$status,
            'status'=>$status,
            'total'=>count($formatted),
            'data'=>$formatted,
        ];

        $mergeData = array_merge($resp, $counter);

        return response()->json($mergeData);
    }
    public function formatCases($singleCase, $user_id)
    {
        $cases = [$singleCase];
        $formatted = null;
        foreach($cases as $case) {
            $user = Case_participant::where('case_id', $case->id)
            ->where('user_id', $user_id)->first();

            $case->sender_fullname = 'CuraCall';
            $case->avatar = '';

            $case->is_read = false;
            if ($user) {
                $case->is_read = ($user->is_read == 1) ? true : false;
            }
            $case->isAccepted = false;

            $case->forwardee = '';
            if ($case->ownership_text === 'Forwarded' && count($case->forwarded)) {
                $countFwd = 1;
                foreach($case->forwarded as $fwd) {
                    $case->forwardee .= $fwd->user->fname.' '.$fwd->user->lname;
                    if ($countFwd < count($case->forwarded)) {
                        $case->forwardee .=', ';
                    }
                    $countFwd++;
                }
            }
            
            $case->acceptee = '';
            if ($case->ownership_text === 'Accepted' && count($case->accepted)) {
                $case->isAccepted = ($case->accepted[0]->user_id == $user_id && $case->status_text != 'Active') ? true : false;
                $case->acceptee = $case->accepted[0]->user->fname.' '.$case->accepted[0]->user->lname;
                $case->sender_fullname = $case->acceptee;
                $case->avatar = $case->accepted[0]->user->avatar;

            }
            
            $case->ownee = '';
            if ($case->ownership_text === 'Forwarded' && count($case->accepted)) {
                $case->isAccepted = ($case->accepted[0]->user_id == $user_id && $case->status_text != 'Active') ? true : false;
                $case->ownee = $case->accepted[0]->user->fname.' '.$case->accepted[0]->user->lname;
                $case->sender_fullname = $case->ownee;
                $case->avatar = $case->accepted[0]->user->avatar;
            }

            if ($case->status === '3' && count($case->accepted)) {
                // $case->isAccepted = ($case->accepted[0]->user_id == $user_id && $case->status_text != 'Active') ? true : false;
                $userClosed = $case->accepted[0]->user->fname.' '.$case->accepted[0]->user->lname;
                $case->sender_fullname = $userClosed;
                $case->avatar = $case->accepted[0]->user->avatar;
            }


            $formatted = $case;
        }
        return $formatted;
    }
    public function caseCounter($user_id) {
        $silentCounter = MobCase::Join('case_participants AS b','cases.id','=','b.case_id')
        ->where('b.user_id',$user_id)
        ->where('b.is_silent',1) 
        ->where(function($q) {
            $q->where('cases.status',1)
            ->orWhere('cases.status',2);
        })->count();
        $closedCounter = MobCase::Join('case_participants AS b','cases.id','=','b.case_id')
        ->where('b.user_id', $user_id)
        ->where('cases.status', 3)
        ->select('*')
        ->count();
        $activeCounter = MobCase::Join('case_participants AS b','cases.id','=','b.case_id')
        ->where('b.user_id', $user_id)
        ->where('cases.status', 1)
        ->select('*')
        ->count();
        $pendingCounter = MobCase::Join('case_participants AS b','cases.id','=','b.case_id')
        ->where('b.user_id', $user_id)
        ->where('cases.status', 2)
        ->select('*')
        ->count();
        $totalActiveCounter = $activeCounter + $pendingCounter;
        $allCaseCounter = $totalActiveCounter + $closedCounter;

        return [
            'silentCounter' => $silentCounter,
            'activeCounter' => $activeCounter,
            'closedCounter' => $closedCounter,
            'pendingCounter' => $pendingCounter,
            'totalActiveCounter' => $totalActiveCounter,
            'allCaseCounter' => $allCaseCounter,
        ];
    }

    public function read_case($case_id, Request $request)
    {
        Case_history::updateOrCreate( [
            "is_visible"=>1,
            "status"=>1,
            "case_id" => $case_id,"action_note" => "Case Read",
            'created_by' => $request->user_id
        ]); 
        Case_participant::where('case_id', $case_id)->where('user_id', $request->user_id)->update(['is_read' => 1]); 

        return $request->input();
    }
    public function reopen_case(Request $request)
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
        $res = MobCase::find($request->case_id);
        $res->status = 2;
        $res->save();
      }
      $res = Case_history::create( $request->all()+["status" => 2,"action_note" => "Case Re-Opened", 'created_by' => $request->user_id ] ); 
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
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $user_id = $request->input('user_id');
        $cases = MobCase::Join('case_participants AS b','cases.id','=','b.case_id')
        ->where('b.user_id', $user_id)
        ->where('cases.id', $id)
        ->select('*')
        ->first();

        
        if ($cases) {
            $formattedCase = $this->formatCases($cases, $user_id);
            $formattedCase->notes = MobNote::where('case_id', $formattedCase->id)->get();
            $formattedCase->history = Case_history::where('case_id', $formattedCase->id)->get();

            Case_history::updateOrCreate( [
                "is_visible"=>1,
                "status"=>1,
                "case_id" => $formattedCase->id,
                "action_note" => "Case Read",
                'created_by' => $user_id
            ]); 

            $formattedCase->readData = Case_history::where('case_id', $formattedCase->id)
            ->where('case_id', $formattedCase->id)
            ->where('action_note', 'Case Read')
            ->where('created_by', $user_id)
            ->first();

            Case_participant::where('case_id', $formattedCase->id)->where('user_id', $user_id)->update(['is_read' => 1]); 

        }
        return response()->json($formattedCase);
    }

    public function add_note(Request $request, $id)
    {
        $user_id = $request->input('created_by');
        $note = MobNote::create(
            [
                'note' =>$request->note,
                'case_id' =>$id,
                'created_by' =>$user_id
            ]
        );
        return $note;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function forward(Request $request, $id)
    // {
    //     $recipients = $request->input('recipients');
    //     $user_id = $request->input('user_id');
    //     $note = $request->input('note');

    //     foreach($recipients as $rec) {
    //         Case_history::create( [
    //             "is_visible"=>1,
    //             "status"=>2,
    //             "case_id" => $id,
    //             "note" => $note,
    //             "action_note" => "Case Forwarded",
    //             'created_by' => $user_id,
    //             'sent_to'=>$rec['id'] 
    //         ]);
            
    //         $parti = Case_participant::where('case_id', $id)
    //         ->where('user_id', $rec['id'])->update(['ownership'=>1]);

    //         if(!$parti) {
    //             Case_participant::create([
    //                 'ownership'=>1,
    //                 'case_id'=>$id,
    //                 'user_id'=>$rec['id']
    //             ]);
    //         }
            
    //     }

    //     return $request->all();
    // }

    public function forwardCase(Request $request)
    {
        $userInfo = auth('api')->user();
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
                        ->where('user_id',$userInfo->id)
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
        $participants_id = Case_participant::select('user_id')->where("case_id",$request->case_id)->where('user_id','!=',$userInfo->id)->get();// jeric's update, exclude self for notification purposes
        $participants = array();
        //$update = array();
        //$insert = array();
        $forwarded_recipients = array(); // list of forwarded users
        foreach ($participants_id as $row) {
            $participants[] = $row->user_id;
        }
    
        /** Notification message template **/
            $message = str_replace("[from_name]",$userInfo->fname . ' ' . $userInfo->lname,__('notification.forward_case'));
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
    
            Case_history::create( $request->all()+["is_visible" => 1,"status" => 2,"action_note" => "Case Forwarded","sent_to"=>$row, 'created_by' => $userInfo->id ] ); 
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
        ->where('user_id', $userInfo->id  )
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

    public function acceptCase(Request $request) 
    { 
        $userInfo = auth('api')->user();
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
        ->where('user_id', $userInfo->id )
        ->update(['ownership' => 2]);
    
        $res = Case_history::create( ["is_visible"=>1,"status"=>2,"case_id" => $request->case_id,"action_note" => "Case Accepted", 'created_by' => $userInfo->id ] ); 
        if($res){
            /** Notify case participants that the case was accepted **/
                $participants = Case_participant::where("case_id",$request->case_id)->where('user_id','!=',$userInfo->id)->get();
    
                $message = str_replace("[from_name]",$userInfo->fname . ' ' . $userInfo->lname,__('notification.accept_case'));
                $message = str_replace("[case_id]",$request->case_id,$message);
                $arr = array(
                    'from_id'     => $userInfo->id,
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
}
