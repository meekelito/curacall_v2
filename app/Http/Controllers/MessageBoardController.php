<?php

namespace App\Http\Controllers;

use App\MessageBoard;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Account_role;
use DataTables;
use App\MessageBoardParticipant;
use Carbon\Carbon;

class MessageBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = MessageBoard::with('user')->latest()->paginate(10);
      
        return view('messageboard.index',['messages'=>$messages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $participants = array();
        $users = array();

          


        return view('messageboard.create',['participants'=>$participants,'users'=>$users]);
    }

    public function fetchParticipants(Request $request)
    {
        if($request->new == 1)
        {
            if( Auth::user()->is_curacall ){
                  $participants = User::leftJoin('roles AS b','users.role_id','=','b.id')
                        ->select('users.id','users.fname','users.lname','users.phone_no','users.email','users.prof_img','b.role_title as title')
                        ->where('users.status','active')
                        ->IsCuraCall() 
                         ->when(request('users') != 'all', function ($q) {
                             $selected_users = explode(",",request('users'));
                            return $q->whereIn('users.id',$selected_users);
                        })
                        ->where('users.id','!=',Auth::user()->id)->get();
            }else{
                  $contacts = Account_role::where('account_id','=', Auth::user()->account_id)
                              ->where('role_id','=', Auth::user()->role_id)
                              ->get();

                  if( $contacts[0]->msg_all ){
                    $participants = User::leftJoin('roles AS b','users.role_id','=','b.id')
                        ->select('users.id','users.fname','users.lname','users.phone_no','users.email','users.prof_img','b.role_title as title')
                        ->where('users.status','active')
                        ->IsCuraCall()
                        ->when(request('users') != 'all', function ($q) {
                             $selected_users = explode(",",request('users'));
                            return $q->whereIn('users.id',$selected_users);
                        })
                        ->where('users.id','!=',Auth::user()->id)->get();
                  }else if( $contacts[0]->msg_management ){
                    $participants = User::leftJoin('roles AS b','users.role_id','=','b.id')
                        ->select('users.id','users.fname','users.lname','users.phone_no','users.email','users.prof_img','b.role_title as title')
                        ->where( 'users.status', 'active' )
                        ->where( 'users.role_id', 5)
                        ->when(request('users') != 'all', function ($q) {
                             $selected_users = explode(",",request('users'));
                            return $q->whereIn('users.id',$selected_users);
                        })
                        ->where( 'users.id', '!=', Auth::user()->id )->get();
                  }else{
                    $participants = User::leftJoin('roles AS b','users.role_id','=','b.id')
                        ->select('users.id','users.fname','users.lname','users.phone_no','users.email','users.prof_img','b.role_title as title')
                        ->where('users.status','active')
                        ->IsCuraCall() 
                        ->when(request('users') != 'all', function ($q) {
                             $selected_users = explode(",",request('users'));
                            return $q->whereIn('users.id',$selected_users);
                        })
                        ->where('users.id','!=',Auth::user()->id)->get();
                  }                            
            }
        }else{
             $participants = MessageBoardParticipant::leftJoin('users AS b','message_board_participants.user_id','=','b.id')->where('message_board_participants.messageboard_id',$request->messageboard_id)->leftJoin('roles AS c','b.role_id','=','c.id')->select('b.id','b.prof_img','b.fname','b.lname','c.role_title as title','b.phone_no','b.email');
        }
            

         // $participants = Case_participant::leftJoin('users AS b','case_participants.user_id','=','b.id')->where('case_participants.case_id',$case_id)->select('b.prof_img','b.fname','b.lname','b.title','b.phone_no','b.email');

            return Datatables::of($participants)
            ->make(true); 
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required',
            'content'      => 'required'
        ]);

        $subscribers = explode(",",$request->subscribers);

        $result = MessageBoard::create(
            $request->all() + ["created_by"=>Auth::user()->id]
        );

        if($result){
            $data = array();
            if(isset($subscribers) && $subscribers[0] != ""){
                foreach($subscribers as $row)
                {
                     $data[] = ["messageboard_id"   => $result->id,
                                "user_id"   => $row,
                                "created_at"    => Carbon::now(),
                                "updated_at"    => Carbon::now(),
                                ];
                }
                MessageBoardParticipant::insert($data);
            }
        
            return response()->json([ 
                "status"=> 1,
                "message"=> "Successfully saved"
              ]);
         }else
           return response()->json([ 
                "status"=> 0,
                "message"=> "Oops. Something went wrong."
              ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MessageBoard  $messageBoard
     * @return \Illuminate\Http\Response
     */
    public function show(MessageBoard $messageBoard)
    {
        $participants = array();
        $users = array();
        return view('messageboard.show',['message'=>$messageBoard,'participants'=>$participants,'users'=>$users]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MessageBoard  $messageBoard
     * @return \Illuminate\Http\Response
     */
    public function edit(MessageBoard $messageBoard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MessageBoard  $messageBoard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MessageBoard $messageBoard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MessageBoard  $messageBoard
     * @return \Illuminate\Http\Response
     */
    public function destroy(MessageBoard $messageBoard)
    {
        //
    }
}
