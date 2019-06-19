<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Room;
use App\User;
use App\MobMessage;
use App\Message;

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
                ->latest()
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
            $formatted_rooms[] = $room;
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
        return $mess;
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
        $room->conversations = MobMessage::where('room_id', $room->id)->get();
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
        return MobMessage::destroy($id);
    }
}
