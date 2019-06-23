<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Room;
use App\User;
use App\MobMessage;
use App\Message;
use App\RoomDeleteMessage;

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
        $rooms = Room::where('name', 'like', '%'.$u->id.'%')
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $mess = MobMessage::create($request->input());
        Room::find($request->input('room_id'))->update(['last_message'=>$mess->id]);
        return $mess;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create_room(Request $request)
    {
        $room = Room::where('name', $request->input('name'))->first();
        if (!$room) {
            $room = Room::create($request->input());
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
        $rooms = Room::where('name', 'like', '%'.$user_id.'%')
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

        $room = Room::find($id);
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
