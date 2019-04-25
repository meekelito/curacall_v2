<?php

namespace App\Http\Controllers;

use App\Message;
use App\Room;
use App\User;
use App\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use App\Notifications\MessageNotification;
use App\Notification;


class ChatsController extends Controller
{
	public function __construct()
	{
	  $this->middleware('auth');
	}

	/**
	 * Show chats
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($room)
	{
	  // return view('chat'); 
    $participants = Room::leftJoin('participants AS b','rooms.id','=','b.room_id') 
						->leftJoin('users AS c','b.user_id','=','c.id')
						->where('rooms.id',$room)
						->where('rooms.status','active')
            ->where('b.user_id','!=',Auth::user()->id)
						->select('c.id','c.fname','c.lname','c.email','c.phone_no','c.title','c.prof_img')
						->get();  

		if($participants->isEmpty()){
			return redirect("new-message");
		}
		
		$document = Participant::where("room_id","=",$room)
								->where("user_id","=",Auth::user()->id)
								->firstOrFail();
    $document->is_read = 1; 
    $document->save();

	  return view('chat',['room_id' => $room,'participants' => $participants ]);

	  
	}

	// public function createMessage($recipient)
 //  {
	// 	$res = unserialize($recipient);
 //  	$participants = Participant::whereIn('user_id',$res)->get();


 //  	return view('messages-new',['participants'=>$participants] );
 //  }

	/**
	 * Fetch all messages
	 *
	 * @return Message
	 */
	public function fetchMessages(Request $request)
	{ 
	  // return Message::with('user')->get();
	  return Message::where('room_id', $request->input('room') )->with('user')->get();
	}

	/**
	 * Persist message to database
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function sendMessage(Request $request)
	{
	  $user = Auth::user();

	  $message = $user->messages()->create([
	  	'room_id' => $request->input('room_id'), 
	    'message' => $request->input('message')
	  ]);
	  Room::find( $request->input('room_id') )->update(['last_message' => $message->id]);
	  Participant::where('room_id', '=', $request->input('room_id')  )->update(['is_read' => 0]);

	  $isgroupchat = false;
	  $participants = Participant::where('room_id',$request->input('room_id'))->where('user_id','!=',Auth::user()->id)->get();
		if($participants->count() > 1)
			$isgroupchat = true;

      foreach($participants as $participant)
      {

        $participant->user->notify(new MessageNotification($message,$isgroupchat));
      }

	  broadcast(new MessageSent($user, $message))->toOthers();

	  return ['status' => 'Message Sent!'];
	}
}
