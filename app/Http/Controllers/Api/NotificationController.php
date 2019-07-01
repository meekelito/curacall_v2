<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Room;
use App\User;
use App\MobMessage;
use App\Message;
use App\Notification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = auth('api')->user() ? auth('api')->user()->id : $request->id;
        $noti = Notification::where('notifiable_id', $user_id)
            ->orderBy('created_at','DESC')
            ->get();
            return $noti;
    }

    public function readCase(Request $request)
    {
        $id = $request->id;
        $dt = new DateTime;

        $noti = Notification::where('id', $id)
            ->update(['read_at' => $dt->format('m-d-y H:i:s')]);
        return $noti;
    }

}
