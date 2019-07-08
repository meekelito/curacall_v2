<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MobRoom;
use App\User;
use App\Participant;
use App\Notifications\MessageNotification;
use App\Events\MessageSent;
use App\MobMessage;
use App\Message;
use App\RoomDeleteMessage;
use App\RoomLastVisit;
use Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $u = auth('api')->user();
        $rooms = MobRoom::where('name', 'like', '%'.$u->id.'%')
                ->orderBy('updated_at','DESC')
                ->get();

        $formatted_rooms = [];
        foreach ($rooms as $room) {
            $users = explode('-',$room->name);
            $contacts = [];
            $contact_name = '';
            foreach($users as $user) {
                if ($u->id != $user){
                    $userInfo = User::find($user);
                    $contacts[] = $userInfo;
                    if (!$contact_name) {
                        $contact_name = $userInfo->fname;
                    } else {
                        $contact_name .= ', '.$userInfo->fname;
                    }
                }
            }
            $room->contact_name = $contact_name; 
            $room->contacts = $contacts;
            $room->last_convo = Message::find($room->last_message);


            //get unread messages count
            $unread = RoomLastVisit::where('user_id', $u->id)
            ->where('room_id', $room->id)
            ->orderBy('id','DESC')
            ->first();

            if($unread) {
                $room->unreadCount = Message::where('room_id', $room->id)
                ->where('created_at', '>', $unread->created_at)->count();
            } else {
                $room->unreadCount = Message::where('room_id', $room->id)->count();
            }
            $room->unreadText = $room->unreadCount ? 'primary' : '';


            $lastDelete = RoomDeleteMessage::where('room_id', $room->id)->where('user_id', $u->id)
            ->orderBy('updated_at','DESC')
            ->first();
            if ($lastDelete) {
                $chatCount = MobMessage::where('room_id', $room->id)
                ->where('created_at', '>', $lastDelete->created_at)
                ->count();
            } else {
                $chatCount = MobMessage::where('room_id', $room->id)->count();
            }

            if($chatCount) {
                $formatted_rooms[] = $room;
            }
        }
        return ['rooms'=>$formatted_rooms, 'user'=>$u];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function roomUnread(Request $request)
    {
        $u = auth('api')->user();
        $rooms = MobRoom::where('name', 'like', '%'.$u->id.'%')
                ->get();
        $totalUnread = 0;
        $formatted_rooms = [];
        foreach ($rooms as $room) {

            //get unread messages count
            $unread = RoomLastVisit::where('user_id', $u->id)
            ->where('room_id', $room->id)
            ->orderBy('id','DESC')
            ->first();

            if($unread) {
                $room->unreadCount = Message::where('room_id', $room->id)
                ->where('created_at', '>', $unread->created_at)->count();
            } else {
                $room->unreadCount = Message::where('room_id', $room->id)->count();
            }
            $room->unreadText = $room->unreadCount ? 'primary' : '';
            $totalUnread = $room->unreadCount ? $totalUnread + 1 : $totalUnread;

        }
        return ['totalUnread'=>$totalUnread];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        // $message = MobMessage::create($request->input());

        $message = $user->messages()->create([
            'room_id' => $request->input('room_id'), 
          'message' => $request->input('message')
        ]);
        MobRoom::find( $request->input('room_id') )->update(['last_message' => $message->id]);
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
  
        return $message;

        // $mess = MobMessage::create($request->input());
        // MobRoom::find($request->input('room_id'))->update(['last_message'=>$mess->id]);
        // return $mess;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create_room(Request $request)
    {
        $room = MobRoom::where('name', $request->input('name'))->first();
        if (!$room) {
            $room = MobRoom::create($request->input());
        }
        return $room;
    }

    /**
     * get a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function recent(Request $request)
    {
        $user_id = $request->has('user_id') ? $request->input('user_id') : auth('api')->user()->id;
        $rooms = MobRoom::where('name', 'like', '%'.$user_id.'%')
                ->orderBy('updated_at','DESC')
                ->get();
        $contacts = [];

        $formatted_rooms = [];
        foreach ($rooms as $room) {
            $users = explode('-',$room->name);
            $contact_name = '';
            if (strlen($room->name) === 3) {
                foreach($users as $user) {
                    if ($user_id != $user){
                        $userInfo = User::find($user);
                        $userInfo->room_id = $room->id;
                    }
                }

                $lastDelete = RoomDeleteMessage::where('room_id', $room->id)->where('user_id', $user_id)
                ->orderBy('updated_at','DESC')
                ->first();
                if ($lastDelete) {
                    $chatCount = MobMessage::where('room_id', $room->id)
                    ->where('created_at', '>', $lastDelete->created_at)
                    ->count();
                } else {
                    $chatCount = MobMessage::where('room_id', $room->id)->count();
                }

                if($chatCount) {
                    $contacts[] = $userInfo;
                }

            }
        }
        return ['contacts'=>$contacts];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $u = auth('api')->user();

        $room = MobRoom::find($id);
        $users = explode('-',$room->name);
        $contacts = [];
        $contact_name = '';
        foreach($users as $user) {
            if ($u->id != $user){
                $userInfo = User::find($user);
                if (!$contact_name) {
                    $contact_name = $userInfo->fname;
                } else {
                    $contact_name .= ', '.$userInfo->fname;
                }

                $roomCheck = MobRoom::where('name', $user.'-'.$u->id)->first();
                if (!$roomCheck) {
                    $roomCheck = MobRoom::where('name', $u->id.'-'.$user)->first();
                }
    
                if ($roomCheck) {
                    $userInfo->room_id = $roomCheck->id;
                } else {
                    $newRoom = MobRoom::create([
                        'name'=>$u->id.'-'.$user,
                        'user_id'=>$user,
                        'participants_no'=>2,
                        'status'=>'active',
                    ]);
                    $userInfo->room_id = $newRoom->id;
                }
                $contacts[] = $userInfo;

            }
        }
        $room->contact_name = $contact_name; 
        $room->contacts = $contacts;
        $lastDelete = RoomDeleteMessage::where('room_id', $id)->where('user_id', $u->id)
                        ->orderBy('updated_at','DESC')
                        ->first();
        if ($lastDelete) {
            $room->conversations = MobMessage::where('room_id', $room->id)
            ->where('created_at', '>', $lastDelete->created_at)
            ->get();
        } else {
            $room->conversations = MobMessage::where('room_id', $room->id)->get();
        }

        RoomLastVisit::create([
            'user_id'=>$u->id,
            'room_id'=>$room->id
        ]);

        return ['room'=>$room, 'user'=>$u];

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
        MobMessage::find($id)->update([
            'message'=>''
        ]);
        return $id;
    }
}
