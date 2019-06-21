<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Room;
use App\User;
use App\RoomDeleteMessage;
use App\Message;

class RoomDeleteMessageController extends Controller
{
 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $mess = RoomDeleteMessage::create($request->input());
        return $mess;
    }

}
