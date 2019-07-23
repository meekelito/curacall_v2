<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Room;
use App\User;
use App\MobMessage;
use App\Message;
use App\Notification;
use Notification as Nootif;
use App\Notifications\MobCaseNotification;


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

    public function unreadNotificationCount(Request $request)
    {
        $user_id = auth('api')->user() ? auth('api')->user()->id : $request->id;
        $noti = Notification::where('notifiable_id', $user_id)
            ->where('read_at', null)
            ->count();

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

    public function send(Request $request)
    {
        $user = User::first();
        $details = [
            'device_token' => 'eI5ZFDg_jl4:APA91bF2JX-wXwM1yCZMuzqu8RkSwC44bCJ8UsXfloet-H4zb6jxDVGjfzRzdWME0QTsjt1bTE_MnykGMKLSKg4Sg_lE9vKNfX-6XAv9FJFJgNREEM2GSxwlRt4yKBkGjzNbquF_-3wK',
        ];

        $nott = $user->notify(new MobCaseNotification($details));
        // $nott = Nootif::send($user, new MobCaseNotification($details));
        return $nott;
    }

}
