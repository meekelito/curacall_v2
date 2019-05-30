<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MobCase;
use App\MobNote;
use App\Case_history;
use App\Case_participant;
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

            $case->is_read = false;
            if ($user) {
                $case->is_read = ($user->is_read == 1) ? true : false;
            }
            $case->isAccepted = false;

            $case->forwardee = '';
            if ($case->ownership_text === 'Forwarded' && count($case->forwarded)) {
                $case->forwardee = $case->forwarded[0]->user->fname.' '.$case->forwarded[0]->user->lname;
            }
            
            $case->acceptee = '';
            if ($case->ownership_text === 'Accepted' && count($case->accepted)) {
                $case->isAccepted = ($case->accepted[0]->user_id == $user_id && $case->status_text != 'Active') ? true : false;
                $case->acceptee = $case->accepted[0]->user->fname.' '.$case->accepted[0]->user->lname;
            }
            
            $case->ownee = '';
            if ($case->ownership_text === 'Forwarded' && count($case->accepted)) {
                $case->isAccepted = ($case->accepted[0]->user_id == $user_id && $case->status_text != 'Active') ? true : false;
                $case->ownee = $case->accepted[0]->user->fname.' '.$case->accepted[0]->user->lname;
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
                "case_id" => $formattedCase->id,"action_note" => "Case Read",
                'created_by' => $user_id
            ]); 
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
    public function forward(Request $request, $id)
    {
        $recipients = $request->input('recipients');
        $user_id = $request->input('user_id');
        $note = $request->input('note');

        foreach($recipients as $rec) {
            Case_history::create( [
                "is_visible"=>1,
                "status"=>2,
                "case_id" => $id,
                "note" => $note,
                "action_note" => "Case Forwarded",
                'created_by' => $user_id,
                'sent_to'=>$rec['id'] 
            ]);
            
            $parti = Case_participant::where('case_id', $id)
            ->where('user_id', $rec['id'])->update(['ownership'=>1]);

            if(!$parti) {
                Case_participant::create([
                    'ownership'=>1,
                    'case_id'=>$id,
                    'user_id'=>$rec['id']
                ]);
            }
            
        }

        return $request->all();
    }
}
