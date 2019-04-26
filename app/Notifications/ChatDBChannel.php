<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class ChatDbChannel 
{

  public function send($notifiable, Notification $notification)
  {
    $data = $notification->toDatabase($notifiable);

    return $notifiable->routeNotificationFor('database')->updateOrCreate([
        'room_id'   => $data['room_id'],
        'notified_by' => $data['from_id'], //<-- comes from toDatabase() Method below
    ],[
        'id' => $notification->id,
        'type' => get_class($notification),
        'data' => $data,
        'read_at' => null,
        'created_at'  => Carbon::now()
    ]);
  }

}